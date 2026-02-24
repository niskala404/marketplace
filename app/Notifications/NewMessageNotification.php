<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Conversation $conversation,
        public string $preview,
        public bool $forSeller
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $url = $this->forSeller
            ? route('seller.messages.show', $this->conversation)
            : route('messages.show', $this->conversation);

        return [
            'title' => 'Pesan baru',
            'message' => $this->preview,
            'url' => $url,
            'conversation_id' => $this->conversation->id,
        ];
    }
}
