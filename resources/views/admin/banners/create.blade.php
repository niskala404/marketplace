@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Tambah Banner</h1>

<div class="bg-white border rounded-2xl p-5">
  <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="font-semibold">Gambar (wajib)</label>
      <input type="file" name="image" required class="w-full">
      <div class="text-xs text-slate-500 mt-1">Rekomendasi: 1200x360 atau 1200x400</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="font-semibold">Judul</label>
        <input name="title" class="w-full rounded-xl border-slate-200" placeholder="Promo besar...">
      </div>
      <div>
        <label class="font-semibold">Link (opsional)</label>
        <input name="link_url" class="w-full rounded-xl border-slate-200" placeholder="/ atau https://...">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="font-semibold">Sort order</label>
        <input type="number" name="sort_order" value="0" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Mulai</label>
        <input type="datetime-local" name="starts_at" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Selesai</label>
        <input type="datetime-local" name="ends_at" class="w-full rounded-xl border-slate-200">
      </div>
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300">
      <span class="font-semibold">Aktif</span>
    </label>

    <div class="flex gap-2">
      <a href="{{ route('admin.banners.index') }}" class="px-4 py-3 rounded-xl border">Batal</a>
      <button class="px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </div>
  </form>
</div>
@endsection
