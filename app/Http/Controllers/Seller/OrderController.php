<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop->id;
        $orders = Order::where('shop_id',$shopId)->with('user')->latest()->paginate(10);
        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);
        $order->load(['items','shipmentEvents']);
        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);

        $request->validate([
            'status' => ['required','in:pending,paid,processing,shipped,completed,cancelled'],
            'tracking_no' => ['nullable','string','max:80'],
        ]);

        $payload = ['status' => $request->status];
        $oldStatus = $order->status;

        if ($request->status === 'shipped') {
            if ($request->filled('tracking_no')) {
                $payload['tracking_no'] = $request->tracking_no;
                $payload['tracking_number'] = $request->tracking_no;
            }
            $payload['shipped_at'] = now();
        }

        if ($request->status === 'paid' && !$order->paid_at) {
            $payload['paid_at'] = now();
        }

        if ($request->status === 'completed' && !$order->completed_at) {
            $payload['completed_at'] = now();
            // if seller manually completes, consider received_at as now (MVP)
            $payload['received_at'] = $payload['received_at'] ?? now();
        }

        $order->update($payload);

        // shipment timeline events (Shopee-like tracking)
        if ($oldStatus !== $order->status) {
            if ($order->status === 'paid') {
                $order->logShipmentEvent('paid', 'Pembayaran diterima', 'Pesanan akan segera diproses.');
            }
            if ($order->status === 'processing') {
                $order->logShipmentEvent('processing', 'Pesanan diproses', 'Penjual sedang menyiapkan pesanan.');
            }
            if ($order->status === 'shipped') {
                $desc = $order->tracking_no ? ('Nomor resi: '.$order->tracking_no) : null;
                $order->logShipmentEvent('shipped', 'Pesanan dikirim', $desc);
            }
            if ($order->status === 'completed') {
                $order->logShipmentEvent('completed', 'Pesanan selesai', 'Dana diproses ke penjual.');
            }
            if ($order->status === 'cancelled') {
                $order->logShipmentEvent('cancelled', 'Pesanan dibatalkan', $order->cancel_reason ?: null);
            }
        }

        // settle commission when completed
        if ($order->status === 'completed') {
            $order->settleCommissionIfNeeded();
        }

        // notify buyer
        $order->loadMissing('user');
        if ($order->user && $oldStatus !== $order->status) {
            $order->user->notify(new OrderStatusChangedNotification($order, $oldStatus, $order->status));
        }
        return back()->with('success','Status order diperbarui.');
    }

    public function markDelivered(Request $request, Order $order)
    {
        abort_if($order->shop_id !== auth()->user()->shop->id, 403);

        // MVP: seller can mark delivered after shipped (for later courier integration)
        abort_if($order->status !== 'shipped', 422);

        if (!$order->delivered_at) {
            $order->update(['delivered_at' => now()]);

            $order->logShipmentEvent('delivered', 'Pesanan sampai', 'Paket sudah sampai di alamat tujuan.');

            // notify buyer about delivery milestone
            $order->loadMissing('user');
            if ($order->user) {
                $order->user->notify(new OrderDeliveredNotification($order));
            }
        }

        return back()->with('success', 'Pesanan ditandai sudah sampai (delivered).');
    }
}
