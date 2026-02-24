@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Payout Seller (Admin)</h1>

<div class="mb-3 flex flex-wrap gap-2">
  @foreach(['requested','approved','paid','rejected'] as $st)
    <a class="px-3 py-2 rounded-xl border {{ $status===$st ? 'bg-slate-900 text-white' : 'bg-white' }}"
       href="{{ route('admin.payouts.index', ['status'=>$st]) }}">{{ $st }}</a>
  @endforeach
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($payouts as $p)
      <a class="block p-4 hover:bg-slate-50" href="{{ route('admin.payouts.show', $p) }}">
        <div class="flex items-center justify-between">
          <div>
            <div class="font-bold">Rp {{ number_format($p->amount,0,',','.') }}</div>
            <div class="text-sm text-slate-500">Toko: {{ $p->shop->name }} • Requester: {{ $p->requester->name }}</div>
            <div class="text-xs text-slate-500">{{ $p->bank_name }} • {{ $p->account_number }} • {{ $p->account_name }}</div>
          </div>
          <div class="text-right">
            <div class="font-semibold">{{ $p->status }}</div>
            <div class="text-xs text-slate-500">{{ $p->created_at->format('d M Y H:i') }}</div>
          </div>
        </div>
      </a>
    @empty
      <div class="p-6 text-slate-600">Tidak ada payout.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $payouts->links() }}</div>
@endsection
