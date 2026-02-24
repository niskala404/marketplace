@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Detail Produk</h1>
        <p class="text-slate-600 text-sm">Moderasi: <span class="font-semibold">{{ $product->approval_status }}</span></p>
    </div>
    <a href="{{ route('admin.products.moderation.index', ['status' => $product->approval_status]) }}" class="px-4 py-2 rounded-xl border">← Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 bg-white border rounded-2xl p-4">
        <div class="flex items-start gap-4">
            <img src="{{ $product->mainImageUrl() }}" class="w-28 h-28 rounded-xl object-cover border" />
            <div>
                <div class="text-xl font-black">{{ $product->name }}</div>
                <div class="text-slate-600">Rp {{ number_format($product->price,0,',','.') }} • stok {{ $product->stock }} • {{ $product->weight_grams }} gr</div>
                <div class="text-sm text-slate-500 mt-1">Kategori: {{ $product->category?->name ?? '-' }}</div>
                <div class="text-sm text-slate-500">Toko: {{ $product->shop?->name ?? '-' }} ({{ $product->shop?->user?->email ?? '' }})</div>
            </div>
        </div>

        <div class="mt-4">
            <div class="font-bold mb-1">Deskripsi</div>
            <div class="prose max-w-none">{!! nl2br(e($product->description)) !!}</div>
        </div>

        @if($product->images && $product->images->count() > 1)
            <div class="mt-4">
                <div class="font-bold mb-2">Gambar</div>
                <div class="flex gap-2 flex-wrap">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img->path) }}" class="w-20 h-20 rounded-xl object-cover border" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <div class="font-black mb-2">Aksi Moderasi</div>

        @if($product->approval_status !== 'approved')
            <form method="POST" action="{{ route('admin.products.moderation.approve', $product) }}">
                @csrf
                <button class="w-full px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Approve</button>
            </form>
        @endif

        <div class="my-3 border-t"></div>

        <form method="POST" action="{{ route('admin.products.moderation.reject', $product) }}" class="space-y-2">
            @csrf
            <label class="text-sm text-slate-700">Alasan penolakan</label>
            <textarea name="reason" rows="4" class="w-full border rounded-xl p-2" placeholder="Contoh: Deskripsi tidak jelas / gambar tidak sesuai">{{ old('reason', $product->rejected_reason) }}</textarea>
            <button class="w-full px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Reject</button>
        </form>
    </div>
</div>
@endsection
