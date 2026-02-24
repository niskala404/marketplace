<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RealtimeController extends Controller
{
    /**
     * Server-Sent Events stream for near real-time counters (notifications + messages).
     * Works without extra packages (no websockets required).
     */
    public function stream(Request $request): StreamedResponse
    {
        $user = $request->user();

        // Prevent buffering by some servers
        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ];

        return response()->stream(function () use ($user) {
            // Send an initial retry interval (ms)
            echo "retry: 3000\n\n";
            @ob_flush(); @flush();

            $start = time();
            $maxSeconds = 60 * 20; // keep for 20 minutes, browser will reconnect

            while (time() - $start < $maxSeconds) {
                // unread notifications
                $unreadNotifs = $user->unreadNotifications()->count();

                // unread messages (by conversation)
                $unreadMsgs = $this->unreadConversationCount($user->id);

                $payload = json_encode([
                    'unread_notifications' => $unreadNotifs,
                    'unread_messages' => $unreadMsgs,
                    'ts' => now()->toIso8601String(),
                ]);

                echo "event: counters\n";
                echo "data: {$payload}\n\n";

                @ob_flush(); @flush();
                sleep(3);
            }

            echo "event: bye\n";
            echo "data: {}\n\n";
            @ob_flush(); @flush();
        }, 200, $headers);
    }

    private function unreadConversationCount(int $userId): int
    {
        // Determine participation:
        // - buyer conversations: conversations.buyer_id = user
        // - seller conversations: conversations.shop_id in shops owned by user
        // We avoid loading relations; do it in SQL.
        $shopIds = DB::table('shops')->where('user_id', $userId)->pluck('id');

        $query = DB::table('conversations')
            ->leftJoin('conversation_reads', function ($join) use ($userId) {
                $join->on('conversation_reads.conversation_id', '=', 'conversations.id')
                    ->where('conversation_reads.user_id', '=', $userId);
            })
            ->leftJoin(DB::raw('(
                select m1.conversation_id, m1.sender_id, m1.created_at as last_msg_at
                from messages m1
                join (
                    select conversation_id, max(created_at) as created_at
                    from messages
                    group by conversation_id
                ) m2 on m1.conversation_id = m2.conversation_id and m1.created_at = m2.created_at
            ) lastmsg'), 'lastmsg.conversation_id', '=', 'conversations.id');

        $query->where(function ($q) use ($userId, $shopIds) {
            $q->where('conversations.buyer_id', $userId);
            if ($shopIds->count() > 0) {
                $q->orWhereIn('conversations.shop_id', $shopIds->all());
            }
        });

        // Unread if last_message_at > last_read_at and the last sender isn't current user
        $query->whereRaw('conversations.last_message_at is not null')
            ->whereRaw('conversations.last_message_at > COALESCE(conversation_reads.last_read_at, "1970-01-01 00:00:00")')
            ->whereRaw('lastmsg.sender_id is null OR lastmsg.sender_id <> ?', [$userId]);

        return (int) $query->distinct('conversations.id')->count('conversations.id');
    }
}
