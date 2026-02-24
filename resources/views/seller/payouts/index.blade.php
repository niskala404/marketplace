@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Payout (Penarikan Saldo)</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Pendapatan (settled)</div>
    <div class="text-xl font-black mt-1">Rp {{ number_format($totalEarnings,0,',','.') }}</div>
  </div>
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Sudah Dibayar</div>
    <div class="text-xl font-black mt-1">Rp {{ number_format($totalPaidOut,0,',','.') }}</div>
  </div>
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Saldo Tersedia</div>
    <div class="text-xl font-black mt-1">Rp {{ number_format($balance,0,',','.') }}</div>
    <div class="text-xs text-slate-500 mt-1">Minimal penarikan: Rp {{ number_format($min,0,',','.') }}</div>
  </div>
</div>

<div class="mb-4">
  <a href="{{ route('seller.payouts.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">
    Ajukan Payout
  </a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($payouts as $p)
      <div class="p-4 flex items-center justify-between">
        <div>
          <div class="font-bold">Rp {{ number_format($p->amount,0,',','.') }}</div>
          <div class="text-sm text-slate-500">{{ $p->bank_name }} • {{ $p->account_number }} • {{ $p->account_name }}</div>
          @if($p->note)
            <div class="text-xs text-slate-500 mt-1">Catatan: {{ $p->note }}</div>
          @endif
          @if($p->admin_note)
            <div class="text-xs text-slate-500 mt-1">Admin: {{ $p->admin_note }}</div>
          @endif
        </div>
        <div class="text-right">
          <div class="font-semibold">{{ $p->status }}</div>
          <div class="text-xs text-slate-500">{{ $p->created_at->format('d M Y H:i') }}</div>
        </div>
      </div>
    @empty
      <div class="p-6 text-slate-600">Belum ada permintaan payout.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $payouts->links() }}</div>
@endsection
