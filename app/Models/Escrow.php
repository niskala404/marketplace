<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    protected $fillable = ['order_id', 'amount', 'status', 'held_at', 'released_at', 'refunded_at', 'meta'];

    protected $casts = [
        'held_at' => 'datetime',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
        'meta' => 'array',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
