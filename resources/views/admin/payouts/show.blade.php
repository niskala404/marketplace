@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Detail Payout</h1>

<div class="bg-white border rounded-2xl p-5 space-y-3">
  <div><span class="font-semibold">Toko:</span> {{ $payout->shop->name }}</div>
  <div><span class="font-semibold">Pemilik:</span> {{ $payout->shop->user->name }}</div>
  <div><span class="font-semibold">Requester:</span> {{ $payout->requester->name }}</div>
  <div><span class="font-semibold">Status:</span> {{ $payout->status }}</div>
  <div><span class="font-semibold">Nominal:</span> Rp {{ number_format($payout->amount,0,',','.') }}</div>

  <div class="p-3 bg-slate-50 rounded-xl border">
    <div class="font-semibold">Rekening Tujuan</div>
    <div class="text-sm text-slate-600">{{ $payout->bank_name }} • {{ $payout->account_number }} • {{ $payout->account_name }}</div>
  </div>

  @if($payout->note)
    <div class="p-3 bg-slate-50 rounded-xl border">
      <div class="font-semibold">Catatan Seller</div>
      <div class="text-sm text-slate-600">{{ $payout->note }}</div>
    </div>
  @endif

  @if($payout->admin_note)
    <div class="p-3 bg-slate-50 rounded-xl border">
      <div class="font-semibold">Catatan Admin</div>
      <div class="text-sm text-slate-600">{{ $payout->admin_note }}</div>
    </div>
  @endif

  @if($payout->status === 'requested')
    <form method="POST" action="{{ route('admin.payouts.decide', $payout) }}" class="space-y-3">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <button name="action" value="approve" class="px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold">Approve</button>
        <button name="action" value="reject" class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold">Reject</button>
      </div>
      <div>
        <label class="font-semibold">Catatan Admin (opsional)</label>
        <textarea name="admin_note" rows="4" class="w-full rounded-xl border-slate-200"></textarea>
      </div>
    </form>
  @endif

  @if($payout->status === 'approved')
    <form method="POST" action="{{ route('admin.payouts.paid', $payout) }}">
      @csrf
      <button class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold">Tandai Sudah Dibayar</button>
    </form>
  @endif

  <div class="pt-2 text-xs text-slate-500">
    Dibuat: {{ $payout->created_at->format('d M Y H:i') }}
    @if($payout->approved_at) • Diputus: {{ $payout->approved_at->format('d M Y H:i') }} @endif
    @if($payout->paid_at) • Dibayar: {{ $payout->paid_at->format('d M Y H:i') }} @endif
  </div>
</div>
@endsection
