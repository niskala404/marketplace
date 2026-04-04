<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\OrderTrackingMilestoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop->id;
        $orders = Order::where('shop_id', $shopId)->with('user')->latest()->paginate(10);
        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);
        $order->load(['items', 'shipmentEvents']);
        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order, OrderTrackingMilestoneService $trackingMilestones)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);

        $request->validate([
            'status'      => ['required', 'in:pending,paid,processing,shipped,completed,cancelled'],
            'tracking_no' => ['nullable', 'string', 'max:80'],
        ]);

        $allowedTransitions = [
            'pending'    => ['paid', 'processing', 'cancelled'],
            'paid'       => ['processing', 'shipped', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped'    => ['completed'],
            'completed'  => [],
            'cancelled'  => [],
        ];

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $allowedTransitions, $request, $trackingMilestones) {
            $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->first();
            if (!$locked) return;

            if ($newStatus !== $locked->status) {
                $allowed = $allowedTransitions[$locked->status] ?? [];
                abort_if(!in_array($newStatus, $allowed, true), 422, 'Transisi status tidak valid.');
            }

            $payload = ['status' => $newStatus];

            if ($newStatus === 'shipped') {
                if ($request->filled('tracking_no')) {
                    $payload['tracking_no'] = $request->tracking_no;
                }
                $payload['shipped_at'] = now();
            }

            if ($newStatus === 'paid' && !$locked->paid_at) {
                $payload['paid_at'] = now();
            }

            if ($newStatus === 'completed' && !$locked->completed_at) {
                $payload['completed_at'] = now();
                $payload['received_at']  = $payload['received_at'] ?? now();
            }

            $locked->update($payload);

            if ($oldStatus !== $locked->status) {
                if ($locked->status === 'paid') {
                    $locked->logShipmentEvent('paid', 'Pembayaran diterima', 'Pesanan akan segera diproses.');
                }
                if ($locked->status === 'processing') {
                    $locked->logShipmentEvent('processing', 'Pesanan diproses', 'Penjual sedang menyiapkan pesanan.');
                }
                if ($locked->status === 'shipped') {
                    $trackingMilestones->seedShippedMilestones($locked);
                }
                if ($locked->status === 'completed') {
                    $locked->logShipmentEvent('completed', 'Pesanan selesai', 'Dana diproses ke penjual.');
                }
                if ($locked->status === 'cancelled') {
                    $locked->logShipmentEvent('cancelled', 'Pesanan dibatalkan', $locked->cancel_reason ?: null);
                }
            }

            if ($locked->status === 'completed') {
                $locked->settleCommissionIfNeeded();
            }
        });

        $order->refresh()->loadMissing('user');
        if ($order->user && $oldStatus !== $order->status) {
            $order->user->notify(new OrderStatusChangedNotification($order, $oldStatus, $order->status));
        }

        return back()->with('success', 'Status order diperbarui.');
    }

    public function markDelivered(Request $request, Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);

        abort_if($order->status !== 'shipped', 422);

        if (!$order->delivered_at) {
            $order->update(['delivered_at' => now()]);

            $order->logShipmentEvent('delivered', 'Pesanan sampai', 'Paket sudah sampai di alamat tujuan.', now(), 'delivered');

            $order->loadMissing('user');
            if ($order->user) {
                $order->user->notify(new OrderDeliveredNotification($order));
            }
        }

        return back()->with('success', 'Pesanan ditandai sudah sampai (delivered).');
    }

    public function addCheckpoint(Request $request, Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);
        abort_if(!in_array($order->status, ['shipped', 'completed'], true), 422);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location'    => ['required', 'string', 'max:160'],
        ]);

        $order->logShipmentEvent(
            'shipped',
            $data['title'],
            $data['description'] ?? null,
            now(),
            'custom_checkpoint',
            $data['location']
        );

        return back()->with('success', 'Checkpoint tracking berhasil ditambahkan.');
    }
}
