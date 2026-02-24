<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBoost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;
        $boosts = ProductBoost::query()
            ->where('shop_id', $shop->id)
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('seller.boosts.index', compact('boosts'));
    }

    public function create(Request $request)
    {
        $shop = $request->user()->shop;
        $products = Product::query()->where('shop_id', $shop->id)->latest()->get(['id','name']);
        return view('seller.boosts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;
        $request->validate([
            'product_id' => ['required', 'integer'],
            'bid_cpc' => ['required', 'integer', 'min:0'],
            'daily_budget' => ['required', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $product = Product::query()->where('shop_id', $shop->id)->whereKey((int) $request->product_id)->firstOrFail();

        ProductBoost::updateOrCreate(
            ['shop_id' => $shop->id, 'product_id' => $product->id],
            [
                'bid_cpc' => (int) $request->bid_cpc,
                'daily_budget' => (int) $request->daily_budget,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'is_active' => (bool) $request->input('is_active', true),
            ]
        );

        return redirect()->route('seller.boosts.index')->with('success', 'Boost disimpan.');
    }

    public function destroy(Request $request, ProductBoost $boost)
    {
        $shop = $request->user()->shop;
        abort_unless($boost->shop_id === $shop->id, 403);
        $boost->delete();
        return back()->with('success', 'Boost dihapus.');
    }
}
