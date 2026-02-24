<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Prevent sellers from entering seller area before creating a Shop.
 */
class EnsureSellerHasShop
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) abort(403);

        // Only enforce for sellers. (Admin/buyer routes should not use this middleware.)
        if (($user->role ?? null) === 'seller' && !$user->shop) {
            // Allow access to shop onboarding routes.
            if ($request->routeIs('seller.shop.create') || $request->routeIs('seller.shop.store')
                || $request->routeIs('seller.shop.edit') || $request->routeIs('seller.shop.update')) {
                return $next($request);
            }

            return redirect()
                ->route('seller.shop.create')
                ->with('error', 'Kamu belum punya toko. Silakan buat toko dulu.');
        }

        return $next($request);
    }
}
