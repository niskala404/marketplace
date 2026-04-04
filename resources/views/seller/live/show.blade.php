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
      <span>👁 {{ number_format($live->viewer_count ?? 0,0,',','.') }}</span>
    </div>
  </div>
  <a href="{{ route('seller.live.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition flex-shrink-0">
    ← Kembali
  </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- ===== KIRI: Kamera + Kontrol ===== --}}
  <div class="lg:col-span-2 space-y-4">

    {{-- Camera Preview --}}
    <div class="bg-black rounded-2xl overflow-hidden relative" style="aspect-ratio:16/9;">
      <video id="cameraPreview" autoplay playsinline muted
        class="w-full h-full object-cover {{ $live->status !== 'live' ? 'opacity-60' : '' }}"></video>

      {{-- Overlay saat belum mulai --}}
      <div id="cameraOverlay"
        class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-white/80
               {{ $live->status === 'live' ? 'hidden' : '' }}">
        <svg class="w-16 h-16 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14
               M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
        <p class="text-sm font-semibold">Kamera belum aktif</p>
        <button id="startCameraBtn"
          class="px-5 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition text-sm">
          📷 Aktifkan Kamera
        </button>
      </div>

      {{-- LIVE badge --}}
      @if($live->status === 'live')
        <div class="absolute top-3 left-3 flex items-center gap-1 bg-rose-600 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse shadow-lg">
          <span class="w-2 h-2 rounded-full bg-white"></span> LIVE
        </div>
      @endif

      {{-- Camera controls overlay (bottom) --}}
      <div id="camControls"
        class="absolute bottom-0 inset-x-0 p-3 flex items-center gap-2 justify-center
               bg-gradient-to-t from-black/60 to-transparent
               {{ $live->status === 'live' ? '' : 'hidden' }}">
        <button id="muteBtn"
          class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition"
          title="Mute/Unmute">🎤</button>
        <button id="camFlipBtn"
          class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition"
          title="Flip Kamera">🔄</button>
        <button id="stopCameraBtn"
          class="w-10 h-10 rounded-full bg-red-500/80 hover:bg-red-600 text-white flex items-center justify-center transition"
          title="Matikan Kamera">⏹</button>
      </div>
    </div>

    {{-- Error kamera --}}
    <div id="cameraError" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm"></div>

    {{-- Start / Stop Live --}}
    <div class="bg-white border rounded-2xl p-4 flex flex-wrap items-center gap-3">
      @if($live->status !== 'live')
        <form method="POST" action="{{ route('seller.live.status', $live) }}" class="flex-1">
          @csrf
          <input type="hidden" name="status" value="live">
          <button id="goLiveBtn"
            class="w-full py-3 rounded-xl bg-rose-600 text-white font-black text-base hover:bg-rose-700 transition">
            🔴 Mulai Live Sekarang
          </button>
        </form>
      @else
        <div class="flex-1 py-3 text-center font-black text-rose-600 text-lg animate-pulse">🔴 Sedang Live!</div>
        <form method="POST" action="{{ route('seller.live.status', $live) }}">
          @csrf
          <input type="hidden" name="status" value="ended">
          <button class="px-5 py-3 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-700 transition">
            ⏹ Stop Live
          </button>
        </form>
      @endif

      {{-- Share link --}}
      <button id="copyLinkBtn"
        data-link="{{ route('live.show', $live) }}"
        class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition text-sm">
        🔗 Salin Link
      </button>
    </div>

    {{-- Live chat monitor (seller view) --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b font-bold text-sm flex items-center gap-2">
        💬 Monitor Chat Penonton
        <span class="ml-auto text-xs text-slate-400" id="chatCountLabel">0 pesan</span>
      </div>
      <div id="sellerChatBox" class="flex flex-col gap-1 p-3 h-48 overflow-y-auto text-sm bg-slate-50">
        <div class="text-slate-400 text-xs text-center mt-4" id="emptyChatSeller">Belum ada chat dari penonton.</div>
      </div>
    </div>

  </div>

  {{-- ===== KANAN: Produk ===== --}}
  <div class="space-y-4">

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-2">
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-rose-600">{{ number_format($live->like_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Likes</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-blue-600">{{ number_format($live->viewer_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Viewers</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-emerald-600">{{ number_format($live->share_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Share</div>
      </div>
    </div>

    {{-- Manage Products --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b font-bold text-sm flex items-center justify-between">
        🛍️ Produk Ditampilkan
        <button id="toggleProductPanel"
          class="text-xs text-rose-600 font-semibold hover:underline">+ Kelola</button>
      </div>

      {{-- Current products --}}
      <div id="currentProducts" class="divide-y max-h-64 overflow-y-auto">
        @forelse($live->products as $p)
          <div class="flex items-center gap-2 p-2.5 text-sm" data-product-id="{{ $p->id }}">
            @php($img = $p->images->first())
            <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('images/placeholder.png') }}"
                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <div class="font-semibold line-clamp-1">{{ $p->name }}</div>
              <div class="text-rose-600 text-xs font-bold">Rp {{ number_format($p->price,0,',','.') }}</div>
            </div>
          </div>
        @empty
          <div class="p-4 text-center text-slate-400 text-sm" id="emptyProducts">Belum ada produk</div>
        @endforelse
      </div>

      {{-- Product picker (hidden by default) --}}
      <div id="productPanel" class="hidden border-t p-3 bg-slate-50">
        <p class="text-xs text-slate-500 mb-2">Centang produk yang ingin ditampilkan ke penonton:</p>
        <div class="space-y-1 max-h-52 overflow-y-auto">
          @foreach($products as $p)
            <label class="flex items-center gap-2 text-sm p-1.5 rounded-lg hover:bg-white cursor-pointer transition">
              <input type="checkbox" class="product-check rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                value="{{ $p->id }}"
                {{ in_array($p->id, $selectedIds) ? 'checked' : '' }}>
              <span class="truncate flex-1">{{ $p->name }}</span>
              <span class="text-rose-600 text-xs font-bold whitespace-nowrap">Rp {{ number_format($p->price,0,',','.') }}</span>
            </label>
          @endforeach
        </div>
        <button id="saveProductsBtn"
          data-url="{{ route('seller.live.products', $live) }}"
          class="w-full mt-3 py-2 rounded-xl bg-rose-600 text-white font-bold text-sm hover:bg-rose-700 transition">
          💾 Simpan Produk
        </button>
        <div id="productSaveMsg" class="hidden mt-2 text-xs text-emerald-600 font-semibold text-center">✅ Produk berhasil diperbarui!</div>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
(function(){
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  /* =========== CAMERA =========== */
  let stream = null;
  let facingMode = 'user'; // front camera default
  let isMuted    = false;

  const video          = document.getElementById('cameraPreview');
  const overlay        = document.getElementById('cameraOverlay');
  const camControls    = document.getElementById('camControls');
  const cameraError    = document.getElementById('cameraError');
  const startCameraBtn = document.getElementById('startCameraBtn');
  const stopCameraBtn  = document.getElementById('stopCameraBtn');
  const muteBtn        = document.getElementById('muteBtn');
  const camFlipBtn     = document.getElementById('camFlipBtn');

  async function startCamera(mode){
    try{
      if(stream){ stream.getTracks().forEach(t => t.stop()); }
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: mode, width: { ideal: 1280 }, height: { ideal: 720 } },
        audio: true,
      });
      video.srcObject = stream;
      overlay?.classList.add('hidden');
      camControls?.classList.remove('hidden');
      video.classList.remove('opacity-60');
      cameraError?.classList.add('hidden');
    } catch(err){
      cameraError.textContent = '⚠️ Tidak dapat mengakses kamera: ' + err.message;
      cameraError?.classList.remove('hidden');
    }
  }

  startCameraBtn?.addEventListener('click', () => startCamera(facingMode));

  stopCameraBtn?.addEventListener('click', () => {
    if(stream){ stream.getTracks().forEach(t => t.stop()); stream = null; }
    video.srcObject = null;
    overlay?.classList.remove('hidden');
    camControls?.classList.add('hidden');
    video.classList.add('opacity-60');
  });

  muteBtn?.addEventListener('click', () => {
    if(!stream) return;
    isMuted = !isMuted;
    stream.getAudioTracks().forEach(t => { t.enabled = !isMuted; });
    muteBtn.textContent = isMuted ? '🔇' : '🎤';
    muteBtn.title = isMuted ? 'Unmute' : 'Mute';
  });

  camFlipBtn?.addEventListener('click', () => {
    facingMode = facingMode === 'user' ? 'environment' : 'user';
    startCamera(facingMode);
  });

  // Auto-start camera if live
  @if($live->status === 'live')
    startCamera(facingMode);
  @endif

  /* =========== COPY LINK =========== */
  document.getElementById('copyLinkBtn')?.addEventListener('click', async () => {
    const link = document.getElementById('copyLinkBtn').dataset.link;
    try{
      await navigator.clipboard.writeText(link);
      alert('Link live berhasil disalin! 🔗\n' + link);
    } catch(e){ prompt('Salin link ini:', link); }
  });

  /* =========== PRODUCT PANEL =========== */
  const toggleBtn = document.getElementById('toggleProductPanel');
  const panel     = document.getElementById('productPanel');
  toggleBtn?.addEventListener('click', () => { panel.classList.toggle('hidden'); });

  document.getElementById('saveProductsBtn')?.addEventListener('click', async () => {
    const checked  = [...document.querySelectorAll('.product-check:checked')].map(el => parseInt(el.value));
    const saveMsg  = document.getElementById('productSaveMsg');
    try{
      const res = await fetch(document.getElementById('saveProductsBtn').dataset.url, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'},
        body: JSON.stringify({ product_ids: checked }),
      });
      const data = await res.json();
      if(data.success){
        saveMsg?.classList.remove('hidden');
        setTimeout(() => saveMsg?.classList.add('hidden'), 3000);
      }
    } catch(e){ console.error(e); }
  });

  /* =========== SELLER CHAT MONITOR =========== */
  const sellerChatBox  = document.getElementById('sellerChatBox');
  const emptyChatSeller= document.getElementById('emptyChatSeller');
  const chatCountLabel = document.getElementById('chatCountLabel');
  let lastId    = 0;
  let chatCount = 0;

  function appendSellerChat(user, body){
    emptyChatSeller?.remove();
    chatCount++;
    chatCountLabel && (chatCountLabel.textContent = chatCount + ' pesan');
    const div = document.createElement('div');
    div.className = 'flex gap-1.5';
    div.innerHTML = `<span class="font-bold text-rose-600 shrink-0">${user}:</span><span class="text-slate-700 break-words">${body}</span>`;
    sellerChatBox.appendChild(div);
    sellerChatBox.scrollTop = sellerChatBox.scrollHeight;
  }

  async function pollSellerChat(){
    try{
      const res  = await fetch(`{{ route('live.comments.poll', $live) }}?since=${lastId}`);
      const data = await res.json();
      data.forEach(c => { if(c.id > lastId){ lastId = c.id; appendSellerChat(c.user, c.body); } });
    }catch(e){}
  }

  @if($live->status === 'live')
    setInterval(pollSellerChat, 3000);
  @endif

})();
</script>
@endpush
@endsection
