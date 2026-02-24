@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Varian Produk</h1>
        <div class="text-slate-600">{{ $product->name }}</div>
        <div class="text-xs text-slate-500 mt-1">Catatan: Mengubah varian akan membuat produk kembali <b>pending</b> (butuh approve admin) agar aman seperti marketplace besar.</div>
    </div>
    <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="{{ route('seller.products.index') }}">← Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border rounded-2xl p-5 h-fit">
        <div class="font-bold text-lg mb-3">Tambah Varian</div>
        <form method="POST" action="{{ route('seller.products.variants.store', $product) }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-semibold">Nama varian</label>
                <input name="name" class="w-full rounded-xl border-slate-200" placeholder="Merah / M" required>
            </div>
            <div>
                <label class="text-sm font-semibold">SKU (opsional)</label>
                <input name="sku" class="w-full rounded-xl border-slate-200" placeholder="SKU-RED-M">
            </div>
            <div>
                <label class="text-sm font-semibold">Harga (opsional, override)</label>
                <input type="number" min="0" name="price" class="w-full rounded-xl border-slate-200" placeholder="Kosongkan untuk pakai harga produk">
            </div>
            <div>
                <label class="text-sm font-semibold">Stok</label>
                <input type="number" min="0" name="stock" class="w-full rounded-xl border-slate-200" required>
            </div>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked>
                <span class="text-sm">Aktif</span>
            </label>
            <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan Varian</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white border rounded-2xl overflow-hidden">
        <div class="p-4 border-b font-bold">Daftar Varian</div>
        <div class="divide-y">
            @forelse($variants as $v)
                <div class="p-4">
                    <form method="POST" action="{{ route('seller.products.variants.update', [$product, $v]) }}" class="grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                        @csrf
                        @method('PUT')
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-slate-600">Nama</label>
                            <input name="name" value="{{ $v->name }}" class="w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">SKU</label>
                            <input name="sku" value="{{ $v->sku }}" class="w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Harga</label>
                            <input type="number" min="0" name="price" value="{{ $v->price }}" class="w-full rounded-xl border-slate-200" placeholder="-">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Stok</label>
                            <input type="number" min="0" name="stock" value="{{ $v->stock }}" class="w-full rounded-xl border-slate-200" required>
                        </div>

                        <div class="md:col-span-5 flex items-center gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" {{ $v->is_active ? 'checked' : '' }}>
                                <span class="text-sm">Aktif</span>
                            </label>

                            <button class="ml-auto px-3 py-2 rounded-xl bg-slate-900 text-white">Update</button>
                        </div>
                    </form>

                    <form class="mt-2" method="POST" action="{{ route('seller.products.variants.destroy', [$product, $v]) }}" onsubmit="return confirm('Hapus varian ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            @empty
                <div class="p-6 text-slate-600">Belum ada varian. Jika produk punya varian (warna/ukuran), tambahkan di sini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
