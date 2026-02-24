@props(['p'])

<div class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-sm transition" data-product-card>
  <div class="relative">
    <a href="{{ route('product.show',$p->slug) }}" class="block">
      <div class="relative aspect-square bg-slate-100 overflow-hidden">
        {{-- skeleton --}}
        <div class="absolute inset-0 animate-pulse bg-slate-200" data-skel></div>
        <img
          src="{{ $p->mainImageUrl() }}"
          class="w-full h-full object-cover opacity-0 transition duration-300"
          alt="{{ $p->name }}"
          loading="lazy"
          data-skel-img
        >

        {{-- Badges (compact) --}}
        <div class="absolute top-1 left-1 flex flex-col gap-1">
          @if($p->shop && ($p->shop->is_official ?? false))
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded bg-slate-900 text-white">Official</span>
          @endif
          @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded bg-emerald-600 text-white">Diskon</span>
          @endif
        </div>

        {{-- Sold chip --}}
        @if((int)($p->sold_count ?? 0) > 0)
          <div class="absolute bottom-1 left-1 text-[10px] font-black px-1.5 py-0.5 rounded bg-rose-600 text-white">
            Terjual {{ (int)$p->sold_count }}
          </div>
        @endif
      </div>
    </a>

    {{-- Quick add (icon only, keeps JS hook) --}}
    @auth
      <form action="{{ route('cart.add', $p->id) }}" method="POST" class="absolute top-1 right-1 js-quick-add">
        @csrf
        <input type="hidden" name="qty" value="1">
        <button
          type="submit"
          class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-slate-200 hover:bg-slate-50"
          title="Tambah ke keranjang"
        >
          <x-ic name="shopping-cart" class="w-4 h-4 text-slate-800" />
        </button>
      </form>
    @else
      <a
        href="{{ route('login') }}"
        class="absolute top-1 right-1 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-slate-200 hover:bg-slate-50"
        title="Login untuk beli"
      >
        <x-ic name="shopping-cart" class="w-4 h-4 text-slate-800" />
      </a>
    @endauth
  </div>

  <a href="{{ route('product.show',$p->slug) }}" class="block p-2">
    <div class="text-[12px] leading-snug line-clamp-2 min-h-[32px] text-slate-800">{{ $p->name }}</div>

    <div class="mt-1 flex items-baseline gap-1">
      @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
        <div class="font-extrabold text-[13px] text-slate-900">Rp {{ number_format($p->discountedPrice(),0,',','.') }}</div>
        <div class="text-[10px] text-slate-400 line-through">Rp {{ number_format($p->price,0,',','.') }}</div>
      @else
        <div class="font-extrabold text-[13px] text-slate-900">Rp {{ number_format($p->price,0,',','.') }}</div>
      @endif
    </div>

    <div class="mt-1 flex items-center justify-between gap-2 text-[11px] text-slate-500">
      <div class="flex items-center gap-1 min-w-0">
        <span class="text-rose-600">★</span>
        @php($avg = (float)($p->reviews_avg_rating ?? 0))
        <span>{{ $avg > 0 ? number_format($avg, 1) : '0.0' }}</span>
        <span class="text-slate-300">•</span>
        <span class="truncate">{{ (int)($p->reviews_count ?? 0) }} ulasan</span>
      </div>
      <div class="truncate max-w-[92px]">
        {{ $p->shop?->name ?? '-' }}
      </div>
    </div>
  </a>
</div>
