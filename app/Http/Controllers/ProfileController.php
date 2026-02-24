<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tab = $request->query('tab', 'orders');
        $status = $request->query('status', 'all');
        $q = trim((string) $request->query('q', ''));

        // Orders (Shopee-like tabs)
        $ordersQuery = $user->orders()->with('shop')->latest();

        if ($q !== '') {
            $ordersQuery->where(function ($qq) use ($q) {
                $qq->where('order_no', 'like', "%{$q}%")
                   ->orWhereHas('items', fn($x) => $x->where('product_name', 'like', "%{$q}%"))
                   ->orWhereHas('shop', fn($x) => $x->where('name', 'like', "%{$q}%"));
            });
        }

        // Map status tabs
        if ($status !== 'all') {
            if ($status === 'unpaid') {
                $ordersQuery->where('status', 'pending');
            } elseif ($status === 'packed') {
                $ordersQuery->whereIn('status', ['paid','processing']);
            } elseif ($status === 'shipped') {
                $ordersQuery->where('status', 'shipped');
            } elseif ($status === 'done') {
                $ordersQuery->where('status', 'completed');
            } elseif ($status === 'cancelled') {
                $ordersQuery->whereIn('status', ['cancelled','refunded']);
            } elseif ($status === 'returns') {
                $ordersQuery->whereHas('dispute');
            }
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        $counts = [
            'all' => (int) $user->orders()->count(),
            'unpaid' => (int) $user->orders()->where('status','pending')->count(),
            'packed' => (int) $user->orders()->whereIn('status',['paid','processing'])->count(),
            'shipped' => (int) $user->orders()->where('status','shipped')->count(),
            'done' => (int) $user->orders()->where('status','completed')->count(),
            'cancelled' => (int) $user->orders()->whereIn('status',['cancelled','refunded'])->count(),
            'returns' => (int) $user->orders()->whereHas('dispute')->count(),
        ];

        $unreadNotif = $user->unreadNotifications()->count();

        return view('account.profile', compact('user','tab','status','q','orders','counts','unreadNotif'));
    }
}
