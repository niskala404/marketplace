<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\VoucherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired {--dry-run : Only show how many orders would be cancelled}';
    protected $description = 'Auto-cancel pending orders that have passed expires_at (manual transfer & midtrans) and restock items.';

    public function handle(): int
    {
        $now = now();

        $q = Order::query()
            ->where('status', 'pending')
            ->whereIn('payment_method', ['manual_transfer', 'midtrans'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->whereNull('cancelled_at');

        $count = (clone $q)->count();
        if ($this->option('dry-run')) {
            $this->info("[DRY RUN] Would cancel {$count} expired orders.");
            return self::SUCCESS;
        }

        $processed = 0;
        $q->orderBy('id')->chunkById(50, function ($orders) use (&$processed) {
            foreach ($orders as $order) {
                DB::transaction(function () use ($order, &$processed) {
                    $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->first();
                    if (!$locked) return;
                    if ($locked->status !== 'pending') return;
                    if (!in_array($locked->payment_method, ['manual_transfer', 'midtrans'], true)) return;
                    if (!$locked->expires_at || $locked->expires_at->isFuture()) return;
                    if ($locked->cancelled_at) return;

                    $locked->loadMissing('items');

                    $old = $locked->status;

                    // restore stock
                    foreach ($locked->items as $item) {
                        Product::query()->whereKey($item->product_id)->increment('stock', (int) $item->qty);
                    }

                    $locked->forceFill([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancel_reason' => $locked->payment_method === 'midtrans' ? 'expired_payment' : 'expired_unpaid',
                    ])->save();

                    // rollback voucher usage if any
                    app(VoucherService::class)->rollbackForOrder((int) $locked->id);

                    // notify buyer & seller
                    $locked->loadMissing(['user', 'shop.user']);
                    if ($locked->user) {
                        $locked->user->notify(new OrderStatusChangedNotification($locked, $old, $locked->status));
                    }
                    if ($locked->shop?->user) {
                        $locked->shop->user->notify(new OrderStatusChangedNotification($locked, $old, $locked->status));
                    }

                    $processed++;
                });
            }
        });

        $this->info("Cancelled {$processed} expired orders.");
        return self::SUCCESS;
    }
}
