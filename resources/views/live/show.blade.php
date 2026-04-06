@extends('layouts.market')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- ===== LEFT: Video + Info ===== --}}
  <div class="lg:col-span-2 space-y-4">

    {{-- Video Player --}}
    <div class="bg-black rounded-2xl overflow-hidden aspect-video relative" id="playerWrap">

      {{-- Container video Agora (diisi oleh SDK) --}}
      <div id="remoteVideo" class="w-full h-full"></div>

      {{-- Overlay: menunggu seller publish --}}
      <div id="waitingOverlay" class="absolute inset-0 flex flex-col items-center justify-center text-white/60 gap-3
        {{ $live->status !== 'live' ? '' : '' }}">
        @if($live->status === 'live')
          <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
          </svg>
          <span class="text-sm font-semibold" id="waitingText">Menghubungkan ke siaran...</span>
        @else
          <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span class="text-sm">Live belum dimulai</span>
          @if($live->scheduled_at)
            <span class="text-xs opacity-60">Terjadwal: {{ $live->scheduled_at->translatedFormat('d M Y, H:i') }} WIB</span>
          @endif
        @endif
      </div>

      {{-- LIVE badge --}}
      @if($live->status === 'live')
        <div class="absolute top-3 left-3 flex items-center gap-1 bg-rose-600 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse shadow-lg z-10">
          <span class="w-2 h-2 rounded-full bg-white inline-block"></span> LIVE
        </div>
        <div class="absolute top-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full z-10">
          👁 <span id="viewerCount">{{ number_format($live->viewer_count, 0, ',', '.') }}</span>
        </div>
      @endif
    </div>

    {{-- Info Stream --}}
    <div class="bg-white border rounded-2xl p-4">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
          <h1 class="font-black text-lg leading-tight line-clamp-2">{{ $live->title }}</h1>
          <div class="flex items-center gap-2 mt-1 text-sm">
            <a href="{{ route('shop.show', $live->shop->slug) }}" class="font-semibold text-rose-600 hover:underline">
              {{ $live->shop->name }}
            </a>
          </div>
        </div>

        {{-- Like & Share --}}
        <div class="flex items-center gap-2 flex-shrink-0">
          @auth
            <button id="likeBtn"
              data-url="{{ route('live.like', $live) }}"
              data-liked="{{ $userLiked ? '1' : '0' }}"
              class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition
                {{ $userLiked ? 'bg-rose-50 border-rose-400 text-rose-600' : 'border-slate-200 text-slate-500 hover:border-rose-300 hover:text-rose-500' }}">
              <span id="likeIcon" class="text-xl">{{ $userLiked ? '❤️' : '🤍' }}</span>
              <span id="likeCount" class="text-xs font-bold">{{ number_format($live->like_count, 0, ',', '.') }}</span>
            </button>
          @else
            <div class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border border-slate-200 text-slate-400">
              <span class="text-xl">🤍</span>
              <span id="likeCount" class="text-xs font-bold">{{ number_format($live->like_count, 0, ',', '.') }}</span>
            </div>
          @endauth

          <button id="shareBtn"
            data-url="{{ route('live.share', $live) }}"
            data-share-link="{{ route('live.show', $live) }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-500 transition">
            <span class="text-xl">🔗</span>
            <span id="shareCount" class="text-xs font-bold">{{ number_format($live->share_count, 0, ',', '.') }}</span>
          </button>
        </div>
      </div>

      @if($live->description)
        <div class="mt-3 text-sm text-slate-600 whitespace-pre-line border-t pt-3">{{ $live->description }}</div>
      @endif
    </div>

    {{-- Products (mobile) --}}
    <div class="lg:hidden">
      @include('live._products', ['products' => $live->products])
    </div>

    {{-- Live Chat --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b flex items-center gap-2 font-bold text-sm">
        💬 Live Chat
        @if($live->status === 'live')
          <span class="ml-auto text-xs text-emerald-600 font-semibold animate-pulse">● aktif</span>
        @endif
      </div>

      <div id="chatBox" class="flex flex-col gap-1 p-3 h-64 overflow-y-auto text-sm bg-slate-50">
        @forelse($comments as $c)
          <div class="flex gap-1.5">
            <span class="font-bold text-rose-600 shrink-0">{{ $c->user->name ?? 'Anonim' }}:</span>
            <span class="text-slate-700">{{ $c->body }}</span>
          </div>
        @empty
          <div class="text-slate-400 text-xs text-center mt-4" id="emptyChat">Belum ada komentar. Mulai percakapan! 👋</div>
        @endforelse
      </div>

      @auth
        <div class="p-3 border-t flex gap-2">
          <input id="chatInput" type="text" maxlength="300" placeholder="Tulis komentar..."
            class="flex-1 rounded-xl border-slate-200 text-sm px-3 py-2 focus:ring-rose-500 focus:border-rose-500">
          <button id="chatSend"
            data-url="{{ route('live.comment', $live) }}"
            class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold text-sm hover:bg-rose-700 transition">
            Kirim
          </button>
        </div>
      @else
        <div class="p-3 border-t text-center text-xs text-slate-500">
          <a href="{{ route('login') }}" class="text-rose-600 font-semibold hover:underline">Login</a> untuk ikut komentar
        </div>
      @endauth
    </div>

  </div>

  {{-- ===== RIGHT: Products ===== --}}
  <div class="hidden lg:block">
    @include('live._products', ['products' => $live->products])
  </div>

</div>
@endsection

@push('scripts')
{{-- Agora Web SDK v4 --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
<script>
(function () {
  const LIVE_STATUS  = '{{ $live->status }}';
  const TOKEN_URL    = '{{ route("live.agora-token", $live) }}';
  const POLL_URL     = '{{ route("live.poll", $live) }}';
  const POLL_MS          = 3000;
  const HEARTBEAT_URL    = '{{ route("live.heartbeat", $live) }}';
  const HEARTBEAT_MS     = 15000; // ping every 15 seconds
  const csrf         = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  // ── Elemen ──────────────────────────────────────────────────
  const remoteVideoEl  = document.getElementById('remoteVideo');
  const waitingOverlay = document.getElementById('waitingOverlay');
  const waitingText    = document.getElementById('waitingText');
  const viewerEl       = document.getElementById('viewerCount');
  const likeBtn        = document.getElementById('likeBtn');
  const likeIcon       = document.getElementById('likeIcon');
  const likeCount      = document.getElementById('likeCount');
  const shareBtn       = document.getElementById('shareBtn');
  const shareCount     = document.getElementById('shareCount');
  const chatBox        = document.getElementById('chatBox');
  const chatInput      = document.getElementById('chatInput');
  const chatSend       = document.getElementById('chatSend');

  let lastCommentAt = null;
  @if($comments->isNotEmpty())
    lastCommentAt = '{{ $comments->first()->created_at->toISOString() }}';
  @endif

  // ── Helper ───────────────────────────────────────────────────
  function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }
  function fmt(n) { return Number(n).toLocaleString('id-ID'); }

  function showVideo()   { if (waitingOverlay) waitingOverlay.classList.add('hidden'); }
  function hideVideo()   { if (waitingOverlay) waitingOverlay.classList.remove('hidden'); }

  function appendComment(name, body) {
    const empty = document.getElementById('emptyChat');
    if (empty) empty.remove();
    const row = document.createElement('div');
    row.className = 'flex gap-1.5';
    row.innerHTML = `<span class="font-bold text-rose-600 shrink-0">${esc(name)}:</span><span class="text-slate-700">${esc(body)}</span>`;
    chatBox.appendChild(row);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  // ── Agora: join sebagai audience ────────────────────────────
  async function initAgora() {
    if (LIVE_STATUS !== 'live') return;

    try {
      const res  = await fetch(TOKEN_URL + '?role=audience');
      const info = await res.json();

      if (!info.appId) {
        if (waitingText) waitingText.textContent = 'Stream sedang berlangsung...';
        return; // Agora App ID belum dikonfigurasi
      }

      const client = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
      await client.setClientRole('audience');

      // Join channel (token null = testing mode tanpa certificate)
      await client.join(info.appId, info.channel, info.token ?? null, null);

      // Ketika seller publish video/audio
      client.on('user-published', async (user, mediaType) => {
        await client.subscribe(user, mediaType);

        if (mediaType === 'video') {
          showVideo();
          user.videoTrack.play('remoteVideo');
        }
        if (mediaType === 'audio') {
          user.audioTrack.play();
        }
      });

      // Ketika seller stop video
      client.on('user-unpublished', (user, mediaType) => {
        if (mediaType === 'video') hideVideo();
      });

      // Ketika seller leave channel (akhiri live)
      client.on('user-left', () => hideVideo());

      if (waitingText) waitingText.textContent = 'Menunggu seller memulai kamera...';

    } catch (err) {
      console.error('Agora join error:', err);
      if (waitingText) waitingText.textContent = 'Gagal menghubungkan ke stream.';
    }
  }

  initAgora();

  // ── Polling real-time ────────────────────────────────────────
  async function poll() {
    try {
      const url  = lastCommentAt
        ? `${POLL_URL}?since=${encodeURIComponent(lastCommentAt)}`
        : POLL_URL;
      const res  = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;
      const data = await res.json();

      if (likeCount)  likeCount.textContent  = fmt(data.like_count);
      if (shareCount) shareCount.textContent = fmt(data.share_count);
      if (viewerEl)   viewerEl.textContent   = fmt(data.viewer_count);

      if (data.comments && data.comments.length > 0) {
        [...data.comments].reverse().forEach(c => appendComment(c.name, c.body));
        lastCommentAt = data.comments[0].created_at;
      }

      // Jika live berakhir → reload
      if (data.status === 'ended' && LIVE_STATUS === 'live') {
        location.reload();
      }
    } catch (e) { /* silent */ }
  }

  if (LIVE_STATUS === 'live') {
    setInterval(poll, POLL_MS);

    // ✅ NEW: Heartbeat — registers this browser as an active viewer.
    // Called immediately on page load, then every 15 seconds.
    // When tab is closed/navigated away the pings stop and the server
    // removes this viewer from the count after 35 seconds.
    async function heartbeat() {
      try {
        await fetch(HEARTBEAT_URL, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
      } catch (e) { /* silent */ }
    }
    heartbeat();
    setInterval(heartbeat, HEARTBEAT_MS);
  }

  // ── Like ─────────────────────────────────────────────────────
  if (likeBtn) {
    likeBtn.addEventListener('click', async () => {
      likeBtn.disabled = true;
      try {
        const res  = await fetch(likeBtn.dataset.url, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await res.json();
        if (likeIcon)  likeIcon.textContent  = data.liked ? '❤️' : '🤍';
        if (likeCount) likeCount.textContent = fmt(data.count);
        likeBtn.className = 'flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition '
          + (data.liked
            ? 'bg-rose-50 border-rose-400 text-rose-600'
            : 'border-slate-200 text-slate-500 hover:border-rose-300 hover:text-rose-500');
      } catch (e) { console.error(e); }
      finally { likeBtn.disabled = false; }
    });
  }

  // ── Share ────────────────────────────────────────────────────
  if (shareBtn) {
    shareBtn.addEventListener('click', async () => {
      try {
        if (navigator.share) {
          await navigator.share({ title: document.title, url: shareBtn.dataset.shareLink });
        } else {
          await navigator.clipboard.writeText(shareBtn.dataset.shareLink);
          alert('Link disalin!');
        }
        const res  = await fetch(shareBtn.dataset.url, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await res.json();
        if (shareCount) shareCount.textContent = fmt(data.count);
      } catch (e) { /* share di-cancel = normal */ }
    });
  }

  // ── Kirim komentar ───────────────────────────────────────────
  async function sendComment() {
    if (!chatInput || !chatSend) return;
    const body = chatInput.value.trim();
    if (!body) return;
    chatSend.disabled = true;
    try {
      const res  = await fetch(chatSend.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ body }),
      });
      const data = await res.json();
      if (data.body) {
        appendComment(data.name, data.body);
        if (data.created_at) lastCommentAt = data.created_at;
        chatInput.value = '';
      }
    } catch (e) { console.error(e); }
    finally { chatSend.disabled = false; }
  }

  if (chatSend)  chatSend.addEventListener('click', sendComment);
  if (chatInput) chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendComment(); });

})();
</script>
@endpush