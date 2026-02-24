<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'shop_id', 'status',
        'reason', 'description',
        'requested_amount', 'approved_amount',
        'seller_note', 'admin_note',
        'evidence_paths', 'return_tracking_no',
        'submitted_at', 'seller_responded_at', 'admin_decided_at',
        'buyer_shipped_at', 'seller_received_at', 'refunded_at',
    ];

    protected $casts = [
        'evidence_paths' => 'array',
        'submitted_at' => 'datetime',
        'seller_responded_at' => 'datetime',
        'admin_decided_at' => 'datetime',
        'buyer_shipped_at' => 'datetime',
        'seller_received_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function shop() { return $this->belongsTo(Shop::class); }
}
