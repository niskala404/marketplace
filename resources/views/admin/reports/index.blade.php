@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Reports</h1>

<div class="mb-3 flex gap-2 overflow-x-auto no-scrollbar">
  @php($cur = $status ?? 'all')
  @php($tabs = ['all'=>'Semua','open'=>'Open','reviewing'=>'Reviewing','resolved'=>'Resolved','rejected'=>'Rejected'])
  @foreach($tabs as $k=>$label)
    <a href="{{ route('admin.reports.index', $k==='all'?[]:['status'=>$k]) }}" class="px-3 py-2 rounded-full border text-sm font-semibold whitespace-nowrap {{ $cur===$k?'bg-slate-900 text-white border-slate-900':'bg-white hover:bg-slate-50' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    @forelse($reports as $r)
      <a href="{{ route('admin.reports.show',$r) }}" class="block p-4 hover:bg-slate-50">
        <div class="font-bold">#{{ $r->id }} • {{ $r->reason }}</div>
        <div class="text-sm text-slate-600">{{ class_basename($r->reportable_type) }} #{{ $r->reportable_id }} • Status: <span class="font-semibold">{{ $r->status }}</span></div>
      </a>
    @empty
      <div class="p-6 text-slate-600">Belum ada laporan.</div>
    @endforelse
  </div>
</div>

<div class="mt-4">{{ $reports->links() }}</div>
@endsection
