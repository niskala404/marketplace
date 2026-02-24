<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\ShopWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'requested');

        $payouts = Payout::with('shop','requester')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payouts.index', compact('payouts','status'));
    }

    public function show(Payout $payout)
    {
        $payout->load('shop.user','requester','approver');
        return view('admin.payouts.show', compact('payout'));
    }

    public function decide(Request $request, Payout $payout)
    {
        abort_if($payout->status !== 'requested', 403);

        $data = $request->validate([
            'action' => ['required','in:approve,reject'],
            'admin_note' => ['nullable','string','max:2000'],
        ]);

        if ($data['action'] === 'approve') {
            $payout->update([
                'status' => 'approved',
                'admin_note' => $data['admin_note'] ?? null,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);
        } else {
            $payout->update([
                'status' => 'rejected',
                'admin_note' => $data['admin_note'] ?? null,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);
        }

        return back()->with('success','Keputusan payout tersimpan.');
    }

    public function markPaid(Request $request, Payout $payout)
    {
        abort_if($payout->status !== 'approved', 403);

        DB::transaction(function () use ($payout) {
            $locked = Payout::query()->whereKey($payout->getKey())->lockForUpdate()->first();
            if (!$locked) return;
            if ($locked->status !== 'approved') return;

            $locked->loadMissing('shop.wallet');
            $wallet = $locked->shop?->walletOrCreate();

            // idempotent: do not double debit
            $exists = $wallet ? ShopWalletTransaction::query()
                ->where('shop_wallet_id', $wallet->id)
                ->where('type', 'payout_paid')
                ->where('payout_id', $locked->id)
                ->exists() : false;

            if ($wallet && !$exists) {
                $amount = (int) $locked->amount;
                if ($amount > 0) {
                    // clamp to available balance
                    $debit = min($amount, (int) $wallet->balance);
                    if ($debit > 0) {
                        $wallet->decrement('balance', $debit);
                        ShopWalletTransaction::create([
                            'shop_wallet_id' => $wallet->id,
                            'type' => 'payout_paid',
                            'amount' => -$debit,
                            'payout_id' => $locked->id,
                            'meta' => ['method' => $locked->method, 'bank' => $locked->bank_name],
                        ]);
                    }
                }
            }

            $locked->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        });

        return back()->with('success','Payout ditandai sudah dibayar.');
    }
}
