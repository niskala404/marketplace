@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div>
    <h1 class="text-2xl font-black">{{ $live->title }}</h1>
    <div class="text-sm text-slate-500">Status: {{ strtoupper($live->status === 'scheduled' ? 'draft' : $live->status) }} • Viewers: {{ number_format($live->viewer_count ?? 0,0,',','.') }}</div>
  </div>
  <div class="flex gap-2">
    <form method="POST" action="{{ route('seller.live.status', $live) }}">
      @csrf
      <input type="hidden" name="status" value="live">
      <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white">Start Live</button>
    </form>
    <form method="POST" action="{{ route('seller.live.status', $live) }}">
      @csrf
      <input type="hidden" name="status" value="ended">
      <button class="px-4 py-2 rounded-xl bg-rose-600 text-white">Stop Live</button>
    </form>
    <a href="{{ route('seller.live.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
  </div>
</div>

<div class="mb-4 bg-white border rounded-2xl p-4">
  <div class="font-bold">Camera Based Live Control</div>
  <div class="text-xs text-slate-500 mt-1">Gunakan kamera perangkat untuk preview sebelum live. Endpoint start/stop live dipanggil via AJAX.</div>
  <div class="mt-3 flex flex-wrap gap-2">
    <button id="btnCameraPreview" type="button" class="px-4 py-2 rounded-xl border font-semibold">Aktifkan Kamera</button>
    <button id="btnApiStartLive" type="button" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold">Start via API</button>
    <button id="btnApiStopLive" type="button" class="px-4 py-2 rounded-xl bg-rose-600 text-white font-semibold">Stop via API</button>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 bg-white border rounded-2xl p-5">
    <div class="aspect-video rounded-2xl overflow-hidden bg-black">
      <video id="sellerCameraPreview" class="w-full h-full object-cover hidden" autoplay muted playsinline></video>
      <div id="cameraPlaceholder" class="w-full h-full flex items-center justify-center text-white">
        @if($live->stream_url)
          <iframe src="{{ $live->stream_url }}" class="w-full h-full" allowfullscreen></iframe>
        @else
          Belum ada URL stream
        @endif
      </div>
    </div>
    <div class="mt-4 text-slate-700 whitespace-pre-line">{{ $live->description }}</div>
  </div>

  <div class="bg-white border rounded-2xl p-5">
    <div class="font-bold mb-2">Produk yang ditampilkan</div>
    <div class="space-y-2">
      @forelse($live->products as $p)
        <div class="text-sm border rounded-xl p-2">{{ $p->name }}</div>
      @empty
        <div class="text-sm text-slate-500">Belum ada produk.</div>
      @endforelse
    </div>
  </div>
</div>

<script>
(function(){
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const liveId = @json($live->id);
  const preview = document.getElementById('sellerCameraPreview');
  const placeholder = document.getElementById('cameraPlaceholder');

  async function postJson(url, payload){
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...(csrf ? {'X-CSRF-TOKEN': csrf} : {}),
      },
      body: JSON.stringify(payload),
    });
    const json = await res.json().catch(() => ({}));
    if(!res.ok) throw new Error(json.message || 'Request gagal');
    return json;
  }

  document.getElementById('btnCameraPreview')?.addEventListener('click', async () => {
    if(!navigator.mediaDevices?.getUserMedia){
      alert('Browser tidak mendukung akses kamera.');
      return;
    }
    try{
      const stream = await navigator.mediaDevices.getUserMedia({video:true, audio:true});
      preview.srcObject = stream;
      preview.classList.remove('hidden');
      placeholder?.classList.add('hidden');
    }catch(e){
      alert('Tidak bisa mengakses kamera.');
    }
  });

  document.getElementById('btnApiStartLive')?.addEventListener('click', async () => {
    try{
      await postJson('{{ route('live.start') }}', {live_id: liveId});
      location.reload();
    }catch(e){ alert(e.message); }
  });

  document.getElementById('btnApiStopLive')?.addEventListener('click', async () => {
    try{
      await postJson('{{ route('live.stop') }}', {live_id: liveId});
      location.reload();
    }catch(e){ alert(e.message); }
  });
})();
</script>
@endsection
