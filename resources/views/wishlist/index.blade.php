@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Wishlist</h1>

@if($products->isEmpty())
    <div class="bg-white border rounded-2xl p-6 text-slate-600">
        Wishlist kamu masih kosong. Yuk cari produk di <a class="text-rose-600 font-semibold" href="{{ route('home') }}">beranda</a>.
    </div>
@else
    <div class="mb-4 flex justify-end">
        <form method="POST" action="{{ route('wishlist.move_all') }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">
                Pindahkan semua ke keranjang
            </button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($products as $p)
            <div class="bg-white border rounded-2xl overflow-hidden">
                <a href="{{ route('product.show',$p->slug) }}">
                    <div class="aspect-square bg-slate-100">
                        <img src="{{ $p->mainImageUrl() }}" class="w-full h-full object-cover" alt="{{ $p->name }}">
                    </div>
                </a>
                <div class="p-3">
                    <div class="font-semibold line-clamp-2 min-h-[3rem]">{{ $p->name }}</div>
                    <div class="mt-2 font-black text-rose-600">Rp {{ number_format($p->price,0,',','.') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $p->shop->name }}</div>

                    <div class="mt-3 flex gap-2">
                        <form method="POST" action="{{ route('wishlist.toggle',$p) }}" class="flex-1">
                            @csrf
                            <button class="w-full px-3 py-2 rounded-xl bg-rose-600 text-white font-bold">Hapus</button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.move_to_cart',$p) }}" class="flex-1">
                            @csrf
                            <button class="w-full px-3 py-2 rounded-xl bg-slate-900 text-white font-bold">Pindah ke Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endif
@endsection
