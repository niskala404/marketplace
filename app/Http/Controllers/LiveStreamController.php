<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;

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
}
