@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-3">
        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Alamat Pengiriman</div>

            <div class="space-y-2">
                <select id="address_select" class="w-full rounded-xl border-slate-200">
                    @foreach($addresses as $a)
                        <option value="{{ $a->id }}" @selected($selectedAddress->id === $a->id)>
                            {{ $a->label }} — {{ $a->recipient_name }} ({{ $a->phone }}) — {{ $a->full_address }}
                        </option>
                    @endforeach
                </select>
                <div class="text-sm text-slate-500">
                    Ongkir dihitung berdasarkan alamat yang dipilih. Kelola alamat di menu
                    <a class="text-rose-600 font-semibold" href="{{ route('account.addresses.index') }}">Alamat</a>.
                </div>
            </div>

            <div class="mt-4">
                <div class="font-bold mb-2">Voucher (opsional)</div>
                <form method="GET" action="{{ route('checkout.show') }}" class="space-y-3">
                    <input type="hidden" name="address_id" value="{{ $selectedAddress->id }}">

                    <div>
                        <label class="text-sm font-semibold">Voucher Platform</label>
                        <div class="mt-1 flex gap-2">
                            <input name="platform_voucher" value="{{ $platformVoucherCode ?? '' }}" placeholder="contoh: ILMIHEMAT"
                                   class="flex-1 rounded-xl border-slate-200">
                            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Hitung</button>
                        </div>
                        @if(!empty($platformVoucherError))
                            <div class="mt-1 text-sm text-rose-600">{{ $platformVoucherError }}</div>
                        @endif
                    </div>

                    <div class="border rounded-2xl p-3 bg-slate-50">
                        <div class="text-sm font-semibold">Voucher Toko</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($shopSummaries as $s)
                                <div class="rounded-2xl border bg-white p-3">
                                    <div class="font-bold text-sm">{{ $s['shop']->name }}</div>
                                    <input
                                        class="mt-2 w-full rounded-xl border-slate-200"
                                        name="shop_voucher[{{ $s['shop']->id }}]"
                                        value="{{ $shopVoucherCodes[$s['shop']->id] ?? '' }}"
                                        placeholder="kode voucher toko"
                                    >
                                </div>
                            @endforeach
                        </div>
                        @if(!empty($voucherErrors) && count($voucherErrors))
                            <div class="mt-2 text-sm text-rose-600 space-y-1">
                                @foreach($voucherErrors as $e)
                                    <div>• {{ $e }}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="text-xs text-slate-500 mt-2">Voucher toko bisa dipakai per toko. Voucher platform dipakai 1x untuk toko dengan diskon terbesar.</div>
                    </div>
                </form>
            </div>

            <form method="POST" action="{{ route('checkout.place') }}" class="space-y-4 mt-4">
                @csrf
                <input type="hidden" name="address_id" value="{{ $selectedAddress->id }}" id="address_id_hidden">
                <input type="hidden" name="platform_voucher" value="{{ $platformVoucherCode ?? '' }}">
                @foreach(($shopVoucherCodes ?? []) as $sid => $code)
                    <input type="hidden" name="shop_voucher[{{ (int)$sid }}]" value="{{ $code }}">
                @endforeach

                <div class="font-bold pt-2">Metode Pembayaran</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <span class="font-semibold">COD</span>
                        <div class="text-sm text-slate-500">Bayar saat barang diterima</div>
                    </label>
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="manual_transfer">
                        <span class="font-semibold">Transfer Manual</span>
                        <div class="text-sm text-slate-500">Upload bukti transfer setelah order dibuat</div>
                    </label>
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="midtrans">
                        <span class="font-semibold">Pembayaran Otomatis (Midtrans)</span>
                        <div class="text-sm text-slate-500">VA / QRIS / e-Wallet (otomatis terverifikasi)</div>
                    </label>
                </div>

                <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">
                    Buat Pesanan
                </button>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Item (otomatis dipisah per toko)</div>

            <div class="space-y-4">
                @foreach($shopSummaries as $s)
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="font-bold">{{ $s['shop']->name }}</div>
                            <div class="text-xs text-slate-500">
                                Berat: {{ number_format($s['shippingMeta']['total_weight_grams']/1000, 2, ',', '.') }} kg • Tarif: {{ $s['shippingMeta']['rate']->name ?? 'Default' }}
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-sm font-semibold">Pilih Pengiriman</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach(($s['shippingOptions'] ?? []) as $opt)
                                    <label class="border rounded-2xl p-3 bg-slate-50 cursor-pointer">
                                        <div class="flex items-start gap-2">
                                            <input type="radio"
                                                   name="shipping_option[{{ $s['shop']->id }}]"
                                                   value="{{ $opt['code'] }}"
                                                   @checked(($s['shippingSelected'] ?? 'regular') === $opt['code'])>
                                            <div class="flex-1">
                                                <div class="font-semibold">{{ $opt['label'] }}</div>
                                                <div class="text-xs text-slate-500">Estimasi: {{ $opt['etd'] }}</div>
                                                <div class="text-sm font-black">Rp {{ number_format($opt['fee'],0,',','.') }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="text-xs text-slate-500 mt-2">* Total di ringkasan menggunakan opsi default (Reguler). Saat submit, ongkir mengikuti pilihan kamu.</div>
                        </div>

                        <div class="mt-2 space-y-2">
                            @foreach($s['groupItems'] as $it)
                                <div class="flex justify-between text-sm">
                                    <div>{{ $it->product->name }} × {{ $it->qty }}</div>
                                    @php
                                        $flashPriceMap = $flashPriceMap ?? [];
                                        $p = $it->product;
                                        $unit = $flashPriceMap[$p->id] ?? (method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price);
                                      @endphp
                                    <div class="font-semibold">Rp {{ number_format($unit * (int)$it->qty,0,',','.') }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t text-sm space-y-1">
                            <div class="flex justify-between"><span>Subtotal</span><span class="font-semibold">Rp {{ number_format($s['subtotal'],0,',','.') }}</span></div>
                            <div class="flex justify-between"><span>Ongkir</span><span class="font-semibold">Rp {{ number_format($s['shippingFee'],0,',','.') }}</span></div>
                            @if(!empty($s['discount']) && $s['discount'] > 0)
                                @if(!empty($s['shippingDiscount']) && $s['shippingDiscount'] > 0)
                                    <div class="flex justify-between"><span>Diskon Ongkir ({{ $s['voucherApplied'] }})</span><span class="font-semibold text-emerald-700">- Rp {{ number_format($s['discount'],0,',','.') }}</span></div>
                                @else
                                    <div class="flex justify-between"><span>Diskon ({{ $s['voucherApplied'] }})</span><span class="font-semibold text-emerald-700">- Rp {{ number_format($s['discount'],0,',','.') }}</span></div>
                                @endif
                            @endif
                            <div class="flex justify-between"><span class="font-bold">Total toko</span><span class="font-black text-rose-600">Rp {{ number_format($s['grandTotal'],0,',','.') }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </form>
    </div>

    <div class="bg-white border rounded-2xl p-5 h-fit">
        <div class="font-bold text-lg">Ringkasan</div>
        <div class="mt-3 flex justify-between">
            <span>Subtotal</span>
            <span class="font-semibold">Rp {{ number_format($subtotalAll,0,',','.') }}</span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Ongkir (per toko)</span>
            <span class="font-semibold">Rp {{ number_format($shippingAll,0,',','.') }}</span>
        </div>
        @if(!empty($discountAll) && $discountAll > 0)
            <div class="mt-2 flex justify-between">
                <span>Diskon</span>
                <span class="font-semibold text-emerald-700">- Rp {{ number_format($discountAll,0,',','.') }}</span>
            </div>
        @endif
        <div class="mt-3 pt-3 border-t flex justify-between">
            <span class="font-bold">Total</span>
            <span class="font-black text-rose-600">Rp {{ number_format($grandTotalAll,0,',','.') }}</span>
        </div>
    </div>
</div>

<script>
    const sel = document.getElementById('address_select');
    const hidden = document.getElementById('address_id_hidden');
    sel.addEventListener('change', () => {
        hidden.value = sel.value;
        const url = new URL(window.location.href);
        url.searchParams.set('address_id', sel.value);
        window.location.href = url.toString();
    });
</script>
@endsection
