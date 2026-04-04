<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Message;
use App\Models\FlashSaleItem;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['shop.user','images','category','reviews.user', 'variants.items.option'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->firstOrFail();

        $reviews = $product->reviews()->with('user')->latest()->paginate(10);

        // Rating breakdown (1..5) for Shopee-like UI
        $ratingCounts = $product->reviews()
            ->selectRaw('rating, COUNT(*) as cnt')
            ->groupBy('rating')
            ->pluck('cnt', 'rating')
            ->toArray();
        $ratingBreakdown = [];
        $ratingTotal = (int) array_sum($ratingCounts);
        for ($i = 5; $i >= 1; $i--) {
            $c = (int) ($ratingCounts[$i] ?? 0);
            $ratingBreakdown[$i] = [
                'count' => $c,
                'percent' => $ratingTotal > 0 ? (int) round(($c / $ratingTotal) * 100) : 0,
            ];
        }
        $ratingAvg = (float) ($product->reviews()->avg('rating') ?? 0);

        $shop = $product->shop;

        // flash sale (if active)
        $flashItem = FlashSaleItem::query()
            ->with('flashSale')
            ->active()
            ->where('product_id', $product->id)
            ->whereHas('flashSale', fn($q) => $q->activeNow())
            ->first();

        $flashPromo = $flashItem?->promo_price !== null ? (int)$flashItem->promo_price : null;
        $flashRemaining = $flashItem ? $flashItem->remainingQuota() : null;
        $flashEndsAt = $flashItem?->flashSale?->ends_at;

        // seller card metrics
        $followersCount = $shop ? (int) $shop->followers()->count() : 0;
        $productsCount = $shop ? (int) $shop->products()->where('is_active', true)->where('approval_status','approved')->count() : 0;

        // "seller aktif membalas" = pernah mengirim pesan dalam 30 hari terakhir
        $sellerRepliedRecently = false;
        if ($shop?->user_id) {
            $sellerRepliedRecently = Message::where('sender_id', $shop->user_id)
                ->where('created_at', '>=', now()->subDays(30))
                ->exists();
        }

        // chat hanya untuk buyer yang sudah pernah order ke shop ini
        $canChat = false;
        if (auth()->check() && $shop) {
            $canChat = Order::where('user_id', auth()->id())
                ->where('shop_id', $shop->id)
                ->exists();
        }

        // Produk lain dari seller (utama yang paling banyak dibeli)
        $otherFromSeller = collect();
        if ($shop) {
            $otherFromSeller = Product::with(['images','shop'])
                ->withAvg('reviews', 'rating')
                ->where('shop_id', $shop->id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->where('approval_status', 'approved')
                ->orderByDesc('sold_count')
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('created_at')
                ->limit(12)
                ->get();
        }

        // Produk serupa dari seller lain (kategori sama)
        $similar = collect();
        if ($product->category_id) {
            $similar = Product::with(['images','shop'])
                ->withAvg('reviews', 'rating')
                ->where('category_id', $product->category_id)
                ->where('shop_id', '!=', $product->shop_id)
                ->where('is_active', true)
                ->where('approval_status', 'approved')
                ->orderByDesc('sold_count')
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('created_at')
                ->limit(12)
                ->get();
        }

        return view('storefront.product', compact(
            'product','reviews','shop','followersCount','productsCount','sellerRepliedRecently','canChat','otherFromSeller','similar',
            'flashPromo','flashRemaining','flashEndsAt',
            'ratingBreakdown','ratingTotal','ratingAvg'
        ));
    }
}
