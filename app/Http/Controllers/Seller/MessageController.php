<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->user()->shop->id;

        $conversations = Conversation::query()
            ->where('shop_id', $shopId)
            ->with(['buyer','latestMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(15);

        return view('seller.messages.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation)
    {
        $shopId = $request->user()->shop->id;
        abort_if($conversation->shop_id !== $shopId, 403);

        $conversation->load(['buyer','messages.sender']);
        return view('seller.messages.show', compact('conversation'));
    }

    public function poll(Request $request, Conversation $conversation)
    {
        $shopId = $request->user()->shop->id;
        abort_if($conversation->shop_id !== $shopId, 403);

        $afterId = (int) $request->query('after_id', 0);
        $messages = $conversation->messages()
            ->with('sender:id,name')
            ->when($afterId > 0, fn($q) => $q->where('id', '>', $afterId))
            ->orderBy('id')
            ->limit(50)
            ->get();

        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'sender_name' => $m->sender?->name,
                'body' => $m->body,
                'created_at' => $m->created_at->format('Y-m-d H:i:s'),
            ]),
            'last_id' => $messages->last()?->id ?? $afterId,
        ]);
    }

    public function send(Request $request, Conversation $conversation)
    {
        $shopId = $request->user()->shop->id;
        abort_if($conversation->shop_id !== $shopId, 403);

        $data = $request->validate([
            'body' => ['required','string','max:2000'],
        ]);

        DB::transaction(function () use ($conversation, $request, $data) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'body' => $data['body'],
            ]);
            $conversation->update(['last_message_at' => now()]);
        });

        $conversation->loadMissing('buyer');
        if ($conversation->buyer) {
            $preview = mb_substr($data['body'], 0, 120);
            $conversation->buyer->notify(new NewMessageNotification($conversation, $preview, false));
        }

        return back()->with('success', 'Pesan terkirim.');
    }
}
