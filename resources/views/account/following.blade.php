@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Toko yang Diikuti</h1>

@if($shops->count() === 0)
    <div class="bg-white border rounded-2xl p-6 text-slate-600">Kamu belum mengikuti toko. Coba jelajahi produk lalu buka halaman toko.</div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($shops as $s)
            <a href="{{ route('shop.show',$s->slug) }}" class="bg-white border rounded-2xl p-4 hover:shadow-sm">
                <div class="font-black text-lg">{{ $s->name }}</div>
                <div class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $s->description }}</div>
                <div class="mt-3 text-rose-600 font-bold">Lihat Toko</div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $shops->links() }}</div>
@endif
@endsection
