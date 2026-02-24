<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan baru: '.$this->order->order_no)
            ->line('Ada pesanan baru untuk toko Anda.')
            ->line('Order: '.$this->order->order_no)
            ->action('Lihat Pesanan', url(route('seller.orders.show', $this->order, false)))
            ->line('Terima kasih menggunakan ilmishop.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesanan baru',
            'message' => 'Order '.$this->order->order_no.' masuk.',
            'url' => route('seller.orders.show', $this->order),
            'order_id' => $this->order->id,
            'order_no' => $this->order->order_no,
        ];
    }
}
