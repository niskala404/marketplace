@extends('layouts.market')

@section('content')
<div class="max-w-2xl mx-auto">
  <h1 class="text-2xl font-black mb-5">🔴 Buat Live Stream</h1>

  <div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('seller.live.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf

      <div>
        <label class="block font-semibold mb-1">Judul Live <span class="text-rose-600">*</span></label>
        <input name="title" value="{{ old('title') }}"
          class="w-full rounded-xl border-slate-200 focus:border-rose-400 focus:ring-rose-400" required
          placeholder="Contoh: Flash Sale Produk Terbaik!">
        @error('title')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block font-semibold mb-1">Deskripsi</label>
        <textarea name="description" rows="3"
          class="w-full rounded-xl border-slate-200 focus:border-rose-400 focus:ring-rose-400"
          placeholder="Ceritakan apa yang akan kamu live-kan...">{{ old('description') }}</textarea>
      </div>

      <div>
        <label class="block font-semibold mb-1">Jadwal (opsional)</label>
        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
          class="w-full rounded-xl border-slate-200 focus:border-rose-400 focus:ring-rose-400">
      </div>

      <div>
        <label class="block font-semibold mb-1">Thumbnail Cover</label>
        <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-slate-500
          file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-semibold
          file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, WebP. Maks 3MB.</p>
      </div>

      <div>
        <label class="block font-semibold mb-2">Produk yang akan ditampilkan</label>
        <p class="text-xs text-slate-500 mb-2">Pilih produk yang ingin kamu promosikan saat live. Bisa diubah nanti.</p>
        <div class="grid grid-cols-1 gap-1.5 max-h-60 overflow-y-auto border rounded-xl p-3 bg-slate-50">
          @forelse($products as $p)
            <label class="flex items-center gap-3 text-sm p-1.5 rounded-lg hover:bg-white cursor-pointer transition">
              <input type="checkbox" name="product_ids[]" value="{{ $p->id }}"
                class="rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                {{ in_array($p->id, old('product_ids', [])) ? 'checked' : '' }}>
              <span class="truncate font-medium">{{ $p->name }}</span>
              <span class="ml-auto text-rose-600 font-bold text-xs whitespace-nowrap">Rp {{ number_format($p->price,0,',','.') }}</span>
            </label>
          @empty
            <div class="text-slate-400 text-center py-4">Belum ada produk aktif di toko kamu.</div>
          @endforelse
        </div>
      </div>

      <button type="submit"
        class="w-full py-3 rounded-xl bg-rose-600 text-white font-black text-base hover:bg-rose-700 transition">
        Simpan & Buka Halaman Live
      </button>
    </form>
  </div>
</div>
@endsection
