<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['user_id','name','slug','description','logo_path','origin_city_id','is_active','is_official'];

    public function user() { return $this->belongsTo(User::class); }
    public function products() { return $this->hasMany(Product::class); }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'shop_follows')
            ->withTimestamps();
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function wallet()
    {
        return $this->hasOne(ShopWallet::class);
    }

    public function kyc()
    {
        return $this->hasOne(SellerKyc::class);
    }

    public function walletOrCreate(): ShopWallet
    {
        return $this->wallet()->firstOrCreate([], ['balance' => 0]);
    }

    /**
     * Total earnings from completed orders (after platform fee).
     */
    public function totalEarnings(): int
    {
        // Prefer wallet ledger if available
        $wallet = $this->wallet;
        if ($wallet) {
            return (int) $wallet->transactions()->where('type', 'order_release')->sum('amount');
        }

        return (int) $this->hasMany(Order::class)
            ->where('status', 'completed')
            ->sum('seller_earnings');
    }

    /**
     * Total paid out to seller.
     */
    public function totalPaidOut(): int
    {
        return (int) $this->payouts()->where('status', 'paid')->sum('amount');
    }

    /**
     * Current withdrawable balance.
     */
    public function balance(): int
    {
        $wallet = $this->wallet;
        if ($wallet) {
            return (int) max(0, $wallet->balance);
        }

        return max(0, $this->totalEarnings() - $this->totalPaidOut());
    }
}
