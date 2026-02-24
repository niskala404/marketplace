<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $links = AffiliateLink::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('affiliate.index', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:product,shop'],
            'id' => ['required', 'integer'],
            'commission_rate_bp' => ['nullable', 'integer', 'min:0', 'max:2000'],
        ]);

        $user = $request->user();
        $type = $request->input('type');
        $id = (int) $request->input('id');

        $productId = null;
        $shopId = null;
        if ($type === 'product') {
            $productId = Product::query()->whereKey($id)->value('id');
            abort_unless($productId, 404);
        } else {
            $shopId = Shop::query()->whereKey($id)->value('id');
            abort_unless($shopId, 404);
        }

        $code = Str::upper(Str::random(8));
        while (AffiliateLink::query()->where('code', $code)->exists()) {
            $code = Str::upper(Str::random(8));
        }

        AffiliateLink::create([
            'user_id' => $user->id,
            'code' => $code,
            'product_id' => $productId,
            'shop_id' => $shopId,
            'commission_rate_bp' => (int) ($request->input('commission_rate_bp') ?? 200),
            'is_active' => true,
        ]);

        return back()->with('success', 'Link affiliate dibuat.');
    }
}
