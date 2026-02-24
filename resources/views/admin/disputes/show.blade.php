@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Detail Dispute (Admin)</h1>

<div class="bg-white border rounded-2xl p-5 space-y-3">
    <div><span class="font-semibold">Order:</span> {{ $dispute->order->order_no }}</div>
    <div><span class="font-semibold">Toko:</span> {{ $dispute->order->shop?->name ?? '-' }}</div>
    <div><span class="font-semibold">Buyer:</span> {{ $dispute->user->name }}</div>
    <div><span class="font-semibold">Status:</span> {{ $dispute->status }}</div>
    <div><span class="font-semibold">Alasan:</span> {{ $dispute->reason }}</div>
    <div><span class="font-semibold">Diminta:</span> Rp {{ number_format($dispute->requested_amount,0,',','.') }}</div>
    <div><span class="font-semibold">Disetujui:</span> Rp {{ number_format($dispute->approved_amount,0,',','.') }}</div>

    @if($dispute->seller_note)
        <div class="p-3 bg-slate-50 rounded-xl border">Catatan Seller: {{ $dispute->seller_note }}</div>
    @endif

    @if($dispute->admin_note)
        <div class="p-3 bg-slate-50 rounded-xl border">Catatan Admin: {{ $dispute->admin_note }}</div>
    @endif

    @if($dispute->evidence_paths)
        <div>
            <div class="font-semibold mb-2">Bukti:</div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach($dispute->evidence_paths as $path)
                    <a href="{{ asset('storage/'.$path) }}" target="_blank" class="block">
                        <img class="rounded-xl border object-cover aspect-square" src="{{ asset('storage/'.$path) }}" alt="Bukti">
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(in_array($dispute->status, ['submitted','seller_approved','seller_rejected'], true))
        <form method="POST" action="{{ route('admin.disputes.decide', $dispute) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <button name="action" value="approve" class="px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold">Approve</button>
                <button name="action" value="reject" class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold">Reject</button>
            </div>

            <div>
                <label class="font-semibold">Nominal disetujui (jika approve)</label>
                <input type="number" min="0" name="approved_amount" class="w-full rounded-xl border-slate-200"
                       value="{{ max($dispute->approved_amount, $dispute->requested_amount) }}">
                @error('approved_amount')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="font-semibold">Catatan Admin</label>
                <textarea name="admin_note" class="w-full rounded-xl border-slate-200" rows="4"></textarea>
                @error('admin_note')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
            </div>
        </form>
    @endif

    @if($dispute->status === 'seller_received')
        <form method="POST" action="{{ route('admin.disputes.refunded', $dispute) }}">
            @csrf
            <button class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold">Tandai Refund Selesai</button>
        </form>
    @endif
</div>
@endsection
