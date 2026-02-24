<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'submitted');

        $disputes = Dispute::with('order.shop', 'user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.disputes.index', compact('disputes', 'status'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load('order.shop', 'user');
        return view('admin.disputes.show', compact('dispute'));
    }

    public function decide(Request $request, Dispute $dispute)
    {
        abort_if(!in_array($dispute->status, ['seller_approved', 'seller_rejected', 'submitted'], true), 403);

        $data = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
            'approved_amount' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($data['action'] === 'approve') {
            $dispute->update([
                'status' => 'admin_approved',
                'admin_note' => $data['admin_note'] ?? null,
                'approved_amount' => (int) ($data['approved_amount'] ?? max($dispute->approved_amount, $dispute->requested_amount)),
                'admin_decided_at' => now(),
            ]);
        } else {
            $dispute->update([
                'status' => 'admin_rejected',
                'admin_note' => $data['admin_note'] ?? null,
                'approved_amount' => 0,
                'admin_decided_at' => now(),
            ]);
        }

        return back()->with('success', 'Keputusan admin tersimpan.');
    }

    public function markRefunded(Request $request, Dispute $dispute)
    {
        abort_if($dispute->status !== 'seller_received', 403);

        DB::transaction(function () use ($dispute) {
            $dispute->loadMissing('order.shop', 'order.escrow');

            $amount = (int) ($dispute->approved_amount ?? 0);
            if ($amount > 0 && $dispute->order) {
                $dispute->order->refundEscrowIfNeeded($amount, 'dispute_refund', [
                    'dispute_id' => $dispute->id,
                    'requested_amount' => (int) ($dispute->requested_amount ?? 0),
                    'approved_amount' => $amount,
                ]);
            }

            $dispute->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);
        });

        return back()->with('success', 'Refund ditandai selesai.');
    }
}
