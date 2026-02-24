<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\ShipmentEvent;
use App\Services\CourierTrackingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PollOrderTracking extends Command
{
    protected $signature = 'orders:poll-tracking {--dry-run : Only show how many orders would be polled}';
    protected $description = 'Poll courier tracking (RajaOngkir PRO waybill) and append shipment events.';

    public function handle(CourierTrackingService $tracking): int
    {
        if (!$tracking->enabled()) {
            $this->warn('Tracking disabled. Set RAJAONGKIR_TYPE=pro and RAJAONGKIR_KEY.');
            return self::SUCCESS;
        }

        $query = Order::query()
            ->where('status', 'shipped')
            ->where(function ($q) {
                $q->whereNotNull('tracking_number')->orWhereNotNull('tracking_no');
            })
            ->whereNotNull('shipping_courier')
            ->where(function ($q) {
                $q->whereNull('tracking_last_polled_at')
                    ->orWhere('tracking_last_polled_at', '<=', now()->subMinutes(30));
            })
            ->orderBy('tracking_last_polled_at');

        $count = (clone $query)->count();
        $this->info("Orders to poll: {$count}");
        if ($this->option('dry-run')) return self::SUCCESS;

        $query->chunkById(50, function ($orders) use ($tracking) {
            foreach ($orders as $order) {
                $this->pollOne($order, $tracking);
            }
        });

        return self::SUCCESS;
    }

    private function pollOne(Order $order, CourierTrackingService $tracking): void
    {
        $courier = strtolower((string) $order->shipping_courier);
        $waybill = (string) ($order->tracking_number ?: $order->tracking_no);

        $res = $tracking->trackWaybill($courier, $waybill);
        $order->tracking_last_polled_at = now();
        $order->save();

        if (!$res['ok']) {
            return;
        }

        $data = $res['data'] ?? [];
        $manifest = $data['manifest'] ?? [];
        $delivery = $data['delivery_status'] ?? [];
        $delivered = strtoupper((string) ($delivery['status'] ?? '')) === 'DELIVERED';

        DB::transaction(function () use ($order, $manifest, $delivered, $delivery) {
            // insert new manifest entries only
            foreach ($manifest as $m) {
                $title = (string) ($m['manifest_description'] ?? 'Update');
                $desc = trim((string) (($m['city_name'] ?? '') . ' ' . ($m['manifest_code'] ?? '')));
                $date = (string) ($m['manifest_date'] ?? '');
                $time = (string) ($m['manifest_time'] ?? '00:00');
                $happenedAt = null;
                if ($date !== '') {
                    $happenedAt = now()->parse($date.' '.$time);
                }

                $exists = ShipmentEvent::query()
                    ->where('order_id', $order->id)
                    ->where('title', $title)
                    ->where('happened_at', $happenedAt)
                    ->exists();

                if (!$exists) {
                    ShipmentEvent::create([
                        'order_id' => $order->id,
                        'status' => 'tracking',
                        'title' => $title,
                        'description' => $desc,
                        'happened_at' => $happenedAt ?? now(),
                    ]);
                }
            }

            if ($delivered && !$order->delivered_at) {
                $order->delivered_at = now();
                $order->save();
                ShipmentEvent::create([
                    'order_id' => $order->id,
                    'status' => 'delivered',
                    'title' => 'Paket diterima kurir',
                    'description' => (string) ($delivery['pod_receiver'] ?? 'Delivered'),
                    'happened_at' => now(),
                ]);
            }
        });
    }
}
