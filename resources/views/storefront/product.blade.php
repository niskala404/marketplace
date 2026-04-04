@extends('layouts.market')

@section('content')
@php
  $avg = number_format($product->avgRating(), 1, ',', '.');
  $ratingCount = $product->ratingCount();
  $sold = (int)($product->sold_count ?? 0);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
  {{-- LEFT --}}
  <div class="lg:col-span-5 space-y-4">
    <x-ui.card padding="p-0" class="overflow-hidden">
      <div class="relative aspect-square bg-slate-100">
        <img id="mainImg" src="{{ $product->mainImageUrl() }}" class="w-full h-full object-cover" alt="{{ $product->name }}">

        <div class="absolute top-3 left-3 text-[11px] font-black px-2 py-1 rounded-full bg-rose-600 text-white shadow">Promo</div>
        <div class="absolute bottom-3 left-3 text-[11px] font-semibold px-2 py-1 rounded-full bg-white/90 backdrop-blur border border-slate-200">Stok {{ $product->stock }}</div>
      </div>

      @if($product->images->count() > 1)
        <div class="p-3 grid grid-cols-5 gap-2">
          @foreach($product->images->sortBy('sort_order') as $img)
            <button type="button" class="thumbBtn rounded-xl border overflow-hidden aspect-square bg-slate-100 hover:opacity-90" data-src="{{ asset('storage/'.$img->path) }}" aria-label="Pilih gambar">
              <img class="w-full h-full object-cover" src="{{ asset('storage/'.$img->path) }}" alt="">
            </button>
          @endforeach
        </div>
      @endif
    </x-ui.card>

    @if($shop)
      <x-ui.card>
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0">
              <x-ic name="store" class="w-6 h-6 text-rose-700" />
            </div>
            <div class="min-w-0">
              <div class="font-black leading-tight truncate">{{ $shop->name }}</div>
              <div class="text-xs text-slate-500">{{ $sellerRepliedRecently ? 'Aktif membalas chat' : 'Belum ada data balasan chat' }}</div>
            </div>
          </div>

          <a href="{{ route('shop.show', $shop->slug) }}" class="px-3 py-2 rounded-xl border font-bold hover:bg-slate-50 shrink-0">Kunjungi</a>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
          <div class="rounded-xl bg-slate-50 border p-3">
            <div class="text-slate-500 text-xs">Rating</div>
            <div class="font-black inline-flex items-center gap-1">
              <x-ic name="star" class="w-4 h-4 text-rose-600" />
              <span>{{ $avg }}</span>
            </div>
          </div>
          <div class="rounded-xl bg-slate-50 border p-3">
            <div class="text-slate-500 text-xs">Produk</div>
            <div class="font-black">{{ number_format($productsCount, 0, ',', '.') }}</div>
          </div>
          <div class="rounded-xl bg-slate-50 border p-3">
            <div class="text-slate-500 text-xs">Pengikut</div>
            <div class="font-black">{{ number_format($followersCount, 0, ',', '.') }}</div>
          </div>
        </div>

        <div class="mt-4 flex gap-2">
          @auth
            @if($canChat)
              <form method="POST" action="{{ route('messages.start', $shop) }}" class="flex-1">
                @csrf
                <button class="w-full px-3 py-2 rounded-xl bg-slate-900 text-white font-black hover:bg-slate-800 inline-flex items-center justify-center gap-2">
                  <x-ic name="message-circle" class="w-5 h-5" />
                  <span>Chat</span>
                </button>
              </form>
            @else
              <button class="flex-1 px-3 py-2 rounded-xl bg-slate-200 text-slate-500 font-black cursor-not-allowed inline-flex items-center justify-center gap-2" title="Chat aktif setelah kamu membeli dari toko ini">
                <x-ic name="message-circle" class="w-5 h-5" />
                <span>Chat</span>
              </button>
            @endif
          @else
            <a href="{{ route('login') }}" class="flex-1 text-center px-3 py-2 rounded-xl bg-slate-900 text-white font-black inline-flex items-center justify-center gap-2">
              <x-ic name="log-in" class="w-5 h-5" />
              <span>Login untuk Chat</span>
            </a>
          @endauth

          @auth
            @if(Route::has('shops.follow.toggle'))
              <form method="POST" action="{{ route('shops.follow.toggle', $shop) }}" class="flex-1">
                @csrf
                <button class="w-full px-3 py-2 rounded-xl border font-black hover:bg-slate-50 inline-flex items-center justify-center gap-2">
                  <x-ic name="user-plus" class="w-5 h-5 text-rose-600" />
                  <span>Follow</span>
                </button>
              </form>
            @endif
          @endauth
        </div>
      </x-ui.card>
    @endif
  </div>

  {{-- RIGHT --}}
  <div class="lg:col-span-7 space-y-4">
    <x-ui.card>
      <h1 class="text-2xl font-black leading-tight">{{ $product->name }}</h1>

      <div class="text-slate-500 text-sm mt-1">
        @if($shop)
          Toko: <a class="font-semibold hover:underline" href="{{ route('shop.show', $shop->slug) }}">{{ $shop->name }}</a>
        @endif
        @if($product->category) • Kategori: {{ $product->category->name }} @endif
      </div>

      <div class="mt-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        @php
          $orig = (int)($product->price ?? 0);
          $dp = $flashPromo !== null
            ? (int)$flashPromo
            : (method_exists($product,'discountedPrice') ? (int)$product->discountedPrice() : (int)$product->price);
        @endphp
        <div class="flex items-center gap-2 flex-wrap">
          <div class="text-3xl font-black text-rose-600">Rp {{ number_format($dp,0,',','.') }}</div>
          @if($flashPromo !== null)
            <span class="px-2 py-1 rounded-full text-xs font-black bg-rose-600 text-white">FLASH SALE</span>
            @if($flashRemaining !== null)
              <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 border">Sisa {{ $flashRemaining }}</span>
            @endif
            @if($flashEndsAt)
              <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 border" data-flash-ends="{{ $flashEndsAt->toIso8601String() }}">Berakhir: {{ $flashEndsAt->format('d M Y H:i') }}</span>
            @endif
          @endif
        </div>
        @if(($flashPromo !== null) || (method_exists($product,'hasDiscount') && $product->hasDiscount()))
          <div class="text-sm text-slate-500 line-through">Rp {{ number_format($orig,0,',','.') }}</div>
        @endif

        <div class="flex flex-wrap gap-2">
          <x-ui.badge class="bg-slate-900 text-white border-slate-900">
            <x-ic name="star" class="w-4 h-4" /> {{ $avg }} <span class="opacity-80 font-semibold">({{ $ratingCount }})</span>
          </x-ui.badge>
          <x-ui.badge>
            <x-ic name="shopping-bag" class="w-4 h-4 text-rose-600" /> Terjual {{ $sold }}
          </x-ui.badge>
          <x-ui.badge>
            <x-ic name="weight" class="w-4 h-4 text-rose-600" /> Berat {{ $product->weight_grams ?? 500 }}g
          </x-ui.badge>

          <form method="POST" action="{{ route('report.store') }}" class="inline-flex">
            @csrf
            <input type="hidden" name="type" value="product">
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="reason" value="Produk bermasalah">
            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-full border text-xs font-semibold hover:bg-slate-50" title="Laporkan">
              <x-ic name="flag" class="w-4 h-4 text-slate-500" /> Laporkan
            </button>
          </form>
        </div>
      </div>

      <div class="mt-2 text-slate-600 text-sm">Stok tersedia: <span class="font-bold" id="stockLabel">{{ $product->stock }}</span></div>

      @if($product->variants->count())
        <div class="mt-4 p-4 rounded-2xl border bg-slate-50">
          <div class="font-semibold mb-2">Pilih Varian</div>
          <div id="variantSelector" class="flex flex-wrap gap-2">
            @foreach($product->variants->where('is_active', true) as $variant)
              @php
                $variantPrice = (int)($variant->price ?? $product->price);
                $variantStock = (int)$variant->stock;
              @endphp
              <button
                type="button"
                class="variant-btn px-3 py-2 rounded-xl border bg-white text-sm"
                data-id="{{ $variant->id }}"
                data-name="{{ $variant->name }}"

                data-price="{{ $variantPrice }}"
                data-stock="{{ $variantStock }}"
              >
                {{ $variant->name }}
              </button>
            @endforeach
          </div>
          <div id="variantInfo" class="mt-2 text-sm text-slate-600">Silakan pilih varian sebelum checkout.</div>
        </div>
      @endif

      <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
        @auth
          <form method="POST" action="{{ route('cart.add',$product->id) }}">
            @csrf
            <input type="hidden" name="qty" value="1">
            <input type="hidden" name="product_variant_id" class="variantInput">

            <button class="w-full px-4 py-3 rounded-2xl border font-black hover:bg-slate-50 inline-flex items-center justify-center gap-2">
              <x-ic name="shopping-cart" class="w-5 h-5 text-rose-600" />
              <span>Masukkan Keranjang</span>
            </button>
          </form>
          <form method="POST" action="{{ route('cart.add',$product->id) }}">
            @csrf
            <input type="hidden" name="qty" value="1">
            <input type="hidden" name="buy_now" value="1">
            <input type="hidden" name="product_variant_id" class="variantInput">

            <button class="w-full px-4 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700 shadow-sm inline-flex items-center justify-center gap-2">
              <x-ic name="zap" class="w-5 h-5" />
              <span>Beli Sekarang</span>
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="block text-center w-full px-4 py-3 rounded-2xl bg-slate-900 text-white font-black inline-flex items-center justify-center gap-2">
            <x-ic name="log-in" class="w-5 h-5" />
            <span>Login untuk Membeli</span>
          </a>
        @endauth
      </div>

      <div class="mt-6">
        <div class="font-black mb-2">Deskripsi</div>
        <div class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $product->description }}</div>
      </div>
    </x-ui.card>

    @if($shop && $otherFromSeller->count() > 0)
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div class="font-black text-lg">Lainnya dari {{ $shop->name }}</div>
          <a href="{{ route('shop.show', $shop->slug) }}" class="text-sm text-rose-600 font-bold hover:underline">Lihat semua</a>
        </div>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
          @foreach($otherFromSeller as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group bg-white border rounded-2xl overflow-hidden hover:shadow-md transition hover:-translate-y-0.5">
              <div class="aspect-square bg-slate-100"><img src="{{ $p->mainImageUrl() }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" alt="{{ $p->name }}"></div>
              <div class="p-3">
                <div class="font-semibold line-clamp-2 min-h-[3rem]">{{ $p->name }}</div>
                @php $dp = method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price; @endphp
                <div class="mt-2 font-black text-rose-600">Rp {{ number_format($dp,0,',','.') }}</div>
                @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
                  <div class="text-xs text-slate-500 line-through">Rp {{ number_format((int)$p->price,0,',','.') }}</div>
                @endif
                <div class="text-xs text-slate-500 mt-1">Terjual {{ (int)($p->sold_count ?? 0) }} • ⭐ {{ number_format((float)($p->reviews_avg_rating ?? 0), 1, ',', '.') }}</div>
              </div>
            </a>
          @endforeach
        </div>
      </x-ui.card>
    @endif

    @if($similar->count() > 0)
      <x-ui.card>
        <div class="font-black text-lg">Produk serupa</div>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
          @foreach($similar as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group bg-white border rounded-2xl overflow-hidden hover:shadow-md transition hover:-translate-y-0.5">
              <div class="aspect-square bg-slate-100"><img src="{{ $p->mainImageUrl() }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" alt="{{ $p->name }}"></div>
              <div class="p-3">
                <div class="font-semibold line-clamp-2 min-h-[3rem]">{{ $p->name }}</div>
                @php $dp = method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price; @endphp
                <div class="mt-2 font-black text-rose-600">Rp {{ number_format($dp,0,',','.') }}</div>
                @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
                  <div class="text-xs text-slate-500 line-through">Rp {{ number_format((int)$p->price,0,',','.') }}</div>
                @endif
                <div class="text-xs text-slate-500 mt-1">Terjual {{ (int)($p->sold_count ?? 0) }} • ⭐ {{ number_format((float)($p->reviews_avg_rating ?? 0), 1, ',', '.') }}</div>
              </div>
            </a>
          @endforeach
        </div>
      </x-ui.card>
    @endif
  </div>
</div>

<x-ui.card class="mt-6">
  <div class="flex items-center justify-between">
    <div class="font-black text-lg">Ulasan Pembeli</div>
    <div class="text-sm text-slate-500">Yang tampil hanya dari pesanan selesai</div>
  </div>

  {{-- Rating breakdown --}}
  <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="rounded-2xl border bg-slate-50 p-4">
      <div class="text-3xl font-black text-slate-900">{{ number_format((float)($ratingAvg ?? 0), 1, ',', '.') }}</div>
      <div class="mt-1 text-sm text-slate-600">dari {{ (int)($ratingTotal ?? 0) }} ulasan</div>
      <div class="mt-2 inline-flex items-center gap-1 text-amber-600">
        <x-ic name="star" class="w-5 h-5" />
        <span class="text-sm font-semibold">Rating</span>
      </div>
    </div>

    <div class="md:col-span-2 rounded-2xl border p-4">
      <div class="text-sm font-bold">Rincian rating</div>
      <div class="mt-3 space-y-2">
        @for($i=5;$i>=1;$i--)
          @php($row = $ratingBreakdown[$i] ?? ['count'=>0,'percent'=>0])
          <div class="flex items-center gap-3">
            <div class="w-10 text-sm font-semibold">{{ $i }}★</div>
            <div class="flex-1 h-2 rounded-full bg-slate-100 overflow-hidden">
              <div class="h-full bg-rose-600" style="width: {{ (int)$row['percent'] }}%"></div>
            </div>
            <div class="w-14 text-right text-sm text-slate-600">{{ (int)$row['count'] }}</div>
          </div>
        @endfor
      </div>
    </div>
  </div>

  @if($reviews->count() === 0)
    <div class="mt-4 text-slate-600">Belum ada ulasan.</div>
  @else
    <div class="mt-4 space-y-4">
      @foreach($reviews as $rv)
        <div class="border rounded-2xl p-4">
          <div class="flex items-center justify-between">
            <div class="font-semibold">{{ $rv->user->name }}</div>
            <div class="text-sm font-bold inline-flex items-center gap-1"><x-ic name="star" class="w-4 h-4 text-rose-600" /> {{ $rv->rating }} / 5</div>
          </div>
          @if($rv->comment)
            <div class="mt-2 text-slate-700 whitespace-pre-line">{{ $rv->comment }}</div>
          @endif
          <div class="mt-2 text-xs text-slate-500">{{ $rv->created_at->format('d M Y H:i') }}</div>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $reviews->links() }}</div>
  @endif
