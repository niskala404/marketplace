<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Str;
use App\Models\Shop;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;

            if ($user->role === 'seller' && !$user->shop) {
                $name = 'Toko '.$user->name;

                Shop::create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'slug' => Str::slug($name).'-'.Str::random(4),
                    'description' => 'Toko baru',
                    'is_active' => true,
                ]);
            }
        });
    }
}
