<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        if ($user->shop) {
            return redirect()->route('seller.shop.edit');
        }

        return view('seller.shop.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->shop) {
            return redirect()->route('seller.shop.edit');
        }

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string','max:2000'],
            'origin_city_id' => ['nullable','integer','min:1'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;
        while (Shop::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i;
            $i++;
        }

        Shop::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'origin_city_id' => $data['origin_city_id'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('seller.dashboard')->with('success','Toko berhasil dibuat.');
    }

    public function edit()
    {
        $user = auth()->user();
        if (!$user->shop) {
            return redirect()->route('seller.shop.create');
        }

        $shop = $user->shop;
        return view('seller.shop.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        if (!$user->shop) {
            return redirect()->route('seller.shop.create');
        }

        $shop = $user->shop;

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string','max:2000'],
            'origin_city_id' => ['nullable','integer','min:1'],
            'is_active' => ['nullable','boolean'],
        ]);

        // keep slug stable to avoid breaking storefront links.
        $shop->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'origin_city_id' => $data['origin_city_id'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return back()->with('success','Toko berhasil diperbarui.');
    }
}
