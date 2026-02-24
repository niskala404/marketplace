@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Dispute (Admin)</h1>

<div class="mb-3 flex flex-wrap gap-2">
    @foreach(['submitted','seller_approved','seller_rejected','admin_approved','buyer_shipped','seller_received','refunded','admin_rejected'] as $st)
        <a class="px-3 py-2 rounded-xl border {{ $status===$st ? 'bg-slate-900 text-white' : 'bg-white' }}"
           href="{{ route('admin.disputes.index', ['status'=>$st]) }}">{{ $st }}</a>
    @endforeach
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @forelse($disputes as $d)
            <a class="block p-4 hover:bg-slate-50" href="{{ route('admin.disputes.show', $d) }}">
                <div class="flex justify-between gap-3">
                    <div>
                        <div class="font-bold">{{ $d->order->order_no }}</div>
                        <div class="text-sm text-slate-500">{{ $d->order->shop?->name }} • Buyer: {{ $d->user->name }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="font-semibold">{{ $d->status }}</div>
                        <div class="text-sm text-slate-500">Rp {{ number_format($d->requested_amount,0,',','.') }}</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-6 text-slate-600">Tidak ada dispute.</div>
        @endforelse
    </div>
</div>

<div class="mt-4">{{ $disputes->links() }}</div>
@endsection
