@extends('layouts.market')

@section('content')
<x-app.page title="Notifikasi" subtitle="Update terbaru untuk akunmu">
  <x-slot:actions>
    <form method="POST" action="{{ route('notifications.read_all') }}">
      @csrf
      <x-ui.button variant="secondary" size="md">
        <x-ic name="check-check" class="w-5 h-5" />
        <span>Tandai semua dibaca</span>
      </x-ui.button>
    </form>
  </x-slot:actions>

  <x-ui.card padding="p-0" class="overflow-hidden">
    <div class="divide-y" id="notifList">
      @forelse($notifications as $n)
        @php
          $data = $n->data;
          $title = $data['title'] ?? 'Notifikasi';
          $msg = $data['message'] ?? '';
          $url = $data['url'] ?? null;
        @endphp
        <div class="p-4 flex items-start justify-between gap-4 {{ $n->read_at ? '' : 'bg-rose-50' }}" data-nid="{{ $n->id }}">
          <div class="min-w-0">
            <div class="font-bold">{{ $title }}</div>
            <div class="text-sm text-slate-600 mt-1 whitespace-pre-line">{{ $msg }}</div>
            <div class="text-xs text-slate-400 mt-2">{{ $n->created_at->format('d M Y H:i') }}</div>
          </div>

          <div class="flex gap-2 shrink-0">
            @if($url)
              <a href="{{ $url }}" class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Lihat</a>
            @else
              <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                @csrf
                <button class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Tandai</button>
              </form>
            @endif
          </div>
        </div>
      @empty
        <div class="p-8 text-center text-slate-600" id="notifEmpty">
          <div class="text-lg font-black text-slate-900">Belum ada notifikasi</div>
          <div class="text-sm text-slate-500 mt-1">Notifikasi akan muncul setelah ada aktivitas.</div>
        </div>
      @endforelse
    </div>
  </x-ui.card>

  <div class="mt-4">{{ $notifications->links() }}</div>

  @auth
  <script>
  (function(){
    const list = document.getElementById('notifList');
    const empty = document.getElementById('notifEmpty');
    const userId = @json(auth()->id());

    if(!window.Echo || !userId || !list) return;

    function escapeHtml(s){
      return (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }

    function formatTime(ts){
      try { return new Date(ts).toLocaleString('id-ID'); } catch(e){ return ts; }
    }

    window.Echo.private(`App.Models.User.${userId}`)
      .notification((n) => {
        if(empty) empty.remove();

        const title = escapeHtml(n.title || 'Notifikasi');
        const message = escapeHtml(n.message || '');
        const created = formatTime(n.created_at || new Date().toISOString());
        const href = n.url ? n.url : '{{ route('notifications.index') }}';

        const row = document.createElement('div');
        row.className = 'p-4 flex items-start justify-between gap-4 bg-rose-50';
        row.innerHTML = `
          <div class="min-w-0">
            <div class="font-bold">${title}</div>
            <div class="text-sm text-slate-600 mt-1 whitespace-pre-line">${message}</div>
            <div class="text-xs text-slate-400 mt-2">${created}</div>
          </div>
          <div class="flex gap-2 shrink-0">
            <a href="${href}" class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Lihat</a>
          </div>
        `;
        list.prepend(row);
      });
  })();
  </script>
  @endauth
</x-app.page>
@endsection
