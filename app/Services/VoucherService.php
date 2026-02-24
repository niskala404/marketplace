<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherRedemption;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VoucherService
{
    /**
     * Validate and compute discount for a given shop subtotal.
     * Returns array: [voucher|null, discount(int), error|null]
     */
    public function preview(?string $code, int $userId, int $shopId, int $subtotal, int $shippingFee = 0): array
    {
        $code = trim((string)$code);
        if ($code === '') return ['voucher' => null, 'discount' => 0, 'error' => null];

        $voucher = Voucher::query()
            ->where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();

        if (!$voucher) return ['voucher' => null, 'discount' => 0, 'error' => 'Voucher tidak ditemukan/aktif.'];

        // scope check
        if ($voucher->shop_id !== null && (int)$voucher->shop_id !== (int)$shopId) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Voucher ini hanya berlaku untuk toko tertentu.'];
        }

        $now = Carbon::now();
        if ($voucher->starts_at && $now->lt($voucher->starts_at)) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Voucher belum berlaku.'];
        }
        if ($voucher->ends_at && $now->gt($voucher->ends_at)) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Voucher sudah berakhir.'];
        }

        if ($subtotal < (int)$voucher->min_subtotal) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Minimal belanja belum terpenuhi.'];
        }

        if ($voucher->usage_limit !== null && (int)$voucher->used_count >= (int)$voucher->usage_limit) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Kuota voucher sudah habis.'];
        }

        $userUsed = VoucherRedemption::query()
            ->where('voucher_id', $voucher->id)
            ->where('user_id', $userId)
            ->count();
        if ($userUsed >= (int)$voucher->per_user_limit) {
            return ['voucher' => null, 'discount' => 0, 'error' => 'Batas penggunaan voucher untuk akun ini sudah tercapai.'];
        }

        $discount = 0;

        // type: percent | nominal | shipping
        if ($voucher->type === 'percent') {
            $discount = (int) floor($subtotal * ((int)$voucher->value / 100));
            if ($voucher->max_discount !== null) {
                $discount = min($discount, (int)$voucher->max_discount);
            }
            $discount = max(0, min($discount, $subtotal));
        } elseif ($voucher->type === 'shipping') {
            $discount = (int) $voucher->value;
            if ($voucher->max_discount !== null) {
                $discount = min($discount, (int)$voucher->max_discount);
            }
            $discount = max(0, min($discount, $shippingFee));
        } else {
            $discount = (int)$voucher->value;
            if ($voucher->max_discount !== null) {
                $discount = min($discount, (int)$voucher->max_discount);
            }
            $discount = max(0, min($discount, $subtotal));
        }

        return ['voucher' => $voucher, 'discount' => $discount, 'error' => null];
    }

    /**
     * Redeem voucher: increments used_count and stores redemption row.
     */
    public function redeem(Voucher $voucher, int $userId, int $orderId, int $discountAmount): void
    {
        DB::transaction(function () use ($voucher, $userId, $orderId, $discountAmount) {
            // lock row to avoid race
            $v = Voucher::whereKey($voucher->id)->lockForUpdate()->firstOrFail();
            if ($v->usage_limit !== null && (int)$v->used_count >= (int)$v->usage_limit) {
                return; // silently skip
            }
            VoucherRedemption::create([
                'voucher_id' => $v->id,
                'user_id' => $userId,
                'order_id' => $orderId,
                'discount_amount' => $discountAmount,
            ]);
            $v->increment('used_count');
        });
    }

    /**
     * Rollback redemption for a cancelled order.
     * - Deletes voucher_redemptions rows for the order
     * - Decrements voucher.used_count accordingly (never below 0)
     */
    public function rollbackForOrder(int $orderId): void
    {
        DB::transaction(function () use ($orderId) {
            $rows = VoucherRedemption::query()->where('order_id', $orderId)->get();
            if ($rows->isEmpty()) {
                return;
            }

            // decrement used_count per voucher id, based on how many redemptions were recorded
            $counts = $rows->groupBy('voucher_id')->map->count();
            foreach ($counts as $voucherId => $cnt) {
                $v = Voucher::query()->whereKey($voucherId)->lockForUpdate()->first();
                if (!$v) continue;
                $new = max(0, (int)$v->used_count - (int)$cnt);
                $v->forceFill(['used_count' => $new])->save();
            }

            VoucherRedemption::query()->where('order_id', $orderId)->delete();
        });
    }
}
