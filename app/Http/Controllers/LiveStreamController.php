<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use App\Models\LiveStreamComment;
use App\Models\LiveStreamLike;
use Illuminate\Http\Request;

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

    public function show(LiveStream $live)
    {
        $live->load(['shop', 'products.images']);

        $comments = LiveStreamComment::where('live_stream_id', $live->id)
            ->with('user')
            ->latest()
            ->limit(100)
            ->get()
            ->reverse()
            ->values();

        $userLiked = auth()->check() ? $live->isLikedBy(auth()->id()) : false;

        return view('live.show', compact('live', 'comments', 'userLiked'));
    }

    public function comment(Request $request, LiveStream $live)
    {
        abort_unless(auth()->check(), 401);
        $data = $request->validate(['body' => ['required', 'string', 'max:300']]);

        $comment = LiveStreamComment::create([
            'live_stream_id' => $live->id,
            'user_id'        => auth()->id(),
            'body'           => $data['body'],
        ]);

        return response()->json([
            'id'         => $comment->id,
            'body'       => $comment->body,
            'user'       => auth()->user()->name,
            'created_at' => $comment->created_at->diffForHumans(),
        ]);
    }

    public function pollComments(Request $request, LiveStream $live)
    {
        $since = $request->query('since', 0);

        $comments = LiveStreamComment::where('live_stream_id', $live->id)
            ->where('id', '>', $since)
            ->with('user')
            ->orderBy('id')
            ->limit(50)
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'body'       => $c->body,
                'user'       => $c->user->name ?? 'Anonim',
                'created_at' => $c->created_at->diffForHumans(),
            ]);

        return response()->json($comments);
    }

    public function like(LiveStream $live)
    {
        abort_unless(auth()->check(), 401);
        $userId = auth()->id();
        $existing = LiveStreamLike::where('live_stream_id', $live->id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $live->decrement('like_count');
            $liked = false;
        } else {
            LiveStreamLike::create(['live_stream_id' => $live->id, 'user_id' => $userId]);
            $live->increment('like_count');
            $liked = true;
        }

        return response()->json(['liked' => $liked, 'like_count' => $live->fresh()->like_count]);
    }

    public function share(LiveStream $live)
    {
        $live->increment('share_count');
        return response()->json(['share_count' => $live->fresh()->share_count]);
    }
}
