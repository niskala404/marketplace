@php
    $route = request()->route() ? request()->route()->getName() : null;
    $is = fn($name) => $route === $name;
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 md:hidden">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-5 py-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl {{ $is('home') ? 'text-rose-600' : 'text-slate-600' }}">
                <x-ic name="home" class="w-5 h-5" />
                <span class="text-[11px] font-semibold">Home</span>
            </a>

            <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl {{ str_starts_with((string)$route,'cart.') ? 'text-rose-600' : 'text-slate-600' }}">
                <x-ic name="shopping-cart" class="w-5 h-5" />
                <span class="text-[11px] font-semibold">Keranjang</span>
            </a>

            @auth
                <a href="{{ route('orders.mine') }}" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl {{ str_starts_with((string)$route,'orders.') ? 'text-rose-600' : 'text-slate-600' }}">
                    <x-ic name="package" class="w-5 h-5" />
                    <span class="text-[11px] font-semibold">Pesanan</span>
                </a>

                <a href="{{ route('messages.index') }}" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl {{ str_starts_with((string)$route,'messages.') ? 'text-rose-600' : 'text-slate-600' }}">
                    <x-ic name="messages-square" class="w-5 h-5" />
                    <span class="text-[11px] font-semibold">Chat</span>
                </a>

                <a href="{{ route('account.profile') }}" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl {{ str_starts_with((string)$route,'account.') ? 'text-rose-600' : 'text-slate-600' }}">
                    <x-ic name="user" class="w-5 h-5" />
                    <span class="text-[11px] font-semibold">Akun</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="col-span-3 flex items-center justify-center gap-2 py-2.5 rounded-2xl bg-rose-600 text-white font-bold">
                    <x-ic name="log-in" class="w-5 h-5" />
                    <span>Login untuk belanja</span>
                </a>
                <a href="{{ route('home') }}#promo" class="flex flex-col items-center justify-center gap-1 py-2 rounded-xl text-slate-600">
                    <x-ic name="tag" class="w-5 h-5" />
                    <span class="text-[11px] font-semibold">Promo</span>
                </a>
            @endauth
        </div>
    </div>
</nav>
