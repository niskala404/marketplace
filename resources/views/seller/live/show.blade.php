@extends('layouts.market')

@section('content')

@if(session('success'))
  <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-semibold">
    ✅ {{ session('success') }}
  </div>
@endif

<div class="flex items-start justify-between gap-3 mb-5">
  <div>
    <h1 class="text-xl font-black line-clamp-2">{{ $live->title }}</h1>
    <div class="flex items-center gap-2 mt-1 text-sm text-slate-500 flex-wrap">
      @php($st = $live->status === 'scheduled' ? 'DRAFT' : strtoupper($live->status))
      <span id="statusBadge" class="px-2 py-0.5 rounded-full font-semibold text-xs
        {{ $live->status === 'live' ? 'bg-rose-100 text-rose-600 animate-pulse' : ($live->status === 'ended' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">
        {{ $st }}
      </span>
      <span>❤️ <span id="likeCount">{{ number_format($live->like_count ?? 0,0,',','.') }}</span></span>
      <span>👁 <span id="viewerCount">{{ number_format($live->viewer_count ?? 0,0,',','.') }}</span></span>
      <span>🔗 <span id="shareCount">{{ number_format($live->share_count ?? 0,0,',','.') }}</span></span>
    </div>
  </div>
  <a href="{{ route('seller.live.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition flex-shrink-0">
    ← Kembali
  </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

  {{-- ===== KIRI: Video & Kontrol ===== --}}
  <div class="lg:col-span-2 space-y-4">

    {{-- Video Container (diisi Agora SDK) --}}
    <div class="bg-black rounded-2xl overflow-hidden relative aspect-video">
      <div id="localVideo" class="w-full h-full"></div>

      {{-- Overlay sebelum kamera aktif --}}
      <div id="cameraOverlay"
        class="absolute inset-0 flex flex-col items-center justify-center bg-black/80 text-white gap-3">
        <div class="text-5xl">📷</div>
        <p class="text-sm font-semibold">Klik "Aktifkan Kamera" untuk preview</p>
        <button id="startCameraBtn"
          class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-sm transition">
          📷 Aktifkan Kamera
        </button>
      </div>

      {{-- Badge LIVE --}}
      @if($live->status === 'live')
      <div id="liveBadge" class="absolute top-3 left-3 bg-rose-600 text-white text-xs font-black px-2 py-1 rounded-full animate-pulse">
        🔴 LIVE
      </div>
      @endif

      {{-- Durasi timer — dihitung dari started_at --}}
      <div id="liveDuration" class="hidden absolute top-3 right-3 bg-black/60 text-white text-xs font-mono px-2 py-1 rounded-full">
        00:00:00
      </div>
    </div>

    {{-- Kontrol Kamera --}}
    <div class="bg-white border rounded-2xl p-4 flex flex-wrap gap-2">
      <button id="startCameraBtn2"
        class="flex-1 min-w-[120px] py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center justify-center gap-1">
        📷 Kamera
      </button>
      <button id="toggleMicBtn"
        class="flex-1 min-w-[120px] py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center justify-center gap-1">
        🎙️ Mik ON
      </button>
      <button id="toggleCamBtn"
        class="flex-1 min-w-[120px] py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center justify-center gap-1">
        📹 Video ON
      </button>
      <button id="flipCamBtn"
        class="flex-1 min-w-[120px] py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center justify-center gap-1">
        🔄 Balik Kamera
      </button>
    </div>

    {{-- Tombol Start / End Live --}}
    <div class="bg-white border rounded-2xl p-4">
      @if($live->status === 'scheduled')
        <p class="text-sm text-slate-500 mb-3">Aktifkan kamera terlebih dahulu, lalu mulai siaran.</p>
        <form method="POST" action="{{ route('seller.live.status', $live) }}">
          @csrf
          <input type="hidden" name="status" value="live">
          <button type="submit"
            class="w-full py-3 rounded-xl bg-rose-600 text-white font-black text-base hover:bg-rose-700 transition">
            🔴 Mulai Live Sekarang
          </button>
        </form>
      @elseif($live->status === 'live')
        <div class="flex gap-2">
          <div class="flex-1 py-3 rounded-xl bg-rose-50 text-rose-700 font-bold text-sm text-center animate-pulse">
            🔴 Sedang Live...
          </div>
          <form method="POST" action="{{ route('seller.live.status', $live) }}">
            @csrf
            <input type="hidden" name="status" value="ended">
            <button type="submit"
              onclick="return confirm('Akhiri sesi live ini?')"
              class="py-3 px-4 rounded-xl bg-slate-700 text-white font-bold text-sm hover:bg-slate-900 transition">
              ⏹ Akhiri Live
            </button>
          </form>
        </div>
      @else
        <div class="py-3 rounded-xl bg-slate-100 text-slate-500 font-semibold text-sm text-center">
          ✅ Sesi live telah berakhir
        </div>
      @endif
    </div>

    {{-- Deskripsi --}}
    @if($live->description)
    <div class="bg-white border rounded-2xl p-4">
      <div class="text-sm font-bold text-slate-700 mb-1">📝 Deskripsi</div>
      <p class="text-sm text-slate-600 whitespace-pre-line">{{ $live->description }}</p>
    </div>
    @endif

    {{-- Jadwal --}}
    @if($live->scheduled_at)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
      📅 Dijadwalkan: <strong>{{ \Carbon\Carbon::parse($live->scheduled_at)->locale('id')->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB</strong>
    </div>
    @endif

    {{-- Live Chat (read panel untuk seller) --}}
    @if($live->status === 'live')
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b font-bold text-sm flex items-center gap-2">
        💬 Komentar Penonton
        <span class="ml-auto text-xs text-emerald-600 font-semibold animate-pulse">● live</span>
      </div>
      <div id="sellerChatBox" class="flex flex-col gap-1 p-3 h-52 overflow-y-auto text-sm bg-slate-50">
        <div class="text-slate-400 text-xs text-center mt-4" id="sellerEmptyChat">Menunggu komentar penonton...</div>
      </div>
    </div>
    @endif

  </div>

  {{-- ===== KANAN: Stats & Produk ===== --}}
  <div class="space-y-4">

    {{-- Stats real-time --}}
    <div class="grid grid-cols-3 gap-2">
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-rose-600" id="statLikes">{{ number_format($live->like_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Likes</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-blue-600" id="statViewers">{{ number_format($live->viewer_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Viewers</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-emerald-600" id="statShares">{{ number_format($live->share_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Share</div>
      </div>
    </div>

    {{-- Thumbnail --}}
    @if($live->thumbnail_path)
    <div class="bg-white border rounded-2xl overflow-hidden">
      <img src="{{ asset('storage/'.$live->thumbnail_path) }}" class="w-full object-cover max-h-40">
    </div>
    @endif

    {{-- Kelola Produk --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b font-bold text-sm flex items-center justify-between">
        🛍️ Produk Ditampilkan
        <button id="toggleProductPanel" class="text-xs text-rose-600 font-semibold hover:underline">+ Kelola</button>
      </div>

      <div id="currentProducts" class="divide-y max-h-64 overflow-y-auto">
        @forelse($live->products as $p)
          <div class="flex items-center gap-2 p-2.5 text-sm">
            @php($img = $p->images->first())
            <img src="{{ $img ? asset('storage/'.($img->path ?? $img->image_path)) : asset('images/placeholder.png') }}"
                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <div class="font-semibold line-clamp-1">{{ $p->name }}</div>
              <div class="text-rose-600 text-xs font-bold">Rp {{ number_format($p->price,0,',','.') }}</div>
            </div>
          </div>
        @empty
          <div class="p-4 text-center text-slate-400 text-sm">Belum ada produk</div>
        @endforelse
      </div>

      <div id="productPanel" class="hidden border-t p-3 bg-slate-50">
        <p class="text-xs text-slate-500 mb-2">Centang produk yang ingin ditampilkan:</p>
        <div class="space-y-1 max-h-52 overflow-y-auto">
          @foreach($products as $p)
            <label class="flex items-center gap-2 text-sm p-1.5 rounded-lg hover:bg-white cursor-pointer transition">
              <input type="checkbox" class="product-check rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                value="{{ $p->id }}" {{ in_array($p->id, $selectedIds) ? 'checked' : '' }}>
              <span class="truncate flex-1">{{ $p->name }}</span>
              <span class="text-rose-600 text-xs font-bold whitespace-nowrap">Rp {{ number_format($p->price,0,',','.') }}</span>
            </label>
          @endforeach
        </div>
        <button id="saveProductsBtn" data-url="{{ route('seller.live.products', $live) }}"
          class="w-full mt-3 py-2 rounded-xl bg-rose-600 text-white font-bold text-sm hover:bg-rose-700 transition">
          💾 Simpan Produk
        </button>
        <div id="productSaveMsg" class="hidden mt-2 text-xs text-emerald-600 font-semibold text-center">✅ Berhasil diperbarui!</div>
      </div>
    </div>

    {{-- Share Link --}}
    <div class="bg-white border rounded-2xl p-3">
      <div class="text-sm font-bold text-slate-700 mb-2">🔗 Bagikan Link Live</div>
      <div class="flex gap-2">
        <input id="shareLinkInput" type="text" readonly value="{{ url('/live/'.$live->id) }}"
          class="flex-1 text-xs bg-slate-50 border rounded-lg px-2 py-1.5 text-slate-600 focus:outline-none">
        <button id="copyLinkBtn"
          class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-semibold transition">
          Salin
        </button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
{{-- Agora Web SDK v4 --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
<script>
(function () {
  const IS_LIVE   = {{ $live->status === 'live' ? 'true' : 'false' }};
  const TOKEN_URL = '{{ route("live.agora-token", $live) }}';
  const POLL_URL  = '{{ route("live.poll", $live) }}';
  const POLL_MS   = 3000;

  // ── Agora state ──────────────────────────────────────────────
  let agoraClient    = null;
  let localAudioTrack = null;
  let localVideoTrack = null;
  let micEnabled     = true;
  let camEnabled     = true;
  let lastCommentAt  = null;

  const overlay    = document.getElementById('cameraOverlay');
  const durationEl = document.getElementById('liveDuration');
  const micBtn     = document.getElementById('toggleMicBtn');
  const camBtn     = document.getElementById('toggleCamBtn');

  function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }
  function fmt(n) { return Number(n).toLocaleString('id-ID'); }

  // ── Inisialisasi Agora sebagai Host ──────────────────────────
  async function initAgoraHost() {
    try {
      const res  = await fetch(TOKEN_URL + '?role=host&uid=1');
      const info = await res.json();

      if (!info.appId) {
        alert('AGORA_APP_ID belum dikonfigurasi di .env');
        return;
      }

      agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
      await agoraClient.setClientRole('host');
      await agoraClient.join(info.appId, info.channel, info.token ?? null, 1);

      // Buat track audio & video
      [localAudioTrack, localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
        {},
        { encoderConfig: '720p_1' }
      );

      // Tampilkan preview lokal
      localVideoTrack.play('localVideo');
      if (overlay) overlay.classList.add('hidden');

      // Publish ke channel (customer bisa nonton)
      await agoraClient.publish([localAudioTrack, localVideoTrack]);

      micEnabled = true;
      camEnabled = true;
      updateButtons();

    } catch (err) {
      console.error('Agora host error:', err);
      alert('Gagal mengakses kamera/mikrofon: ' + err.message);
    }
  }

  // ── Aktifkan kamera (panggil initAgora jika sudah live, atau hanya preview) ──
  async function startCamera() {
    if (IS_LIVE) {
      await initAgoraHost();
    } else {
      // Belum live — preview lokal saja tanpa join channel
      try {
        if (localVideoTrack) return; // sudah aktif
        localVideoTrack = await AgoraRTC.createCameraVideoTrack();
        localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        localVideoTrack.play('localVideo');
        if (overlay) overlay.classList.add('hidden');
        micEnabled = true;
        camEnabled = true;
        updateButtons();
      } catch (err) {
        alert('Gagal mengakses kamera: ' + err.message);
      }
    }
  }

  function toggleMic() {
    if (!localAudioTrack) return;
    micEnabled = !micEnabled;
    localAudioTrack.setEnabled(micEnabled);
    updateButtons();
  }

  function toggleCam() {
    if (!localVideoTrack) return;
    camEnabled = !camEnabled;
    localVideoTrack.setEnabled(camEnabled);
    updateButtons();
  }

  async function flipCamera() {
    if (!localVideoTrack) return;
    try {
      const cameras = await AgoraRTC.getCameras();
      if (cameras.length < 2) { alert('Hanya ada 1 kamera.'); return; }
      const curId  = localVideoTrack.getTrackLabel();
      const next   = cameras.find(c => c.label !== curId) ?? cameras[0];
      await localVideoTrack.setDevice(next.deviceId);
    } catch (e) { console.error(e); }
  }

  function updateButtons() {
    if (micBtn) micBtn.textContent = micEnabled ? '🎙️ Mik ON' : '🔇 Mik OFF';
    if (camBtn) camBtn.textContent = camEnabled ? '📹 Video ON' : '🚫 Video OFF';
  }

  async function stopCamera() {
    if (localAudioTrack) { localAudioTrack.stop(); localAudioTrack.close(); localAudioTrack = null; }
    if (localVideoTrack) { localVideoTrack.stop(); localVideoTrack.close(); localVideoTrack = null; }
    if (agoraClient)     { await agoraClient.leave(); agoraClient = null; }
  }

  // Event listeners kamera
  const startBtn  = document.getElementById('startCameraBtn');
  const startBtn2 = document.getElementById('startCameraBtn2');
  const flipBtn   = document.getElementById('flipCamBtn');
  if (startBtn)  startBtn.addEventListener('click', startCamera);
  if (startBtn2) startBtn2.addEventListener('click', startCamera);
  if (micBtn)    micBtn.addEventListener('click', toggleMic);
  if (camBtn)    camBtn.addEventListener('click', toggleCam);
  if (flipBtn)   flipBtn.addEventListener('click', flipCamera);

  // Auto aktifkan kamera + join Agora jika sudah live
  if (IS_LIVE) initAgoraHost();

  // ── Timer durasi (FIX: hitung dari started_at bukan jam sekarang) ──
  @if($live->status === 'live' && $live->started_at)
  (function () {
    const startedAt = new Date('{{ $live->started_at->toISOString() }}');
    if (isNaN(startedAt)) return;

    if (durationEl) durationEl.classList.remove('hidden');

    function tick() {
      const diff = Math.max(0, Math.floor((Date.now() - startedAt.getTime()) / 1000));
      const h = String(Math.floor(diff / 3600)).padStart(2, '0');
      const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
      const s = String(diff % 60).padStart(2, '0');
      if (durationEl) durationEl.textContent = `${h}:${m}:${s}`;
    }
    tick();
    setInterval(tick, 1000);
  })();
  @endif

  // ── Polling real-time untuk seller ──────────────────────────
  const likeCountEl   = document.getElementById('likeCount');
  const viewerCountEl = document.getElementById('viewerCount');
  const shareCountEl  = document.getElementById('shareCount');
  const statLikesEl   = document.getElementById('statLikes');
  const statViewersEl = document.getElementById('statViewers');
  const statSharesEl  = document.getElementById('statShares');
  const chatBox       = document.getElementById('sellerChatBox');

  function appendSellerComment(name, body) {
    const empty = document.getElementById('sellerEmptyChat');
    if (empty) empty.remove();
    if (!chatBox) return;
    const row = document.createElement('div');
    row.className = 'flex gap-1.5';
    row.innerHTML = `<span class="font-bold text-rose-600 shrink-0">${esc(name)}:</span><span class="text-slate-700">${esc(body)}</span>`;
    chatBox.appendChild(row);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  async function poll() {
    try {
      const url  = lastCommentAt
        ? `${POLL_URL}?since=${encodeURIComponent(lastCommentAt)}`
        : POLL_URL;
      const res  = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;
      const data = await res.json();

      if (likeCountEl)   likeCountEl.textContent   = fmt(data.like_count);
      if (viewerCountEl) viewerCountEl.textContent  = fmt(data.viewer_count);
      if (shareCountEl)  shareCountEl.textContent   = fmt(data.share_count);
      if (statLikesEl)   statLikesEl.textContent    = fmt(data.like_count);
      if (statViewersEl) statViewersEl.textContent  = fmt(data.viewer_count);
      if (statSharesEl)  statSharesEl.textContent   = fmt(data.share_count);

      if (data.comments && data.comments.length > 0) {
        [...data.comments].reverse().forEach(c => appendSellerComment(c.name, c.body));
        lastCommentAt = data.comments[0].created_at;
      }
    } catch (e) { /* silent */ }
  }

  if (IS_LIVE) setInterval(poll, POLL_MS);

  // ── Panel produk ─────────────────────────────────────────────
  const toggleBtn = document.getElementById('toggleProductPanel');
  const panel     = document.getElementById('productPanel');
  if (toggleBtn && panel) {
    toggleBtn.addEventListener('click', () => {
      panel.classList.toggle('hidden');
      toggleBtn.textContent = panel.classList.contains('hidden') ? '+ Kelola' : '− Tutup';
    });
  }

  const saveBtn = document.getElementById('saveProductsBtn');
  const saveMsg = document.getElementById('productSaveMsg');
  if (saveBtn) {
    saveBtn.addEventListener('click', async () => {
      const ids  = Array.from(document.querySelectorAll('.product-check:checked')).map(c => parseInt(c.value));
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
      saveBtn.disabled = true;
      saveBtn.textContent = '⏳ Menyimpan...';
      try {
        const res  = await fetch(saveBtn.dataset.url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
          body: JSON.stringify({ product_ids: ids }),
        });
        const json = await res.json();
        if (json.success && saveMsg) { saveMsg.classList.remove('hidden'); setTimeout(() => saveMsg.classList.add('hidden'), 3000); }
      } catch (e) { alert('Gagal menyimpan produk.'); }
      finally { saveBtn.disabled = false; saveBtn.textContent = '💾 Simpan Produk'; }
    });
  }

  // ── Salin link ───────────────────────────────────────────────
  const copyBtn   = document.getElementById('copyLinkBtn');
  const linkInput = document.getElementById('shareLinkInput');
  if (copyBtn && linkInput) {
    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(linkInput.value).then(() => {
        copyBtn.textContent = '✅ Tersalin!';
        setTimeout(() => copyBtn.textContent = 'Salin', 2000);
      });
    });
  }

  // Cleanup saat halaman ditutup
  window.addEventListener('beforeunload', stopCamera);

})();
</script>
@endpush