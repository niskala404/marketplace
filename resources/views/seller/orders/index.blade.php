@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Pesanan Masuk</h1>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @foreach($orders as $o)
            <a href="{{ route('seller.orders.show',$o) }}" class="block p-4 hover:bg-slate-50">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold">{{ $o->order_no }}</div>
                        <div class="text-sm text-slate-500">Pembeli: {{ $o->user->name }} • {{ $o->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-black text-rose-600">Rp {{ number_format($o->grand_total,0,',','.') }}</div>
                        <div class="text-sm">Status: <span class="font-semibold">{{ $o->status }}</span></div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
