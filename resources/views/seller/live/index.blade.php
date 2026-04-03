@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-black">Kelola Live Stream</h1>
  <a href="{{ route('seller.live.create') }}" class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">+ Buat Live</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($streams as $live)
      <div class="p-4 flex items-center justify-between gap-3">
        <div>
          <a href="{{ route('seller.live.show', $live) }}" class="font-bold hover:underline">{{ $live->title }}</a>
          <div class="text-xs text-slate-500">Status: {{ strtoupper($live->status) }}</div>
        </div>
        <form method="POST" action="{{ route('seller.live.status', $live) }}" class="flex gap-2">
          @csrf
          <select name="status" class="rounded-xl border-slate-200">
            @foreach(['scheduled','live','ended'] as $status)
              <option value="{{ $status }}" @selected($live->status === $status)>{{ strtoupper($status) }}</option>
            @endforeach
          </select>
          <button class="px-3 py-2 rounded-xl bg-slate-900 text-white">Update</button>
        </form>
      </div>
    @empty
      <div class="p-6 text-slate-600">Belum ada live stream.</div>
    @endforelse
  </div>
</div>

<div class="mt-6">{{ $streams->links() }}</div>
@endsection
