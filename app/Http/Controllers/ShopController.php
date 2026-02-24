<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $shop = Shop::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $products = $shop->products()
            ->where('is_active', true)
            ->with(['images','category'])
            ->orderByDesc('sold_count')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $isFollowing = false;
        if ($request->user()) {
            $isFollowing = $shop->followers()->where('users.id', $request->user()->id)->exists();
        }

        $followersCount = $shop->followers()->count();

        return view('shops.show', compact('shop','products','isFollowing','followersCount'));
    }
}
