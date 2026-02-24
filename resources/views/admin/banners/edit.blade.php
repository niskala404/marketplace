@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Edit Banner</h1>

<div class="bg-white border rounded-2xl p-5">
  <div class="mb-4">
    <img src="{{ asset('storage/'.$banner->image_path) }}" class="w-full max-h-48 object-cover rounded-2xl border" alt="">
  </div>

  <form method="POST" action="{{ route('admin.banners.update',$banner) }}" enctype="multipart/form-data" class="space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="font-semibold">Ganti gambar (opsional)</label>
      <input type="file" name="image" class="w-full">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="font-semibold">Judul</label>
        <input name="title" value="{{ $banner->title }}" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Link (opsional)</label>
        <input name="link_url" value="{{ $banner->link_url }}" class="w-full rounded-xl border-slate-200">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="font-semibold">Sort order</label>
        <input type="number" name="sort_order" value="{{ $banner->sort_order }}" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Mulai</label>
        <input type="datetime-local" name="starts_at" value="{{ $banner->starts_at ? $banner->starts_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">Selesai</label>
        <input type="datetime-local" name="ends_at" value="{{ $banner->ends_at ? $banner->ends_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-200">
      </div>
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded border-slate-300">
      <span class="font-semibold">Aktif</span>
    </label>

    <div class="flex gap-2">
      <a href="{{ route('admin.banners.index') }}" class="px-4 py-3 rounded-xl border">Kembali</a>
      <button class="px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Update</button>
    </div>
  </form>
</div>
@endsection
