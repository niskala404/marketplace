<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\CourierTrackingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PollShipmentTracking extends Command
{
    protected $signature = 'orders:poll-tracking {--limit=50 : Max orders to check each run}';
    protected $description = 'Poll courier tracking (RajaOngkir PRO waybill) for shipped orders and append shipment events.';

    public function handle(CourierTrackingService $tracking): int
    {
        if (!$tracking->enabled()) {
            $this->info('Tracking is disabled. Set RAJAONGKIR_TYPE=pro and RAJAONGKIR_KEY.');
            return self::SUCCESS;
        }

        $limit = max(1, (int) $this->option('limit'));

        $orders = Order::query()
            ->where('status', 'shipped')
            ->whereNotNull('tracking_no')
            ->whereNotNull('shipping_courier')
            ->orderBy('shipped_at')
            ->limit($limit)
            ->get();

        $updated = 0;
        foreach ($orders as $order) {
            $result = $tracking->track((string) $order->shipping_courier, (string) $order->tracking_no);
            if (!$result) continue;

            DB::transaction(function () use ($order, $result, &$updated) {
                $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
                if (!$locked || $locked->status !== 'shipped') return;

                $locked->loadMissing('shipmentEvents');

                $seen = $locked->shipmentEvents->pluck('description')->filter()->all();
                foreach ((array) ($result['events'] ?? []) as $ev) {
                    $desc = trim((string) ($ev['desc'] ?? ''));
                    if ($desc === '') continue;
                    if (in_array($desc, $seen, true)) continue;

                    $locked->logShipmentEvent('tracking', 'Update kurir', $desc);
                }

                if (($result['status'] ?? null) === 'DELIVERED' && !$locked->delivered_at) {
                    $locked->forceFill([
                        'delivered_at' => now(),
                    ])->save();
                    $locked->logShipmentEvent('delivered', 'Pesanan sampai', 'Kurir menandai paket sudah sampai.');
                }

                $updated++;
            });
        }

        $this->info("Checked {$orders->count()} orders; updated {$updated}.");
        return self::SUCCESS;
    }
}
