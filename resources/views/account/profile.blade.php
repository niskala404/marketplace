@extends('layouts.market')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

  <aside class="lg:col-span-3">
    <x-ui.card>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center">
                    <x-ic name="user" class="w-6 h-6 text-rose-700" />
        </div>
        <div class="min-w-0">
          <div class="font-black leading-tight truncate">{{ $user->name }}</div>
          <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
        </div>
      </div>

      <div class="mt-4 space-y-1">
        <a href="{{ route('account.profile', ['tab'=>'orders']) }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50 {{ $tab==='orders' ? 'bg-slate-900 text-white hover:bg-slate-900' : '' }}">
                    <x-ic name="receipt" class="w-5 h-5" />
          <span class="font-semibold">Pesanan Saya</span>
        </a>

        <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-50">
          <span class="flex items-center gap-2">
                        <x-ic name="bell" class="w-5 h-5 text-rose-600" />
            <span class="font-semibold">Notifikasi</span>
          </span>
          @if($unreadNotif>0)
            <span class="text-xs bg-rose-600 text-white rounded-full px-2 py-0.5 font-bold">{{ $unreadNotif > 99 ? '99+' : $unreadNotif }}</span>
          @endif
        </a>

        <a href="{{ route('account.addresses.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                    <x-ic name="map-pin" class="w-5 h-5 text-rose-600" />
          <span class="font-semibold">Alamat</span>
        </a>

        <a href="{{ route('messages.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                    <x-ic name="messages-square" class="w-5 h-5 text-rose-600" />
          <span class="font-semibold">Pesan</span>
        </a>

        <a href="{{ route('followings.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                    <x-ic name="store" class="w-5 h-5 text-rose-600" />
          <span class="font-semibold">Toko Diikuti</span>
        </a>

        <a href="{{ route('disputes.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                    <x-ic name="rotate-ccw" class="w-5 h-5 text-rose-600" />
          <span class="font-semibold">Pengembalian / Dispute</span>
        </a>
      </div>

      @if($user->isSeller())
        <div class="mt-4 pt-4 border-t">
          <div class="text-xs font-black text-slate-500 mb-2">SELLER</div>
          <div class="space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="layout-dashboard" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Dashboard Seller</span>
            </a>
            <a href="{{ route('seller.messages.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="inbox" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Inbox</span>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="package" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Pesanan Masuk</span>
            </a>
            <a href="{{ route('seller.payouts.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="wallet" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Payout</span>
            </a>
          </div>
        </div>
      @endif

      @if($user->isAdmin())
        <div class="mt-4 pt-4 border-t">
          <div class="text-xs font-black text-slate-500 mb-2">ADMIN</div>
          <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="shield" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="credit-card" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Verifikasi Pembayaran</span>
            </a>
            <a href="{{ route('admin.products.moderation.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
                            <x-ic name="badge-check" class="w-5 h-5 text-rose-600" />
              <span class="font-semibold">Moderasi Produk</span>
            </a>
          </div>
        </div>
      @endif

      <div class="mt-4 pt-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="w-full px-3 py-2 rounded-xl bg-rose-600 text-white font-black hover:bg-rose-700 inline-flex items-center justify-center gap-2">
                        <x-ic name="log-out" class="w-5 h-5" />
            <span>Logout</span>
          </button>
        </form>
      </div>
    </x-ui.card>
  </aside>

  <section class="lg:col-span-9">
    <x-ui.card>
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
          <div class="font-black text-lg">Pesanan Saya</div>
          <div class="text-sm text-slate-500">Pantau status pesanan kamu</div>
        </div>

        <form method="GET" action="{{ route('account.profile') }}" class="flex-1 min-w-[260px]">
          <input type="hidden" name="tab" value="orders">
          <input type="hidden" name="status" value="{{ $status }}">
          <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><x-ic name="search" class="w-5 h-5" /></span>
            <input name="q" value="{{ $q }}" class="w-full pl-10 pr-24 rounded-xl border-slate-200" placeholder="Cari penjual, no pesanan, atau produk">
            <button class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Cari</button>
          </div>
        </form>
      </div>

      <div class="mt-4 flex gap-2 flex-wrap">
        @php
          $tabs = [
              'all' => 'Semua',
              'unpaid' => 'Belum Bayar',
              'packed' => 'Dikemas',
              'shipped' => 'Dikirim',
              'done' => 'Selesai',
              'cancelled' => 'Dibatalkan',
              'returns' => 'Pengembalian',
          ];
        @endphp

        @foreach($tabs as $key => $label)
          <a href="{{ route('account.profile', ['tab'=>'orders','status'=>$key,'q'=>$q]) }}" class="px-3 py-2 rounded-xl border text-sm font-semibold transition {{ $status===$key ? 'bg-rose-600 text-white border-rose-600' : 'bg-white hover:bg-slate-50' }}">
            {{ $label }}
            <span class="ml-1 text-xs {{ $status===$key ? 'text-white/90' : 'text-slate-500' }}">({{ $counts[$key] ?? 0 }})</span>
          </a>
        @endforeach
      </div>
    </x-ui.card>

    <div class="mt-4 space-y-3">
      @forelse($orders as $o)
        <a href="{{ route('orders.show', $o) }}" class="block bg-white border rounded-2xl p-4 hover:bg-slate-50 transition">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <div class="font-black truncate">{{ $o->shop->name }}</div>
              <div class="text-sm text-slate-500">No: <span class="font-semibold">{{ $o->order_no }}</span></div>
            </div>

            <div class="text-right shrink-0">
              <span class="text-xs px-2 py-1 rounded-full border bg-slate-50 border-slate-200 text-slate-700 font-bold">{{ strtoupper($o->status) }}</span>
              <div class="text-sm text-slate-500 mt-1">Rp {{ number_format($o->grand_total,0,',','.') }}</div>
            </div>
          </div>
        </a>
      @empty
        <x-ui.empty title="Belum ada pesanan" />
      @endforelse
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
  </section>

</div>
@endsection
