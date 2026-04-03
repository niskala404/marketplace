<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentEvent extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'event_code',
        'title',
        'description',
        'location',
        'meta',
        'happened_at',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
