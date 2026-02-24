@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Dispute Masuk</h1>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @forelse($disputes as $d)
            <a class="block p-4 hover:bg-slate-50" href="{{ route('seller.disputes.show', $d) }}">
                <div class="flex justify-between gap-3">
                    <div>
                        <div class="font-bold">{{ $d->order->order_no }}</div>
                        <div class="text-sm text-slate-500">Buyer: {{ $d->user->name }} • {{ $d->reason }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="font-semibold">{{ $d->status }}</div>
                        <div class="text-sm text-slate-500">Rp {{ number_format($d->requested_amount,0,',','.') }}</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-6 text-slate-600">Belum ada dispute masuk.</div>
        @endforelse
    </div>
</div>

<div class="mt-4">{{ $disputes->links() }}</div>
@endsection
