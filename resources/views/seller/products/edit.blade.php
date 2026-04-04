@extends('layouts.market')

@section('content')
@php
    $existingOptions = $product->variantOptions()->orderBy('sort_order')->pluck('name')->values();
@endphp
<h1 class="text-2xl font-black mb-4">Edit Produk</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('seller.products.update',$product) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" value="{{ $product->name }}" class="w-full rounded-xl border-slate-200" required>
        </div>

        <div>
            <label class="font-semibold">Kategori</label>
            <select name="category_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected($product->category_id===$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Harga</label>
                <input type="number" min="0" name="price" value="{{ $product->price }}" class="w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="font-semibold">Stok</label>
                <input type="number" min="0" name="stock" value="{{ $product->stock }}" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Diskon</label>
                <select name="discount_type" class="w-full rounded-xl border-slate-200">
                    <option value="none" @selected(($product->discount_type ?? 'none')==='none')>Tidak ada</option>
                    <option value="percent" @selected(($product->discount_type ?? '')==='percent')>Persen (%)</option>
                    <option value="amount" @selected(($product->discount_type ?? '')==='amount')>Potongan (Rp)</option>
                </select>
                <div class="text-xs text-slate-500 mt-1">Diskon per produk. Contoh: 10% atau potongan Rp.</div>
            </div>
            <div>
                <label class="font-semibold">Nilai Diskon</label>
                <input type="number" min="0" name="discount_value" value="{{ (int)($product->discount_value ?? 0) }}" class="w-full rounded-xl border-slate-200">
            </div>
        </div>
        <div>
            <label class="font-semibold">Berat (gram)</label>
            <input
                type="number"
                min="1"
                name="weight_grams"
                value="{{ old('weight_grams', $product->weight_grams) }}"
                class="w-full rounded-xl border-slate-200"
                required
            >
            @error('weight_grams')
                <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200">{{ $product->description }}</textarea>
        </div>

        <div class="border rounded-2xl p-4 bg-slate-50">
            <div class="flex items-center justify-between mb-3">
                <div class="font-bold">Kelola Varian</div>
                <button type="button" id="addVariantBtnEdit" class="px-3 py-2 rounded-xl border text-sm font-semibold">+ Tambah Varian</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">

                <input id="variantOptionValues2Edit" class="rounded-xl border-slate-200" placeholder="Nilai opsi 2 (S, M, L)">
            </div>
            <button type="button" id="generateVariantsBtnEdit" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold mb-3">Generate Kombinasi Otomatis</button>
            <div id="variantRowsEdit" class="space-y-2">
                @foreach($product->variants()->orderBy('id')->get() as $idx => $variant)

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 border rounded-xl p-2 bg-white variant-edit-row">
                        <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $variant->id }}">
                        <input name="variants[{{ $idx }}][name]" value="{{ $variant->name }}" class="rounded-xl border-slate-200" placeholder="Nama varian (Merah / XL)">
                        <input name="variants[{{ $idx }}][sku]" value="{{ $variant->sku }}" class="rounded-xl border-slate-200" placeholder="SKU (opsional)">
                        <input type="number" min="0" name="variants[{{ $idx }}][price]" value="{{ $variant->price }}" class="rounded-xl border-slate-200" placeholder="Harga varian (opsional)">
                        <div class="flex gap-2">
                            <input type="number" min="0" name="variants[{{ $idx }}][stock]" value="{{ $variant->stock }}" class="rounded-xl border-slate-200 w-full" placeholder="Stok">
                            <button type="button" class="remove-variant px-3 rounded-xl border">✕</button>
                        </div>

                    </div>
                @endforeach
            </div>
            <div class="text-xs text-slate-500 mt-2">Tips: Anda bisa ubah ukuran/harga varian langsung di halaman edit produk ini.</div>
        </div>

        <div>
            <label class="font-semibold">Tambah gambar (opsional)</label>
            <input type="file" id="imagesInputEdit" name="images[]" multiple class="w-full" accept="image/*">
            <div id="imagePreviewEdit" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
        </div>

        @if($product->images->count())
            <div>
                <div class="font-semibold mb-2">Gambar Produk</div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                    @foreach($product->images->sortBy('sort_order') as $img)
                        <div class="relative">
                            <img class="rounded-xl border object-cover aspect-square w-full" src="{{ asset('storage/'.$img->path) }}" alt="">
                            <form method="POST" action="{{ route('seller.products.images.destroy', [$product, $img]) }}" class="absolute top-1 right-1">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 rounded-lg bg-white/90 border text-slate-700 hover:bg-white" title="Hapus">
                                    ✕
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="text-xs text-slate-500 mt-2">*Gambar pertama otomatis jadi thumbnail utama.</div>
            </div>
        @endif

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked($product->is_active)>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update</button>
    </form>
</div>

