<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order, OrderItem $orderItem)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($orderItem->order_id !== $order->id, 404);

        // Only allow review when order completed
        if ($order->status !== 'completed') {
            return back()->with('error', 'Review hanya bisa setelah pesanan selesai.');
        }

        $request->validate([
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string','max:1000'],
        ]);

        // prevent duplicate
        if ($orderItem->review) {
            return back()->with('error', 'Item ini sudah direview.');
        }

        Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating' => (int)$request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Review tersimpan.');
    }
}
