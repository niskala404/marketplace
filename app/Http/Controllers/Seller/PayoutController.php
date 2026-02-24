<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;
        $payouts = Payout::where('shop_id', $shop->id)->latest()->paginate(10);

        $totalEarnings = $shop->totalEarnings();
        $totalPaidOut = $shop->totalPaidOut();
        $balance = $shop->balance();
        $min = (int) config('ilmishop.min_payout_amount', 0);

        return view('seller.payouts.index', compact('payouts','totalEarnings','totalPaidOut','balance','min'));
    }

    public function create(Request $request)
    {
        $shop = $request->user()->shop;
        $balance = $shop->balance();
        $min = (int) config('ilmishop.min_payout_amount', 0);

        return view('seller.payouts.create', compact('balance','min'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        // KYC gate (lightweight)
        $kycStatus = $shop->kyc?->status;
        if ($kycStatus !== 'approved') {
            return redirect()->route('seller.kyc.edit')->with('error', 'Lengkapi & tunggu verifikasi KYC sebelum bisa payout.');
        }

        $balance = $shop->balance();
        $min = (int) config('ilmishop.min_payout_amount', 0);

        $data = $request->validate([
            'amount' => ['required','integer','min:1'],
            'bank_name' => ['required','string','max:60'],
            'account_name' => ['required','string','max:80'],
            'account_number' => ['required','string','max:40'],
            'note' => ['nullable','string','max:1000'],
        ]);

        $amount = (int) $data['amount'];

        if ($min > 0 && $amount < $min) {
            return back()->withInput()->with('error', 'Minimal penarikan Rp '.number_format($min,0,',','.'));
        }

        if ($amount > $balance) {
            return back()->withInput()->with('error', 'Saldo tidak cukup. Saldo tersedia Rp '.number_format($balance,0,',','.'));
        }

        Payout::create([
            'shop_id' => $shop->id,
            'requested_by' => $request->user()->id,
            'amount' => $amount,
            'status' => 'requested',
            'method' => 'bank_transfer',
            'bank_name' => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('seller.payouts.index')->with('success','Permintaan payout berhasil dibuat. Menunggu persetujuan admin.');
    }
}
