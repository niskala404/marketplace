<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->wishlistProducts()
            ->with(['shop','images'])
            ->latest('wishlist_items.created_at')
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Request $request, Product $product)
    {
        $userId = $request->user()->id;

        $exists = WishlistItem::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            WishlistItem::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->delete();
            return back()->with('success', 'Dihapus dari wishlist.');
        }

        WishlistItem::create([
            'user_id' => $userId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Ditambahkan ke wishlist.');
    }
}
