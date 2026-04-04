<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStreamComment extends Model
{
    protected $fillable = ['live_stream_id', 'user_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liveStream()
    {
        return $this->belongsTo(LiveStream::class);
    }
}
