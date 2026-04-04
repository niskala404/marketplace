@extends('layouts.market')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- ===== LEFT: Video Player + Info ===== --}}
  <div class="lg:col-span-2 space-y-4">

    {{-- Player --}}
    <div class="bg-black rounded-2xl overflow-hidden aspect-video relative" id="livePlayerWrap">
      @if($live->status === 'live' && $live->stream_url)
        <iframe src="{{ $live->stream_url }}" class="w-full h-full" allowfullscreen allow="camera;microphone"></iframe>
      @elseif($live->status === 'live')
        {{-- Placeholder when no external stream url: show camera preview (viewer sees black screen) --}}
        <div class="w-full h-full flex flex-col items-center justify-center text-white/60 gap-3">
          <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
          </svg>
          <span class="text-sm">Stream sedang berlangsung...</span>
        </div>
      @else
        <div class="w-full h-full flex flex-col items-center justify-center text-white/60 gap-3">
          <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span class="text-sm">Live belum dimulai</span>
          @if($live->scheduled_at)
            <span class="text-xs opacity-60">Terjadwal: {{ $live->scheduled_at->translatedFormat('d M Y, H:i') }} WIB</span>
          @endif
        </div>
      @endif

      {{-- LIVE badge overlay --}}
      @if($live->status === 'live')
        <div class="absolute top-3 left-3 flex items-center gap-1 bg-rose-600 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse shadow-lg">
          <span class="w-2 h-2 rounded-full bg-white inline-block"></span> LIVE
        </div>
      @endif
    </div>

    {{-- Title + Actions --}}
    <div class="bg-white border rounded-2xl p-4">
      <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
          <h1 class="text-xl font-black line-clamp-2">{{ $live->title }}</h1>
          <div class="flex items-center gap-2 mt-1 text-sm text-slate-500">
            @if($live->shop->logo_path)
              <img src="{{ asset('storage/'.$live->shop->logo_path) }}" class="w-5 h-5 rounded-full object-cover">
            @endif
            <span>{{ $live->shop->name }}</span>
            @if($live->status === 'live')
              <span class="text-slate-300">•</span>
              <span class="text-rose-600 font-semibold text-xs">● LIVE</span>
            @endif
          </div>
          @if($live->viewer_count)
            <div class="text-xs text-slate-400 mt-0.5">👁 {{ number_format($live->viewer_count,0,',','.') }} penonton</div>
          @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex items-center gap-2 flex-shrink-0">
          {{-- Like --}}
          <button id="likeBtn"
            data-url="{{ route('live.like', $live) }}"
            data-liked="{{ $userLiked ? '1' : '0' }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition
              {{ $userLiked ? 'bg-rose-50 border-rose-400 text-rose-600' : 'border-slate-200 text-slate-500 hover:border-rose-300 hover:text-rose-500' }}">
            <span id="likeIcon" class="text-xl">{{ $userLiked ? '❤️' : '🤍' }}</span>
            <span id="likeCount" class="text-xs font-bold">{{ number_format($live->like_count,0,',','.') }}</span>
          </button>

          {{-- Share --}}
          <button id="shareBtn"
            data-url="{{ route('live.share', $live) }}"
            data-share-link="{{ route('live.show', $live) }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-500 transition">
            <span class="text-xl">🔗</span>
            <span id="shareCount" class="text-xs font-bold">{{ number_format($live->share_count,0,',','.') }}</span>
          </button>
        </div>
      </div>

      @if($live->description)
        <div class="mt-3 text-sm text-slate-600 whitespace-pre-line border-t pt-3">{{ $live->description }}</div>
      @endif
    </div>

    {{-- Products (mobile: shown below video) --}}
    <div class="lg:hidden">
      @include('live._products', ['products' => $live->products])
    </div>

    {{-- Comments / Chat --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b flex items-center gap-2 font-bold text-sm">
        💬 Live Chat
        @if($live->status === 'live')
          <span class="ml-auto text-xs text-emerald-600 font-semibold animate-pulse">● aktif</span>
        @endif
      </div>

      {{-- Chat messages --}}
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

      {{-- Input --}}
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

@push('scripts')
<script>
(function(){
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  /* ---- Like ---- */
  const likeBtn  = document.getElementById('likeBtn');
  const likeIcon = document.getElementById('likeIcon');
  const likeCount= document.getElementById('likeCount');

  if(likeBtn){
    likeBtn.addEventListener('click', async () => {
      @auth
        try{
          const res = await fetch(likeBtn.dataset.url, {
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'},
          });
          const data = await res.json();
          likeIcon.textContent  = data.liked ? '❤️' : '🤍';
          likeCount.textContent = Number(data.like_count).toLocaleString('id');
          likeBtn.dataset.liked = data.liked ? '1' : '0';
          likeBtn.classList.toggle('bg-rose-50', data.liked);
          likeBtn.classList.toggle('border-rose-400', data.liked);
          likeBtn.classList.toggle('text-rose-600', data.liked);
        }catch(e){ console.error(e); }
      @else
        window.location.href = '{{ route("login") }}';
      @endauth
    });
  }

  /* ---- Share ---- */
  const shareBtn = document.getElementById('shareBtn');
  const shareCount = document.getElementById('shareCount');
  if(shareBtn){
    shareBtn.addEventListener('click', async () => {
      const link = shareBtn.dataset.shareLink;
      try{
        if(navigator.share){
          await navigator.share({ title: '{{ addslashes($live->title) }}', url: link });
        } else {
          await navigator.clipboard.writeText(link);
          alert('Link live disalin! 📋');
        }
        // increment server
        fetch(shareBtn.dataset.url, {
          method:'POST',
          headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        }).then(r=>r.json()).then(d=>{
          shareCount.textContent = Number(d.share_count).toLocaleString('id');
        });
      }catch(e){ console.error(e); }
    });
  }

  /* ---- Chat polling ---- */
  const chatBox  = document.getElementById('chatBox');
  const chatInput= document.getElementById('chatInput');
  const chatSend = document.getElementById('chatSend');
  const emptyMsg = document.getElementById('emptyChat');
  let lastId = {{ $comments->last()->id ?? 0 }};

  function appendComment(user, body){
    if(emptyMsg) emptyMsg.remove();
    const div = document.createElement('div');
    div.className = 'flex gap-1.5 animate-[fadeIn_.3s_ease]';
    div.innerHTML = `<span class="font-bold text-rose-600 shrink-0">${user}:</span><span class="text-slate-700 break-words">${body}</span>`;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  // Poll every 3 seconds
  async function pollComments(){
    try{
      const res  = await fetch(`{{ route('live.comments.poll', $live) }}?since=${lastId}`);
      const data = await res.json();
      data.forEach(c => {
        if(c.id > lastId){ lastId = c.id; appendComment(c.user, c.body); }
      });
    }catch(e){}
  }

  @if($live->status === 'live')
    setInterval(pollComments, 3000);
  @endif

  // Send comment
  async function sendComment(){
    const body = chatInput?.value?.trim();
    if(!body) return;
    chatInput.value = '';
    try{
      const res  = await fetch(chatSend.dataset.url, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'},
        body: JSON.stringify({ body }),
      });
      const data = await res.json();
      if(data.id){ lastId = Math.max(lastId, data.id); appendComment(data.user, data.body); }
    }catch(e){ console.error(e); }
  }

  chatSend?.addEventListener('click', sendComment);
  chatInput?.addEventListener('keydown', e => { if(e.key === 'Enter') sendComment(); });

  // Auto-scroll chat on load
  if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
})();
</script>
@endpush
@endsection
