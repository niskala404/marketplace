@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">KYC Seller</h1>

<div class="bg-white border rounded-2xl p-5 space-y-4">
  <div class="text-sm text-slate-600">
    Status: <span class="font-bold">{{ $kyc->status }}</span>
    @if($kyc->admin_note)
      <div class="mt-1 text-rose-700">Catatan admin: {{ $kyc->admin_note }}</div>
    @endif
  </div>

  <form method="POST" action="{{ route('seller.kyc.update') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
      <label class="font-semibold">Nomor KTP</label>
      <input name="ktp_number" class="w-full rounded-xl border-slate-200" value="{{ old('ktp_number', $kyc->ktp_number) }}" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold">Foto KTP</label>
        <input type="file" name="ktp_image" accept="image/*" class="w-full">
        @if($kyc->ktp_image_path)
          <img src="{{ asset('storage/'.$kyc->ktp_image_path) }}" class="mt-2 w-48 rounded-xl border" />
        @endif
      </div>
      <div>
        <label class="font-semibold">Selfie + KTP</label>
        <input type="file" name="selfie_image" accept="image/*" class="w-full">
        @if($kyc->selfie_image_path)
          <img src="{{ asset('storage/'.$kyc->selfie_image_path) }}" class="mt-2 w-48 rounded-xl border" />
        @endif
      </div>
    </div>

    <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Kirim / Update KYC</button>
  </form>
</div>
@endsection
