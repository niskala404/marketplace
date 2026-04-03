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

    public function show(LiveStream $live)
    {
        $live->load(['shop', 'products.images', 'products.variants']);

        return view('live.show', compact('live'));
    }
}
