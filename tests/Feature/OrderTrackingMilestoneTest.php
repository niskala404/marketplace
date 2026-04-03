<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTrackingMilestoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipped_status_seeds_detailed_tracking_milestones(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $buyer = User::factory()->create();

        $shop = Shop::create([
            'user_id' => $seller->id,
            'name' => 'Toko A',
            'slug' => 'toko-a',
            'description' => 'Desc',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_no' => 'ILMI-TEST-001',
            'user_id' => $buyer->id,
            'shop_id' => $shop->id,
            'status' => 'processing',
            'subtotal' => 100000,
            'shipping_fee' => 10000,
            'discount_amount' => 0,
            'grand_total' => 110000,
            'payment_method' => 'manual_transfer',
            'shipping_address_snapshot' => json_encode([
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
            ]),
        ]);

        $this->actingAs($seller)->post(route('seller.orders.status', $order), [
            'status' => 'shipped',
            'tracking_no' => 'RESI123',
        ])->assertRedirect();

        $this->assertDatabaseHas('shipment_events', [
            'order_id' => $order->id,
            'event_code' => 'warehouse_received',
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'order_id' => $order->id,
            'event_code' => 'destination_dc',
        ]);
    }

    public function test_seller_can_add_manual_checkpoint(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $buyer = User::factory()->create();

        $shop = Shop::create([
            'user_id' => $seller->id,
            'name' => 'Toko B',
            'slug' => 'toko-b',
            'description' => 'Desc',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_no' => 'ILMI-TEST-002',
            'user_id' => $buyer->id,
            'shop_id' => $shop->id,
            'status' => 'shipped',
            'subtotal' => 100000,
            'shipping_fee' => 10000,
            'discount_amount' => 0,
            'grand_total' => 110000,
            'payment_method' => 'manual_transfer',
            'shipping_address_snapshot' => json_encode([
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
            ]),
        ]);

        $this->actingAs($seller)->post(route('seller.orders.checkpoint', $order), [
            'title' => 'Masuk DC Jakarta',
            'location' => 'Distribution Center Jakarta Timur',
            'description' => 'Paket sedang menunggu kurir.',
        ])->assertRedirect();

        $this->assertDatabaseHas('shipment_events', [
            'order_id' => $order->id,
            'event_code' => 'custom_checkpoint',
            'location' => 'Distribution Center Jakarta Timur',
        ]);
    }
}
