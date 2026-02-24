@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Flash Sale</h1>

<div class="flex justify-between items-center mb-4">
  <div class="text-slate-600 text-sm">Flash sale aktif akan tampil di homepage selama periodenya.</div>
  <a class="px-4 py-2 rounded-xl bg-fuchsia-600 text-white font-bold" href="{{ route('admin.flash-sales.create') }}">+ Flash Sale</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50">
      <tr>
        <th class="text-left p-3">Nama</th>
        <th class="text-left p-3">Periode</th>
        <th class="text-left p-3">Aktif</th>
        <th class="text-right p-3">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @forelse($sales as $s)
        <tr>
          <td class="p-3">
            <div class="font-semibold">{{ $s->name }}</div>
            <div class="text-xs text-slate-500">ID: {{ $s->id }}</div>
          </td>
          <td class="p-3 text-slate-600">
            <div>{{ $s->starts_at->format('Y-m-d H:i') }}</div>
            <div>{{ $s->ends_at->format('Y-m-d H:i') }}</div>
          </td>
          <td class="p-3">
            <span class="px-2 py-1 rounded-full text-xs {{ $s->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
              {{ $s->is_active ? 'aktif' : 'nonaktif' }}
            </span>
          </td>
          <td class="p-3 text-right">
            <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('admin.flash-sales.edit',$s) }}">Kelola</a>
            <form class="inline" method="POST" action="{{ route('admin.flash-sales.destroy',$s) }}" onsubmit="return confirm('Hapus flash sale?')">
              @csrf @method('DELETE')
              <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="p-6 text-slate-600">Belum ada flash sale.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $sales->links() }}</div>
@endsection
