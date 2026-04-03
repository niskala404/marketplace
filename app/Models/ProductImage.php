<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id','path','image_path','is_primary','sort_order'];
    protected $casts = ['is_primary' => 'boolean'];

    public function getPathAttribute($value)
    {
        return $value ?: $this->attributes['image_path'] ?? null;
    }

    public function product() { return $this->belongsTo(Product::class); }
}
