<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        $variants = $product->variants()->orderBy('id')->get();
        return view('seller.products.variants', compact('product','variants'));
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
        if ($sku === '') {
            $sku = 'SKU-'.Str::upper(Str::random(8));
        }

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

        $variant->update([
            'name' => $data['name'],
            'sku' => trim((string)($data['sku'] ?? $variant->sku)) ?: $variant->sku,
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
}
