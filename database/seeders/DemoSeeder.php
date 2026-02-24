<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ilmishop.test'],
            [
                'name' => 'Admin Ilmishop',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true
            ]
        );

        User::firstOrCreate(
            ['email' => 'seller@ilmishop.test'],
            [
                'name' => 'Seller Ilmishop',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'is_active' => true
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@ilmishop.test'],
            [
                'name' => 'Customer Ilmishop',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true
            ]
        );

        ShippingRate::firstOrCreate(
            ['province' => null, 'city' => null],
            [
                'name' => 'Default',
                'base_fee' => 15000,
                'per_kg_fee' => 2000,
                'is_active' => true,
            ]
        );

        Voucher::firstOrCreate(
            ['code' => 'ILMI10'],
            [
                'name' => 'Diskon 10% (max 20rb)',
                'shop_id' => null,
                'type' => 'percent',
                'value' => 10,
                'min_subtotal' => 50000,
                'max_discount' => 20000,
                'usage_limit' => null,
                'per_user_limit' => 1,
                'is_active' => true,
            ]
        );
    }
}
