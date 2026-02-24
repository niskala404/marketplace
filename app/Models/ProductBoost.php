<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBoost extends Model
{
    protected $fillable = [
        'product_id',
        'bid_cpc',
        'daily_budget',
        'spent_today',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bid_cpc' => 'integer',
        'daily_budget' => 'integer',
        'spent_today' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
