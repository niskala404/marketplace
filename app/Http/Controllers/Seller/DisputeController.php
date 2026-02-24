<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop->id;

        $disputes = Dispute::where('shop_id', $shopId)
            ->with('order', 'user')
            ->latest()
            ->paginate(10);

        return view('seller.disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        abort_if($dispute->shop_id !== auth()->user()->shop->id, 403);
        $dispute->load('order', 'user');

        return view('seller.disputes.show', compact('dispute'));
    }

    public function respond(Request $request, Dispute $dispute)
    {
        abort_if($dispute->shop_id !== auth()->user()->shop->id, 403);
        abort_if($dispute->status !== 'submitted', 403);

        $data = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'seller_note' => ['nullable', 'string', 'max:2000'],
            'approved_amount' => ['nullable', 'integer', 'min:0'],
        ]);

        $status = $data['action'] === 'approve' ? 'seller_approved' : 'seller_rejected';

        $dispute->update([
            'status' => $status,
            'seller_note' => $data['seller_note'] ?? null,
            'approved_amount' => $data['action'] === 'approve'
                ? (int) ($data['approved_amount'] ?? $dispute->requested_amount)
                : 0,
            'seller_responded_at' => now(),
        ]);

        return back()->with('success', 'Respon dispute tersimpan. Menunggu keputusan admin.');
    }

    public function markReceived(Request $request, Dispute $dispute)
    {
        abort_if($dispute->shop_id !== auth()->user()->shop->id, 403);
        abort_if($dispute->status !== 'buyer_shipped', 403);

        $dispute->update([
            'status' => 'seller_received',
            'seller_received_at' => now(),
        ]);

        return back()->with('success', 'Ditandai: barang retur sudah diterima seller.');
    }
}
