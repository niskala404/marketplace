<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\Product;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    public function index()
    {
        $shopId  = auth()->user()->shop->id;
        $streams = LiveStream::where('shop_id', $shopId)->latest()->paginate(10);
        return view('seller.live.index', compact('streams'));
    }

    public function create()
    {
        $shopId   = auth()->user()->shop->id;
        $products = Product::where('shop_id', $shopId)->where('is_active', true)->latest()->limit(200)->get();
        return view('seller.live.create', compact('products'));
    }

    public function store(Request $request)
    {
        $shopId = auth()->user()->shop->id;

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:180'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'scheduled_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'max:3072'],
            'product_ids'  => ['nullable', 'array'],
            'product_ids.*'=> ['integer'],
        ]);

        $stream = LiveStream::create([
            'shop_id'        => $shopId,
            'title'          => $data['title'],
            'description'    => $data['description'] ?? null,
            'scheduled_at'   => $data['scheduled_at'] ?? null,
            'status'         => 'scheduled',
            'thumbnail_path' => $request->file('thumbnail')
                ? $request->file('thumbnail')->store('live-thumbnails', 'public')
                : null,
        ]);

        $this->syncProducts($stream, $shopId, $data['product_ids'] ?? []);

        return redirect()->route('seller.live.show', $stream)->with('success', 'Live stream berhasil dibuat. Mulai live dari halaman ini!');
    }

    public function show(LiveStream $live)
    {
        abort_if($live->shop_id !== auth()->user()->shop->id, 403);
        $shopId   = auth()->user()->shop->id;
        $live->load('products.images');
        $products = Product::where('shop_id', $shopId)->where('is_active', true)->latest()->limit(200)->get();
        $selectedIds = $live->products->pluck('id')->toArray();

        return view('seller.live.show', compact('live', 'products', 'selectedIds'));
    }

    public function updateStatus(Request $request, LiveStream $live)
    {
        abort_if($live->shop_id !== auth()->user()->shop->id, 403);

        $data    = $request->validate(['status' => ['required', 'in:draft,scheduled,live,ended']]);
        $target  = $data['status'] === 'draft' ? 'scheduled' : $data['status'];
        $payload = ['status' => $target];

        if ($target === 'live' && !$live->started_at) {
            $payload['started_at'] = now();
        }
        if ($target === 'ended' && !$live->ended_at) {
            $payload['ended_at'] = now();
        }

        $live->update($payload);
        return back()->with('success', 'Status live stream diperbarui.');
    }

    /**
     * AJAX – add / remove products during a live session.
     */
    public function updateProducts(Request $request, LiveStream $live)
    {
        abort_if($live->shop_id !== auth()->user()->shop->id, 403);

        $data = $request->validate([
            'product_ids'   => ['nullable', 'array'],
            'product_ids.*' => ['integer'],
        ]);

        $this->syncProducts($live, $live->shop_id, $data['product_ids'] ?? []);

        return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui.']);
    }

    private function syncProducts(LiveStream $stream, int $shopId, array $ids): void
    {
        $productIds = Product::where('shop_id', $shopId)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->values();

        $attach = [];
        foreach ($productIds as $i => $pid) {
            $attach[$pid] = ['sort_order' => $i];
        }
        $stream->products()->sync($attach);
    }
}
