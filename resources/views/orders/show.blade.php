@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Detail Pesanan</h1>
        <div class="text-slate-500 text-sm">{{ $order->order_no }} • {{ $order->created_at->format('d M Y H:i') }}</div>
    </div>
    <a href="{{ route('orders.mine') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <x-order-timeline :order="$order" />

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-2">Toko</div>
            <div class="text-slate-700">{{ $order->shop->name }}</div>
            <div class="mt-3 text-sm">Status: <span class="font-semibold">{{ $order->status }}</span></div>
            <div class="text-sm">Metode bayar: <span class="font-semibold">{{ $order->payment_method }}</span></div>
            <div class="text-sm">Pengiriman: <span class="font-semibold">{{ $order->shipping_courier ? $order->shipping_courier.' ' : '' }}{{ $order->shipping_service ?? '-' }}</span>
                @if($order->shipping_etd)
                    <span class="text-xs text-slate-500">({{ $order->shipping_etd }})</span>
                @endif
            </div>
            @if($order->status === 'pending' && $order->payment_method === 'manual_transfer' && $order->expires_at)
                <div class="text-sm mt-1">
                    Batas bayar:
                    <span class="font-semibold">{{ $order->expires_at->format('d M Y H:i') }}</span>
                    <span id="payCountdown" class="ml-2 text-rose-600 font-bold" data-exp="{{ $order->expires_at->toIso8601String() }}"></span>
                </div>
                <div class="text-xs text-slate-500 mt-1">Jika melewati batas waktu, pesanan akan otomatis dibatalkan.</div>
            @endif
            @if($order->paid_at)
                <div class="text-sm">Dibayar: <span class="font-semibold">{{ $order->paid_at->format('d M Y H:i') }}</span></div>
            @endif
            @if($order->tracking_no)
                <div class="mt-2 text-sm">Resi: <span class="font-semibold">{{ $order->tracking_no }}</span></div>
            @endif
            @if($order->shipped_at)
                <div class="text-sm">Dikirim: <span class="font-semibold">{{ $order->shipped_at->format('d M Y H:i') }}</span></div>
            @endif
            @if($order->delivered_at)
                <div class="text-sm">Sampai: <span class="font-semibold">{{ $order->delivered_at->format('d M Y H:i') }}</span></div>
            @endif
            @if($order->received_at)
                <div class="text-sm">Diterima: <span class="font-semibold">{{ $order->received_at->format('d M Y H:i') }}</span></div>
            @endif
            @if($order->completed_at)
                <div class="text-sm">Selesai: <span class="font-semibold">{{ $order->completed_at->format('d M Y H:i') }}</span></div>
            @endif

            @if($order->status === 'shipped' && !$order->received_at)
                <form class="mt-4" method="POST" action="{{ route('orders.confirm_received', $order) }}" onsubmit="return confirm('Konfirmasi pesanan sudah diterima? Setelah dikonfirmasi, pesanan akan selesai dan dana seller dapat diproses.');">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Pesanan Diterima</button>
                </form>
            @endif

            @if($order->status === 'pending' && $order->payment_method === 'manual_transfer' && !$order->payment_verified_at)
                <form class="mt-3" method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.');">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-slate-200 text-slate-900 font-bold">Batalkan Pesanan</button>
                </form>
            @endif

            <div class="mt-4">
                @if($order->dispute)
                    <a href="{{ route('disputes.show', $order->dispute) }}" class="inline-flex px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Lihat Dispute</a>
                @elseif(in_array($order->status, ['shipped','completed'], true))
                    <a href="{{ route('disputes.create', $order) }}" class="inline-flex px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Ajukan Dispute / Retur</a>
                @endif
            </div>
        </div>

        @if($order->payment_method === 'manual_transfer')
            <div class="bg-white border rounded-2xl p-5">
                <div class="font-bold mb-2">Bukti Transfer</div>

                @if($order->payment_proof_path)
                    <img class="w-full max-w-md rounded-2xl border" src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti transfer">
                    <div class="text-sm text-slate-500 mt-2">Jika bukti salah, kamu bisa unggah ulang selama status masih <span class="font-semibold">pending</span>.</div>
                @else
                    <div class="text-sm text-slate-600">Belum ada bukti transfer.</div>
                @endif

                @if($order->status === 'pending')
                    <form class="mt-4" method="POST" enctype="multipart/form-data" action="{{ route('orders.payment_proof.upload', $order) }}">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/*" required>
                        <button class="mt-3 px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">
                            Upload Bukti Transfer
                        </button>
                    </form>
                @elseif($order->status === 'cancelled')
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan dibatalkan{{ $order->cancel_reason === 'expired_unpaid' ? ' (melewati batas bayar).' : '.' }}</div>
                @elseif($order->status === 'refunded')
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan direfund.</div>
                @endif
            </div>
        @endif

        @if($order->payment_method === 'midtrans')
            <div class="bg-white border rounded-2xl p-5">
                <div class="font-bold mb-2">Pembayaran (Midtrans)</div>

                <div class="text-sm text-slate-600">
                    Status pembayaran: <span class="font-semibold">{{ $order->payment_status ?? ($order->status === 'pending' ? 'pending' : $order->status) }}</span>
                </div>

                @if($order->status === 'pending')
                    @if($order->expires_at)
                        <div class="mt-2 text-sm text-rose-600">
                            Batas bayar: <span class="font-semibold">{{ $order->expires_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                    <a href="{{ route('payments.midtrans.pay', $order) }}" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">
                        Bayar Sekarang
                    </a>
                @elseif($order->status === 'cancelled')
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan dibatalkan.</div>
                @elseif($order->status === 'refunded')
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan direfund.</div>
                @endif
            </div>
        @endif

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Item</div>
            <div class="space-y-3">
                @foreach($order->items as $it)
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold">{{ $it->product_name }} × {{ $it->qty }}</div>
                            <div class="font-black text-rose-600">Rp {{ number_format($it->line_total,0,',','.') }}</div>
                        </div>

                        @if($order->status === 'completed')
                            @if($it->review)
                                <div class="mt-3 text-sm">
                                    <div class="font-semibold">Ulasan kamu</div>
                                    <div>⭐ {{ $it->review->rating }} / 5</div>
                                    @if($it->review->comment)
                                        <div class="text-slate-700 whitespace-pre-line mt-1">{{ $it->review->comment }}</div>
                                    @endif
                                </div>
                            @else
                                <form class="mt-3" method="POST" action="{{ route('orders.items.review', [$order, $it]) }}">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-sm font-semibold">Rating</label>
                                            <select name="rating" class="w-full rounded-xl border-slate-200" required>
                                                <option value="5">5 - Sangat puas</option>
                                                <option value="4">4 - Puas</option>
                                                <option value="3">3 - Cukup</option>
                                                <option value="2">2 - Kurang</option>
                                                <option value="1">1 - Buruk</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-sm font-semibold">Komentar (opsional)</label>
                                            <input name="comment" class="w-full rounded-xl border-slate-200" placeholder="Tulis pengalamanmu...">
                                        </div>
                                    </div>
                                    <button class="mt-3 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Kirim Ulasan</button>
                                </form>
                            @endif
                        @else
                            <div class="mt-2 text-xs text-slate-500">Ulasan bisa diberikan setelah status pesanan <span class="font-semibold">completed</span>.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-2">Alamat Pengiriman</div>
            @php($addr = json_decode($order->shipping_address_snapshot, true) ?: [])
            <div class="text-slate-700">
                <div class="font-semibold">{{ $addr['recipient_name'] ?? '-' }} ({{ $addr['phone'] ?? '-' }})</div>
                <div class="text-sm text-slate-600 mt-1">{{ $addr['full_address'] ?? '-' }}</div>
                @if(!empty($addr['detail_address']))
                    <div class="text-sm text-slate-500 mt-1">Patokan: {{ $addr['detail_address'] }}</div>
                @endif
                <div class="text-sm text-slate-500">{{ $addr['village'] ?? '' }} {{ $addr['district'] ?? '' }} {{ $addr['city'] ?? '' }} {{ $addr['province'] ?? '' }} {{ $addr['postal_code'] ?? '' }}</div>
                @if(!empty($addr['latitude']) && !empty($addr['longitude']))
                    <a target="_blank" rel="noopener" class="inline-block mt-2 text-sm text-rose-600 font-semibold hover:underline"
                       href="https://www.google.com/maps?q={{ $addr['latitude'] }},{{ $addr['longitude'] }}">
                        Lihat lokasi pengiriman
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-5 h-fit">
        <div class="font-bold text-lg">Ringkasan</div>
        <div class="mt-3 flex justify-between">
            <span>Subtotal</span>
            <span class="font-semibold">Rp {{ number_format($order->subtotal,0,',','.') }}</span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Pengiriman</span>
            <span class="font-semibold">
                {{ $order->shipping_courier ? $order->shipping_courier.' ' : '' }}{{ $order->shipping_service ?? '-' }}
                @if($order->shipping_etd)
                    <span class="text-xs text-slate-500">({{ $order->shipping_etd }})</span>
                @endif
            </span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Ongkir</span>
            <span class="font-semibold">Rp {{ number_format($order->shipping_fee,0,',','.') }}</span>
        </div>
        @if(($order->shipping_discount ?? 0) > 0)
            <div class="mt-2 flex justify-between">
                <span>Diskon Ongkir @if($order->voucher_code) ({{ $order->voucher_code }}) @endif</span>
                <span class="font-semibold text-emerald-700">- Rp {{ number_format($order->shipping_discount,0,',','.') }}</span>
            </div>
        @endif
        @if($order->tracking_no)
            <div class="mt-2 flex justify-between">
                <span>Resi</span>
                <span class="font-semibold">{{ $order->tracking_no }}</span>
            </div>
        @endif
        @if($order->discount_amount > 0)
            <div class="mt-2 flex justify-between">
                <span>Diskon @if($order->voucher_code) ({{ $order->voucher_code }}) @endif</span>
                <span class="font-semibold text-emerald-700">- Rp {{ number_format($order->discount_amount,0,',','.') }}</span>
            </div>
        @endif
        <div class="mt-3 pt-3 border-t flex justify-between">
            <span class="font-bold">Total</span>
            <span class="font-black text-rose-600">Rp {{ number_format($order->grand_total,0,',','.') }}</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const el = document.getElementById('payCountdown');
  if (!el) return;
  const exp = el.getAttribute('data-exp');
  const expAt = new Date(exp);
  const pad = (n) => String(n).padStart(2, '0');

  const tick = () => {
    const now = new Date();
    const diff = expAt.getTime() - now.getTime();
    if (diff <= 0) {
      el.textContent = '(expired)';
      return;
    }
    const totalSec = Math.floor(diff / 1000);
    const h = Math.floor(totalSec / 3600);
    const m = Math.floor((totalSec % 3600) / 60);
    const s = totalSec % 60;
    el.textContent = `(${pad(h)}:${pad(m)}:${pad(s)} tersisa)`;
    setTimeout(tick, 1000);
  };

  tick();
})();
</script>
@endpush
