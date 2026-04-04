<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = $cart->items()->with('product.images','product.shop','variant')->get();

        $productIds = $items->pluck('product_id')->map(fn($v) => (int)$v)->all();
        $flashPriceMap = FlashSaleItem::promoPriceMap($productIds);

        $subtotal = $items->sum(function ($it) use ($flashPriceMap) {
            $p = $it->product;
            // Prioritas: flash sale → harga variant → harga produk (discounted)
            if (array_key_exists($p->id, $flashPriceMap)) {
                $unit = (int) $flashPriceMap[$p->id];
            } elseif ($it->variant && $it->variant->price !== null) {
                $unit = (int) $it->variant->price;
            } else {
                $unit = method_exists($p, 'discountedPrice') ? (int) $p->discountedPrice() : (int) $p->price;
            }
            return $unit * (int)$it->qty;
        });

        return view('cart.index', compact('items','subtotal','flashPriceMap'));

    }

    public function add(Request $request, int $productId)
    {
        $request->validate([
            'qty' => ['nullable','integer','min:1'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);
        $qty = (int)($request->input('qty', 1));
        $buyNow = (bool) $request->boolean('buy_now');
        $productVariantId = $request->input('product_variant_id');

        $product = Product::where('is_active', true)->findOrFail($productId);
        $variant = null;
        $availableStock = (int)$product->stock;
        if ($product->variants()->exists()) {
            if (!$productVariantId) {
                return back()->with('error', 'Pilih varian produk terlebih dahulu.');
            }

            $variant = ProductVariant::query()
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->findOrFail((int)$productVariantId);

            $availableStock = (int)$variant->stock;
        }

        if ($availableStock < $qty) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $newQty = $item->qty + $qty;
        if ($availableStock < $newQty) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi untuk jumlah tersebut.'], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
        }

        $item->update(['qty' => $newQty]);

        if ($request->expectsJson()) {
            $cartCount = (int) $cart->items()->sum('qty');
            return response()->json([
                'message' => $buyNow ? 'Barang siap di-checkout.' : 'Berhasil ditambahkan ke keranjang.',
                'cart_count' => $cartCount,
                'redirect' => $buyNow ? route('checkout.show') : null,
            ]);
        }

        if ($buyNow) {
            return redirect()->route('checkout.show')->with('success', 'Barang siap di-checkout.');
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['qty' => ['required','integer','min:1']]);

        $item = CartItem::with('product','cart','variant')->findOrFail($itemId);
        abort_if($item->cart->user_id !== $request->user()->id, 403);

        $availableStock = $item->variant ? (int)$item->variant->stock : (int)$item->product->stock;
        if ($availableStock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $item->update(['qty' => $request->qty]);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request, int $itemId)
    {
        $item = CartItem::with('cart')->findOrFail($itemId);
        abort_if($item->cart->user_id !== $request->user()->id, 403);
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
