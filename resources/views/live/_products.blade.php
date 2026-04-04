<div class="bg-white border rounded-2xl overflow-hidden sticky top-20">
  <div class="p-3 border-b font-bold text-sm">🛍️ Produk di Live Ini</div>
  <div class="divide-y max-h-[70vh] overflow-y-auto">
    @forelse($products as $p)
      <a href="{{ route('product.show', $p->slug) }}"
         class="flex items-center gap-3 p-3 hover:bg-rose-50 transition group">
        @php($img = $p->images->first())
        <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('images/placeholder.png') }}"
             class="w-14 h-14 rounded-xl object-cover border flex-shrink-0"
             alt="{{ $p->name }}">
        <div class="min-w-0 flex-1">
          <div class="text-sm font-semibold line-clamp-2 group-hover:text-rose-600 transition">{{ $p->name }}</div>
          <div class="text-rose-600 font-black text-sm mt-0.5">Rp {{ number_format($p->price,0,',','.') }}</div>
          @if($p->stock > 0)
            <span class="inline-block text-xs px-2 py-0.5 mt-1 rounded-full bg-rose-600 text-white font-semibold">Beli Sekarang</span>
          @else
            <span class="inline-block text-xs px-2 py-0.5 mt-1 rounded-full bg-slate-200 text-slate-500">Habis</span>
          @endif
        </div>
      </a>
    @empty
      <div class="p-5 text-center text-slate-400 text-sm">
        <div class="text-3xl mb-2">📦</div>
        Belum ada produk ditampilkan
      </div>
    @endforelse
  </div>
</div>
