<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CourierTrackingService
{
    public function enabled(): bool
    {
        return (string) config('ilmishop.rajaongkir.key') !== '' && strtolower((string) config('ilmishop.rajaongkir.type')) === 'pro';
    }

    /**
     * Track shipment by waybill (RajaOngkir PRO).
     *
     * @return array{ok:bool, data?:array, message?:string}
     */
    public function trackWaybill(string $courier, string $waybill): array
    {
        if (!$this->enabled()) {
            return ['ok' => false, 'message' => 'tracking_disabled'];
        }

        $key = (string) config('ilmishop.rajaongkir.key');
        $url = 'https://pro.rajaongkir.com/api/waybill';

        try {
            $resp = Http::timeout(15)
                ->asForm()
                ->withHeaders(['key' => $key])
                ->post($url, [
                    'waybill' => $waybill,
                    'courier' => $courier,
                ]);

            if (!$resp->ok()) {
                return ['ok' => false, 'message' => 'http_'.$resp->status()];
            }

            $json = $resp->json();
            $result = $json['rajaongkir']['result'] ?? null;
            if (!$result) {
                return ['ok' => false, 'message' => 'invalid_response'];
            }

            return ['ok' => true, 'data' => $result];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'exception'];
        }
    }
}
