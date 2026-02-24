@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Edit Tarif Ongkir</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('admin.shipping-rates.update',$rate) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="font-semibold">Nama tarif</label>
            <input name="name" value="{{ $rate->name }}" class="w-full rounded-xl border-slate-200" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Provinsi</label>
                <input name="province" value="{{ $rate->province }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="font-semibold">Kota</label>
                <input name="city" value="{{ $rate->city }}" class="w-full rounded-xl border-slate-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Base fee (Rp)</label>
                <input type="number" min="0" name="base_fee" value="{{ $rate->base_fee }}" class="w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="font-semibold">Fee per kg (Rp)</label>
                <input type="number" min="0" name="per_kg_fee" value="{{ $rate->per_kg_fee }}" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked($rate->is_active)>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update</button>
    </form>
</div>
@endsection
