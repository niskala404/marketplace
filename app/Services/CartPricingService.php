<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class CartPricingService
{
    public function resolveUnitPrice(Product $product, ?ProductVariant $variant = null, array $flashPriceMap = []): int
    {
        if (array_key_exists((int) $product->id, $flashPriceMap)) {
            return (int) $flashPriceMap[(int) $product->id];
        }

        if ($variant && $variant->price !== null) {
            return (int) $variant->price;
        }

        if (method_exists($product, 'discountedPrice')) {
            return (int) $product->discountedPrice();
        }

        return (int) $product->price;
    }
}

