@props(['p'])

<div class="group bg-white border rounded-2xl overflow-hidden hover:shadow-md transition hover:-translate-y-0.5" data-product-card>
  <a href="{{ route('product.show',$p->slug) }}" class="block">
    <div class="relative aspect-square bg-slate-100 overflow-hidden">
      {{-- skeleton --}}
      <div class="absolute inset-0 animate-pulse bg-slate-200" data-skel></div>
      <img
        src="{{ $p->mainImageUrl() }}"
        class="w-full h-full object-cover opacity-0 transition duration-300 group-hover:scale-[1.03]"
        alt="{{ $p->name }}"
        loading="lazy"
        data-skel-img
      >
@php
  $flashPriceMap = $flashPriceMap ?? [];
  $flashPromo = $flashPriceMap[$p->id] ?? null;

  $basePrice = method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price;
  $shownPrice = $flashPromo ?? $basePrice;

  $showStrikethrough = $flashPromo !== null || (method_exists($p,'hasDiscount') && $p->hasDiscount());
@endphp

<div class="font-black text-rose-600">Rp {{ number_format($shownPrice,0,',','.') }}</div>

@if($showStrikethrough)
  <div class="text-xs text-slate-500 line-through">
    Rp {{ number_format($flashPromo !== null ? $basePrice : (int)$p->price,0,',','.') }}
  </div>
@endif

@if($flashPromo !== null)
  <div class="mt-1 inline-flex text-[10px] bg-rose-600 text-white px-2 py-0.5 rounded-full font-black">
    FLASH
  </div>
@endif
      <div class="absolute bottom-2 left-2 flex items-center gap-2">
        <div class="text-[11px] font-semibold px-2 py-1 rounded-full bg-white/90 backdrop-blur border border-slate-200">Stok {{ $p->stock }}</div>
        @if((int)($p->sold_count ?? 0) > 0)
          <div class="text-[11px] font-black px-2 py-1 rounded-full bg-rose-600 text-white shadow">Terjual {{ (int)$p->sold_count }}</div>
        @endif
      </div>
    </div>

    <div class="p-3">
      <div class="font-semibold line-clamp-2 min-h-[3rem]">{{ $p->name }}</div>

      <div class="mt-2 flex items-end justify-between gap-2">
      @php $dp = method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price; @endphp
      <div class="font-black text-rose-600">Rp {{ number_format($dp,0,',','.') }}</div>
      @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
        <div class="text-xs text-slate-500 line-through">Rp {{ number_format((int)$p->price,0,',','.') }}</div>
      @endif
        <div class="text-xs text-slate-500 flex items-center gap-1">
          <x-ic name="star" class="w-4 h-4 text-rose-600" />
          @php($avg = (float)($p->reviews_avg_rating ?? 0))
          <span>{{ $avg > 0 ? number_format($avg, 1) : '0.0' }}</span>
          <span class="text-slate-400">({{ (int)($p->reviews_count ?? 0) }})</span>
        </div>
      </div>

      <div class="mt-2 text-xs text-slate-500 flex items-center justify-between gap-2">
        <div class="truncate">
          @if($p->shop)
            <span class="inline-flex items-center gap-1">
              <x-ic name="store" class="w-4 h-4 text-slate-400" />
              <span class="truncate">{{ $p->shop->name }}</span>
            </span>
          @else
            <span>-</span>
          @endif
        </div>
        <span class="text-rose-700 font-semibold group-hover:underline">Lihat</span>
      </div>
    </div>
  </a>

  {{-- Quick actions --}}
  <div class="px-3 pb-3">
    <div class="flex items-center gap-2">
      @auth
        <form action="{{ route('cart.add', $p->id) }}" method="POST" class="flex-1 js-quick-add">
          @csrf
          <input type="hidden" name="qty" value="1">
          <button
            type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800"
          >
            <x-ic name="shopping-cart" class="w-5 h-5 text-white" />
            <span class="text-sm">Tambah</span>
          </button>
        </form>

        <form action="{{ route('cart.add', $p->id) }}" method="POST" class="js-buy-now">
          @csrf
          <input type="hidden" name="qty" value="1">
          <input type="hidden" name="buy_now" value="1">
          <button
            type="submit"
            class="inline-flex items-center justify-center w-11 h-11 rounded-xl border font-black hover:bg-slate-50"
            title="Beli Sekarang"
          >
            <x-ic name="zap" class="w-5 h-5 text-slate-700" />
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">
          <x-ic name="shopping-cart" class="w-5 h-5 text-white" />
          <span class="text-sm">Login untuk beli</span>
        </a>
      @endauth
    </div>
  </div>
</div>
