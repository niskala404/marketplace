@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Tambah Tarif Ongkir</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('admin.shipping-rates.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="font-semibold">Nama tarif</label>
            <input name="name" class="w-full rounded-xl border-slate-200" placeholder="Default / Jakarta / Bandung" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Provinsi (opsional)</label>
                <input name="province" class="w-full rounded-xl border-slate-200" placeholder="DKI Jakarta">
                <div class="text-xs text-slate-500 mt-1">Kosongkan jika ingin default semua.</div>
            </div>
            <div>
                <label class="font-semibold">Kota (opsional, prioritas)</label>
                <input name="city" class="w-full rounded-xl border-slate-200" placeholder="Jakarta Selatan">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Base fee (Rp)</label>
                <input type="number" min="0" name="base_fee" value="15000" class="w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="font-semibold">Fee per kg (Rp)</label>
                <input type="number" min="0" name="per_kg_fee" value="0" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>
@endsection
