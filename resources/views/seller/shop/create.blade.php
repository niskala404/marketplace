@extends('layouts.market')

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="mb-4">
    <h1 class="text-2xl font-black">Buat Toko</h1>
    <p class="text-slate-600">Sebelum mulai jualan, buat toko kamu dulu.</p>
  </div>

  <div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('seller.shop.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-semibold mb-1">Nama Toko</label>
        <input name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-200" required>
        @error('name')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Deskripsi (opsional)</label>
        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200">{{ old('description') }}</textarea>
        @error('description')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Origin City ID (RajaOngkir) - opsional</label>
        <input name="origin_city_id" value="{{ old('origin_city_id') }}" class="w-full rounded-xl border-slate-200" placeholder="contoh: 39">
        <p class="text-slate-600 text-sm mt-1">Isi City ID asal toko untuk ongkir real (RajaOngkir). Kalau kosong, ongkir pakai demo.</p>
        @error('origin_city_id')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>

      <div class="flex gap-2">
        <a href="{{ route('account.profile') }}" class="px-4 py-2 rounded-xl border bg-white hover:bg-slate-50">Batal</a>
        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">Buat Toko</button>
      </div>
    </form>
  </div>
</div>
@endsection
