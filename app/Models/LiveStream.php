<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        'like_count',
        'share_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
        'viewer_count' => 'integer',
        'like_count'   => 'integer',
        'share_count'  => 'integer',
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

    /**
     * ✅ FIX: Removed ->with('user')->latest()->limit(200) from relationship definition.
     * limit() on a relationship applies globally, NOT per live stream when eager loading.
     * Use this base relationship; apply limits/ordering at query-time in the controller.
     */
    public function comments()
    {
        return $this->hasMany(LiveStreamComment::class);
    }

    public function likes()
    {
        return $this->hasMany(LiveStreamLike::class);
    }

    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // ─────────────────────────────────────────────────────────
    // Viewer count — cache-based active viewer tracking
    // ─────────────────────────────────────────────────────────

    /**
     * Cache key used to store the active viewer token map for this live stream.
     */
    public function viewerCacheKey(): string
    {
        return "live_viewers_{$this->id}";
    }

    /**
     * Register or refresh a viewer token.
     * Tokens expire after 35 seconds of no heartbeat.
     * Returns the updated viewer count.
     */
    public function heartbeat(string $token): int
    {
        $viewers         = Cache::get($this->viewerCacheKey(), []);
        $now             = now()->timestamp;
        $expirySec       = 35;

        // Add / refresh this token
        $viewers[$token] = $now;

        // Remove tokens that haven't pinged in the last 35 seconds
        $viewers = array_filter($viewers, fn($ts) => $ts > ($now - $expirySec));

        $count = count($viewers);

        // Store for 60 seconds (double the heartbeat interval so we don't lose data)
        Cache::put($this->viewerCacheKey(), $viewers, 60);

        // Update the DB column so poll() and the index page show accurate numbers
        $this->updateQuietly(['viewer_count' => $count]);

        return $count;
    }

    /**
     * Get live viewer count from cache (falls back to DB column).
     */
    public function liveViewerCount(): int
    {
        $cached = Cache::get($this->viewerCacheKey());

        if ($cached === null) {
            return (int) $this->viewer_count;
        }

        $now       = now()->timestamp;
        $expirySec = 35;
        $active    = array_filter($cached, fn($ts) => $ts > ($now - $expirySec));

        return count($active);
    }
}