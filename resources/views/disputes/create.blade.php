@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Ajukan Dispute / Retur</h1>

<div class="bg-white border rounded-2xl p-5">
    <div class="text-slate-600 mb-4">
        Order: <span class="font-semibold">{{ $order->order_no }}</span>
    </div>

    <form method="POST" action="{{ route('disputes.store', $order) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="font-semibold">Alasan</label>
            <input name="reason" class="w-full rounded-xl border-slate-200" placeholder="Barang rusak / tidak sesuai / dll" required>
            @error('reason')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="font-semibold">Detail</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200"></textarea>
            @error('description')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="font-semibold">Nominal diminta (Rp)</label>
            <input type="number" min="0" name="requested_amount" class="w-full rounded-xl border-slate-200" required>
            @error('requested_amount')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="font-semibold">Bukti Foto (opsional, bisa banyak)</label>
            <input type="file" name="evidences[]" multiple class="w-full">
            @error('evidences.*')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Kirim Dispute</button>
    </form>
</div>
@endsection
