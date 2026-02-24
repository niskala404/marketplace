<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $transactions = $wallet
            ? $wallet->transactions()->latest()->paginate(20)
            : collect();

        return view('wallet.index', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }
}
