@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Tambah Produk</h1>

<div class="bg-white border rounded-2xl p-5">
    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-200" required>
        </div>

        <div>
            <label class="font-semibold">Kategori</label>
            <select name="category_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Harga (Rp)</label>
                <input type="number" min="0" name="price" value="{{ old('price') }}" class="w-full rounded-xl border-slate-200" required>
            </div>

            <div>
                <label class="font-semibold">Stok</label>
                <input type="number" min="0" name="stock" value="{{ old('stock') }}" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Diskon</label>
                <select name="discount_type" class="w-full rounded-xl border-slate-200">
                    <option value="none" @selected(old('discount_type','none')==='none')>Tidak ada</option>
                    <option value="percent" @selected(old('discount_type')==='percent')>Persen (%)</option>
                    <option value="amount" @selected(old('discount_type')==='amount')>Potongan (Rp)</option>
                </select>
                <div class="text-xs text-slate-500 mt-1">Atur diskon per produk (Shopee-style). Jika tidak ada diskon pilih “Tidak ada”.</div>
            </div>
            <div>
                <label class="font-semibold">Nilai Diskon</label>
                <input type="number" min="0" name="discount_value" value="{{ old('discount_value',0) }}" class="w-full rounded-xl border-slate-200">
                <div class="text-xs text-slate-500 mt-1">Contoh: 10 untuk 10% atau 5000 untuk potongan Rp 5.000.</div>
            </div>
        </div>

        {{-- ✅ Tambahkan field berat --}}
        <div>
            <label class="font-semibold">Berat (gram)</label>
            <input
                type="number"
                min="1"
                name="weight_grams"
                value="{{ old('weight_grams') }}"
                class="w-full rounded-xl border-slate-200"
                required
            >
            @error('weight_grams')
                <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200">{{ old('description') }}</textarea>
        </div>

        <div class="border rounded-2xl p-4 bg-slate-50">
            <div class="flex items-center justify-between mb-3">
                <div class="font-bold">Varian Produk (Opsional)</div>
                <button type="button" id="addVariantBtn" class="px-3 py-2 rounded-xl border text-sm font-semibold">+ Tambah Varian</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">

                <input id="variantOptionValues2" class="rounded-xl border-slate-200" placeholder="Nilai opsi 2 (S, M, L)">
            </div>
            <button type="button" id="generateVariantsBtn" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold mb-3">Generate Kombinasi Otomatis</button>
            <div id="variantRows" class="space-y-2"></div>
            <div class="text-xs text-slate-500 mt-2">Jika produk punya ukuran/warna berbeda, tambahkan di sini agar langsung terkelola saat produk dibuat.</div>
        </div>

        <div>
            <label class="font-semibold">Gambar (opsional, bisa banyak)</label>
            <input type="file" id="imagesInput" name="images[]" multiple class="w-full" accept="image/*">
            <div id="imagePreview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>

<script>
(() => {
  const addVariantBtn = document.getElementById('addVariantBtn');
  const generateVariantsBtn = document.getElementById('generateVariantsBtn');
  const variantRows = document.getElementById('variantRows');
  let variantIdx = 0;

  const addVariantRow = (row = {}) => {
    const wrap = document.createElement('div');
    wrap.className = 'grid grid-cols-1 md:grid-cols-4 gap-2 border rounded-xl p-2 bg-white';
    wrap.innerHTML = `
      <input name=\"variants[${variantIdx}][name]\" value=\"${row.name || ''}\" class=\"rounded-xl border-slate-200\" placeholder=\"Nama varian (Merah / XL)\">
      <input name=\"variants[${variantIdx}][sku]\" value=\"${row.sku || ''}\" class=\"rounded-xl border-slate-200\" placeholder=\"SKU (opsional)\">
      <input type=\"number\" min=\"0\" name=\"variants[${variantIdx}][price]\" value=\"${row.price || ''}\" class=\"rounded-xl border-slate-200\" placeholder=\"Harga varian (opsional)\">
      <div class=\"flex gap-2\">
        <input type=\"number\" min=\"0\" name=\"variants[${variantIdx}][stock]\" value=\"${row.stock || ''}\" class=\"rounded-xl border-slate-200 w-full\" placeholder=\"Stok\">
        <button type=\"button\" class=\"remove-variant px-3 rounded-xl border\">✕</button>
      </div>

    `;
    variantRows.appendChild(wrap);
    wrap.querySelector('.remove-variant').addEventListener('click', () => wrap.remove());
    variantIdx++;
  };

  addVariantBtn?.addEventListener('click', () => addVariantRow());

  const cartesian = (arr) => arr.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [[]]);
  generateVariantsBtn?.addEventListener('click', () => {
    const name1 = document.getElementById('variantOptionName1')?.value?.trim();
    const name2 = document.getElementById('variantOptionName2')?.value?.trim();
    const vals1 = (document.getElementById('variantOptionValues1')?.value || '').split(',').map(v => v.trim()).filter(Boolean);
    const vals2 = (document.getElementById('variantOptionValues2')?.value || '').split(',').map(v => v.trim()).filter(Boolean);

    if (!name1 || !vals1.length) {
      alert('Isi minimal opsi 1 dan nilainya untuk generate kombinasi.');
      return;
    }

    variantRows.innerHTML = '';
    variantIdx = 0;

    const combos = vals2.length ? cartesian([vals1, vals2]) : vals1.map(v => [v]);
    combos.forEach((combo) => {
      const name = combo.join(' / ');

    });
  });

  const input = document.getElementById('imagesInput');
  const preview = document.getElementById('imagePreview');
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
