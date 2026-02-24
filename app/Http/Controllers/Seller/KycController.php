<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerKyc;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function edit(Request $request)
    {
        $shop = $request->user()->shop;
        $kyc = SellerKyc::query()->firstOrCreate(['shop_id' => $shop->id]);
        return view('seller.kyc.edit', compact('kyc'));
    }

    public function update(Request $request)
    {
        $shop = $request->user()->shop;
        $kyc = SellerKyc::query()->firstOrCreate(['shop_id' => $shop->id]);

        $request->validate([
            'ktp_number' => ['required', 'string', 'max:40'],
            'ktp_image' => ['nullable', 'image', 'max:5120'],
            'selfie_image' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = ['ktp_number' => $request->ktp_number];
        if ($request->hasFile('ktp_image')) {
            $data['ktp_image_path'] = $request->file('ktp_image')->store('kyc', 'public');
        }
        if ($request->hasFile('selfie_image')) {
            $data['selfie_image_path'] = $request->file('selfie_image')->store('kyc', 'public');
        }

        $kyc->fill($data);
        $kyc->status = 'submitted';
        $kyc->submitted_at = now();
        $kyc->save();

        return back()->with('success', 'KYC dikirim. Menunggu verifikasi admin.');
    }
}
