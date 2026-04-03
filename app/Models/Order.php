<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    protected $fillable = [
        'order_no','user_id','shop_id','status',
        'subtotal','shipping_fee','shipping_discount','shipping_courier','shipping_service','shipping_etd','platform_fee','seller_earnings','commission_percent','settled_at',
        'voucher_code','affiliate_code','affiliate_user_id','discount_amount','grand_total',
        'payment_method','payment_gateway','payment_reference','payment_status','snap_token',
        'payment_proof_path','paid_at','payment_verified_by','payment_verified_at',
        'expires_at','cancelled_at','cancel_reason',
        'refunded_at',
        'shipping_address_snapshot','tracking_no','tracking_number','tracking_last_polled_at','shipped_at','delivered_at','received_at','completed_at'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function shop() { return $this->belongsTo(Shop::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    public function shipmentEvents() { return $this->hasMany(ShipmentEvent::class)->orderBy('happened_at'); }

    public function escrow() { return $this->hasOne(Escrow::class); }

    public function dispute() { return $this->hasOne(Dispute::class); }

    public function verifier() { return $this->belongsTo(User::class, 'payment_verified_by'); }

    public function logShipmentEvent(
        string $status,
        string $title,
        ?string $description = null,
        $when = null,
        ?string $eventCode = null,
        ?string $location = null,
        ?array $meta = null
    ): void
    {
        $when = $when ?: now();
        $this->shipmentEvents()->create([
            'status' => $status,
            'event_code' => $eventCode ?: $status,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'meta' => $meta,
            'happened_at' => $when,
        ]);
    }

    /**
     * Create escrow hold record when payment is confirmed.
     * Idempotent.
     */
    public function holdEscrowIfNeeded(): void
    {
        DB::transaction(function () {
            $locked = self::query()->whereKey($this->getKey())->lockForUpdate()->first();
            if (!$locked) return;

            $locked->loadMissing('escrow');
            if ($locked->escrow) return;

            $amount = (int) ($locked->grand_total ?? 0);
            if ($amount <= 0) return;

            Escrow::create([
                'order_id' => $locked->id,
                'amount' => $amount,
                'status' => 'held',
                'held_at' => now(),
                'meta' => ['payment_method' => $locked->payment_method, 'order_no' => $locked->order_no],
            ]);
        });
    }

    /**
     * Settle platform commission and seller earnings when order is completed.
     * Idempotent: will not re-settle if already settled.
     */
    public function settleCommissionIfNeeded(): void
    {
        if ($this->status !== 'completed') {
            return;
        }
        if ($this->settled_at) {
            return;
        }

        DB::transaction(function () {
            // lock order row to keep idempotent even under concurrency
            $order = self::query()->whereKey($this->getKey())->lockForUpdate()->first();
            if (!$order || $order->settled_at || $order->status !== 'completed') {
                return;
            }

            // if escrow exists but already released, treat as settled
            if ($order->escrow && $order->escrow->status === 'released') {
                $order->forceFill(['settled_at' => $order->settled_at ?? now()])->save();
                return;
            }

            $percent = (int) config('ilmishop.platform_fee_percent', 0);
            $percent = max(0, min(100, $percent));

            $platformFee = (int) floor(((int)$order->subtotal * $percent) / 100);
            $sellerEarnings = max(0, (int)$order->subtotal - $platformFee);

            $order->forceFill([
                'commission_percent' => $percent,
                'platform_fee' => $platformFee,
                'seller_earnings' => $sellerEarnings,
                'settled_at' => now(),
            ])->save();

            // credit seller wallet (escrow release)
            $order->loadMissing('shop');
            if ($order->shop) {
                $wallet = $order->shop->walletOrCreate();
                // idempotent guard: do not double-credit for same order
                $exists = ShopWalletTransaction::query()
                    ->where('shop_wallet_id', $wallet->id)
                    ->where('type', 'order_release')
                    ->where('order_id', $order->id)
                    ->exists();
                if (!$exists && $sellerEarnings > 0) {
                    $wallet->increment('balance', $sellerEarnings);
                    ShopWalletTransaction::create([
                        'shop_wallet_id' => $wallet->id,
                        'type' => 'order_release',
                        'amount' => $sellerEarnings,
                        'order_id' => $order->id,
                        'meta' => ['order_no' => $order->order_no],
                    ]);
                }
            }

            // record platform fee
            if ($platformFee > 0) {
                $pfExists = PlatformWalletTransaction::query()
                    ->where('type', 'platform_fee')
                    ->where('order_id', $order->id)
                    ->exists();
                if (!$pfExists) {
                    PlatformWalletTransaction::create([
                        'type' => 'platform_fee',
                        'amount' => $platformFee,
                        'order_id' => $order->id,
                        'meta' => ['order_no' => $order->order_no],
                    ]);
                }
            }

            // mark escrow released if any
            $order->loadMissing('escrow');
            if ($order->escrow && $order->escrow->status === 'held') {
                $order->escrow->forceFill([
                    'status' => 'released',
                    'released_at' => now(),
                ])->save();
            }

            // update sold_count per product (counted once, tied to settled_at)
            $order->loadMissing('items');
            foreach ($order->items as $item) {
                Product::query()->whereKey($item->product_id)->increment('sold_count', (int) $item->qty);
            }

            // Coins reward for buyer (lightweight: 1% of subtotal)
            $order->loadMissing('user');
            if ($order->user) {
                $wallet = $order->user->walletOrCreate();
                $coins = (int) floor(((int)$order->subtotal) * 0.01);
                if ($coins > 0) {
                    $coinsExists = UserWalletTransaction::query()
                        ->where('user_wallet_id', $wallet->id)
                        ->where('type', 'coins_reward')
                        ->where('order_id', $order->id)
                        ->exists();
                    if (!$coinsExists) {
                        $wallet->increment('coins_balance', $coins);
                        UserWalletTransaction::create([
                            'user_wallet_id' => $wallet->id,
                            'type' => 'coins_reward',
                            'amount' => $coins,
                            'order_id' => $order->id,
                            'meta' => ['order_no' => $order->order_no],
                        ]);
                    }
                }
            }

            // Affiliate commission (lightweight: per order)
            if ($order->affiliate_code) {
                $link = AffiliateLink::query()->where('code', $order->affiliate_code)->where('is_active', true)->first();
                if ($link) {
                    $exists = AffiliateCommission::query()->where('order_id', $order->id)->exists();
                    if (!$exists) {
                        $bp = max(0, min(2000, (int) $link->commission_rate_bp));
                        $base = (int) $order->subtotal;
                        $commission = (int) floor(($base * $bp) / 10000);
                        if ($commission > 0) {
                            AffiliateCommission::create([
                                'affiliate_link_id' => $link->id,
                                'user_id' => $link->user_id,
                                'order_id' => $order->id,
                                'base_amount' => $base,
                                'commission_amount' => $commission,
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);

                            $affUser = User::query()->whereKey($link->user_id)->first();
                            if ($affUser) {
                                $affWallet = $affUser->walletOrCreate();
                                $affWallet->increment('balance', $commission);
                                UserWalletTransaction::create([
                                    'user_wallet_id' => $affWallet->id,
                                    'type' => 'affiliate_commission',
                                    'amount' => $commission,
                                    'order_id' => $order->id,
                                    'meta' => ['order_no' => $order->order_no, 'code' => $link->code],
                                ]);

                                PlatformWalletTransaction::create([
                                    'type' => 'affiliate_payout',
                                    'amount' => -$commission,
                                    'order_id' => $order->id,
                                    'meta' => ['code' => $link->code],
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Refund escrow for this order (full or partial).
     *
     * Rules:
     * - If escrow is held: mark escrow refunded, no seller credit.
     * - If escrow already released: debit seller wallet up to available balance (cannot go negative).
     *   Any remainder is recorded on platform ledger as 'refund_uncollected'.
     * - Idempotent: repeated calls will not double-refund.
     */
    public function refundEscrowIfNeeded(int $amount, string $reason = 'dispute_refund', array $meta = []): void
    {
        $amount = max(0, (int) $amount);
        if ($amount <= 0) return;

        DB::transaction(function () use ($amount, $reason, $meta) {
            $order = self::query()->whereKey($this->getKey())->lockForUpdate()->first();
            if (!$order) return;

            $order->loadMissing('escrow', 'shop', 'user');
            if (!$order->escrow) {
                return;
            }

            // Already refunded
            if ($order->escrow->status === 'refunded') {
                if ($order->status !== 'refunded') {
                    $order->forceFill(['status' => 'refunded', 'refunded_at' => $order->refunded_at ?: now()])->save();
                }
                return;
            }

            $refundAmount = min($amount, (int) $order->escrow->amount);
            if ($refundAmount <= 0) return;

            // Credit buyer wallet (idempotent)
            if ($order->user && $refundAmount > 0) {
                $buyerWallet = $order->user->walletOrCreate();
                $creditExists = UserWalletTransaction::query()
                    ->where('user_wallet_id', $buyerWallet->id)
                    ->where('type', 'refund_credit')
                    ->where('order_id', $order->id)
                    ->exists();
                if (!$creditExists) {
                    $buyerWallet->increment('balance', $refundAmount);
                    UserWalletTransaction::create([
                        'user_wallet_id' => $buyerWallet->id,
                        'type' => 'refund_credit',
                        'amount' => $refundAmount,
                        'order_id' => $order->id,
                        'meta' => ['order_no' => $order->order_no, 'reason' => $reason] + $meta,
                    ]);
                }
            }

            if ($order->escrow->status === 'held') {
                $order->escrow->forceFill([
                    'status' => 'refunded',
                    'refunded_at' => now(),
                    'meta' => array_merge((array)($order->escrow->meta ?? []), [
                        'refund_reason' => $reason,
                        'refund_amount' => $refundAmount,
                    ], $meta),
                ])->save();

                PlatformWalletTransaction::create([
                    'type' => 'refund_out',
                    'amount' => -$refundAmount,
                    'order_id' => $order->id,
                    'meta' => ['order_no' => $order->order_no, 'reason' => $reason] + $meta,
                ]);
            } elseif ($order->escrow->status === 'released') {
                $wallet = $order->shop ? $order->shop->walletOrCreate() : null;
                $available = $wallet ? (int) $wallet->balance : 0;
                $debitApplied = min($available, $refundAmount);
                $remainder = $refundAmount - $debitApplied;

                if ($wallet && $debitApplied > 0) {
                    $wallet->forceFill(['balance' => $available - $debitApplied])->save();
                    ShopWalletTransaction::create([
                        'shop_wallet_id' => $wallet->id,
                        'type' => 'refund_debit',
                        'amount' => -$debitApplied,
                        'order_id' => $order->id,
                        'meta' => ['order_no' => $order->order_no, 'reason' => $reason] + $meta,
                    ]);
                }

                PlatformWalletTransaction::create([
                    'type' => 'refund_out',
                    'amount' => -$refundAmount,
                    'order_id' => $order->id,
                    'meta' => ['order_no' => $order->order_no, 'reason' => $reason, 'clawback_applied' => $debitApplied, 'clawback_remaining' => $remainder] + $meta,
                ]);

                if ($remainder > 0) {
                    PlatformWalletTransaction::create([
                        'type' => 'refund_uncollected',
                        'amount' => $remainder,
                        'order_id' => $order->id,
                        'meta' => ['order_no' => $order->order_no, 'reason' => $reason] + $meta,
                    ]);
                }

                $order->escrow->forceFill([
                    'status' => 'refunded',
                    'refunded_at' => now(),
                    'meta' => array_merge((array)($order->escrow->meta ?? []), [
                        'refund_reason' => $reason,
                        'refund_amount' => $refundAmount,
                        'clawback_applied' => $debitApplied,
                        'clawback_remaining' => $remainder,
                    ], $meta),
                ])->save();
            }

            $order->forceFill([
                'status' => 'refunded',
                'refunded_at' => $order->refunded_at ?: now(),
            ])->save();
        });
    }
}
