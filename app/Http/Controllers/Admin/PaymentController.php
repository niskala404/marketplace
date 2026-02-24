<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\PaymentVerifiedNotification;
use App\Notifications\PaymentVerifiedSellerNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with(['user', 'shop'])
            ->where('payment_method', 'manual_transfer')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.payments.index', compact('orders'));
    }

    public function verify(Request $request, Order $order)
    {
        if ($order->payment_method !== 'manual_transfer') {
            return back()->with('error', 'Pesanan ini bukan transfer manual.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan sudah berubah.');
        }

        if (!$order->payment_proof_path) {
            return back()->with('error', 'Belum ada bukti transfer.');
        }

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_verified_by' => $request->user()->id,
            'payment_verified_at' => now(),
        ]);

        // create escrow hold (money received)
        $order->loadMissing('escrow');
        if (!$order->escrow) {
            \App\Models\Escrow::create([
                'order_id' => $order->id,
                'amount' => (int) ($order->grand_total ?? 0),
                'status' => 'held',
                'held_at' => now(),
                'meta' => ['payment_method' => 'manual_transfer'],
            ]);
        }

        $order->loadMissing(['user', 'shop.user']);
        if ($order->user) {
            $order->user->notify(new PaymentVerifiedNotification($order));
        }
        if ($order->shop?->user) {
            $order->shop->user->notify(new PaymentVerifiedSellerNotification($order));
        }

        return back()->with('success', 'Pembayaran diverifikasi. Pesanan menjadi PAID.');
    }

    public function reject(Request $request, Order $order)
    {
        if ($order->payment_method !== 'manual_transfer') {
            return back()->with('error', 'Pesanan ini bukan transfer manual.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan sudah berubah.');
        }

        // Keep status pending, just clear proof so buyer can re-upload
        $order->update([
            'payment_proof_path' => null,
        ]);

        return back()->with('success', 'Bukti transfer ditolak. Pembeli bisa unggah ulang.');
    }
}
