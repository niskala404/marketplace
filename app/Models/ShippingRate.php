<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['name','province','city','base_fee','per_kg_fee','is_active'];
}
