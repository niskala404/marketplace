<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code','name','shop_id','type','value','min_subtotal','max_discount',
        'usage_limit','per_user_limit','used_count','starts_at','ends_at','is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function redemptions()
    {
        return $this->hasMany(VoucherRedemption::class);
    }
}
