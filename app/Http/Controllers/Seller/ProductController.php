<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->shop->id;
        $products = Product::where('shop_id', $shopId)->latest()->paginate(10);
        return view('seller.products.index', compact('products'));
    }

    public function bulk(Request $request)
    {
        $shopId = auth()->user()->shop->id;
        $q = trim((string) $request->query('q', ''));
        $products = Product::query()
            ->where('shop_id', $shopId)
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('name', 'like', "%{$q}%");
            })
            ->orderByDesc('updated_at')
            ->paginate(25)
            ->withQueryString();

        return view('seller.products.bulk', compact('products', 'q'));
    }

    public function bulkUpdate(Request $request)
    {
        $shopId = auth()->user()->shop->id;
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer'],
            'items.*.price' => ['nullable', 'integer', 'min:0'],
            'items.*.stock' => ['nullable', 'integer', 'min:0'],
            'items.*.is_active' => ['nullable', 'boolean'],
            'items.*.discount_type' => ['nullable', 'in:none,percent,amount'],
            'items.*.discount_value' => ['nullable', 'integer', 'min:0'],
        ]);

        $ids = collect($data['items'])->pluck('id')->map(fn($v) => (int)$v)->all();
        $products = Product::where('shop_id', $shopId)->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($data['items'] as $row) {
            $id = (int) $row['id'];
            $p = $products->get($id);
            if (!$p) continue;

            $p->forceFill([
                'price' => isset($row['price']) ? (int)$row['price'] : $p->price,
                'stock' => isset($row['stock']) ? (int)$row['stock'] : $p->stock,
                'is_active' => (bool)($row['is_active'] ?? false),
                'discount_type' => $row['discount_type'] ?? $p->discount_type,
                'discount_value' => isset($row['discount_value']) ? (int)$row['discount_value'] : $p->discount_value,
                // bulk edit requires re-approval
                'approval_status' => 'pending',
                'rejected_reason' => null,
            ])->save();
        }

        return back()->with('success', 'Perubahan massal tersimpan. Produk masuk antrian approval.');
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $shopId = auth()->user()->shop->id;

        $data = $request->validate([
            'name' => ['required','string','max:180'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'description' => ['nullable','string'],
            'price' => ['required','integer','min:0'],
            'discount_type' => ['nullable','in:none,percent,amount'],
            'discount_value' => ['nullable','integer','min:0'],
            'weight_grams' => ['required','integer','min:1'],
            'stock' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'images.*' => ['nullable','image','max:2048'],
            'variants' => ['nullable', 'array'],

            'variants.*.name' => ['required_with:variants', 'string', 'max:120'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],

        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $product = null;
        DB::transaction(function () use ($request, $shopId, $data, $slug, &$product) {
            $product = Product::create([
                'shop_id' => $shopId,
                'category_id' => $data['category_id'] ?? null,
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'discount_type' => $data['discount_type'] ?? 'none',
                'discount_value' => (int)($data['discount_value'] ?? 0),
                'weight_grams' => $data['weight_grams'],
                'stock' => $data['stock'],
                'is_active' => (bool)($data['is_active'] ?? true),
                'approval_status' => 'pending',
                'rejected_reason' => null,
            ]);

            if ($request->hasFile('images')) {
                $i = 0;
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'image_path' => $path,
                        'is_primary' => $i === 0,
                        'sort_order' => $i++,
                    ]);
                }
            }


        });

        return redirect()->route('seller.products.index')->with('success', 'Produk dibuat.');
    }

    public function edit(Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        $categories = Category::orderBy('name')->get();
        $product->load('images');
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);

        $data = $request->validate([
            'name' => ['required','string','max:180'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'description' => ['nullable','string'],
            'price' => ['required','integer','min:0'],
            'discount_type' => ['nullable','in:none,percent,amount'],
            'discount_value' => ['nullable','integer','min:0'],
            'weight_grams' => ['required','integer','min:1'],
            'stock' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'images.*' => ['nullable','image','max:2048'],
            'variants' => ['nullable', 'array'],

            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.name' => ['required_with:variants', 'string', 'max:120'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],

        });

        return back()->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);

        $product->load('images');
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }


    {
        $variants = collect($variants)
            ->filter(fn ($row) => trim((string) ($row['name'] ?? '')) !== '')
            ->values();

        if ($variants->isEmpty()) {
            if ($variantsPayloadExists) {
                $product->variants()->delete();

            }
            return;
        }

        $keepIds = [];


            $variant = null;
            if (!empty($row['id'])) {
                $variant = ProductVariant::where('product_id', $product->id)->where('id', (int)$row['id'])->first();
            }
            if (!$variant) {
                $variant = new ProductVariant(['product_id' => $product->id]);
            }

            $sku = trim((string) ($row['sku'] ?? ''));

                'price' => isset($row['price']) && $row['price'] !== '' ? (int) $row['price'] : null,
                'stock' => (int) ($row['stock'] ?? 0),
                'is_active' => true,
            ]);
            $variant->save();

            $keepIds[] = $variant->id;
        }

        $product->variants()->whereNotIn('id', $keepIds)->delete();
        $product->update(['stock' => (int) $product->variants()->sum('stock')]);
    }
}
