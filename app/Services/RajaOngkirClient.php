<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirClient
{
    public function enabled(): bool
    {
        return (string) config('ilmishop.rajaongkir.key') !== '';
    }

    /**
     * @return array<int, array{courier:string, service:string, description?:string, etd?:string, fee:int, code:string, label:string}>
     */
    public function getCosts(int $originCityId, int $destinationCityId, int $weightGrams, array $couriers): array
    {
        $key = (string) config('ilmishop.rajaongkir.key');
        $baseUrl = rtrim((string) config('ilmishop.rajaongkir.base_url'), '/');

        $weightGrams = max(1, (int) $weightGrams);
        $results = [];

        foreach ($couriers as $courier) {
            $courier = trim((string) $courier);
            if ($courier === '') continue;

            $resp = Http::asForm()
                ->timeout(10)
                ->withHeaders(['key' => $key])
                ->post($baseUrl.'/cost', [
                    'origin' => $originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weightGrams,
                    'courier' => $courier,
                ]);

            if (!$resp->ok()) {
                continue;
            }

            $json = $resp->json();
            $costs = data_get($json, 'rajaongkir.results.0.costs', []);
            foreach ($costs as $c) {
                $service = (string) data_get($c, 'service', '');
                $desc = (string) data_get($c, 'description', '');
                $value = (int) data_get($c, 'cost.0.value', 0);
                $etd = (string) data_get($c, 'cost.0.etd', '');

                if ($service === '' || $value <= 0) continue;

                $code = strtolower($courier).'_'.strtolower($service);
                $label = strtoupper($courier).' '.$service;

                $results[] = [
                    'code' => $code,
                    'label' => $label,
                    'courier' => strtoupper($courier),
                    'service' => $service,
                    'description' => $desc,
                    'etd' => $etd !== '' ? $etd.' hari' : null,
                    'fee' => $value,
                ];
            }
        }

        // Sort cheapest first
        usort($results, fn($a, $b) => ($a['fee'] <=> $b['fee']));

        return $results;
    }
}
