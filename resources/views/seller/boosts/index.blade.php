@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-black">Iklan (Boost)</h1>
  <a href="{{ route('seller.boosts.create') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Tambah Boost</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($boosts as $b)
      <div class="p-4 flex items-center justify-between gap-4">
        <div>
          <div class="font-bold">{{ $b->product?->name ?? ('Produk #'.$b->product_id) }}</div>
          <div class="text-sm text-slate-600">Bid: Rp {{ number_format($b->bid_cpc,0,',','.') }} • Budget: Rp {{ number_format($b->daily_budget,0,',','.') }} • {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}</div>
        </div>
        <form method="POST" action="{{ route('seller.boosts.destroy', $b) }}" onsubmit="return confirm('Hapus boost ini?')">
          @csrf
          @method('DELETE')
          <button class="px-3 py-2 rounded-xl border font-bold hover:bg-slate-50">Hapus</button>
        </form>
      </div>
    @empty
      <div class="p-6 text-slate-600">Belum ada boost.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $boosts->links() }}</div>
@endsection
