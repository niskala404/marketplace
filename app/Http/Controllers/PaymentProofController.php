<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    public function upload(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        if ($order->payment_method !== 'manual_transfer') {
            return back()->with('error', 'Pesanan ini tidak memakai transfer manual.');
        }

        if (in_array($order->status, ['paid', 'processing', 'shipped', 'completed'], true)) {
            return back()->with('error', 'Pesanan sudah diproses, bukti transfer tidak bisa diubah.');
        }

        $data = $request->validate([
            'payment_proof' => ['required', 'image', 'max:4096'],
        ]);

        // delete old proof if any
        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $path = $data['payment_proof']->store('payment-proofs', 'public');

        $order->update([
            'payment_proof_path' => $path,
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.');
    }
}
