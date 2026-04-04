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
     * Hitung total berat dari cart items.
     */
    protected function calcTotalWeight(Collection $cartItems): int
    {
        $total = 0;
        foreach ($cartItems as $it) {
            $total += (int) ($it->product->weight_grams ?? 500) * (int) $it->qty;
        }
        return $total;
    }

    /**
     * Return a list of shipping options for a given address + items.
     */
    public function options(Address $address, Collection $cartItems): array
    {
        $originCityId      = (int) optional($cartItems->first()?->product?->shop)->origin_city_id;
        $destinationCityId = (int) ($address->rajaongkir_city_id ?? 0);

        if ($this->rajaOngkir->enabled() && $originCityId > 0 && $destinationCityId > 0) {
            $totalWeight = $this->calcTotalWeight($cartItems);
            $couriers    = (array) config('ilmishop.rajaongkir.couriers', ['jne', 'pos', 'tiki']);

            try {
                $options = $this->rajaOngkir->getCosts($originCityId, $destinationCityId, $totalWeight, $couriers);
                if (!empty($options)) {
                    return $options;
                }
            } catch (\Throwable $e) {
                // fall back to demo rates
            }
        }

        // Fallback: demo abstraction
        $base = $this->calculate($address, $cartItems);
        $fee  = (int) $base['fee'];

        return [
            [
                'code'    => 'economy',
                'label'   => 'Ekonomi',
                'courier' => 'ILMI',
                'service' => 'Economy',
                'etd'     => '4-8 hari',
                'fee'     => max(0, (int) floor($fee * 0.8)),
            ],
            [
                'code'    => 'regular',
                'label'   => 'Reguler',
                'courier' => 'ILMI',
                'service' => 'Regular',
                'etd'     => '2-5 hari',
                'fee'     => $fee,
            ],
            [
                'code'    => 'express',
                'label'   => 'Express',
                'courier' => 'ILMI',
                'service' => 'Express',
                'etd'     => '1-3 hari',
                'fee'     => (int) ceil($fee * 1.5),
            ],
        ];
    }

    /**
     * Calculate base shipping cost for address + cart items.
     *
     * @param Address    $address
     * @param Collection $cartItems Collection of CartItem with loaded product
     */
    public function calculate(Address $address, Collection $cartItems): array
    {
        $totalWeight = $this->calcTotalWeight($cartItems);
        $rate        = $this->resolveRate($address);
        $kgUnits     = (int) max(1, (int) ceil($totalWeight / 1000));
        $fee         = (int) $rate->base_fee + (int) $rate->per_kg_fee * $kgUnits;

        return [
            'rate'               => $rate,
            'total_weight_grams' => $totalWeight,
            'kg_units'           => $kgUnits,
            'fee'                => $fee,
        ];
    }

    protected function resolveRate(Address $address): ShippingRate
    {
        $province = trim((string) ($address->province ?? ''));
        $city     = trim((string) ($address->city ?? ''));

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

        return new ShippingRate([
            'name'       => 'Default',
            'province'   => null,
            'city'       => null,
            'base_fee'   => 15000,
            'per_kg_fee' => 0,
            'is_active'  => true,
        ]);
    }
}
