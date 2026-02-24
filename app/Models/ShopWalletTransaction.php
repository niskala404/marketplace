<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopWalletTransaction extends Model
{
    protected $fillable = ['shop_wallet_id', 'type', 'amount', 'order_id', 'payout_id', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function wallet() { return $this->belongsTo(ShopWallet::class, 'shop_wallet_id'); }
    public function order() { return $this->belongsTo(Order::class); }
    public function payout() { return $this->belongsTo(Payout::class); }
}
