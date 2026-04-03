@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Varian Produk Advanced</h1>
        <div class="text-slate-600">{{ $product->name }}</div>
        <div class="text-xs text-slate-500 mt-1">Contoh opsi: Warna (Merah, Hitam) + Ukuran (S, M, L). Sistem akan generate kombinasi otomatis.</div>
    </div>
    <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="{{ route('seller.products.index') }}">← Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5 mb-6">
    <form method="POST" action="{{ route('seller.products.variants.generate', $product) }}" id="variantGeneratorForm" class="space-y-4">
        @csrf

        <div id="optionRows" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 option-row">
                <div>
                    <label class="text-sm font-semibold">Nama Opsi</label>
                    <input name="options[0][name]" class="w-full rounded-xl border-slate-200" placeholder="Warna" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Nilai Opsi (pisahkan koma)</label>
                    <input name="options[0][values]" class="w-full rounded-xl border-slate-200" placeholder="Merah, Hitam" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 option-row">
                <div>
                    <label class="text-sm font-semibold">Nama Opsi</label>
                    <input name="options[1][name]" class="w-full rounded-xl border-slate-200" placeholder="Ukuran">
                </div>
                <div>
                    <label class="text-sm font-semibold">Nilai Opsi (pisahkan koma)</label>
                    <input name="options[1][values]" class="w-full rounded-xl border-slate-200" placeholder="S, M, L">
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="button" id="addOptionBtn" class="px-3 py-2 rounded-xl border font-semibold">+ Opsi</button>
            <button type="button" id="generateBtn" class="px-3 py-2 rounded-xl bg-slate-900 text-white font-semibold">Generate Kombinasi</button>
        </div>

        <div class="overflow-x-auto border rounded-2xl">
            <table class="min-w-full text-sm" id="variantTable">
                <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-3">Kombinasi</th>
                    <th class="text-left p-3">SKU</th>
                    <th class="text-left p-3">Harga</th>
                    <th class="text-left p-3">Stok</th>
                </tr>
                </thead>
                <tbody id="variantRows">
                <tr>
                    <td colspan="4" class="p-4 text-slate-500">Klik "Generate Kombinasi" untuk membuat varian otomatis.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan Kombinasi Varian</button>
    </form>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="p-4 border-b font-bold">Kombinasi Aktif Saat Ini</div>
    <div class="divide-y">
        @forelse($variants as $v)
            <div class="p-4">
                <div class="font-semibold">{{ $v->name }}</div>
                <div class="text-xs text-slate-500">SKU: {{ $v->sku ?: '-' }} | Harga: {{ $v->price ? 'Rp '.number_format($v->price,0,',','.') : 'Harga utama produk' }} | Stok: {{ $v->stock }}</div>
                @if($v->items->count())
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        @foreach($v->items as $item)
                            <span class="px-2 py-1 rounded-full bg-slate-100 border">{{ $item->option?->name }}: {{ $item->value }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="p-6 text-slate-600">Belum ada varian.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const optionRows = document.getElementById('optionRows');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const generateBtn = document.getElementById('generateBtn');
    const variantRows = document.getElementById('variantRows');

    const getOptions = () => {
        const rows = [...optionRows.querySelectorAll('.option-row')];
        return rows.map((row, index) => {
            const name = row.querySelector(`input[name="options[${index}][name]"]`)?.value?.trim();
            const valuesRaw = row.querySelector(`input[name="options[${index}][values]"]`)?.value || '';
            const values = valuesRaw.split(',').map(v => v.trim()).filter(Boolean);
            return { name, values };
        }).filter(opt => opt.name && opt.values.length);
    };

    const cartesian = (arrays) => arrays.reduce((acc, curr) => acc.flatMap(a => curr.map(c => [...a, c])), [[]]);

    const renderVariantRows = () => {
        const options = getOptions();
        if (!options.length) {
            variantRows.innerHTML = '<tr><td colspan="4" class="p-4 text-slate-500">Isi minimal 1 opsi varian.</td></tr>';
            return;
        }

        const valueArrays = options.map(o => o.values.map(v => ({ option: o.name, value: v })));
        const combos = cartesian(valueArrays);

        variantRows.innerHTML = '';
        combos.forEach((combo, idx) => {
            const comboName = combo.map(x => x.value).join(' / ');
            const itemsHidden = combo.map((item, itemIdx) => `
                <input type="hidden" name="variants[${idx}][items][${itemIdx}][option]" value="${item.option}">
                <input type="hidden" name="variants[${idx}][items][${itemIdx}][value]" value="${item.value}">
            `).join('');

            const row = document.createElement('tr');
            row.className = 'border-t';
            row.innerHTML = `
                <td class="p-3">
                    <div class="font-semibold">${comboName}</div>
                    <input type="hidden" name="variants[${idx}][name]" value="${comboName}">
                    ${itemsHidden}
                </td>
                <td class="p-3"><input name="variants[${idx}][sku]" class="w-full rounded-xl border-slate-200" placeholder="Opsional"></td>
                <td class="p-3"><input type="number" min="0" name="variants[${idx}][price]" class="w-full rounded-xl border-slate-200" placeholder="Ikuti harga produk"></td>
                <td class="p-3"><input type="number" min="0" name="variants[${idx}][stock]" class="w-full rounded-xl border-slate-200" required></td>
            `;
            variantRows.appendChild(row);
        });
    };

    addOptionBtn.addEventListener('click', () => {
        const idx = optionRows.querySelectorAll('.option-row').length;
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 option-row';
        div.innerHTML = `
            <div>
                <label class="text-sm font-semibold">Nama Opsi</label>
                <input name="options[${idx}][name]" class="w-full rounded-xl border-slate-200" placeholder="Contoh: Material">
            </div>
            <div>
                <label class="text-sm font-semibold">Nilai Opsi (pisahkan koma)</label>
                <input name="options[${idx}][values]" class="w-full rounded-xl border-slate-200" placeholder="Cotton, Linen">
            </div>
        `;
        optionRows.appendChild(div);
    });

    generateBtn.addEventListener('click', renderVariantRows);
})();
</script>
@endpush
