@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Ajukan Payout</h1>

<div class="bg-white border rounded-2xl p-5">
  <div class="text-slate-600 mb-4">
    Saldo tersedia: <span class="font-bold">Rp {{ number_format($balance,0,',','.') }}</span>
    <span class="text-sm text-slate-500">(minimal Rp {{ number_format($min,0,',','.') }})</span>
  </div>

  <form method="POST" action="{{ route('seller.payouts.store') }}" class="space-y-4">
    @csrf

    <div>
      <label class="font-semibold">Nominal (Rp)</label>
      <input type="number" min="1" name="amount" value="{{ old('amount') }}"
             class="w-full rounded-xl border-slate-200" required>
      @error('amount')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="font-semibold">Nama Bank</label>
        <input name="bank_name" value="{{ old('bank_name') }}" class="w-full rounded-xl border-slate-200" placeholder="BCA" required>
        @error('bank_name')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="font-semibold">No Rekening</label>
        <input name="account_number" value="{{ old('account_number') }}" class="w-full rounded-xl border-slate-200" placeholder="1234567890" required>
        @error('account_number')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="font-semibold">Atas Nama</label>
        <input name="account_name" value="{{ old('account_name') }}" class="w-full rounded-xl border-slate-200" placeholder="Nama pemilik" required>
        @error('account_name')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
      </div>
    </div>

    <div>
      <label class="font-semibold">Catatan (opsional)</label>
      <textarea name="note" rows="3" class="w-full rounded-xl border-slate-200">{{ old('note') }}</textarea>
      @error('note')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('seller.payouts.index') }}" class="px-4 py-2 rounded-xl border bg-white hover:bg-slate-50">Batal</a>
      <button class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Kirim Permintaan</button>
    </div>
  </form>
</div>
@endsection
