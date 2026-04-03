<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id','category_id','name','slug','description','price','weight_grams','stock','is_active',
        'sold_count',
        'discount_type','discount_value',
        'approval_status','rejected_reason'
    ];

    protected $casts = [
        'discount_value' => 'integer',
    ];

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function shop() { return $this->belongsTo(Shop::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function variantOptions() { return $this->hasMany(ProductVariantOption::class)->orderBy('sort_order'); }

    public function mainImageUrl(): string
    {
        $img = $this->images()->orderByDesc('is_primary')->orderBy('sort_order')->first();
        return $img ? asset('storage/'.$img->path) : asset('images/placeholder.png');
    }

    public function hasDiscount(): bool
    {
        $type = (string) ($this->discount_type ?? 'none');
        $val = (int) ($this->discount_value ?? 0);
        return $type !== 'none' && $val > 0;
    }

    public function discountedPrice(): int
    {
        $base = (int) ($this->price ?? 0);
        $type = (string) ($this->discount_type ?? 'none');
        $val = (int) ($this->discount_value ?? 0);

        if ($base <= 0 || $val <= 0 || $type === 'none') return $base;

        if ($type === 'percent') {
            $pct = max(0, min(100, $val));
            $disc = (int) floor($base * ($pct / 100));
            return max(0, $base - $disc);
        }

        if ($type === 'amount') {
            return max(0, $base - min($base, $val));
        }

        return $base;
    }

    public function avgRating(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    public function ratingCount(): int
    {
        return (int) ($this->reviews()->count() ?? 0);
    }
}
