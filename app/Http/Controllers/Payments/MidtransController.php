<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\MidtransService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    /**
     * Buyer payment page (Snap).
     */
    public function pay(Request $request, Order $order, MidtransService $midtrans)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->payment_method !== 'midtrans') {
            return redirect()->route('orders.show', $order)->with('error', 'Pesanan ini bukan pembayaran Midtrans.');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('info', 'Pesanan ini sudah tidak menunggu pembayaran.');
        }

        if (!$midtrans->enabled()) {
            return redirect()->route('orders.show', $order)->with('error', 'Midtrans belum dikonfigurasi (server/client key).');
        }

        $order->loadMissing(['user', 'items']);

        // create/refresh snap token
        if (!$order->snap_token) {
            $token = $midtrans->createSnapToken($order);
            $order->forceFill([
                'snap_token' => $token,
                'payment_reference' => $order->order_no,
                'payment_status' => 'pending',
            ])->save();
        }

        return view('payments.midtrans.pay', [
            'order' => $order,
            'clientKey' => config('ilmishop.midtrans.client_key'),
        ]);
    }

    /**
     * Midtrans notification webhook.
     * NOTE: Should be reachable publicly.
     */
    public function notify(Request $request, MidtransService $midtrans, VoucherService $vouchers)
    {
        $payload = $request->all();

        if (!$midtrans->verifySignature($payload)) {
            return response()->json(['ok' => false, 'message' => 'invalid signature'], 401);
        }

        $orderNo = (string) ($payload['order_id'] ?? '');
        if ($orderNo === '') {
            return response()->json(['ok' => false, 'message' => 'missing order_id'], 400);
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');

        $order = Order::query()->where('order_no', $orderNo)->first();
        if (!$order) {
            return response()->json(['ok' => false, 'message' => 'order not found'], 404);
        }

        DB::transaction(function () use ($order, $transactionStatus, $fraudStatus, $payload, $vouchers) {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
            if (!$locked) return;

            // only handle midtrans orders
            if ($locked->payment_method !== 'midtrans') return;

            // map midtrans status
            $paidStatuses = ['capture', 'settlement'];
            $cancelStatuses = ['cancel', 'deny', 'expire', 'failure'];

            $newPaymentStatus = $transactionStatus;
            $locked->payment_status = $newPaymentStatus;
            $locked->payment_reference = (string) ($payload['transaction_id'] ?? $locked->payment_reference);

            // Paid
            if (in_array($transactionStatus, $paidStatuses, true)) {
                $old = $locked->status;

                // for capture (credit card) consider fraud_status
                if ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
                    // keep pending, do not mark paid yet
                    $locked->save();
                    return;
                }

                if (!$locked->paid_at) {
                    $locked->paid_at = now();
                }

                if ($locked->status === 'pending') {
                    $locked->status = 'processing';
                }

                $locked->save();

                // ensure escrow hold exists (money is now received)
                $locked->loadMissing('escrow');
                if (!$locked->escrow) {
                    \App\Models\Escrow::create([
                        'order_id' => $locked->id,
                        'amount' => (int) ($locked->grand_total ?? 0),
                        'status' => 'held',
                        'held_at' => now(),
                        'meta' => ['gateway' => 'midtrans', 'transaction_id' => (string)($payload['transaction_id'] ?? null)],
                    ]);
                }

                $new = $locked->status;

                // notify seller
                $locked->loadMissing('shop.user');
                if ($locked->shop?->user) {
                    $locked->shop->user->notify(new OrderStatusChangedNotification($locked, $old, $new));
                }

                // notify buyer
                $locked->loadMissing('user');
                if ($locked->user) {
                    $locked->user->notify(new OrderStatusChangedNotification($locked, $old, $new));
                }

                return;
            }

            // Cancelled/expired
            if (in_array($transactionStatus, $cancelStatuses, true)) {
                $old = $locked->status;
                if ($locked->status === 'cancelled' || $locked->status === 'completed') {
                    $locked->save();
                    return;
                }

                // rollback voucher if any
                if ($locked->voucher_code) {
                    $vouchers->rollbackForOrder($locked->id);
                }

                // restock
                $locked->loadMissing('items.product');
                foreach ($locked->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', (int) $item->qty);
                    }
                }

                $locked->forceFill([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancel_reason' => 'payment_failed',
                ])->save();

                // mark escrow refunded if it exists (edge-case)
                $locked->loadMissing('escrow');
                if ($locked->escrow && $locked->escrow->status === 'held') {
                    $locked->escrow->forceFill([
                        'status' => 'refunded',
                        'refunded_at' => now(),
                    ])->save();
                }

                $new = $locked->status;

                // notify seller/buyer
                $locked->loadMissing(['shop.user', 'user']);
                if ($locked->shop?->user) {
                    $locked->shop->user->notify(new OrderStatusChangedNotification($locked, $old, $new));
                }
                if ($locked->user) {
                    $locked->user->notify(new OrderStatusChangedNotification($locked, $old, $new));
                }

                return;
            }

            // Pending
            $locked->save();
        });

        return response()->json(['ok' => true]);
    }
}
