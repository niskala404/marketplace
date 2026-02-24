@extends('layouts.market')

@section('content')
<x-app.page title="Saldo" subtitle="Refund dan penyesuaian saldo masuk ke sini">
  <x-ui.card class="mb-4">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-slate-500">Saldo Saat Ini</div>
        <div class="text-2xl font-black mt-1">Rp {{ number_format((int)($wallet->balance ?? 0), 0, ',', '.') }}</div>
      </div>
      <div class="text-right">
        <div class="text-xs text-slate-500">Catatan</div>
        <div class="text-sm text-slate-700">Saldo ini berasal dari refund (dispute/cancel setelah bayar).</div>
      </div>
    </div>
  </x-ui.card>

  <x-ui.card padding="p-0" class="overflow-hidden">
    <div class="p-4 border-b">
      <div class="font-bold">Riwayat Saldo</div>
      <div class="text-sm text-slate-500">Transaksi terbaru akan muncul di atas.</div>
    </div>

    <div class="divide-y">
      @if($wallet)
        @forelse($transactions as $t)
          <div class="p-4 flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold">
                {{ $t->type === 'refund_credit' ? 'Refund' : ucfirst(str_replace('_',' ', $t->type)) }}
              </div>
              <div class="text-sm text-slate-600 mt-1">
                @if($t->order_id)
                  Order: <span class="font-mono">{{ $t->meta['order_no'] ?? ('#'.$t->order_id) }}</span>
                @else
                  {{ $t->meta['note'] ?? '-' }}
                @endif
              </div>
              <div class="text-xs text-slate-400 mt-2">{{ $t->created_at->format('d M Y H:i') }}</div>
            </div>

            <div class="shrink-0 text-right">
              <div class="font-black {{ $t->amount >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                {{ $t->amount >= 0 ? '+' : '-' }}Rp {{ number_format(abs((int)$t->amount), 0, ',', '.') }}
              </div>
            </div>
          </div>
        @empty
          <div class="p-8 text-center text-slate-600">
            <div class="text-lg font-black text-slate-900">Belum ada transaksi</div>
            <div class="text-sm text-slate-500 mt-1">Transaksi saldo akan muncul setelah ada refund.</div>
          </div>
        @endforelse
      @else
        <div class="p-8 text-center text-slate-600">
          <div class="text-lg font-black text-slate-900">Saldo kamu masih kosong</div>
          <div class="text-sm text-slate-500 mt-1">Saldo akan dibuat otomatis saat ada refund pertama.</div>
        </div>
      @endif
    </div>
  </x-ui.card>

  @if($wallet)
    <div class="mt-4">{{ $transactions->links() }}</div>
  @endif
</x-app.page>
@endsection
