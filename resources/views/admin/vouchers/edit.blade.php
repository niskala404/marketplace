@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Edit Voucher</h1>

<form method="POST" action="{{ route('admin.vouchers.update',$voucher) }}" class="bg-white border rounded-2xl p-5 space-y-4">
    @csrf
    @method('PUT')

    <div>
        <div class="font-semibold">Kode</div>
        <input value="{{ $voucher->code }}" readonly class="w-full rounded-xl border-slate-200 bg-slate-50">
    </div>

    <div>
        <div class="font-semibold">Nama</div>
        <input name="name" value="{{ old('name',$voucher->name) }}" class="w-full rounded-xl border-slate-200">
        @error('name')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="font-semibold">Scope</div>
            <select name="shop_id" class="w-full rounded-xl border-slate-200">
                <option value="" @selected(old('shop_id',$voucher->shop_id)===null)>Platform (semua toko)</option>
                @foreach($shops as $s)
                    <option value="{{ $s->id }}" @selected((string)old('shop_id',$voucher->shop_id)===(string)$s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div class="font-semibold">Tipe</div>
            <select name="type" class="w-full rounded-xl border-slate-200">
                <option value="fixed" @selected(old('type',$voucher->type)==='fixed')>Fixed (rupiah)</option>
                <option value="percent" @selected(old('type',$voucher->type)==='percent')>Percent (%)</option>
                <option value="shipping" @selected(old('type',$voucher->type)==='shipping')>Diskon Ongkir (rupiah)</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <div class="font-semibold">Nilai</div>
            <input type="number" name="value" value="{{ old('value',$voucher->value) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Min Subtotal</div>
            <input type="number" name="min_subtotal" value="{{ old('min_subtotal',$voucher->min_subtotal) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Max Discount (percent)</div>
            <input type="number" name="max_discount" value="{{ old('max_discount',$voucher->max_discount) }}" class="w-full rounded-xl border-slate-200" placeholder="Opsional">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <div class="font-semibold">Usage Limit (total)</div>
            <input type="number" name="usage_limit" value="{{ old('usage_limit',$voucher->usage_limit) }}" class="w-full rounded-xl border-slate-200" placeholder="Opsional">
        </div>
        <div>
            <div class="font-semibold">Per User Limit</div>
            <input type="number" name="per_user_limit" value="{{ old('per_user_limit',$voucher->per_user_limit) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Aktif</div>
            <select name="is_active" class="w-full rounded-xl border-slate-200">
                <option value="1" @selected(old('is_active',$voucher->is_active)==true)>Ya</option>
                <option value="0" @selected(old('is_active',$voucher->is_active)==false)>Tidak</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="font-semibold">Mulai</div>
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($voucher->starts_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Berakhir</div>
            <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($voucher->ends_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-200">
        </div>
    </div>

    <div class="flex gap-2">
        <button class="px-5 py-3 rounded-xl bg-amber-600 text-white font-black">Update</button>
        <a class="px-5 py-3 rounded-xl bg-slate-100 font-bold" href="{{ route('admin.vouchers.index') }}">Kembali</a>
    </div>
</form>
@endsection
