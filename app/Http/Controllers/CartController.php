<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSaleItem;
use App\Services\CartPricingService;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(Request $request, CartPricingService $pricing)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = $this->sanitizeItems($cart);

        $productIds = $items->pluck('product_id')->map(fn($v) => (int)$v)->all();
        $flashPriceMap = FlashSaleItem::promoPriceMap($productIds);

        $subtotal = $items->sum(function ($it) {
            $unit = (int)($it->unit_price_snapshot ?? 0);
            return $unit * (int) $it->qty;
        });

        return view('cart.index', compact('items','subtotal','flashPriceMap'));

    }

    public function add(CartAddRequest $request, int $productId)
    {
        $qty = (int)($request->input('qty', 1));
        $buyNow = (bool) $request->boolean('buy_now');
        $productVariantId = $request->input('product_variant_id');
        $skuInput = trim((string) $request->input('sku', ''));

        $product = Product::where('is_active', true)->findOrFail($productId);
        $variant = null;
        $skuSnapshot = 'PRODUCT-'.$product->id;
        $availableStock = (int)$product->stock;
        if ($product->variants()->exists()) {
            if (!$productVariantId && $skuInput === '') {
                return back()->with('error', 'Pilih varian produk terlebih dahulu.');
            }

            $variantQuery = ProductVariant::query()
                ->where('product_id', $product->id)
                ->where('is_active', true);

            if ($productVariantId) {
                $variantQuery->whereKey((int) $productVariantId);
            } else {
                $variantQuery->where('sku', $skuInput);
            }

            $variant = $variantQuery->firstOrFail();
            $skuSnapshot = (string) $variant->sku;

            $availableStock = (int)$variant->stock;
        }

        if ($availableStock < $qty) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $flashPriceMap = FlashSaleItem::promoPriceMap([(int) $product->id]);
        $snapshotPrice = $pricing->resolveUnitPrice($product, $variant, $flashPriceMap);

        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ], [
            'sku_snapshot' => $skuSnapshot,
            'unit_price_snapshot' => (int) $snapshotPrice,
        ]);

        $newQty = $item->qty + $qty;
        if ($availableStock < $newQty) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi untuk jumlah tersebut.'], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
        }

        $item->update([
            'qty' => $newQty,
            'sku_snapshot' => $skuSnapshot,
            'unit_price_snapshot' => $item->unit_price_snapshot ?: (int) $snapshotPrice,
        ]);

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

    public function update(CartUpdateRequest $request, int $itemId)
    {
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

    private function sanitizeItems(Cart $cart): Collection
    {
        $items = $cart->items()->with('product.images', 'product.shop', 'product.variants', 'variant')->get();
        $invalidIds = [];

        foreach ($items as $item) {
            if (!$item->product || !$item->product->is_active) {
                $invalidIds[] = $item->id;
                continue;
            }

            if ($item->product_variant_id) {
                if ($item->variant && !$item->sku_snapshot) {
                    $item->forceFill(['sku_snapshot' => $item->variant->sku])->save();
                }
                if (!$item->unit_price_snapshot) {
                    Log::warning('Missing variant cart price snapshot, regenerating.', ['cart_item_id' => $item->id, 'variant_id' => $item->product_variant_id]);
                    $item->forceFill(['unit_price_snapshot' => (int)($item->variant?->price ?? 0)])->save();
                }
                if (
                    !$item->variant
                    || (int) $item->variant->product_id !== (int) $item->product_id
                    || !$item->variant->is_active
                    || ($item->sku_snapshot && $item->sku_snapshot !== $item->variant->sku)
                ) {
                    $invalidIds[] = $item->id;
                }
                continue;
            }

            $expectedBaseSku = 'PRODUCT-'.$item->product_id;
            if ($item->sku_snapshot !== $expectedBaseSku) {
                $item->forceFill(['sku_snapshot' => $expectedBaseSku])->save();
            }
            if (!$item->unit_price_snapshot) {
                Log::warning('Missing non-variant cart price snapshot, regenerating.', ['cart_item_id' => $item->id, 'product_id' => $item->product_id]);
                $item->forceFill(['unit_price_snapshot' => (int)$item->product->price])->save();
            }

            if ($item->product->variants->where('is_active', true)->isNotEmpty()) {
                $invalidIds[] = $item->id;
            }
        }

        if ($invalidIds) {
            $cart->items()->whereIn('id', $invalidIds)->delete();
            return $cart->items()->with('product.images', 'product.shop', 'variant')->get();
        }

        return $items;
    }
}
