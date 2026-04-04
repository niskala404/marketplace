<?php

namespace App\Http\Controllers;



class LiveStreamController extends Controller
{
    public function index()
    {
        $streams = LiveStream::with('shop')
            ->whereIn('status', ['scheduled', 'live'])
            ->orderByRaw("FIELD(status, 'live', 'scheduled')")
            ->orderByDesc('like_count')
            ->latest()
            ->paginate(12);

        return view('live.index', compact('streams'));
    }



}
