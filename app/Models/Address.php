<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id','label','recipient_name','phone','province','city','rajaongkir_city_id','district',
        'village','postal_code','full_address','detail_address','latitude','longitude','is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
