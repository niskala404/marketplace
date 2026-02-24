<x-app-layout>
    <div class="max-w-3xl mx-auto p-4">
        <div class="bg-white border rounded-2xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-black text-lg">Bayar Pesanan</div>
                    <div class="text-sm text-slate-500">{{ $order->order_no }} • Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                </div>
                <a href="{{ route('orders.show', $order) }}" class="text-sm underline">Lihat detail</a>
            </div>

            @if($order->expires_at)
                <div class="mt-3 text-sm text-rose-600">
                    Batas bayar: <span class="font-bold" id="expires_at" data-expires="{{ $order->expires_at->toIso8601String() }}">{{ $order->expires_at->format('d M Y H:i') }}</span>
                    <span class="ml-2" id="countdown"></span>
                </div>
            @endif

            <div class="mt-5">
                <button id="payBtn" class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Bayar Sekarang</button>
                <div class="text-xs text-slate-500 mt-2">Metode: VA / QRIS / e-Wallet (tergantung yang tersedia di Midtrans)</div>
            </div>
        </div>
    </div>

    @php($snapUrl = config('ilmishop.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js')
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        (function(){
            const token = @json($order->snap_token);
            const btn = document.getElementById('payBtn');
            if(btn){
                btn.addEventListener('click', function(){
                    if(window.snap && token){
                        window.snap.pay(token, {
                            onSuccess: function(){ window.location.href = @json(route('orders.show', $order)); },
                            onPending: function(){ window.location.href = @json(route('orders.show', $order)); },
                            onError: function(){ alert('Pembayaran gagal. Silakan coba lagi.'); },
                            onClose: function(){ /* user closed popup */ },
                        });
                    }
                });
            }

            // countdown
            const el = document.getElementById('expires_at');
            const cd = document.getElementById('countdown');
            if(el && cd){
                const expires = new Date(el.dataset.expires);
                const tick = () => {
                    const now = new Date();
                    const diff = expires - now;
                    if(diff <= 0){
                        cd.textContent = '(expired)';
                        return;
                    }
                    const s = Math.floor(diff/1000);
                    const hh = String(Math.floor(s/3600)).padStart(2,'0');
                    const mm = String(Math.floor((s%3600)/60)).padStart(2,'0');
                    const ss = String(s%60).padStart(2,'0');
                    cd.textContent = `(${hh}:${mm}:${ss})`;
                    requestAnimationFrame(()=>setTimeout(tick, 500));
                };
                tick();
            }
        })();
    </script>
</x-app-layout>
