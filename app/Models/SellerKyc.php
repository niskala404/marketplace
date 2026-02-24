<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerKyc extends Model
{
    protected $fillable = [
        'shop_id',
        'ktp_number',
        'ktp_image_path',
        'selfie_image_path',
        'status',
        'admin_note',
        'submitted_at',
        'verified_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function shop() { return $this->belongsTo(Shop::class); }
}
