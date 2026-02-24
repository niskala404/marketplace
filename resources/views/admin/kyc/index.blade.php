@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">KYC Seller</h1>

<div class="mb-3 flex gap-2 overflow-x-auto no-scrollbar">
  @php($cur = $status ?? 'all')
  @php($tabs = ['all'=>'Semua','submitted'=>'Submitted','approved'=>'Approved','rejected'=>'Rejected','draft'=>'Draft'])
  @foreach($tabs as $k=>$label)
    <a href="{{ route('admin.kyc.index', $k==='all'?[]:['status'=>$k]) }}" class="px-3 py-2 rounded-full border text-sm font-semibold whitespace-nowrap {{ $cur===$k?'bg-slate-900 text-white border-slate-900':'bg-white hover:bg-slate-50' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($kycs as $k)
      <a href="{{ route('admin.kyc.show',$k) }}" class="block p-4 hover:bg-slate-50">
        <div class="font-bold">Toko: {{ $k->shop?->name ?? ('#'.$k->shop_id) }}</div>
        <div class="text-sm text-slate-600">Status: <span class="font-semibold">{{ $k->status }}</span> • Submitted: {{ $k->submitted_at?->format('d M Y H:i') ?? '-' }}</div>
      </a>
    @empty
      <div class="p-6 text-slate-600">Belum ada data.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $kycs->links() }}</div>
@endsection
