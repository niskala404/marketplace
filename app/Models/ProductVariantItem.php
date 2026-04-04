<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    protected $fillable = [
        'product_variant_id',
        'product_variant_option_id',
        'value',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function option()
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_option_id');
    }
}
