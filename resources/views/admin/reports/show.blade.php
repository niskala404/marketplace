@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Report #{{ $report->id }}</h1>

<div class="bg-white border rounded-2xl p-5 space-y-3">
  <div><span class="font-semibold">Target:</span> {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</div>
  <div><span class="font-semibold">Reason:</span> {{ $report->reason }}</div>
  @if($report->details)
    <div class="text-slate-700 whitespace-pre-line">{{ $report->details }}</div>
  @endif

  <form method="POST" action="{{ route('admin.reports.status', $report) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
    @csrf
    <div>
      <label class="font-semibold">Status</label>
      <select name="status" class="w-full rounded-xl border-slate-200">
        @foreach(['open','reviewing','resolved','rejected'] as $s)
          <option value="{{ $s }}" @selected($report->status===$s)>{{ $s }}</option>
        @endforeach
      </select>
    </div>
    <div class="md:col-span-2">
      <label class="font-semibold">Catatan Admin</label>
      <input name="admin_note" class="w-full rounded-xl border-slate-200" value="{{ old('admin_note', $report->admin_note) }}">
    </div>
    <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold md:col-span-3">Simpan</button>
  </form>
</div>
@endsection
