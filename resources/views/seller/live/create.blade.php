@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Buat Live Stream</h1>

<div class="bg-white border rounded-2xl p-5">
  <form method="POST" action="{{ route('seller.live.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
      <label class="font-semibold">Judul Live</label>
      <input name="title" class="w-full rounded-xl border-slate-200" required>
    </div>
    <div>
      <label class="font-semibold">Deskripsi</label>
      <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200"></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="font-semibold">Jadwal</label>
        <input type="datetime-local" name="scheduled_at" class="w-full rounded-xl border-slate-200">
      </div>
      <div>
        <label class="font-semibold">URL Stream (opsional)</label>
        <input type="url" name="stream_url" class="w-full rounded-xl border-slate-200" placeholder="https://...">
      </div>
    </div>
    <div>
      <label class="font-semibold">Thumbnail</label>
      <input type="file" name="thumbnail" accept="image/*" class="w-full">
    </div>

    <div>
      <label class="font-semibold">Produk yang ditampilkan</label>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 max-h-64 overflow-auto border rounded-xl p-3">
        @foreach($products as $p)
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="product_ids[]" value="{{ $p->id }}">
            <span>{{ $p->name }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan Live Stream</button>
  </form>
</div>
@endsection
