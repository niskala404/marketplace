<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    protected $fillable = ['flash_sale_id','product_id','promo_price','quota','sold','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function flashSale() { return $this->belongsTo(FlashSale::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Map promo price untuk product ids pada flash sale yang sedang aktif.
     * Return: [product_id => promo_price]
     */
    public static function promoPriceMap(array $productIds): array
    {
        $productIds = array_values(array_unique(array_filter($productIds)));
        if (count($productIds) === 0) return [];

        return self::query()
            ->active()
            ->whereIn('product_id', $productIds)
            ->whereHas('flashSale', fn($q) => $q->activeNow())
            ->get(['product_id','promo_price'])
            ->pluck('promo_price','product_id')
            ->map(fn($v) => (int)$v)
            ->toArray();
    }

    public function remainingQuota(): ?int
    {
        if ($this->quota === null) return null;
        return max(0, (int)$this->quota - (int)$this->sold);
    }
}
