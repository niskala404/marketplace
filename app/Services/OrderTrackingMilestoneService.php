<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderTrackingMilestoneService
{
    /**
     * Generate initial shipment checkpoint when order becomes shipped.
     * Next checkpoints are expected to be pushed manually by seller/courier integration.
     */
    public function seedShippedMilestones(Order $order): void
    {
        $exists = $order->shipmentEvents()
            ->where('event_code', 'warehouse_received')
            ->exists();

        if ($exists) {
            return;
        }

        $order->logShipmentEvent(
            status: 'shipped',
            title: 'Paket diterima di gudang',
            description: 'Paket sudah masuk sistem logistik dan menunggu update checkpoint berikutnya.',
            when: Carbon::parse($order->shipped_at ?: now()),
            eventCode: 'warehouse_received',
            location: 'Gudang Penjual',
            meta: [
                'tracking_no' => $order->tracking_no,
                'source' => 'seller_manual',
                'next_step' => 'await_checkpoint',
            ],
        );
    }
}
