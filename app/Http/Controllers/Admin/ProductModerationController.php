<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductModerationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        if (!in_array($status, ['pending','approved','rejected'], true)) {
            $status = 'pending';
        }

        $products = Product::query()
            ->with(['shop.user','category'])
            ->where('approval_status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.moderation.index', compact('products','status'));
    }

    public function show(Product $product)
    {
        $product->load(['shop.user','images','category']);
        return view('admin.products.moderation.show', compact('product'));
    }

    public function approve(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'rejected_reason' => null,
        ]);

        return back()->with('success', 'Produk disetujui.');
    }

    public function reject(Request $request, Product $product)
    {
        $data = $request->validate([
            'reason' => ['required','string','max:500'],
        ]);

        $product->update([
            'approval_status' => 'rejected',
            'rejected_reason' => $data['reason'],
        ]);

        return back()->with('success', 'Produk ditolak.');
    }
}
