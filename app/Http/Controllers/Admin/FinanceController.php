<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Escrow;
use App\Models\PlatformWalletTransaction;
use App\Models\ShopWallet;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'escrow_held' => (int) Escrow::query()->where('status', 'held')->sum('amount'),
            'escrow_released' => (int) Escrow::query()->where('status', 'released')->sum('amount'),
            'escrow_refunded' => (int) Escrow::query()->where('status', 'refunded')->sum('amount'),
            'platform_balance' => (int) PlatformWalletTransaction::query()->sum('amount'),
            'refund_uncollected' => (int) PlatformWalletTransaction::query()->where('type', 'refund_uncollected')->sum('amount'),
            'seller_wallet_total' => (int) ShopWallet::query()->sum('balance'),
            'buyer_wallet_total' => (int) UserWallet::query()->sum('balance'),
        ];

        $recentEscrows = Escrow::query()
            ->with(['order:id,order_no,user_id,shop_id,status,subtotal,grand_total', 'order.user:id,name', 'order.shop:id,name'])
            ->latest('id')
            ->limit(10)
            ->get();

        $recentPlatformTx = PlatformWalletTransaction::query()
            ->with(['order:id,order_no'])
            ->latest('id')
            ->limit(12)
            ->get();

        $topSellerWallets = ShopWallet::query()
            ->with(['shop:id,name'])
            ->orderByDesc('balance')
            ->limit(10)
            ->get();

        return view('admin.finance.index', compact('stats', 'recentEscrows', 'recentPlatformTx', 'topSellerWallets'));
    }
}
