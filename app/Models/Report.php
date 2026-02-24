<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'status',
        'admin_note',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function reportable() { return $this->morphTo(); }
}
