@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">KYC Toko: {{ $kyc->shop?->name ?? ('#'.$kyc->shop_id) }}</h1>

<div class="bg-white border rounded-2xl p-5 space-y-4">
  <div>Status: <span class="font-bold">{{ $kyc->status }}</span></div>
  <div>Nomor KTP: <span class="font-semibold">{{ $kyc->ktp_number }}</span></div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <div class="font-semibold mb-2">Foto KTP</div>
      @if($kyc->ktp_image_path)
        <img src="{{ asset('storage/'.$kyc->ktp_image_path) }}" class="w-full rounded-xl border" />
      @else
        <div class="text-slate-500">-</div>
      @endif
    </div>
    <div>
      <div class="font-semibold mb-2">Selfie + KTP</div>
      @if($kyc->selfie_image_path)
        <img src="{{ asset('storage/'.$kyc->selfie_image_path) }}" class="w-full rounded-xl border" />
      @else
        <div class="text-slate-500">-</div>
      @endif
    </div>
  </div>

  <form method="POST" action="{{ route('admin.kyc.decide', $kyc) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
    @csrf
    <div>
      <label class="font-semibold">Keputusan</label>
      <select name="decision" class="w-full rounded-xl border-slate-200">
        <option value="approved">approved</option>
        <option value="rejected">rejected</option>
      </select>
    </div>
    <div class="md:col-span-2">
      <label class="font-semibold">Catatan</label>
      <input name="admin_note" class="w-full rounded-xl border-slate-200" value="{{ old('admin_note', $kyc->admin_note) }}">
    </div>
    <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold md:col-span-3">Simpan</button>
  </form>
</div>
@endsection
