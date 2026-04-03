<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy(Product $product, ProductImage $image)
    {
        abort_if($product->shop_id !== auth()->user()->shop->id, 403);
        abort_if((int)$image->product_id !== (int)$product->id, 404);

        Storage::disk('public')->delete($image->path);
        $wasPrimary = (bool) $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $fallback = $product->images()->orderBy('sort_order')->first();
            if ($fallback) {
                $fallback->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Gambar dihapus.');
    }
}
