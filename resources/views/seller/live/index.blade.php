@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-5">
  <div>
    <h1 class="text-2xl font-black">📺 Kelola Live Stream</h1>
    <p class="text-sm text-slate-500">Buat, mulai, dan kelola sesi live streaming kamu.</p>
  </div>
  <a href="{{ route('seller.live.create') }}"
     class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition">
    + Buat Live
  </a>
</div>

@if(session('success'))
  <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-semibold">
    ✅ {{ session('success') }}
  </div>
@endif

<div class="bg-white border rounded-2xl overflow-hidden">
  @forelse($streams as $live)
    <div class="p-4 flex items-center gap-4 border-b last:border-0 hover:bg-slate-50 transition">
      {{-- Thumbnail --}}
      <div class="w-20 h-14 rounded-xl overflow-hidden bg-slate-200 flex-shrink-0">
        @if($live->thumbnail_path)
          <img src="{{ asset('storage/'.$live->thumbnail_path) }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center text-slate-400">📺</div>
        @endif
      </div>

      <div class="flex-1 min-w-0">
        <a href="{{ route('seller.live.show', $live) }}" class="font-bold hover:text-rose-600 transition line-clamp-1">{{ $live->title }}</a>
        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-3 flex-wrap">
          @php($displayStatus = $live->status === 'scheduled' ? 'DRAFT' : strtoupper($live->status))
          <span class="px-2 py-0.5 rounded-full font-semibold
            {{ $live->status === 'live' ? 'bg-rose-100 text-rose-600' : ($live->status === 'ended' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">
            {{ $displayStatus }}
          </span>
          <span>❤️ {{ number_format($live->like_count ?? 0,0,',','.') }}</span>
          <span>👁 {{ number_format($live->viewer_count ?? 0,0,',','.') }}</span>
          <span>{{ $live->created_at->diffForHumans() }}</span>
        </div>
      </div>

      <form method="POST" action="{{ route('seller.live.status', $live) }}" class="flex gap-2 flex-shrink-0">
        @csrf
        <select name="status" class="rounded-xl border-slate-200 text-sm">
          <option value="draft" @selected($live->status === 'scheduled')>DRAFT</option>
          <option value="live" @selected($live->status === 'live')>LIVE</option>
          <option value="ended" @selected($live->status === 'ended')>ENDED</option>
        </select>
        <button class="px-3 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-700 transition">Update</button>
      </form>
    </div>
  @empty
    <div class="p-10 text-center text-slate-500">
      <div class="text-5xl mb-3">📺</div>
      <div class="font-semibold">Belum ada live stream.</div>
      <div class="text-sm mt-1">Mulai live pertamamu sekarang!</div>
      <a href="{{ route('seller.live.create') }}" class="inline-block mt-4 px-5 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition">
        Buat Live Pertama
      </a>
    </div>
  @endforelse
</div>

<div class="mt-6">{{ $streams->links() }}</div>
@endsection
