<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SearchQuery;
use Illuminate\Http\Request;

class SearchSuggestController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $q = mb_substr($q, 0, 60);

        // If user just focuses input: show recent + trending
        if ($q === '' || mb_strlen($q) < 2) {
            $recent = [];
            if ($request->user()) {
                $recent = SearchQuery::query()
                    ->where('user_id', $request->user()->id)
                    ->orderByDesc('last_searched_at')
                    ->limit(8)
                    ->pluck('query');
            }

            $trending = SearchQuery::query()
                ->where('last_searched_at', '>=', now()->subDays(7))
                ->orderByDesc('hits')
                ->limit(8)
                ->pluck('query');

            return response()->json([
                'products' => [],
                'categories' => [],
                'recent' => $recent,
                'trending' => $trending,
            ]);
        }

        // Log search query (per-user + global)
        $userId = $request->user()?->id;
        foreach ([$userId, null] as $uid) {
            $row = SearchQuery::query()->firstOrNew(['user_id' => $uid, 'query' => $q]);
            $row->hits = (int) ($row->hits ?? 0) + 1;
            $row->last_searched_at = now();
            $row->save();
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->where('name', 'like', '%'.$q.'%')
            ->select(['id','name','slug'])
            ->limit(6)
            ->get();

        $categories = Category::query()
            ->where('name', 'like', '%'.$q.'%')
            ->select(['id','name'])
            ->limit(4)
            ->get();

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'recent' => [],
            'trending' => [],
        ]);
    }
}
