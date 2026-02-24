<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentVerifiedNotification extends Notification
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
            ->subject('Pembayaran terverifikasi: '.$this->order->order_no)
            ->line('Pembayaran pesanan Anda telah diverifikasi admin.')
            ->line('Order: '.$this->order->order_no)
            ->action('Lihat Pesanan', url(route('orders.show', $this->order, false)))
            ->line('Terima kasih menggunakan ilmishop.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran terverifikasi',
            'message' => 'Pembayaran untuk order '.$this->order->order_no.' sudah diverifikasi.',
            'url' => route('orders.show', $this->order),
            'order_id' => $this->order->id,
            'order_no' => $this->order->order_no,
        ];
    }
}
