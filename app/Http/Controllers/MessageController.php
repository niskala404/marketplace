<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Shop;
use App\Models\Order;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // Buyer inbox
    public function index(Request $request)
    {
        $conversations = Conversation::query()
            ->where('buyer_id', $request->user()->id)
            ->with(['shop','latestMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(15);

        return view('messages.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation)
    {
        abort_if($conversation->buyer_id !== $request->user()->id, 403);

        $conversation->load(['shop','messages.sender']);
        return view('messages.show', compact('conversation'));
    }

    /**
     * Poll new messages for simple "real-time" chat (without websockets).
     */
    public function poll(Request $request, Conversation $conversation)
    {
        abort_if($conversation->buyer_id !== $request->user()->id, 403);
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
        abort_if($conversation->buyer_id !== $request->user()->id, 403);

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

        $conversation->loadMissing('shop.user');
        if ($conversation->shop?->user) {
            $preview = mb_substr($data['body'], 0, 120);
            $conversation->shop->user->notify(new NewMessageNotification($conversation, $preview, true));
        }

        return back()->with('success', 'Pesan terkirim.');
    }

    // Start a conversation with a shop (buyer)
    public function start(Request $request, Shop $shop)
    {
        abort_if(!$shop->is_active, 404);
        $user = $request->user();

        // Buyer can chat only after purchasing from this shop
        $hasOrder = Order::where('user_id', $user->id)->where('shop_id', $shop->id)->exists();
        abort_if(!$hasOrder, 403);

        $data = $request->validate([
            'body' => ['nullable','string','max:2000'],
        ]);

        $conversation = null;

        DB::transaction(function () use ($shop, $user, $data, &$conversation) {
            $conversation = Conversation::firstOrCreate(
                ['shop_id' => $shop->id, 'buyer_id' => $user->id],
                ['last_message_at' => now()]
            );

            if (!empty($data['body'])) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'body' => $data['body'],
                ]);
                $conversation->update(['last_message_at' => now()]);
            }
        });

        $shop->loadMissing('user');
        if ($shop->user && !empty($data['body'])) {
            $preview = mb_substr($data['body'], 0, 120);
            $shop->user->notify(new NewMessageNotification($conversation, $preview, true));
        }

        return redirect()->route('messages.show', $conversation)->with('success', 'Percakapan dibuka.');
    }
}
