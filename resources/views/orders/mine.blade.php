@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Pesanan Saya</h1>

@php($cur = $status ?? 'all')
<div class="mb-3 flex gap-2 overflow-x-auto no-scrollbar">
  @php($tabs = [
    'all' => 'Semua',
    'pending' => 'Belum Bayar',
    'processing' => 'Diproses',
    'shipped' => 'Dikirim',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
  ])
  @foreach($tabs as $k => $label)
    <a href="{{ route('orders.mine', ['status' => $k]) }}"
      class="px-3 py-2 rounded-full border text-sm font-semibold whitespace-nowrap {{ $cur===$k?'bg-slate-900 text-white border-slate-900':'bg-white hover:bg-slate-50' }}">
      {{ $label }}
    </a>
  @endforeach
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @forelse($orders as $o)
            <a href="{{ route('orders.show', $o) }}" class="block p-4 hover:bg-slate-50">
                <div>
                    <div class="font-bold">{{ $o->order_no }}</div>
                    <div class="text-sm text-slate-500">{{ $o->shop->name }} • {{ $o->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="text-right">
                    <div class="font-black text-rose-600">Rp {{ number_format($o->grand_total,0,',','.') }}</div>
                    <div class="text-sm text-slate-600">Status: <span class="font-semibold">{{ $o->status }}</span></div>
                    @if($o->status === 'pending' && $o->payment_method === 'manual_transfer' && $o->expires_at)
                        <div class="text-xs text-rose-600 font-semibold">Bayar sebelum {{ $o->expires_at->format('d M Y H:i') }}</div>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-6 text-slate-600">Belum ada pesanan.</div>
        @endforelse
    </div>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
