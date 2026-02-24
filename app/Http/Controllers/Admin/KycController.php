<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerKyc;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = SellerKyc::query()->with('shop')->latest();
        if ($status) {
            $q->where('status', $status);
        }
        $kycs = $q->paginate(20);
        return view('admin.kyc.index', compact('kycs', 'status'));
    }

    public function show(SellerKyc $kyc)
    {
        $kyc->load('shop.user');
        return view('admin.kyc.show', compact('kyc'));
    }

    public function decide(Request $request, SellerKyc $kyc)
    {
        $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $kyc->status = $request->decision;
        $kyc->admin_note = $request->input('admin_note');
        $kyc->verified_at = now();
        $kyc->save();

        return back()->with('success', 'KYC diperbarui.');
    }
}
