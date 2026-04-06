<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use App\Services\AgoraTokenService;
use Illuminate\Http\JsonResponse;
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

        // Viewer count sekarang dikelola lewat heartbeat, bukan increment saat load
        // (jika status live, frontend akan kirim heartbeat setiap 15 detik)

        $comments = $live->comments()
            ->with('user')
            ->latest()
            ->limit(100)
            ->get();

        $userLiked = auth()->check()
            ? $live->isLikedBy(auth()->id())
            : false;

        return view('live.show', compact('live', 'comments', 'userLiked'));
    }

    public function active(): JsonResponse
    {
        $streams = LiveStream::with('shop')
            ->where('status', 'live')
            ->orderByDesc('like_count')
            ->latest()
            ->get();

        return response()->json($streams);
    }

    public function like(LiveStream $live): JsonResponse
    {
        $userId   = auth()->id();
        $existing = $live->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $live->decrement('like_count');
            $liked = false;
        } else {
            $live->likes()->create(['user_id' => $userId]);
            $live->increment('like_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $live->fresh()->like_count,
        ]);
    }

    public function share(LiveStream $live): JsonResponse
    {
        $live->increment('share_count');

        return response()->json([
            'count' => $live->fresh()->share_count,
        ]);
    }

    public function comment(Request $request, LiveStream $live): JsonResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:300'],
        ]);

        $comment = $live->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $data['body'],
        ]);

        return response()->json([
            'name'       => auth()->user()->name,
            'body'       => $comment->body,
            'created_at' => $comment->created_at->toISOString(),
        ]);
    }

    /**
     * Polling endpoint — buyer & seller polling every 3 seconds.
     * Syncs: like_count, viewer_count, share_count, status, new comments.
     */
    public function poll(LiveStream $live): JsonResponse
    {
        $live->refresh();

        $since = request('since');

        $commentsQuery = $live->comments()->with('user');
        if ($since) {
            $commentsQuery->where('created_at', '>', $since);
        } else {
            $commentsQuery->latest()->limit(50);
        }

        $newComments = $commentsQuery->get()->map(fn($c) => [
            'name'       => $c->user->name ?? 'Anonim',
            'body'       => $c->body,
            'created_at' => $c->created_at->toISOString(),
        ]);

        // ✅ FIX: Use cache-based live viewer count instead of raw DB column
        $viewerCount = $live->status === 'live'
            ? $live->liveViewerCount()
            : (int) $live->viewer_count;

        return response()->json([
            'status'       => $live->status,
            'like_count'   => $live->like_count,
            'viewer_count' => $viewerCount,
            'share_count'  => $live->share_count,
            'comments'     => $newComments,
        ]);
    }

    /**
     * ✅ NEW: Heartbeat endpoint.
     * Frontend calls this every 15 seconds while user is on the live page.
     * Viewer count goes up when someone arrives, down when they leave/close tab.
     *
     * No auth required — guests can watch too.
     * We use a per-session token (stored in cookie) as the viewer identifier.
     */
    public function heartbeat(LiveStream $live, Request $request): JsonResponse
    {
        if ($live->status !== 'live') {
            return response()->json(['viewer_count' => 0]);
        }

        // Create a stable per-browser token (cookie-based, no auth needed)
        $token = $request->cookie('live_viewer_token');
        if (!$token) {
            $token = \Illuminate\Support\Str::uuid()->toString();
        }

        $viewerCount = $live->heartbeat($token);

        return response()->json(['viewer_count' => $viewerCount])
            ->cookie('live_viewer_token', $token, 60 * 24 * 7); // 7 days
    }

    /**
     * Generate Agora token for joining a live stream channel.
     * Called by the frontend when the live page is opened.
     */
    public function agoraToken(LiveStream $live, Request $request): JsonResponse
    {
        $role    = $request->query('role', 'audience'); // 'host' or 'audience'
        $uid     = (int) $request->query('uid', 0);
        $channel = AgoraTokenService::channelName($live->id);

        $token = app(AgoraTokenService::class)->buildRtcToken($channel, $uid, $role);

        return response()->json([
            'appId'   => config('services.agora.app_id', ''),
            'token'   => $token,   // null = testing mode (no certificate set)
            'channel' => $channel,
            'uid'     => $uid,
        ]);
    }
}