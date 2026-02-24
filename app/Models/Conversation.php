<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['shop_id','buyer_id','last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function shop() { return $this->belongsTo(Shop::class); }
    public function buyer() { return $this->belongsTo(User::class, 'buyer_id'); }
    public function messages() { return $this->hasMany(Message::class); }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
