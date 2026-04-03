<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function moveToCart(Request $request, Product $product)
    {
        $user = $request->user();

        $wishlistItem = WishlistItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlistItem) {
            return back()->with('error', 'Produk tidak ditemukan di wishlist.');
        }

        if (!$product->is_active || $product->stock < 1) {
            return back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }
        if ($product->variants()->exists()) {
            return back()->with('error', 'Produk ini punya varian. Silakan pilih varian di halaman produk.');
        }

        DB::transaction(function () use ($user, $product, $wishlistItem) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            $cartItem = CartItem::firstOrCreate([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => null,
            ], [
                'sku_snapshot' => null,
                'qty' => 0,
            ]);

            if ($cartItem->qty < $product->stock) {
                $cartItem->update(['qty' => $cartItem->qty + 1]);
                $wishlistItem->delete();
            }
        });

        return back()->with('success', 'Produk dipindahkan ke keranjang.');
    }

    public function moveAllToCart(Request $request)
    {
        $user = $request->user();
        $wishlistItems = WishlistItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($wishlistItems->isEmpty()) {
            return back()->with('error', 'Wishlist masih kosong.');
        }

        $moved = 0;
        $skipped = 0;

        DB::transaction(function () use ($user, $wishlistItems, &$moved, &$skipped) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($wishlistItems as $wishlistItem) {
                $product = $wishlistItem->product;
                if (!$product || !$product->is_active || $product->stock < 1) {
                    $skipped++;
                    continue;
                }
                if ($product->variants()->exists()) {
                    $skipped++;
                    continue;
                }

                $cartItem = CartItem::firstOrCreate([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                ], [
                    'sku_snapshot' => null,
                    'qty' => 0,
                ]);

                if ($cartItem->qty >= $product->stock) {
                    $skipped++;
                    continue;
                }

                $cartItem->update(['qty' => $cartItem->qty + 1]);
                $wishlistItem->delete();
                $moved++;
            }
        });

        if ($moved === 0) {
            return back()->with('error', 'Tidak ada produk yang bisa dipindahkan ke keranjang.');
        }

        $message = "Berhasil memindahkan {$moved} produk ke keranjang.";
        if ($skipped > 0) {
            $message .= " {$skipped} produk dilewati (stok habis/tidak aktif).";
        }

        return back()->with('success', $message);
    }
}
