<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('public-report', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perHour(20)->by($request->ip()),
            ];
        });

        RateLimiter::for('marketplace-write', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return [
                Limit::perMinute(60)->by($key),
                Limit::perMinute(12)->by($key.'|'.$request->path()),
            ];
        });

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
