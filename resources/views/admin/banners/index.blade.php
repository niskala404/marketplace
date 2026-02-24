@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Banner Campaign</h1>

<div class="flex justify-between items-center mb-4">
  <div class="text-slate-600 text-sm">Banner aktif akan tampil di homepage.</div>
  <a class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold" href="{{ route('admin.banners.create') }}">+ Banner</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50">
      <tr>
        <th class="text-left p-3">Preview</th>
        <th class="text-left p-3">Judul</th>
        <th class="text-left p-3">Aktif</th>
        <th class="text-left p-3">Periode</th>
        <th class="text-right p-3">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @forelse($banners as $b)
        <tr>
          <td class="p-3">
            <img src="{{ asset('storage/'.$b->image_path) }}" class="w-40 h-16 object-cover rounded-xl border" alt="">
          </td>
          <td class="p-3">
            <div class="font-semibold">{{ $b->title ?? '-' }}</div>
            @if($b->link_url)
              <div class="text-xs text-slate-500">{{ $b->link_url }}</div>
            @endif
            <div class="text-xs text-slate-500">Sort: {{ $b->sort_order }}</div>
          </td>
          <td class="p-3">
            <span class="px-2 py-1 rounded-full text-xs {{ $b->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
              {{ $b->is_active ? 'aktif' : 'nonaktif' }}
            </span>
          </td>
          <td class="p-3 text-slate-600">
            <div>{{ $b->starts_at ? $b->starts_at->format('Y-m-d H:i') : '—' }}</div>
            <div>{{ $b->ends_at ? $b->ends_at->format('Y-m-d H:i') : '—' }}</div>
          </td>
          <td class="p-3 text-right">
            <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('admin.banners.edit',$b) }}">Edit</a>
            <form class="inline" method="POST" action="{{ route('admin.banners.destroy',$b) }}" onsubmit="return confirm('Hapus banner?')">
              @csrf @method('DELETE')
              <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="p-6 text-slate-600">Belum ada banner.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $banners->links() }}</div>
@endsection
