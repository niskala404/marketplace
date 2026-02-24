@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Tambah Boost</h1>

<div class="bg-white border rounded-2xl p-5">
  <form method="POST" action="{{ route('seller.boosts.store') }}" class="space-y-4">
    @csrf
    <div>
      <label class="font-semibold">Produk</label>
      <select name="product_id" class="w-full rounded-xl border-slate-200" required>
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold">Bid CPC (Rp)</label>
        <input type="number" min="0" name="bid_cpc" class="w-full rounded-xl border-slate-200" value="100" required>
      </div>
      <div>
        <label class="font-semibold">Budget Harian (Rp)</label>
        <input type="number" min="0" name="daily_budget" class="w-full rounded-xl border-slate-200" value="10000" required>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold">Mulai</label>
        <input type="date" name="start_date" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Selesai</label>
        <input type="date" name="end_date" class="w-full rounded-xl border-slate-200">
      </div>
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_active" value="1" checked>
      <span class="font-semibold">Aktif</span>
    </label>

    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Simpan</button>
      <a href="{{ route('seller.boosts.index') }}" class="px-4 py-2 rounded-xl border font-bold">Batal</a>
    </div>
  </form>
</div>
@endsection
