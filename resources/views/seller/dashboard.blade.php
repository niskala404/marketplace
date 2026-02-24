@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Seller Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Saldo Tersedia</div>
        <div class="text-2xl font-black">Rp {{ number_format($balance ?? 0,0,',','.') }}</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Total Pendapatan (settled)</div>
        <div class="text-2xl font-black">Rp {{ number_format($totalEarnings ?? 0,0,',','.') }}</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Total Sudah Dibayar</div>
        <div class="text-2xl font-black">Rp {{ number_format($totalPaidOut ?? 0,0,',','.') }}</div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach($stats as $k=>$v)
        <div class="bg-white border rounded-2xl p-4">
            <div class="text-slate-500 text-sm">{{ strtoupper($k) }}</div>
            <div class="text-3xl font-black">{{ $v }}</div>
        </div>
    @endforeach
</div>

<div class="mt-6 flex gap-2">
    <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="{{ route('seller.products.index') }}">Kelola Produk</a>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="{{ route('seller.orders.index') }}">Kelola Pesanan</a>
    <a class="px-4 py-3 rounded-xl bg-white border font-bold" href="{{ route('seller.payouts.index') }}">💸 Payout</a>
</div>
@endsection
