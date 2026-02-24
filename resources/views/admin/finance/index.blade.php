@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Keuangan</h1>
        <div class="text-sm text-slate-500">Monitor escrow, saldo seller & buyer, serta ledger platform</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Escrow (Held)</div>
        <div class="text-2xl font-black">Rp {{ number_format($stats['escrow_held'],0,',','.') }}</div>
        <div class="text-xs text-slate-500 mt-1">Dana ditahan (belum release/refund)</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Escrow (Released)</div>
        <div class="text-2xl font-black">Rp {{ number_format($stats['escrow_released'],0,',','.') }}</div>
        <div class="text-xs text-slate-500 mt-1">Sudah masuk ke saldo seller</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Escrow (Refunded)</div>
        <div class="text-2xl font-black">Rp {{ number_format($stats['escrow_refunded'],0,',','.') }}</div>
        <div class="text-xs text-slate-500 mt-1">Sudah diproses refund</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Saldo Platform (Ledger)</div>
        <div class="text-2xl font-black">Rp {{ number_format($stats['platform_balance'],0,',','.') }}</div>
        <div class="text-xs text-slate-500 mt-1">Akumulasi fee - refund</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Refund Uncollected</div>
        <div class="text-2xl font-black">Rp {{ number_format($stats['refund_uncollected'],0,',','.') }}</div>
        <div class="text-xs text-slate-500 mt-1">Clawback seller kurang saldo</div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Total Saldo Seller / Buyer</div>
        <div class="text-lg font-black">Seller: Rp {{ number_format($stats['seller_wallet_total'],0,',','.') }}</div>
        <div class="text-lg font-black">Buyer: Rp {{ number_format($stats['buyer_wallet_total'],0,',','.') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
    <div class="bg-white border rounded-2xl overflow-hidden">
        <div class="p-4 border-b">
            <div class="font-black">Escrow Terbaru</div>
            <div class="text-sm text-slate-500">10 transaksi escrow terakhir</div>
        </div>
        <div class="divide-y">
            @forelse($recentEscrows as $e)
                <div class="p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold">{{ $e->order?->order_no ?? '—' }}</div>
                            <div class="text-sm text-slate-500">
                                Buyer: {{ $e->order?->user?->name ?? '—' }} • Toko: {{ $e->order?->shop?->name ?? '—' }}
                            </div>
                            <div class="text-xs text-slate-500">Status order: <span class="font-semibold">{{ $e->order?->status ?? '—' }}</span></div>
                        </div>
                        <div class="text-right">
                            <div class="font-black">Rp {{ number_format((int)$e->amount,0,',','.') }}</div>
                            <div class="text-xs">Escrow: <span class="font-semibold">{{ $e->status }}</span></div>
                            <div class="text-xs text-slate-500">{{ $e->created_at?->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-slate-600">Belum ada escrow.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white border rounded-2xl overflow-hidden">
        <div class="p-4 border-b">
            <div class="font-black">Platform Ledger</div>
            <div class="text-sm text-slate-500">12 transaksi terakhir (fee/refund/uncollected)</div>
        </div>
        <div class="divide-y">
            @forelse($recentPlatformTx as $t)
                <div class="p-4 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-bold">{{ $t->type }}</div>
                        <div class="text-sm text-slate-500">Order: {{ $t->order?->order_no ?? '—' }}</div>
                        <div class="text-xs text-slate-500">{{ $t->created_at?->format('d M Y H:i') }}</div>
                    </div>
                    <div class="text-right font-black">
                        Rp {{ number_format((int)$t->amount,0,',','.') }}
                    </div>
                </div>
            @empty
                <div class="p-6 text-slate-600">Belum ada transaksi platform.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white border rounded-2xl overflow-hidden mt-6">
    <div class="p-4 border-b">
        <div class="font-black">Top Seller by Balance</div>
        <div class="text-sm text-slate-500">10 toko dengan saldo terbesar</div>
    </div>
    <div class="divide-y">
        @forelse($topSellerWallets as $w)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-bold">{{ $w->shop?->name ?? '—' }}</div>
                    <div class="text-xs text-slate-500">Wallet ID: {{ $w->id }}</div>
                </div>
                <div class="font-black">Rp {{ number_format((int)$w->balance,0,',','.') }}</div>
            </div>
        @empty
            <div class="p-6 text-slate-600">Belum ada wallet seller.</div>
        @endforelse
    </div>
</div>
@endsection
