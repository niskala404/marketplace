<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Order;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $disputes = Dispute::where('user_id', $request->user()->id)
            ->with('order.shop')
            ->latest()
            ->paginate(10);

        return view('disputes.index', compact('disputes'));
    }

    public function create(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($order->dispute, 403);
        abort_if(!in_array($order->status, ['shipped', 'completed'], true), 403);

        return view('disputes.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($order->dispute, 403);
        abort_if(!in_array($order->status, ['shipped', 'completed'], true), 403);

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'requested_amount' => ['required', 'integer', 'min:0'],
            'evidences.*' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm'],
        ]);

        $paths = [];
        if ($request->hasFile('evidences')) {
            foreach ($request->file('evidences') as $img) {
                $paths[] = $img->store('disputes', 'public');
            }
        }

        Dispute::create([
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'shop_id' => $order->shop_id,
            'status' => 'submitted',
            'reason' => $data['reason'],
            'description' => $data['description'] ?? null,
            'requested_amount' => (int) $data['requested_amount'],
            'approved_amount' => 0,
            'evidence_paths' => $paths ?: null,
            'submitted_at' => now(),
        ]);

        return redirect()->route('disputes.index')->with('success', 'Dispute berhasil diajukan.');
    }

    public function show(Request $request, Dispute $dispute)
    {
        abort_if($dispute->user_id !== $request->user()->id, 403);
        $dispute->load('order.shop');

        return view('disputes.show', compact('dispute'));
    }

    public function shipBack(Request $request, Dispute $dispute)
    {
        abort_if($dispute->user_id !== $request->user()->id, 403);
        abort_if($dispute->status !== 'admin_approved', 403);

        $data = $request->validate([
            'return_tracking_no' => ['required', 'string', 'max:100'],
        ]);

        $dispute->update([
            'return_tracking_no' => $data['return_tracking_no'],
            'status' => 'buyer_shipped',
            'buyer_shipped_at' => now(),
        ]);

        return back()->with('success', 'Resi retur tersimpan.');
    }
}
