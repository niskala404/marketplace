@extends('layouts.market')

@section('content')

@if(session('success'))
  <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-semibold">
    ✅ {{ session('success') }}
  </div>
@endif

<div class="flex items-start justify-between gap-3 mb-5">
  <div>
    <h1 class="text-xl font-black line-clamp-2">{{ $live->title }}</h1>
    <div class="flex items-center gap-2 mt-1 text-sm text-slate-500 flex-wrap">
      @php($st = $live->status === 'scheduled' ? 'DRAFT' : strtoupper($live->status))
      <span id="statusBadge" class="px-2 py-0.5 rounded-full font-semibold text-xs
        {{ $live->status === 'live' ? 'bg-rose-100 text-rose-600 animate-pulse' : ($live->status === 'ended' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">
        {{ $st }}
      </span>
      <span>❤️ <span id="likeCount">{{ number_format($live->like_count ?? 0,0,',','.') }}</span></span>
      <span>👁 {{ number_format($live->viewer_count ?? 0,0,',','.') }}</span>
    </div>
  </div>
  <a href="{{ route('seller.live.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition flex-shrink-0">
    ← Kembali
  </a>
</div>


    </div>

  </div>

  {{-- ===== KANAN: Produk ===== --}}
  <div class="space-y-4">

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-2">
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-rose-600">{{ number_format($live->like_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Likes</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-blue-600">{{ number_format($live->viewer_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Viewers</div>
      </div>
      <div class="bg-white border rounded-xl p-3 text-center">
        <div class="text-lg font-black text-emerald-600">{{ number_format($live->share_count ?? 0,0,',','.') }}</div>
        <div class="text-xs text-slate-500">Share</div>
      </div>
    </div>

    {{-- Manage Products --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
      <div class="p-3 border-b font-bold text-sm flex items-center justify-between">
        🛍️ Produk Ditampilkan
        <button id="toggleProductPanel"
          class="text-xs text-rose-600 font-semibold hover:underline">+ Kelola</button>
      </div>

      {{-- Current products --}}
      <div id="currentProducts" class="divide-y max-h-64 overflow-y-auto">
        @forelse($live->products as $p)
          <div class="flex items-center gap-2 p-2.5 text-sm" data-product-id="{{ $p->id }}">
            @php($img = $p->images->first())
            <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('images/placeholder.png') }}"
                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <div class="font-semibold line-clamp-1">{{ $p->name }}</div>
              <div class="text-rose-600 text-xs font-bold">Rp {{ number_format($p->price,0,',','.') }}</div>
            </div>
          </div>
        @empty
          <div class="p-4 text-center text-slate-400 text-sm" id="emptyProducts">Belum ada produk</div>
        @endforelse
      </div>

      {{-- Product picker (hidden by default) --}}
      <div id="productPanel" class="hidden border-t p-3 bg-slate-50">
        <p class="text-xs text-slate-500 mb-2">Centang produk yang ingin ditampilkan ke penonton:</p>
        <div class="space-y-1 max-h-52 overflow-y-auto">
          @foreach($products as $p)
            <label class="flex items-center gap-2 text-sm p-1.5 rounded-lg hover:bg-white cursor-pointer transition">
              <input type="checkbox" class="product-check rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                value="{{ $p->id }}"
                {{ in_array($p->id, $selectedIds) ? 'checked' : '' }}>
              <span class="truncate flex-1">{{ $p->name }}</span>
              <span class="text-rose-600 text-xs font-bold whitespace-nowrap">Rp {{ number_format($p->price,0,',','.') }}</span>
            </label>
          @endforeach
        </div>
        <button id="saveProductsBtn"
          data-url="{{ route('seller.live.products', $live) }}"
          class="w-full mt-3 py-2 rounded-xl bg-rose-600 text-white font-bold text-sm hover:bg-rose-700 transition">
          💾 Simpan Produk
        </button>
        <div id="productSaveMsg" class="hidden mt-2 text-xs text-emerald-600 font-semibold text-center">✅ Produk berhasil diperbarui!</div>
      </div>
    </div>

  </div>
</div>


@endsection
