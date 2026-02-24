<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletTransaction extends Model
{
    protected $fillable = [
        'user_wallet_id',
        'type',
        'amount',
        'order_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(UserWallet::class, 'user_wallet_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
