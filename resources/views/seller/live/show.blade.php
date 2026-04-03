@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div>
    <h1 class="text-2xl font-black">{{ $live->title }}</h1>
    <div class="text-sm text-slate-500">Status: {{ strtoupper($live->status === 'scheduled' ? 'draft' : $live->status) }} • Viewers: {{ number_format($live->viewer_count ?? 0,0,',','.') }}</div>
  </div>
  <div class="flex gap-2">
    <form method="POST" action="{{ route('seller.live.status', $live) }}">
      @csrf
      <input type="hidden" name="status" value="live">
      <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white">Start Live</button>
    </form>
    <form method="POST" action="{{ route('seller.live.status', $live) }}">
      @csrf
      <input type="hidden" name="status" value="ended">
      <button class="px-4 py-2 rounded-xl bg-rose-600 text-white">Stop Live</button>
    </form>
    <a href="{{ route('seller.live.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
  </div>
</div>


    </div>
    <div class="mt-4 text-slate-700 whitespace-pre-line">{{ $live->description }}</div>
  </div>

  <div class="bg-white border rounded-2xl p-5">
    <div class="font-bold mb-2">Produk yang ditampilkan</div>
    <div class="space-y-2">
      @forelse($live->products as $p)
        <div class="text-sm border rounded-xl p-2">{{ $p->name }}</div>
      @empty
        <div class="text-sm text-slate-500">Belum ada produk.</div>
      @endforelse
    </div>
  </div>
</div>

@endsection
