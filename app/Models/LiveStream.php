<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'shop_id',
        'title',
        'description',
        'thumbnail_path',
        'stream_url',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'viewer_count',
        'chat_enabled',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'viewer_count' => 'integer',
        'chat_enabled' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'live_stream_products')
            ->withPivot('sort_order')
            ->orderBy('live_stream_products.sort_order');
    }
}
