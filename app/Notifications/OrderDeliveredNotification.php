<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDeliveredNotification extends Notification
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
            ->subject('Pesanan sampai: '.$this->order->order_no)
            ->line('Pesanan Anda telah ditandai sudah sampai (delivered).')
            ->line('Order: '.$this->order->order_no)
            ->action('Lihat Pesanan', url(route('orders.show', $this->order, false)))
            ->line('Jika barang sudah Anda terima, silakan klik “Pesanan Diterima” untuk menyelesaikan pesanan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesanan sudah sampai',
            'message' => 'Order '.$this->order->order_no.' ditandai sudah sampai. Konfirmasi “Pesanan Diterima” jika barang sudah diterima.',
            'url' => route('orders.show', $this->order),
            'order_id' => $this->order->id,
            'order_no' => $this->order->order_no,
            'milestone' => 'delivered',
        ];
    }
}
