@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Detail Dispute</h1>

<div class="bg-white border rounded-2xl p-5 space-y-3">
    <div><span class="font-semibold">Order:</span> {{ $dispute->order->order_no }}</div>
    <div><span class="font-semibold">Toko:</span> {{ $dispute->order->shop?->name ?? '-' }}</div>
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
                        @php($ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)))
                        @if(in_array($ext, ['mp4','mov','webm'], true))
                          <video class="rounded-xl border object-cover aspect-square" src="{{ asset('storage/'.$path) }}" controls></video>
                        @else
                          <img class="rounded-xl border object-cover aspect-square" src="{{ asset('storage/'.$path) }}" alt="Bukti">
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($dispute->status === 'admin_approved')
        <form method="POST" action="{{ route('disputes.ship_back', $dispute) }}" class="flex gap-2 items-center">
            @csrf
            <input name="return_tracking_no" class="flex-1 rounded-xl border-slate-200" placeholder="Resi pengiriman retur..." required>
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Simpan Resi</button>
        </form>
        @error('return_tracking_no')<div class="text-sm text-rose-600">{{ $message }}</div>@enderror
    @endif

    @if($dispute->return_tracking_no)
        <div><span class="font-semibold">Resi Retur:</span> {{ $dispute->return_tracking_no }}</div>
    @endif
</div>
@endsection
