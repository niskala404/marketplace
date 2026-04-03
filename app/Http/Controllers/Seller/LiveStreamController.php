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
        $shopId = auth()->user()->shop->id;
        $streams = LiveStream::where('shop_id', $shopId)->latest()->paginate(10);

        return view('seller.live.index', compact('streams'));
    }

    public function create()
    {
        $shopId = auth()->user()->shop->id;
        $products = Product::where('shop_id', $shopId)->where('is_active', true)->latest()->limit(100)->get();

        return view('seller.live.create', compact('products'));
    }

    public function store(Request $request)
    {
        $shopId = auth()->user()->shop->id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:2000'],
            'stream_url' => ['nullable', 'url', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer'],
        ]);

        $stream = LiveStream::create([
            'shop_id' => $shopId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'stream_url' => $data['stream_url'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'status' => 'scheduled',
            'thumbnail_path' => $request->file('thumbnail') ? $request->file('thumbnail')->store('live-thumbnails', 'public') : null,
        ]);

        $productIds = Product::where('shop_id', $shopId)
            ->whereIn('id', $data['product_ids'] ?? [])
            ->pluck('id')
            ->values();

        $attach = [];
        foreach ($productIds as $i => $pid) {
            $attach[$pid] = ['sort_order' => $i];
        }
        $stream->products()->sync($attach);

        return redirect()->route('seller.live.index')->with('success', 'Live stream berhasil dibuat.');
    }

    public function show(LiveStream $live)
    {
        abort_if($live->shop_id !== auth()->user()->shop->id, 403);
        $live->load('products');

        return view('seller.live.show', compact('live'));
    }

    public function updateStatus(Request $request, LiveStream $live)
    {
        abort_if($live->shop_id !== auth()->user()->shop->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:scheduled,live,ended'],
        ]);

        $payload = ['status' => $data['status']];
        if ($data['status'] === 'live' && !$live->started_at) {
            $payload['started_at'] = now();
        }
        if ($data['status'] === 'ended' && !$live->ended_at) {
            $payload['ended_at'] = now();
        }

        $live->update($payload);

        return back()->with('success', 'Status live stream diperbarui.');
    }
}
