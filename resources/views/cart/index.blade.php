@extends('layouts.market')

@section('content')
<x-app.page title="Keranjang" subtitle="Cek barang sebelum checkout">

  @if($items->isEmpty())
    <x-ui.empty title="Keranjang kamu kosong" message="Yuk cari produk menarik dulu.">
      <x-slot:action>
        <a href="{{ route('home') }}" class="inline-flex px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Mulai Belanja</a>
      </x-slot:action>
    </x-ui.empty>
  @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-3">
        @foreach($items as $it)
          @php
            $p = $it->product;
            $orig = (int)($p->price ?? 0);
            $flashPriceMap = $flashPriceMap ?? [];
            $flashPromo = $flashPriceMap[$p->id] ?? null;
            $price = $flashPromo !== null
                ? (int)$flashPromo
                : (method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price);
            $line = $price * (int)$it->qty;
          @endphp

          <x-ui.card>
            <div class="flex gap-4">
              <img class="w-24 h-24 rounded-2xl object-cover border bg-slate-100" src="{{ $p->mainImageUrl() }}" alt="{{ $p->name }}">

              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <div class="font-extrabold line-clamp-2">{{ $p->name }}</div>
                    <div class="text-sm text-slate-500 mt-0.5 inline-flex items-center gap-1">
                      <x-ic name="store" class="w-4 h-4 text-slate-400" />
                      <span class="truncate">{{ $p->shop->name ?? '-' }}</span>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('cart.remove',$it->id) }}">
                    @csrf
                    <button class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700" title="Hapus" aria-label="Hapus">
                      <x-ic name="trash-2" class="w-5 h-5" />
                    </button>
                  </form>
                </div>

                <div class="mt-3 flex items-end justify-between gap-3">
                  <div>
                    <div class="flex items-center gap-2">
                      <div class="text-rose-600 font-black text-lg">Rp {{ number_format($price,0,',','.') }}</div>
                      @if($flashPromo !== null)
                        <span class="px-2 py-0.5 rounded-full text-xs font-black bg-rose-600 text-white">FLASH SALE</span>
                      @endif
                    </div>
                    @if(method_exists($p,'hasDiscount') && $p->hasDiscount())
                      <div class="text-xs text-slate-500 line-through">Rp {{ number_format($orig,0,',','.') }}</div>
                    @endif
                    <div class="text-xs text-slate-500 mt-0.5">Subtotal: <span class="font-semibold">Rp {{ number_format($line,0,',','.') }}</span></div>
                  </div>

                  <form method="POST" action="{{ route('cart.update',$it->id) }}" class="flex items-center gap-2 qtyForm">
                    @csrf
                    <button type="button" class="w-10 h-10 rounded-xl border bg-white hover:bg-slate-50 font-black qtyMinus" aria-label="Kurangi">−</button>
                    <input name="qty" type="number" min="1" value="{{ $it->qty }}" class="w-16 text-center rounded-xl border-slate-200 bg-slate-50 qtyInput">
                    <button type="button" class="w-10 h-10 rounded-xl border bg-white hover:bg-slate-50 font-black qtyPlus" aria-label="Tambah">+</button>
                  </form>
                </div>
              </div>
            </div>
          </x-ui.card>
        @endforeach
      </div>

      <div class="hidden lg:block">
        <x-ui.card class="sticky top-24" padding="p-5">
          <div class="font-black text-lg">Ringkasan Belanja</div>
          <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-slate-600">Subtotal</span>
              <span class="font-bold">Rp {{ number_format($subtotal,0,',','.') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-600">Ongkir</span>
              <span class="text-slate-500">Dihitung saat checkout</span>
            </div>
            <div class="border-t pt-3 flex justify-between">
              <span class="font-black">Total</span>
              <span class="font-black text-rose-600">Rp {{ number_format($subtotal,0,',','.') }}</span>
            </div>
          </div>

          <a href="{{ route('checkout.show') }}" class="mt-4 block text-center px-4 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700">
            Checkout
          </a>

          <div class="text-xs text-slate-500 mt-3">*S&K berlaku</div>
        </x-ui.card>
      </div>
    </div>

    {{-- Mobile sticky bar --}}
    <div class="fixed bottom-0 left-0 right-0 z-30 lg:hidden">
      <div class="max-w-6xl mx-auto px-4 pb-3">
        <div class="bg-white border rounded-2xl shadow-lg p-3 flex items-center gap-3">
          <div class="flex-1">
            <div class="text-xs text-slate-500">Total</div>
            <div class="font-black text-rose-600">Rp {{ number_format($subtotal,0,',','.') }}</div>
          </div>
          <a href="{{ route('checkout.show') }}" class="px-5 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700">Checkout</a>
        </div>
      </div>
    </div>
    <div class="h-24 lg:hidden"></div>

    <script>
    (function(){
      function clamp(v){
        v = parseInt(v || '1', 10);
        if(isNaN(v) || v < 1) v = 1;
        return v;
      }
      document.querySelectorAll('.qtyForm').forEach(form => {
        const input = form.querySelector('.qtyInput');
        const minus = form.querySelector('.qtyMinus');
        const plus  = form.querySelector('.qtyPlus');

        minus.addEventListener('click', () => {
          input.value = Math.max(1, clamp(input.value) - 1);
          form.submit();
        });
        plus.addEventListener('click', () => {
          input.value = clamp(input.value) + 1;
          form.submit();
        });
        input.addEventListener('change', () => {
          input.value = clamp(input.value);
          form.submit();
        });
      });
    })();
    </script>
  @endif

</x-app.page>
@endsection
