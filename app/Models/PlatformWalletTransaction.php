<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformWalletTransaction extends Model
{
    protected $fillable = ['type', 'amount', 'order_id', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
