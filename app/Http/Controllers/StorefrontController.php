<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\LiveStream;
use App\Models\Product;
use App\Models\ProductBoost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = $request->query('category');

        // Filters
        $minPrice  = $request->integer('min_price');
        $maxPrice  = $request->integer('max_price');
        $minRating = (float) $request->query('min_rating', 0);
        $sort      = (string) $request->query('sort', 'newest');

        // Use one "now" with app timezone to avoid mismatch
        $now = now()->timezone(config('app.timezone'));
        $today = $now->toDateString();

        // Banners for homepage
        $banners = Banner::query()
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('sort_order')
            ->latest('id')
            ->get();

        /**
         * Flash Sale active now
         */
        $activeFlashSale = FlashSale::query()
            ->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->orderByDesc('starts_at')
            ->first();

        $flashItems = collect();
        $flashPriceMap = [];   // product_id => promo_price
        $flashProductIds = []; // for ordering priority

        if ($activeFlashSale) {
            $flashItems = FlashSaleItem::query()
                ->with(['product.shop', 'product.images'])
                ->where('flash_sale_id', $activeFlashSale->id)
                ->where('is_active', true)
                ->get();

            foreach ($flashItems as $it) {
                $flashProductIds[] = (int) $it->product_id;
                if ($it->promo_price !== null) {
                    $flashPriceMap[(int) $it->product_id] = (int) $it->promo_price;
                }
            }
        }

        /**
         * Products query
         */
        $productsQuery = Product::query()
            ->with(['shop', 'images', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($category, fn ($qr) => $qr->where('category_id', $category))
            ->when($minPrice !== null && $minPrice > 0, fn ($qr) => $qr->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice > 0, fn ($qr) => $qr->where('price', '<=', $maxPrice))
            ->when($minRating > 0, fn ($qr) => $qr->having('reviews_avg_rating', '>=', $minRating));

        /**
         * Sponsored (boosted) products first
         */
        $boostBidSub = ProductBoost::query()
            ->select('bid_cpc')
            ->whereColumn('product_id', 'products.id')
            ->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->limit(1);

        $productsQuery->addSelect(['boost_bid' => $boostBidSub]);

        // Prioritize flash sale items (after boost)
        if (!empty($flashProductIds)) {
            $ids = implode(',', array_map('intval', array_unique($flashProductIds)));
            $productsQuery->orderByDesc(DB::raw("CASE WHEN products.id IN ($ids) THEN 1 ELSE 0 END"));
        }

        // Then boosted bid
        $productsQuery->orderByDesc(DB::raw('COALESCE(boost_bid, 0)'));

        /**
         * Sorting
         */
        $productsQuery = match ($sort) {
            'best_selling' => $productsQuery
                ->orderByDesc('sold_count')
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('created_at'),
            'price_asc' => $productsQuery->orderBy('price')->orderByDesc('created_at'),
            'price_desc' => $productsQuery->orderByDesc('price')->orderByDesc('created_at'),
            'rating' => $productsQuery
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->orderByDesc('sold_count')
                ->orderByDesc('created_at'),
            default => $productsQuery->orderByDesc('created_at'),
        };

        $products = $productsQuery->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $liveStreams = LiveStream::with('shop')
            ->where('status', 'live')
            ->latest('started_at')
            ->limit(8)
            ->get();

        /**
         * Infinite scroll / load more
         */
        if ($request->expectsJson()) {
            $html = view('storefront._product_cards', [
                'products' => $products,
                'flashPriceMap' => $flashPriceMap,
            ])->render();

            return response()->json([
                'html' => $html,
                'next_page_url' => $products->nextPageUrl(),
            ]);
        }

        // DEBUG (kalau masih tidak tampil, uncomment sementara):
        // dd($now->toDateTimeString(), $activeFlashSale?->toArray(), $flashItems->count());

        return view('storefront.index', compact(
            'products',
            'categories',
            'q',
            'category',
            'banners',
            'minPrice',
            'maxPrice',
            'minRating',
            'sort',
            'activeFlashSale',
            'flashItems',
            'flashPriceMap',
            'liveStreams'
        ));
    }
}
