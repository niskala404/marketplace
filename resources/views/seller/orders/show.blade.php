@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-2">Detail Pesanan</h1>

<div class="mb-4">
    <x-order-timeline :order="$order" />
</div>

<div class="bg-white border rounded-2xl p-5">
    <div class="font-bold">{{ $order->order_no }}</div>
    <div class="text-sm text-slate-500">Status: <span class="font-semibold">{{ $order->status }}</span></div>
    <div class="text-sm text-slate-500">Metode bayar: <span class="font-semibold">{{ $order->payment_method }}</span></div>
    <div class="text-sm text-slate-500">Pengiriman: <span class="font-semibold">{{ $order->shipping_courier ? $order->shipping_courier.' ' : '' }}{{ $order->shipping_service ?? '-' }}</span>
        @if($order->shipping_etd)
            <span class="text-xs text-slate-500">({{ $order->shipping_etd }})</span>
        @endif
    </div>
    @if($order->payment_method === 'manual_transfer' && $order->payment_proof_path)
        <div class="mt-3">
            <div class="text-sm font-semibold mb-2">Bukti Transfer</div>
            <img class="w-full max-w-md rounded-2xl border" src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti transfer">
        </div>
    @endif

    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
        @if($order->paid_at)
            <div>Dibayar: <span class="font-semibold">{{ $order->paid_at->format('d M Y H:i') }}</span></div>
        @endif
        @if($order->shipped_at)
            <div>Dikirim: <span class="font-semibold">{{ $order->shipped_at->format('d M Y H:i') }}</span></div>
        @endif
        @if($order->delivered_at)
            <div>Sampai: <span class="font-semibold">{{ $order->delivered_at->format('d M Y H:i') }}</span></div>
        @endif
        @if($order->received_at)
            <div>Diterima: <span class="font-semibold">{{ $order->received_at->format('d M Y H:i') }}</span></div>
        @endif
    </div>

    @if(in_array($order->status, ['paid','processing'], true))
        <form class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="{{ route('seller.orders.status',$order) }}">
            @csrf
            <input type="hidden" name="status" value="shipped" />
            <div class="md:col-span-2">
                <input name="tracking_no" value="{{ $order->tracking_no }}" placeholder="Masukkan nomor resi" required
                       class="w-full rounded-xl border-slate-200" />
            </div>
            <button class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Kirim Pesanan</button>
        </form>
        <div class="text-xs text-slate-500 mt-2">Setelah dikirim, pembeli bisa melacak resi dan mengonfirmasi “Pesanan Diterima”.</div>
    @else
        <form class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="{{ route('seller.orders.status',$order) }}">
            @csrf
            <select name="status" class="rounded-xl border-slate-200">
                @foreach(['pending','paid','processing','shipped','completed','cancelled'] as $st)
                    <option value="{{ $st }}" @selected($order->status===$st)>{{ $st }}</option>
                @endforeach
            </select>
            <input name="tracking_no" value="{{ $order->tracking_no }}" placeholder="Resi (opsional)"
                   class="rounded-xl border-slate-200" />
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Update</button>
        </form>
    @endif

    @if($order->status === 'shipped' && !$order->delivered_at)
        <form class="mt-3" method="POST" action="{{ route('seller.orders.delivered',$order) }}" onsubmit="return confirm('Tandai pesanan sudah sampai (delivered)?');">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Tandai Sudah Sampai</button>
        </form>
        <div class="text-xs text-slate-500 mt-2">MVP: tombol ini untuk simulasi status “delivered” sebelum integrasi tracking kurir otomatis.</div>
    @endif

    @if(in_array($order->status, ['shipped','completed'], true))
        <div class="mt-5 p-4 rounded-2xl border bg-slate-50">
            <div class="font-bold text-sm">Tambah Checkpoint Tracking</div>
            <div class="text-xs text-slate-500 mt-1">Gunakan untuk update manual seperti “masuk DC Bandung”, “sedang diantar kurir”, dll.</div>

            <form class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="{{ route('seller.orders.checkpoint', $order) }}">
                @csrf
                <input name="title" class="rounded-xl border-slate-200" placeholder="Contoh: Masuk DC Bandung" required>
                <input name="location" class="rounded-xl border-slate-200" placeholder="Lokasi checkpoint" required>
                <input name="description" class="rounded-xl border-slate-200 md:col-span-3" placeholder="Keterangan tambahan (opsional)">
                <button class="md:col-span-3 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Tambah Checkpoint</button>
            </form>
        </div>
    @endif

    <div class="mt-6 font-bold">Items</div>
    <div class="mt-2 space-y-2">
        @foreach($order->items as $it)
            <div class="flex justify-between text-sm">
                <div>{{ $it->product_name }} × {{ $it->qty }}</div>
                <div class="font-semibold">Rp {{ number_format($it->line_total,0,',','.') }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 pt-4 border-t flex justify-between">
        <span class="font-bold">Total</span>
        <span class="font-black text-rose-600">Rp {{ number_format($order->grand_total,0,',','.') }}</span>
    </div>
</div>
@endsection