</x-ui.card>

<script>
(function(){
  const buttons = document.querySelectorAll('.variant-btn');
  if(!buttons.length) return;

  const priceEl = document.querySelector('.text-3xl.font-black.text-rose-600');
  const stockLabel = document.getElementById('stockLabel');
  const info = document.getElementById('variantInfo');
  const variantInputs = document.querySelectorAll('.variantInput');

  const forms = document.querySelectorAll('form[action*="/cart/add/"]');
  let selected = null;

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      buttons.forEach(b => b.classList.remove('bg-rose-600','text-white','border-rose-600'));
      btn.classList.add('bg-rose-600','text-white','border-rose-600');
      selected = btn.dataset.id;
      variantInputs.forEach(i => i.value = selected);

      if(info) info.textContent = `Varian ${btn.dataset.name} dipilih • stok ${btn.dataset.stock}`;
      if(stockLabel) stockLabel.textContent = btn.dataset.stock;
      if(priceEl) priceEl.textContent = `Rp ${Number(btn.dataset.price).toLocaleString('id-ID')}`;
    });
  });

  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!selected) {
        e.preventDefault();
        alert('Silakan pilih varian produk terlebih dahulu.');
      }
    });
  });
})();
</script>

<script>
(function(){
  const main = document.getElementById('mainImg');
  if(!main) return;
  document.querySelectorAll('.thumbBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      const src = btn.getAttribute('data-src');
      if(src) main.src = src;
    });
  });
})();
</script>

<script>
(function(){
  const el = document.querySelector('[data-flash-ends]');
  if(!el) return;
  const ends = new Date(el.getAttribute('data-flash-ends')).getTime();
  function tick(){
    const now = Date.now();
    let diff = Math.max(0, ends - now);
    const h = Math.floor(diff / 3600000); diff%=3600000;
    const m = Math.floor(diff / 60000); diff%=60000;
    const s = Math.floor(diff / 1000);
    el.textContent = 'Berakhir dalam: ' + String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    if(ends - now <= 0) clearInterval(timer);
  }
  tick();
  const timer = setInterval(tick, 1000);
})();
</script>
@endsection
