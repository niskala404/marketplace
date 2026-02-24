<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'affiliate_link_id',
        'user_id',
        'order_id',
        'base_amount',
        'commission_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'base_amount' => 'integer',
        'commission_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function link() { return $this->belongsTo(AffiliateLink::class, 'affiliate_link_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
