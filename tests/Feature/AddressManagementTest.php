<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_address_with_geo_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('account.addresses.store'), [
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'phone' => '08123456789',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'village' => 'Dago',
            'postal_code' => '40135',
            'full_address' => 'Jl. Ir. H. Juanda No. 1',
            'detail_address' => 'Rumah warna hijau',
            'latitude' => '-6.8891200',
            'longitude' => '107.6104800',
            'is_default' => '1',
        ]);

        $response->assertRedirect(route('account.addresses.index'));

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'village' => 'Dago',
            'is_default' => true,
        ]);
    }

    public function test_user_can_update_address(): void
    {
        $user = User::factory()->create();
        $address = Address::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'phone' => '08123456789',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'village' => 'Senayan',
            'postal_code' => '12190',
            'full_address' => 'Alamat lama',
            'detail_address' => 'Patokan lama',
            'latitude' => -6.2250000,
            'longitude' => 106.7990000,
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->put(route('account.addresses.update', $address), [
            'label' => 'Kantor',
            'recipient_name' => 'Budi Santoso',
            'phone' => '08123456780',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Setiabudi',
            'village' => 'Karet',
            'postal_code' => '12920',
            'full_address' => 'Alamat baru',
            'detail_address' => 'Dekat halte bus',
            'latitude' => '-6.2088000',
            'longitude' => '106.8456000',
            'is_default' => '1',
        ]);

        $response->assertRedirect(route('account.addresses.index'));

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Kantor',
            'district' => 'Setiabudi',
            'village' => 'Karet',
        ]);
    }

    public function test_user_can_delete_address(): void
    {
        $user = User::factory()->create();
        $address = Address::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'phone' => '08123456789',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'district' => 'Coblong',
            'village' => 'Dago',
            'postal_code' => '40135',
            'full_address' => 'Jl. Dago',
            'latitude' => -6.8891200,
            'longitude' => 107.6104800,
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('account.addresses.destroy', $address));

        $response->assertRedirect();
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }
}
