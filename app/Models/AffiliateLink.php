<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'product_id',
        'shop_id',
        'commission_rate_bp',
        'is_active',
    ];

    protected $casts = [
        'commission_rate_bp' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function shop() { return $this->belongsTo(Shop::class); }
    public function commissions() { return $this->hasMany(AffiliateCommission::class); }
}
