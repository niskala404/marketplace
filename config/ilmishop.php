<?php

return [
    // Platform commission fee in percent (0-100), applied to order subtotal.
    'platform_fee_percent' => (int) env('PLATFORM_FEE_PERCENT', 5),

    // Minimum balance required for payout request (in Rupiah)
    'min_payout_amount' => (int) env('MIN_PAYOUT_AMOUNT', 100000),

    // Manual transfer payment expiry (in hours). Pending orders will be auto-cancelled after this period.
    'manual_transfer_expiry_hours' => (int) env('MANUAL_TRANSFER_EXPIRY_HOURS', 24),

    // Midtrans payment expiry (in minutes) for pending orders.
    'midtrans_expiry_minutes' => (int) env('MIDTRANS_EXPIRY_MINUTES', 30),

    // Midtrans Snap (optional). If server key is empty, Midtrans payment method won't work.
    'midtrans' => [
        'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
    ],

    // RajaOngkir integration (optional). If key is empty, system falls back to demo shipping rates.
    'rajaongkir' => [
        'key' => env('RAJAONGKIR_KEY'),
        // 'starter' or 'basic' or 'pro' (affects available endpoints; cost works on all)
        'type' => env('RAJAONGKIR_TYPE', 'starter'),
        // Base URL for RajaOngkir API (official).
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),
        // Default couriers to query (comma-separated): jne,pos,tiki,sicepat,jnt,anteraja, etc.
        'couriers' => array_filter(array_map('trim', explode(',', env('RAJAONGKIR_COURIERS', 'jne,pos,tiki')))),
    ],

];
