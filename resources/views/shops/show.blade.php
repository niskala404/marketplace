@extends('layouts.market')

@section('content')
<div class="max-w-6xl mx-auto">

  <div class="rounded-3xl overflow-hidden border bg-white mb-6 shadow-sm">
    <div class="h-24 sm:h-32 bg-rose-600"></div>

    <div class="p-5 -mt-10">
      <div class="bg-white border rounded-3xl p-5">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
          <div class="flex items-start gap-4 min-w-0">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0">
              <x-ic name="store" class="w-7 h-7 text-rose-700" />
            </div>

            <div class="min-w-0">
              <h1 class="text-2xl font-black truncate">{{ $shop->name }}</h1>
              <div class="mt-1 flex flex-wrap items-center gap-2 text-sm">
                <span class="px-3 py-1 rounded-full bg-slate-100 border text-slate-700 font-semibold">{{ number_format($followersCount,0,',','.') }} pengikut</span>
                <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold">Aktif</span>
              </div>

              @if($shop->description)
                <div class="text-slate-700 mt-3 whitespace-pre-line leading-relaxed">{{ $shop->description }}</div>
              @endif
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            @auth
              <form method="POST" action="{{ route('shops.follow.toggle', $shop) }}">
                @csrf
                <button class="px-4 py-3 rounded-2xl font-black shadow-sm active:scale-[0.99] transition {{ $isFollowing ? 'bg-slate-900 text-white hover:bg-slate-800' : 'bg-rose-600 text-white hover:bg-rose-700' }}">
                  <span class="inline-flex items-center gap-2">
                    @if($isFollowing)
                      <x-ic name="check" class="w-5 h-5" />
                      Mengikuti
                    @else
                      <x-ic name="user-plus" class="w-5 h-5" />
                      Ikuti
                    @endif
                  </span>
                </button>
              </form>

              <form method="POST" action="{{ route('messages.start', $shop) }}">
                @csrf
                <input type="hidden" name="body" value="Halo, saya ingin bertanya tentang produk di toko ini.">
                <button class="px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 font-black active:scale-[0.99] transition inline-flex items-center gap-2">
                  <x-ic name="message-circle" class="w-5 h-5" />
                  Chat
                </button>
              </form>
            @else
              <a href="{{ route('login') }}" class="px-4 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700 text-center">Login untuk Ikuti/Chat</a>
            @endauth

            <form method="POST" action="{{ route('report.store') }}">
              @csrf
              <input type="hidden" name="type" value="shop">
              <input type="hidden" name="id" value="{{ $shop->id }}">
              <input type="hidden" name="reason" value="Toko bermasalah">
              <button type="submit" class="px-4 py-2 rounded-xl border font-bold hover:bg-slate-50">Laporkan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="flex items-end justify-between mb-4">
    <div>
      <h2 class="text-xl font-black">Produk Toko</h2>
      <div class="text-slate-500 text-sm">Produk aktif terbaru dari toko ini</div>
    </div>
  </div>

  @if($products->count() === 0)
    <x-ui.empty title="Belum ada produk" />
  @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($products as $p)
        <a href="{{ route('product.show',$p->slug) }}" class="group bg-white border rounded-2xl overflow-hidden hover:shadow-md transition hover:-translate-y-0.5">
          <div class="relative aspect-square bg-slate-100 overflow-hidden">
            <img src="{{ $p->mainImageUrl() }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" alt="{{ $p->name }}">
            <div class="absolute top-2 left-2 text-[11px] font-black px-2 py-1 rounded-full bg-rose-600 text-white shadow">Promo</div>
            <div class="absolute bottom-2 left-2 text-[11px] font-semibold px-2 py-1 rounded-full bg-white/90 backdrop-blur border border-slate-200">Stok {{ $p->stock }}</div>
          </div>
          <div class="p-3">
            <div class="font-semibold line-clamp-2 min-h-[3rem]">{{ $p->name }}</div>
            <div class="mt-2 font-black text-rose-600">Rp {{ number_format($p->price,0,',','.') }}</div>
            <div class="text-xs text-slate-500 mt-1">Terjual {{ (int)($p->sold_count ?? 0) }}</div>
          </div>
        </a>
      @endforeach
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
  @endif

</div>
@endsection
