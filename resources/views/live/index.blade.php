@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-5">
  <div>
    <h1 class="text-2xl font-black">🔴 Live Streaming</h1>
    <div class="text-sm text-slate-500">Tonton live shopping, beli produk langsung dari seller.</div>
  </div>
</div>

@if($streams->isEmpty())
  <div class="bg-white border rounded-2xl p-10 text-center text-slate-500">
    <div class="text-5xl mb-3">📺</div>
    <div class="font-semibold">Belum ada live stream aktif saat ini.</div>
    <div class="text-sm mt-1">Pantau terus untuk live shopping terbaru!</div>
  </div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
  @foreach($streams as $live)
    <a href="{{ route('live.show', $live) }}"
       class="bg-white border rounded-2xl overflow-hidden hover:shadow-lg transition group relative">

      {{-- Thumbnail --}}
      <div class="aspect-video bg-slate-900 relative overflow-hidden">
        @if($live->thumbnail_path)
          <img src="{{ asset('storage/'.$live->thumbnail_path) }}"
               class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
               alt="{{ $live->title }}">
        @else
          <div class="w-full h-full flex items-center justify-center text-slate-600">
            <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
          </div>
        @endif

        {{-- LIVE badge --}}
        @if($live->status === 'live')
          <div class="absolute top-2 left-2 flex items-center gap-1 bg-rose-600 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse shadow">
            <span class="w-2 h-2 rounded-full bg-white inline-block"></span> LIVE
          </div>
        @else
          <div class="absolute top-2 left-2 bg-slate-700 text-white text-xs font-bold px-2 py-1 rounded-full">
            TERJADWAL
          </div>
        @endif

        {{-- Like badge --}}
        @if($live->like_count > 0)
          <div class="absolute bottom-2 right-2 flex items-center gap-1 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
            ❤️ {{ number_format($live->like_count,0,',','.') }}
          </div>
        @endif
      </div>

      {{-- Info --}}
      <div class="p-3">
        <div class="font-bold line-clamp-2 text-sm">{{ $live->title }}</div>
        <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
          @if($live->shop->logo_path)
            <img src="{{ asset('storage/'.$live->shop->logo_path) }}" class="w-4 h-4 rounded-full object-cover">
          @endif
          {{ $live->shop->name }}
        </div>
        @if($live->viewer_count)
          <div class="text-xs text-slate-400 mt-1">👁 {{ number_format($live->viewer_count,0,',','.') }} penonton</div>
        @endif
      </div>
    </a>
  @endforeach
</div>

<div class="mt-6">{{ $streams->links() }}</div>
@endif
@endsection
