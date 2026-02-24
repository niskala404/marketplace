@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Affiliate</h1>

<div class="bg-white border rounded-2xl p-5 space-y-3">
  <div class="text-slate-600">Buat link affiliate untuk produk atau toko (akan menghasilkan komisi saat pesanan selesai).</div>

  <form method="POST" action="{{ route('affiliate.links.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
    @csrf
    <select name="type" class="rounded-xl border-slate-200">
      <option value="product">Produk</option>
      <option value="shop">Toko</option>
    </select>
    <input name="id" type="number" class="rounded-xl border-slate-200" placeholder="ID Produk/Toko" required>
    <input name="commission_rate_bp" type="number" class="rounded-xl border-slate-200" placeholder="Komisi (bp) default 200">
    <button class="rounded-xl bg-slate-900 text-white font-bold px-4 py-2">Buat Link</button>
  </form>

  <div class="text-xs text-slate-500">Catatan: Komisi default 2% (200 bp). Link affiliate digunakan via parameter <span class="font-mono">?aff=KODE</span>.</div>
</div>

<div class="mt-4 bg-white border rounded-2xl overflow-hidden">
  <div class="p-4 font-bold">Link Saya</div>
  <div class="divide-y">
    @forelse($links as $l)
      <div class="p-4">
        <div class="font-black">{{ $l->code }}</div>
        <div class="text-sm text-slate-600">Target: {{ $l->product_id ? 'Produk #'.$l->product_id : 'Toko #'.$l->shop_id }} • Komisi {{ number_format(($l->commission_rate_bp ?? 0)/100,2) }}%</div>
        <div class="text-sm mt-1">
          <span class="text-slate-500">Gunakan kode:</span>
          <span class="font-mono">?aff={{ $l->code }}</span>
        </div>
      </div>
    @empty
      <div class="p-4 text-slate-600">Belum ada link.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $links->links() }}</div>
@endsection
