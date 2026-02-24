<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $sales = FlashSale::latest('id')->paginate(15);
        return view('admin.flash_sales.index', compact('sales'));
    }

    public function create()
    {
        return view('admin.flash_sales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'starts_at' => ['required','date'],
            'ends_at' => ['required','date','after:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);

        $sale = FlashSale::create([
            'name' => $data['name'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'is_active' => (bool)($request->boolean('is_active', true)),
        ]);

        return redirect()->route('admin.flash-sales.edit', $sale)->with('success', 'Flash sale dibuat. Tambahkan produk promo.');
    }

    public function edit(FlashSale $flash_sale)
    {
        $flash_sale->load(['items.product']);
        // for quick product search (approved only)
        $products = Product::query()
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('admin.flash_sales.edit', [
            'sale' => $flash_sale,
            'products' => $products,
        ]);
    }

    public function update(Request $request, FlashSale $flash_sale)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'starts_at' => ['required','date'],
            'ends_at' => ['required','date','after:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);

        $flash_sale->update([
            'name' => $data['name'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'is_active' => (bool)($request->boolean('is_active', false)),
        ]);

        return back()->with('success', 'Flash sale diperbarui.');
    }

    public function destroy(FlashSale $flash_sale)
    {
        $flash_sale->delete();
        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale dihapus.');
    }

    public function addItem(Request $request, FlashSale $flash_sale)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'promo_price' => ['required','integer','min:1'],
            'quota' => ['nullable','integer','min:1'],
            'is_active' => ['nullable','boolean'],
        ]);

        FlashSaleItem::updateOrCreate(
            ['flash_sale_id' => $flash_sale->id, 'product_id' => (int)$data['product_id']],
            [
                'promo_price' => (int)$data['promo_price'],
                'quota' => $data['quota'] !== null ? (int)$data['quota'] : null,
                'is_active' => (bool)($request->boolean('is_active', true)),
            ]
        );

        return back()->with('success', 'Item flash sale ditambahkan/diupdate.');
    }

    public function toggleItem(Request $request, FlashSaleItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);
        return back()->with('success', 'Status item diperbarui.');
    }

    public function deleteItem(Request $request, FlashSaleItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item dihapus.');
    }
}
