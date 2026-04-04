<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiveStartRequest;
use App\Http\Requests\LiveStopRequest;
use App\Models\LiveStream;
use App\Models\Product;

class LiveStreamController extends Controller
{
    public function index()
    {
        $streams = LiveStream::with('shop')
            ->whereIn('status', ['scheduled', 'live'])
            ->orderByRaw("FIELD(status, 'live', 'scheduled')")
            ->latest()
            ->paginate(12);

        return view('live.index', compact('streams'));
    }

    public function active()
    {
        $streams = LiveStream::with('shop')
            ->where('status', 'live')
            ->latest('started_at')
            ->limit(12)
            ->get(['id', 'shop_id', 'title', 'thumbnail_path', 'status', 'started_at']);

        return response()->json([
            'data' => $streams->map(fn ($stream) => [
                'id' => $stream->id,
                'title' => $stream->title,
                'shop_name' => $stream->shop?->name,
                'status' => $stream->status,
                'thumbnail_url' => $stream->thumbnail_path ? asset('storage/'.$stream->thumbnail_path) : null,
                'url' => route('live.show', $stream),
            ])->values(),
        ]);
    }

    public function show(LiveStream $live)
    {
        $live->load(['shop', 'products.images', 'products.variants']);

        return view('live.show', compact('live'));
    }

    public function start(LiveStartRequest $request)
    {
        $shop = $request->user()->shop;
        abort_if(!$shop, 403);

        $live = null;
        $liveId = (int) $request->input('live_id', 0);
        if ($liveId > 0) {
            $live = LiveStream::where('shop_id', $shop->id)->findOrFail($liveId);
        } else {
            $live = LiveStream::create([
                'shop_id' => $shop->id,
                'title' => (string) $request->input('title'),
                'description' => $request->input('description'),
                'stream_url' => $request->input('stream_url'),
                'status' => 'live',
                'started_at' => now(),
            ]);

            $productIds = Product::where('shop_id', $shop->id)
                ->where('is_active', true)
                ->latest('id')
                ->limit(8)
                ->pluck('id')
                ->all();
            $live->products()->sync($productIds);
        }

        $live->forceFill([
            'status' => 'live',
            'started_at' => $live->started_at ?: now(),
            'ended_at' => null,
        ])->save();

        return response()->json([
            'message' => 'Live dimulai.',
            'live_id' => $live->id,
            'url' => route('live.show', $live),
        ]);
    }

    public function stop(LiveStopRequest $request)
    {
        $shop = $request->user()->shop;
        abort_if(!$shop, 403);

        $live = LiveStream::where('shop_id', $shop->id)->findOrFail((int) $request->input('live_id'));
        $live->forceFill([
            'status' => 'ended',
            'ended_at' => now(),
        ])->save();

        return response()->json([
            'message' => 'Live dihentikan.',
            'live_id' => $live->id,
        ]);
    }
}