<script>
(() => {
  const rows = document.getElementById('variantRowsEdit');
  const generateBtn = document.getElementById('generateVariantsBtnEdit');
  let idx = rows ? rows.querySelectorAll('.variant-edit-row').length : 0;

  const bindRemoveButtons = () => {
    rows?.querySelectorAll('.remove-variant').forEach((btn) => {
      if (btn.dataset.bound === '1') return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', () => btn.closest('.variant-edit-row')?.remove());
    });
  };

  document.getElementById('addVariantBtnEdit')?.addEventListener('click', () => {
    if (!rows) return;
    const wrap = document.createElement('div');
    wrap.className = 'grid grid-cols-1 md:grid-cols-4 gap-2 border rounded-xl p-2 bg-white variant-edit-row';
    wrap.innerHTML = `
      <input name=\"variants[${idx}][name]\" class=\"rounded-xl border-slate-200\" placeholder=\"Nama varian (Merah / XL)\">
      <input name=\"variants[${idx}][sku]\" class=\"rounded-xl border-slate-200\" placeholder=\"SKU (opsional)\">
      <input type=\"number\" min=\"0\" name=\"variants[${idx}][price]\" class=\"rounded-xl border-slate-200\" placeholder=\"Harga varian (opsional)\">
      <div class=\"flex gap-2\">
        <input type=\"number\" min=\"0\" name=\"variants[${idx}][stock]\" class=\"rounded-xl border-slate-200 w-full\" placeholder=\"Stok\">
        <button type=\"button\" class=\"remove-variant px-3 rounded-xl border\">✕</button>
      </div>

    `;
    rows.appendChild(wrap);
    idx++;
    bindRemoveButtons();
  });

  bindRemoveButtons();

  const cartesian = (arr) => arr.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [[]]);
  generateBtn?.addEventListener('click', () => {
    const name1 = document.getElementById('variantOptionName1Edit')?.value?.trim();
    const name2 = document.getElementById('variantOptionName2Edit')?.value?.trim();
    const vals1 = (document.getElementById('variantOptionValues1Edit')?.value || '').split(',').map(v => v.trim()).filter(Boolean);
    const vals2 = (document.getElementById('variantOptionValues2Edit')?.value || '').split(',').map(v => v.trim()).filter(Boolean);

    if (!rows || !name1 || !vals1.length) {
      alert('Isi minimal opsi 1 dan nilainya untuk generate kombinasi.');
      return;
    }

    rows.innerHTML = '';
    idx = 0;

    const combos = vals2.length ? cartesian([vals1, vals2]) : vals1.map(v => [v]);
    combos.forEach((combo) => {
      const wrap = document.createElement('div');
      wrap.className = 'grid grid-cols-1 md:grid-cols-4 gap-2 border rounded-xl p-2 bg-white variant-edit-row';
      wrap.innerHTML = `
        <input name=\"variants[${idx}][name]\" value=\"${combo.join(' / ')}\" class=\"rounded-xl border-slate-200\" placeholder=\"Nama varian (Merah / XL)\">
        <input name=\"variants[${idx}][sku]\" class=\"rounded-xl border-slate-200\" placeholder=\"SKU (opsional)\">
        <input type=\"number\" min=\"0\" name=\"variants[${idx}][price]\" class=\"rounded-xl border-slate-200\" placeholder=\"Harga varian (opsional)\">
        <div class=\"flex gap-2\">
          <input type=\"number\" min=\"0\" name=\"variants[${idx}][stock]\" class=\"rounded-xl border-slate-200 w-full\" placeholder=\"Stok\">
          <button type=\"button\" class=\"remove-variant px-3 rounded-xl border\">✕</button>
        </div>

      `;
      rows.appendChild(wrap);
      idx++;
    });
    bindRemoveButtons();
  });

  const input = document.getElementById('imagesInputEdit');
  const preview = document.getElementById('imagePreviewEdit');
  if (!input || !preview) return;

  input.addEventListener('change', () => {
    preview.innerHTML = '';
    [...input.files].forEach((file, idx) => {
      if (!file.type.startsWith('image/')) return;
      const url = URL.createObjectURL(file);
      const card = document.createElement('div');
      card.className = 'relative border rounded-xl overflow-hidden';
      card.innerHTML = `<img src=\"${url}\" class=\"w-full aspect-square object-cover\"><button type=\"button\" data-index=\"${idx}\" class=\"remove-image absolute top-1 right-1 bg-white/90 border rounded px-2\">✕</button>`;
      preview.appendChild(card);
    });

    preview.querySelectorAll('.remove-image').forEach((btn) => {
      btn.addEventListener('click', () => {
        const removeIndex = Number(btn.dataset.index);
        const dt = new DataTransfer();
        [...input.files].forEach((f, i) => { if (i !== removeIndex) dt.items.add(f); });
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
      });
    });
  });
})();
</script>
@endsection
