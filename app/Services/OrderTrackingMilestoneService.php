<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderTrackingMilestoneService
{
    /**
     * Generate detailed shipment checkpoints for a shipped order.
     */
    public function seedShippedMilestones(Order $order): void
    {
        $snapshot = json_decode((string) $order->shipping_address_snapshot, true) ?: [];
        $destinationCity = (string) ($snapshot['city'] ?? 'Kota tujuan');
        $destinationProvince = (string) ($snapshot['province'] ?? 'Provinsi tujuan');

        $origin = 'Gudang Penjual';
        $destinationDc = "Distribution Center {$destinationCity}";
        $handoverAt = $order->shipped_at ?: now();

        $events = [
            [
                'status' => 'shipped',
                'event_code' => 'warehouse_received',
                'title' => 'Paket diterima di gudang',
                'description' => 'Paket sudah masuk sistem logistik dan menunggu proses sortir.',
                'location' => $origin,
                'at' => $handoverAt,
            ],
            [
                'status' => 'shipped',
                'event_code' => 'sorting_center',
                'title' => 'Paket di Sorting Center',
                'description' => 'Paket sedang dipilah sesuai rute pengiriman.',
                'location' => $origin,
                'at' => (clone $handoverAt)->addHours(4),
            ],
            [
                'status' => 'shipped',
                'event_code' => 'line_haul',
                'title' => 'Paket dalam perjalanan antar kota',
                'description' => 'Paket sedang dikirim ke distribution center kota tujuan.',
                'location' => "Menuju {$destinationCity}",
                'at' => (clone $handoverAt)->addHours(14),
            ],
            [
                'status' => 'shipped',
                'event_code' => 'destination_dc',
                'title' => 'Paket tiba di distribution center',
                'description' => 'Paket sudah tiba di distribution center kota tujuan.',
                'location' => $destinationDc,
                'at' => (clone $handoverAt)->addDay(),
            ],
            [
                'status' => 'shipped',
                'event_code' => 'courier_delivery',
                'title' => 'Paket dibawa kurir',
                'description' => 'Kurir menuju alamat penerima.',
                'location' => "{$destinationCity}, {$destinationProvince}",
                'at' => (clone $handoverAt)->addDay()->addHours(5),
            ],
        ];

        foreach ($events as $event) {
            $exists = $order->shipmentEvents()
                ->where('event_code', $event['event_code'])
                ->exists();

            if ($exists) {
                continue;
            }

            $order->logShipmentEvent(
                status: $event['status'],
                title: $event['title'],
                description: $event['description'],
                when: Carbon::parse($event['at']),
                eventCode: $event['event_code'],
                location: $event['location'],
                meta: [
                    'tracking_no' => $order->tracking_no,
                ],
            );
        }
    }
}
