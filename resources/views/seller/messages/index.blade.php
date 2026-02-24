@extends('layouts.market')

@section('content')
<x-app.page title="Inbox Penjual" subtitle="Percakapan dengan pembeli">
  <x-slot:actions>
    <a href="{{ route('seller.dashboard') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 font-semibold inline-flex items-center gap-2">
      <x-ic name="layout-dashboard" class="w-5 h-5" />
      <span>Dashboard</span>
    </a>
  </x-slot:actions>

  <x-ui.card padding="p-0" class="overflow-hidden">
    <div class="divide-y">
      @forelse($conversations as $c)
        @php
          $buyerName = $c->buyer->name ?? 'Pembeli';
          $initial = strtoupper(mb_substr($buyerName, 0, 1));
          $preview = $c->latestMessage?->body ?? 'Mulai percakapan';
          $time = optional($c->last_message_at)->diffForHumans();
          $unread = $c->unread_count ?? null;
        @endphp

        <a href="{{ route('seller.messages.show',$c) }}" class="block p-4 hover:bg-slate-50 transition">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center font-black text-rose-700 shrink-0">
              {{ $initial }}
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <div class="font-extrabold truncate">{{ $buyerName }}</div>
                  <div class="text-sm text-slate-500 truncate">{{ $preview }}</div>
                </div>
                <div class="flex flex-col items-end gap-2 shrink-0">
                  <div class="text-xs text-slate-400 whitespace-nowrap">{{ $time }}</div>
                  @if(!is_null($unread) && (int)$unread > 0)
                    <div class="min-w-[20px] h-5 px-2 rounded-full bg-rose-600 text-white text-[11px] font-bold flex items-center justify-center">
                      {{ (int)$unread > 99 ? '99+' : (int)$unread }}
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="text-slate-300 shrink-0">›</div>
          </div>
        </a>
      @empty
        <x-ui.empty title="Belum ada pesan masuk" message="Pesan akan muncul setelah pembeli memulai chat." />
      @endforelse
    </div>
  </x-ui.card>

  <div class="mt-4">{{ $conversations->links() }}</div>
</x-app.page>
@endsection
