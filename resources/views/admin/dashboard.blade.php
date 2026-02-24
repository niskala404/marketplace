@extends('layouts.market')

@section('content')
<x-app.page title="Admin Dashboard" subtitle="Ringkasan aktivitas marketplace">

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.card>
      <div class="text-slate-500 text-sm">Users</div>
      <div class="text-3xl font-black">{{ $stats['users'] }}</div>
    </x-ui.card>
    <x-ui.card>
      <div class="text-slate-500 text-sm">Products</div>
      <div class="text-3xl font-black">{{ $stats['products'] }}</div>
    </x-ui.card>
    <x-ui.card>
      <div class="text-slate-500 text-sm">Orders</div>
      <div class="text-3xl font-black">{{ $stats['orders'] }}</div>
    </x-ui.card>
  </div>

  <x-ui.card class="mt-6">
    <div class="font-black mb-3">Menu Admin</div>
    <div class="flex flex-wrap gap-2">
      <a class="px-4 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700" href="{{ route('admin.finance.index') }}">Keuangan</a>
      <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700" href="{{ route('admin.categories.index') }}">Kategori</a>
      <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800" href="{{ route('admin.users.index') }}">User</a>
      <a class="px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700" href="{{ route('admin.payments.index') }}">Pembayaran</a>
      <a class="px-4 py-3 rounded-xl bg-slate-100 font-bold hover:bg-slate-200" href="{{ route('admin.shipping-rates.index') }}">Tarif Ongkir</a>
      <a class="px-4 py-3 rounded-xl bg-slate-100 font-bold hover:bg-slate-200" href="{{ route('admin.vouchers.index') }}">Voucher</a>
      <a class="px-4 py-3 rounded-xl bg-slate-100 font-bold hover:bg-slate-200" href="{{ route('admin.products.moderation.index') }}">Moderasi Produk</a>

      @if(Route::has('admin.banners.index'))
        <a class="px-4 py-3 rounded-xl bg-slate-100 font-bold hover:bg-slate-200" href="{{ route('admin.banners.index') }}">Banner</a>
      @endif
      @if(Route::has('admin.flash-sales.index'))
        <a class="px-4 py-3 rounded-xl bg-slate-100 font-bold hover:bg-slate-200" href="{{ route('admin.flash-sales.index') }}">Flash Sale</a>
      @endif
    </div>
  </x-ui.card>

</x-app.page>
@endsection
