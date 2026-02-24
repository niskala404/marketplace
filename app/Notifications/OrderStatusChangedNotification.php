<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus, public string $newStatus) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update pesanan: '.$this->order->order_no)
            ->line('Status pesanan Anda berubah.')
            ->line('Order: '.$this->order->order_no)
            ->line('Status: '.$this->oldStatus.' → '.$this->newStatus)
            ->action('Lihat Pesanan', url(route('orders.show', $this->order, false)))
            ->line('Terima kasih menggunakan ilmishop.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Status pesanan diperbarui',
            'message' => 'Order '.$this->order->order_no.' berubah: '.$this->oldStatus.' → '.$this->newStatus,
            'url' => route('orders.show', $this->order),
            'order_id' => $this->order->id,
            'order_no' => $this->order->order_no,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
