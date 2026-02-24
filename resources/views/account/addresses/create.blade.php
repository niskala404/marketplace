@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tambah Alamat</h1>
    <a href="{{ route('account.addresses.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('account.addresses.store') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Label</label>
                <input name="label" value="{{ old('label','Rumah') }}" class="w-full rounded-xl border-slate-200" required>
                @error('label')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="font-semibold">Kode Pos (opsional)</label>
                <input name="postal_code" value="{{ old('postal_code') }}" class="w-full rounded-xl border-slate-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Nama Penerima</label>
                <input name="recipient_name" value="{{ old('recipient_name') }}" class="w-full rounded-xl border-slate-200" required>
                @error('recipient_name')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="font-semibold">No. HP</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-slate-200" required>
                @error('phone')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="font-semibold">Provinsi (opsional)</label>
                <input name="province" value="{{ old('province') }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="font-semibold">Kota (opsional)</label>
                <input name="city" value="{{ old('city') }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="font-semibold">Kecamatan (opsional)</label>
                <input name="district" value="{{ old('district') }}" class="w-full rounded-xl border-slate-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">RajaOngkir City ID (opsional)</label>
                <input name="rajaongkir_city_id" value="{{ old('rajaongkir_city_id') }}" class="w-full rounded-xl border-slate-200" placeholder="contoh: 39">
                <div class="text-slate-500 text-sm mt-1">Isi jika kamu mau ongkir real (RajaOngkir). Kalau kosong, pakai ongkir demo berdasarkan ShippingRate.</div>
                @error('rajaongkir_city_id')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div>
            <label class="font-semibold">Alamat Lengkap</label>
            <textarea name="full_address" rows="4" class="w-full rounded-xl border-slate-200" required>{{ old('full_address') }}</textarea>
            @error('full_address')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
            <span class="font-semibold">Jadikan default</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>
@endsection
