<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - {{ $title ?? 'Marketplace' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Skeleton shimmer + loading UX --}}
    <style>
        .skeleton{
            position:relative;
            overflow:hidden;
            background:#eef2f7;
            border-radius:12px;
        }
        .skeleton::after{
            content:"";
            position:absolute;
            inset:0;
            transform:translateX(-100%);
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.65),transparent);
            animation:shimmer 1.2s infinite;
        }
        @keyframes shimmer{100%{transform:translateX(100%)}}
        body.page-loading{cursor:progress;}
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 selection:bg-rose-200 selection:text-rose-900">

{{-- Top loading bar (Shopee feel) --}}
<div id="topLoader" class="fixed top-0 left-0 h-[3px] w-0 bg-rose-600 z-[9999] transition-all"></div>

@auth
    <script>
        window.App = window.App || {};
        window.App.userId = @json(auth()->id());
    </script>
@endauth

<header class="sticky top-0 z-40 bg-white border-b border-slate-200">
    <div class="h-1 bg-rose-600"></div>

    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center gap-3">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black shadow-sm">ma</span>
            <span class="font-black text-xl tracking-tight">lik<span class="text-rose-600">ishop</span></span>
        </a>

        {{-- Search --}}
        <form action="{{ route('home') }}" method="GET" class="flex-1">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <x-ic name="search" class="w-5 h-5" />
                </span>
                <input
                    name="q"
                    value="{{ request('q') }}"
                    id="searchInput"
                    class="w-full pl-10 pr-28 py-2.5 rounded-2xl border border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-200 shadow-sm"
                    placeholder="Cari produk... (contoh: headset)">
                <button
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-2 rounded-xl bg-rose-600 text-white font-semibold hover:bg-rose-700 active:scale-[0.99] transition">
                    Cari
                </button>

                {{-- Suggestions dropdown --}}
                <div id="searchSuggest" class="hidden absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-2">
                        <div class="text-[11px] uppercase tracking-wide text-slate-400 px-2 py-1">Saran pencarian</div>
                        <div id="searchSuggestList" class="divide-y"></div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Actions --}}
        <div class="flex items-center gap-1">
            @auth
                @php($cartCount = (int) (auth()->user()->cart?->items()->sum('qty') ?? 0))
            @else
                @php($cartCount = 0)
            @endauth

            <a href="{{ route('cart.index') }}" class="relative px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Keranjang" aria-label="Keranjang">
                <x-ic name="shopping-cart" class="w-5 h-5" />
                <span id="cartBadge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 text-[10px] font-black bg-rose-600 text-white rounded-full px-2 py-0.5 shadow">
                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                </span>
            </a>

            @auth
                @php($unread = auth()->user()->unreadNotifications()->count())

                <a href="{{ route('wishlist.index') }}" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Wishlist" aria-label="Wishlist">
                    <x-ic name="heart" class="w-5 h-5" />
                </a>

                <a href="{{ route('messages.index') }}" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Pesan" aria-label="Pesan">
                    <x-ic name="messages-square" class="w-5 h-5" />
                </a>

                <a href="{{ route('notifications.index') }}" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition relative" title="Notifikasi" aria-label="Notifikasi">
                    <x-ic name="bell" class="w-5 h-5" />
                    <span id="notifBadge" class="{{ $unread > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 text-[10px] font-bold bg-rose-600 text-white rounded-full px-2 py-0.5 shadow">
                        {{ $unread > 99 ? '99+' : $unread }}
                    </span>
                </a>

                <a href="{{ route('wallet.index') }}" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Saldo" aria-label="Saldo">
                    <x-ic name="wallet" class="w-5 h-5" />
                </a>

                <a href="{{ route('account.profile') }}" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Akun" aria-label="Akun">
                    <x-ic name="user" class="w-5 h-5" />
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 active:scale-[0.99] transition shadow-sm">
                    Login
                </a>
            @endauth
        </div>
    </div>

    <div class="border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-2 flex items-center justify-between text-sm">
            <div class="flex items-center gap-2 text-slate-600">
                <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-700 font-semibold border border-rose-100">Promo</span>
                <span class="hidden sm:inline">Gratis ongkir* • Pembayaran aman • Chat penjual</span>
            </div>
            <div class="hidden md:block text-slate-500">Belanja nyaman & aman</div>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6 pb-28">
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="font-black mb-1">Ada yang perlu diperbaiki:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
            <div class="font-bold">Berhasil</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="font-bold">Gagal</div>
            <div class="text-sm">{{ session('error') }}</div>
        </div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
