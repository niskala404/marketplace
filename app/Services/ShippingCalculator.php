<?php

namespace App\Services;

use App\Models\ShippingRate;
use App\Models\Address;
use Illuminate\Support\Collection;

use App\Services\RajaOngkirClient;

class ShippingCalculator
{
    public function __construct(protected RajaOngkirClient $rajaOngkir)
    {
    }

    /**
     * Return a list of shipping options for a given address + items.
     * This is a demo abstraction (no external courier API yet).
     */
    public function options(Address $address, Collection $cartItems): array
    {
        // If RajaOngkir is configured and we have city IDs for origin+destination, query real courier costs.
        $originCityId = (int) optional($cartItems->first()?->product?->shop)->origin_city_id;
        $destinationCityId = (int) ($address->rajaongkir_city_id ?? 0);

        if ($this->rajaOngkir->enabled() && $originCityId > 0 && $destinationCityId > 0) {
            $totalWeight = 0;
            foreach ($cartItems as $it) {
                $w = (int)($it->product->weight_grams ?? 500);
                $totalWeight += $w * (int)$it->qty;
            }

            $couriers = (array) config('ilmishop.rajaongkir.couriers', ['jne','pos','tiki']);
            try {
                $options = $this->rajaOngkir->getCosts($originCityId, $destinationCityId, $totalWeight, $couriers);
                if (!empty($options)) {
                    return $options;
                }
            } catch (\Throwable $e) {
                // fall back to demo rates
            }
        }

        // Fallback: demo abstraction (no external courier API).
        $base = $this->calculate($address, $cartItems);
        $fee = (int) $base['fee'];

        // Multipliers are demo defaults.
        return [
            [
                "code" => "economy",
                "label" => "Ekonomi",
                "courier" => "ILMI",
                "service" => "Economy",
                "etd" => "4-8 hari",
                "fee" => max(0, (int) floor($fee * 0.8)),
            ],
            [
                "code" => "regular",
                "label" => "Reguler",
                "courier" => "ILMI",
                "service" => "Regular",
                "etd" => "2-5 hari",
                "fee" => $fee,
            ],
            [
                "code" => "express",
                "label" => "Express",
                "courier" => "ILMI",
                "service" => "Express",
                "etd" => "1-3 hari",
                "fee" => (int) ceil($fee * 1.5),
            ],
        ];
    }

    /** /**
     * @param Address $address
     * @param Collection $cartItems collection of CartItem with loaded product
     */
    public function calculate(Address $address, Collection $cartItems): array
    {
        $totalWeight = 0;
        foreach ($cartItems as $it) {
            $w = (int)($it->product->weight_grams ?? 500);
            $totalWeight += $w * (int)$it->qty;
        }

        $rate = $this->resolveRate($address);

        $kgUnits = (int) max(1, (int) ceil($totalWeight / 1000));
        $fee = (int)$rate->base_fee + (int)$rate->per_kg_fee * $kgUnits;

        return [
            'rate' => $rate,
            'total_weight_grams' => $totalWeight,
            'kg_units' => $kgUnits,
            'fee' => $fee,
        ];
    }

    protected function resolveRate(Address $address): ShippingRate
    {
        $province = trim((string)($address->province ?? ''));
        $city = trim((string)($address->city ?? ''));

        // Priority: city match, then province match, else default
        $q = ShippingRate::query()->where('is_active', true);

        if ($city !== '') {
            $rate = (clone $q)->whereNotNull('city')->where('city', $city)->first();
            if ($rate) return $rate;
        }

        if ($province !== '') {
            $rate = (clone $q)->whereNull('city')->whereNotNull('province')->where('province', $province)->first();
            if ($rate) return $rate;
        }

        $default = (clone $q)->whereNull('province')->whereNull('city')->first();
        if ($default) return $default;

        // fallback instance
        return new ShippingRate([
            'name' => 'Default',
            'province' => null,
            'city' => null,
            'base_fee' => 15000,
            'per_kg_fee' => 0,
            'is_active' => true,
        ]);
    }
}
