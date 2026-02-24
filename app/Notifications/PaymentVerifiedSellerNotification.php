<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentVerifiedSellerNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran diterima',
            'message' => 'Order '.$this->order->order_no.' sudah dibayar (verified).',
            'url' => route('seller.orders.show', $this->order),
            'order_id' => $this->order->id,
            'order_no' => $this->order->order_no,
        ];
    }
}