</main>

@include('partials.mobile-nav')

<footer class="fixed bottom-0 left-0 right-0 z-40 hidden md:block">
    <div class="bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div class="text-sm text-slate-600">
                    <span class="font-black text-slate-900">ilm<span class="text-rose-600">ishop</span></span>
                    <span class="mx-2 text-slate-300">•</span>
                    <span class="font-semibold">Jual Beli Online</span>
                    <span class="hidden sm:inline"> — Aman, Cepat, dan Terpercaya</span>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1"><x-ic name="shield-check" class="w-4 h-4 text-rose-600" /><span>Garansi</span></span>
                    <span class="inline-flex items-center gap-1"><x-ic name="truck" class="w-4 h-4 text-rose-600" /><span>Gratis Ongkir*</span></span>
                    <span class="inline-flex items-center gap-1"><x-ic name="credit-card" class="w-4 h-4 text-rose-600" /><span>Pembayaran Aman</span></span>
                    <span class="hidden md:inline text-slate-300">|</span>
                    <a href="#" class="hover:underline">Tentang</a>
                    <a href="#" class="hover:underline">Bantuan</a>
                    <a href="#" class="hover:underline">S&K</a>
                </div>
            </div>

            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                <div>© {{ date('Y') }} ilmishop</div>
                <div class="hidden sm:block">*S&K berlaku</div>
            </div>
        </div>
        <div class="h-1 bg-rose-600"></div>
    </div>
</footer>

@stack('scripts')

{{-- Loading UX Script: skeleton + top loader + countdown flash sale --}}
<script>
(function () {
    // ===== TOP LOADER =====
    const bar = document.getElementById('topLoader');
    function startBar(){
        if(!bar) return;
        bar.style.width = '30%';
        setTimeout(()=> bar.style.width = '60%', 200);
        setTimeout(()=> bar.style.width = '85%', 600);
    }
    function doneBar(){
        if(!bar) return;
        bar.style.width = '100%';
        setTimeout(()=> bar.style.width = '0%', 250);
    }

    // ===== SKELETON (optional; only if page provides #productGrid & #productSkeleton) =====
    const grid = document.getElementById('productGrid');
    const skel = document.getElementById('productSkeleton');

    function showSkeleton(){
        if (!grid || !skel) return;
        document.body.classList.add('page-loading');
        grid.classList.add('hidden');
        skel.classList.remove('hidden');
    }
    function hideSkeleton(){
        if (!grid || !skel) return;
        document.body.classList.remove('page-loading');
        skel.classList.add('hidden');
        grid.classList.remove('hidden');
    }

    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn && (conn.effectiveType === '2g' || conn.effectiveType === 'slow-2g' || conn.saveData)) {
        showSkeleton();
        window.addEventListener('load', () => setTimeout(hideSkeleton, 250));
    }

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if(!a) return;
        const href = a.getAttribute('href');
        if(!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
        if(a.getAttribute('target') === '_blank') return;

        startBar();
        showSkeleton();
    });

    document.addEventListener('submit', () => {
        startBar();
        showSkeleton();
    });

    window.addEventListener('load', () => {
        doneBar();
        hideSkeleton();
    });

    window.addEventListener('pageshow', (e) => {
        doneBar();
        if (e.persisted) hideSkeleton();
    });

    // ===== FLASH SALE COUNTDOWN (global) =====
    function pad(n){ return String(n).padStart(2,'0'); }

    function tickFlash(){
        document.querySelectorAll('[data-fs-ends]').forEach(el=>{
            const ends = el.getAttribute('data-fs-ends');
            const t = Date.parse(ends);
            if(!t) return;

            const now = Date.now();
            let diff = Math.max(0, Math.floor((t - now) / 1000));

            const h = Math.floor(diff / 3600);
            diff -= h * 3600;
            const m = Math.floor(diff / 60);
            const s = diff - (m * 60);

            const hh = (h > 99 ? 99 : h);
            const timer = el.querySelector('.fs-timer');
            if(timer) timer.textContent = `${pad(hh)}:${pad(m)}:${pad(s)}`;
        });
    }
    tickFlash();
    setInterval(tickFlash, 1000);
})();
</script>

</body>
</html>