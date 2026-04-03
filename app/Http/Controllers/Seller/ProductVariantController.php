<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\ProductVariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        $variants = $product->variants()->with('items.option')->orderBy('id')->get();
        $options = $product->variantOptions()->get();
        return view('seller.products.variants', compact('product','variants', 'options'));
    }

    public function generate(Request $request, Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);

        $data = $request->validate([
            'options' => ['required', 'array', 'min:1'],
            'options.*.name' => ['nullable', 'string', 'max:80'],
            'options.*.values' => ['nullable', 'string', 'max:500'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.name' => ['required', 'string', 'max:120'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.items' => ['required', 'array', 'min:1'],
            'variants.*.items.*.option' => ['required', 'string', 'max:80'],
            'variants.*.items.*.value' => ['required', 'string', 'max:80'],
        ]);

        DB::transaction(function () use ($product, $data) {
            $product->variantOptions()->delete();
            $product->variants()->delete();

            $optionMap = [];
            $validOptions = collect($data['options'])
                ->filter(fn ($option) => trim((string) ($option['name'] ?? '')) !== '' && trim((string) ($option['values'] ?? '')) !== '')
                ->values()
                ->all();

            foreach ($validOptions as $idx => $option) {
                $created = ProductVariantOption::create([
                    'product_id' => $product->id,
                    'name' => trim($option['name']),
                    'sort_order' => $idx,
                ]);
                $optionMap[$created->name] = $created;
            }

            foreach ($data['variants'] as $variantPayload) {
                $sku = trim((string)($variantPayload['sku'] ?? ''));


                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variantPayload['name'],
                    'sku' => $sku,
                    'price' => $variantPayload['price'] ?? null,
                    'stock' => (int) $variantPayload['stock'],
                    'is_active' => true,
                ]);

                foreach ($variantPayload['items'] as $itemPayload) {
                    $optionName = trim((string) $itemPayload['option']);
                    $option = $optionMap[$optionName] ?? null;
                    if (!$option) {
                        continue;
                    }

                    ProductVariantItem::create([
                        'product_variant_id' => $variant->id,
                        'product_variant_option_id' => $option->id,
                        'value' => trim((string) $itemPayload['value']),
                    ]);
                }
            }

            $product->update([
                'stock' => (int) $product->variants()->sum('stock'),
                'approval_status' => 'pending',
                'rejected_reason' => null,
            ]);
        });

        return back()->with('success', 'Kombinasi varian berhasil dibuat.');
    }

    public function store(Request $request, Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'sku' => ['nullable','string','max:60'],
            'price' => ['nullable','integer','min:0'],
            'stock' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $sku = trim((string)($data['sku'] ?? ''));
        $sku = $this->generateUniqueSku($sku);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => $data['name'],
            'sku' => $sku,
            'price' => $data['price'] ?? null,
            'stock' => (int)$data['stock'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        // keep product stock in sync (best-effort)
        $product->update([
            'stock' => (int)$product->variants()->sum('stock'),
            'approval_status' => 'pending',
            'rejected_reason' => null,
        ]);

        return back()->with('success','Varian ditambahkan. Produk kembali pending untuk moderasi.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        abort_if($variant->product_id !== $product->id, 404);

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'sku' => ['nullable','string','max:60'],
            'price' => ['nullable','integer','min:0'],
            'stock' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $incomingSku = trim((string)($data['sku'] ?? $variant->sku));
        $sku = $incomingSku !== '' ? $incomingSku : (string) $variant->sku;
        if (ProductVariant::query()->where('sku', $sku)->where('id', '!=', $variant->id)->exists()) {
            $sku = $this->generateUniqueSku('');
        }

        $variant->update([
            'name' => $data['name'],
            'sku' => $sku,
            'price' => $data['price'] ?? null,
            'stock' => (int)$data['stock'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        $product->update([
            'stock' => (int)$product->variants()->sum('stock'),
            'approval_status' => 'pending',
            'rejected_reason' => null,
        ]);

        return back()->with('success','Varian diperbarui.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        abort_if($variant->product_id !== $product->id, 404);

        $variant->delete();

        $product->update([
            'stock' => (int)$product->variants()->sum('stock'),
            'approval_status' => 'pending',
            'rejected_reason' => null,
        ]);

        return back()->with('success','Varian dihapus.');
    }

    private function generateUniqueSku(string $preferred): string
    {
        $sku = trim($preferred);
        if ($sku === '') {
            $sku = 'SKU-'.Str::upper(Str::random(8));
        }

        while (ProductVariant::query()->where('sku', $sku)->exists()) {
            $sku = 'SKU-'.Str::upper(Str::random(8));
        }

        return $sku;
    }
}
