@extends('layouts.market')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 bg-white border rounded-2xl p-5">
    <h1 class="text-2xl font-black">{{ $live->title }}</h1>
    <div class="text-sm text-slate-500">{{ $live->shop->name }} • {{ strtoupper($live->status) }}</div>
    <div class="mt-4 aspect-video rounded-2xl overflow-hidden bg-black">
      @if($live->stream_url)
        <iframe src="{{ $live->stream_url }}" class="w-full h-full" allowfullscreen></iframe>
      @else
        <div class="w-full h-full flex items-center justify-center text-white">Video stream belum diatur</div>
      @endif
    </div>
    @if($live->description)
      <div class="mt-4 text-slate-700 whitespace-pre-line">{{ $live->description }}</div>
    @endif
  </div>

  <div class="bg-white border rounded-2xl p-5">
    <div class="font-bold mb-3">Produk Saat Live</div>
    <div class="space-y-3">
      @forelse($live->products as $p)
        <a href="{{ route('product.show', $p->slug) }}" class="flex items-center gap-3 border rounded-xl p-2 hover:bg-slate-50">
          <img src="{{ $p->mainImageUrl() }}" class="w-14 h-14 rounded-lg object-cover" alt="{{ $p->name }}">
          <div>
            <div class="text-sm font-semibold line-clamp-2">{{ $p->name }}</div>
            <div class="text-rose-600 font-bold">Rp {{ number_format($p->price,0,',','.') }}</div>
            @auth
              @if($p->variants->where('is_active', true)->isNotEmpty())
                <a href="{{ route('product.show', $p->slug) }}" class="inline-block mt-1 text-xs px-2 py-1 rounded-full bg-slate-900 text-white">Pilih Varian</a>
              @else
                <form method="POST" action="{{ route('cart.add', $p->id) }}" class="mt-1 js-live-add-cart">
                  @csrf
                  <input type="hidden" name="qty" value="1">
                  <button class="text-xs px-2 py-1 rounded-full bg-rose-600 text-white">+ Keranjang</button>
                </form>
              @endif
            @else
              <span class="inline-block mt-1 text-xs px-2 py-1 rounded-full bg-rose-600 text-white">Beli Sekarang</span>
            @endauth
          </div>
        </a>
      @empty
        <div class="text-sm text-slate-500">Belum ada produk dipasangkan ke live stream ini.</div>
      @endforelse
    </div>
  </div>
</div>
@auth
  <div class="fixed bottom-4 right-4 z-40">
    <div class="flex flex-col gap-2">
      @if(Route::has('messages.start'))
        <form method="POST" action="{{ route('messages.start', $live->shop) }}">
          @csrf
          <button class="px-4 py-3 rounded-2xl bg-white border text-slate-900 font-bold shadow-lg w-full">Chat Penjual</button>
        </form>
      @endif
      <a href="{{ route('cart.index') }}" class="px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold shadow-lg text-center">Lihat Keranjang</a>
    </div>
  </div>
@endauth

<script>
(function(){
  const token = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content');
  document.querySelectorAll('.js-live-add-cart').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type=\"submit\"]');
      btn?.setAttribute('disabled', 'disabled');
      try{
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            ...(token ? {'X-CSRF-TOKEN': token} : {}),
          },
          body: new FormData(form),
        });
        const json = await res.json().catch(() => ({}));
        if(!res.ok){
          alert(json.message || 'Gagal menambahkan produk ke keranjang.');
          return;
        }
      }finally{
        btn?.removeAttribute('disabled');
      }
    });
  });
})();
</script>
@endsection
