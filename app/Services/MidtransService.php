<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    public function enabled(): bool
    {
        return (string) config('ilmishop.midtrans.server_key') !== '' && (string) config('ilmishop.midtrans.client_key') !== '';
    }

    private function baseUrl(): string
    {
        return config('ilmishop.midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }

    /**
     * Create/refresh Snap token for a given order.
     */
    public function createSnapToken(Order $order): string
    {
        $serverKey = (string) config('ilmishop.midtrans.server_key');
        if ($serverKey === '') {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diisi.');
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_no,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Customer',
                'email' => $order->user->email ?? null,
                'phone' => data_get($order->user, 'phone'),
            ],
            'item_details' => $order->items->map(function ($it) {
                return [
                    'id' => (string) $it->product_id,
                    'price' => (int) $it->price,
                    'quantity' => (int) $it->qty,
                    'name' => mb_substr((string) $it->product_name, 0, 50),
                ];
            })->values()->all(),
            'expiry' => [
                'unit' => 'minutes',
                'duration' => (int) config('ilmishop.midtrans_expiry_minutes', 30),
            ],
            'callbacks' => [
                'finish' => route('orders.show', $order),
            ],
        ];

        $resp = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl().'/snap/v1/transactions', $payload);

        if (!$resp->successful()) {
            throw new \RuntimeException('Gagal membuat transaksi Midtrans: '.$resp->body());
        }

        $token = (string) ($resp->json('token') ?? '');
        if ($token === '') {
            throw new \RuntimeException('Token Midtrans kosong.');
        }

        return $token;
    }

    /**
     * Verify notification signature.
     */
    public function verifySignature(array $payload): bool
    {
        $serverKey = (string) config('ilmishop.midtrans.server_key');
        $sig = (string) ($payload['signature_key'] ?? '');
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');

        if ($serverKey === '' || $sig === '' || $orderId === '' || $statusCode === '' || $grossAmount === '') {
            return false;
        }

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        return hash_equals($expected, $sig);
    }
}
