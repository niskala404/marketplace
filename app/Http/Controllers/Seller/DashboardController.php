<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        $shopId = $shop?->id;
        $stats = [
            'pending' => Order::where('shop_id',$shopId)->where('status','pending')->count(),
            'processing' => Order::where('shop_id',$shopId)->where('status','processing')->count(),
            'shipped' => Order::where('shop_id',$shopId)->where('status','shipped')->count(),
            'completed' => Order::where('shop_id',$shopId)->where('status','completed')->count(),
            'refunded' => Order::where('shop_id',$shopId)->where('status','refunded')->count(),
        ];

        $balance = $shop ? $shop->balance() : 0;
        $totalEarnings = $shop ? $shop->totalEarnings() : 0;
        $totalPaidOut = $shop ? $shop->totalPaidOut() : 0;

        return view('seller.dashboard', compact('stats','balance','totalEarnings','totalPaidOut'));
    }
}
