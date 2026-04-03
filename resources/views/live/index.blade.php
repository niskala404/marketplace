@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div>
    <h1 class="text-2xl font-black">Live Streaming</h1>
    <div class="text-sm text-slate-500">Tonton live shopping dan klik produk favoritmu.</div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  @forelse($streams as $live)
    <a href="{{ route('live.show', $live) }}" class="bg-white border rounded-2xl overflow-hidden hover:shadow">
      <div class="aspect-video bg-slate-100">
        @if($live->thumbnail_path)
          <img src="{{ asset('storage/'.$live->thumbnail_path) }}" class="w-full h-full object-cover" alt="{{ $live->title }}">
        @endif
      </div>
      <div class="p-3">
        <div class="font-bold">{{ $live->title }}</div>
        <div class="text-xs text-slate-500 mt-1">{{ $live->shop->name }}</div>
        @php($displayStatus = $live->status === 'scheduled' ? 'draft' : $live->status)
        <div class="mt-2 text-xs px-2 py-1 rounded-full inline-block {{ $live->status === 'live' ? 'bg-rose-600 text-white animate-pulse' : 'bg-slate-100' }}">{{ strtoupper($displayStatus) }}</div>
      </div>
    </a>
  @empty
    <div class="bg-white border rounded-2xl p-6 text-slate-600">Belum ada live stream aktif.</div>
  @endforelse
</div>

<div class="mt-6">{{ $streams->links() }}</div>
@endsection
