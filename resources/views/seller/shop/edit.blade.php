@extends('layouts.market')

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h1 class="text-2xl font-black">Pengaturan Toko</h1>
      <p class="text-slate-600">Slug toko: <span class="font-mono">{{ $shop->slug }}</span></p>
    </div>
    <a href="{{ route('shop.show', $shop->slug) }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200">Lihat Toko</a>
  </div>

  <div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('seller.shop.update') }}" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-semibold mb-1">Nama Toko</label>
        <input name="name" value="{{ old('name', $shop->name) }}" class="w-full rounded-xl border-slate-200" required>
        @error('name')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Deskripsi (opsional)</label>
        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200">{{ old('description', $shop->description) }}</textarea>
        @error('description')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Origin City ID (RajaOngkir) - opsional</label>
        <input name="origin_city_id" value="{{ old('origin_city_id', $shop->origin_city_id) }}" class="w-full rounded-xl border-slate-200" placeholder="contoh: 39">
        <p class="text-slate-600 text-sm mt-1">Isi City ID asal toko untuk ongkir real (RajaOngkir). Kalau kosong, ongkir tetap pakai demo.</p>
        @error('origin_city_id')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $shop->is_active) ? 'checked' : '' }}>
        <span class="text-sm">Toko aktif</span>
      </label>

      <div class="flex gap-2">
        <a href="{{ route('seller.dashboard') }}" class="px-4 py-2 rounded-xl border bg-white hover:bg-slate-50">Kembali</a>
        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
