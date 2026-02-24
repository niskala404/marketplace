<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuditLog extends Model
{
    protected $fillable = [
        'admin_user_id',
        'method',
        'path',
        'route_name',
        'ip',
        'user_agent',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function admin() { return $this->belongsTo(User::class, 'admin_user_id'); }
}
