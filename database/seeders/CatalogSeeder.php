<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // ====== SETTING JUMLAH DATA ======
        $sellerCount   = (int) env('SEED_SELLERS', 30);
        $productsEach  = (int) env('SEED_PRODUCTS_PER_SELLER', 80);
        $imagesPerProd = (int) env('SEED_IMAGES_PER_PRODUCT', 3);

        // ====== KATEGORI (mirip marketplace besar) ======
        $categoryNames = [
            'Elektronik','Handphone & Aksesoris','Komputer & Laptop','Gaming',
            'Fashion Pria','Fashion Wanita','Sepatu','Tas','Jam Tangan',
            'Kecantikan','Kesehatan','Ibu & Anak','Bayi','Rumah Tangga',
            'Dapur','Furniture','Otomotif','Motor','Alat Tulis','Buku',
            'Hobi','Olahraga','Outdoor','Makanan & Minuman','Sembako',
            'Pet Care','Perlengkapan Kantor','Musik','Kamera','Aksesoris',
            'Perhiasan','Kerajinan','Voucher & Tiket','Mainan','Tool & Hardware',
            'Dekorasi','Perawatan Diri','Laundry','Travel',
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $slug = Str::slug($name);
            $categories[] = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // ====== BIKIN SELLER + TOKO ======
        $sellers = collect();
        for ($i = 1; $i <= $sellerCount; $i++) {
            $email = "seller{$i}@ilmishop.test";

            $seller = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Seller {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    'is_active' => true,
                ]
            );

            $shopName = "Toko Seller {$i}";
            $shopSlug = Str::slug($shopName);

            // pastikan slug unik
            $uniqueSlug = $shopSlug;
            $n = 1;
            while (Shop::where('slug', $uniqueSlug)->exists()) {
                $n++;
                $uniqueSlug = $shopSlug.'-'.$n;
            }

            Shop::firstOrCreate(
                ['user_id' => $seller->id],
                [
                    'name' => $shopName,
                    'slug' => $uniqueSlug,
                    'description' => "Toko demo {$i} untuk ilmishop",
                    'is_active' => true,
                ]
            );

            $sellers->push($seller);
        }

        // ====== MASTER NAMA PRODUK ======
        $adjectives = ['Premium','Original','Best Seller','Limited','New','Classic','Eco','Pro','Smart','Mini','Max'];
        $items = [
            'Headset Bluetooth','Earbuds TWS','Powerbank 20000mAh','Charger Fast',
            'Kabel Data USB','Mouse Wireless','Keyboard Mechanical','Monitor 24 inch',
            'SSD 1TB','Flashdisk 64GB','Smartwatch','Casing HP','Tempered Glass',
            'Kaos Oversize','Hoodie','Kemeja Flanel','Jaket Bomber','Celana Chino',
            'Celana Jeans','Dress','Hijab Pashmina','Sepatu Sneakers','Sandal',
            'Tas Selempang','Tas Ransel','Topi','Kaos Kaki',
            'Skincare Serum','Facial Wash','Sunscreen','Lip Tint','Body Lotion',
            'Set Pisau Dapur','Wajan Anti Lengket','Botol Minum','Rak Serbaguna',
            'Lampu LED','Sprei','Handuk','Gorden',
            'Kopi Arabica','Teh','Madu','Snack Keripik','Coklat','Sambal',
            'Action Figure','Puzzle','Bola Futsal','Raket Badminton','Matras Yoga',
        ];

        $descTemplates = [
            "Produk berkualitas, siap pakai. Cocok untuk kebutuhan harian.",
            "Harga terbaik, stok terbatas. Garansi toko.",
            "Material bagus, nyaman dipakai. Bisa COD.",
            "Rekomendasi pembeli! Packing aman, kirim cepat.",
        ];

        $categoriesCount = count($categories);

        // Tentukan nama kolom image (path atau image_path)
        $imageCol = null;
        if (Schema::hasTable('product_images')) {
            if (Schema::hasColumn('product_images', 'path')) $imageCol = 'path';
            elseif (Schema::hasColumn('product_images', 'image_path')) $imageCol = 'image_path';
        }

        foreach ($sellers as $seller) {
            $shop = Shop::where('user_id', $seller->id)->first();
            if (!$shop) continue;

            for ($p = 1; $p <= $productsEach; $p++) {
                $cat = $categories[random_int(0, $categoriesCount - 1)];

                $baseName = $items[array_rand($items)];
                $adj = $adjectives[array_rand($adjectives)];
                $name = "{$baseName} {$adj} {$p}";

                $slugBase = Str::slug($name);
                $slug = $slugBase;
                $n = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $n++;
                    $slug = $slugBase.'-'.$n;
                }

                $price  = random_int(10_000, 2_500_000);
                $stock  = random_int(0, 200);
                $weight = random_int(100, 5000);

                $data = [
                    'shop_id' => $shop->id,
                    'category_id' => $cat->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $descTemplates[array_rand($descTemplates)],
                    'price' => $price,
                    'stock' => $stock,
                    'is_active' => true,
                ];

                if (Schema::hasColumn('products', 'weight_grams')) $data['weight_grams'] = $weight;
                if (Schema::hasColumn('products', 'approval_status')) $data['approval_status'] = 'approved';
                if (Schema::hasColumn('products', 'sold_count')) $data['sold_count'] = random_int(0, 500);

                $product = Product::create($data);

                // ====== Gambar produk (dibuat beneran) ======
                if ($imageCol && class_exists(ProductImage::class)) {
                    $countImg = max(1, $imagesPerProd);
                    for ($i = 1; $i <= $countImg; $i++) {
                        $imgPath = $this->makePlaceholderImage($product->name." {$i}");

                        $imgData = [
                            'product_id' => $product->id,
                            $imageCol => $imgPath,
                        ];

                        if (Schema::hasColumn('product_images', 'is_primary')) {
                            $imgData['is_primary'] = ($i === 1);
                        }

                        ProductImage::create($imgData);
                    }
                }

                // ====== Varian (opsional) ======
                if (class_exists(ProductVariant::class) && Schema::hasTable('product_variants')) {
                    if (random_int(1, 100) <= 40) {
                        $variantSets = [
                            ['S','M','L','XL'],
                            ['Hitam','Putih','Navy','Abu'],
                            ['64GB','128GB','256GB'],
                        ];
                        $set = $variantSets[array_rand($variantSets)];
                        $varCount = random_int(2, min(4, count($set)));

                        for ($v = 0; $v < $varCount; $v++) {
                            $vName = $set[$v];
                            $vSku  = strtoupper(Str::random(3)).'-'.$product->id.'-'.$vName;

                            $vData = ['product_id' => $product->id];

                            if (Schema::hasColumn('product_variants', 'name'))  $vData['name'] = $vName;
                            if (Schema::hasColumn('product_variants', 'sku'))   $vData['sku'] = $vSku;
                            if (Schema::hasColumn('product_variants', 'stock')) $vData['stock'] = random_int(0, 80);
                            if (Schema::hasColumn('product_variants', 'price')) $vData['price'] = max(1000, $price + random_int(-5000, 15000));

                            if (count($vData) >= 2) {
                                ProductVariant::create($vData);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Buat gambar placeholder JPG (tanpa internet) dan simpan ke storage/app/public
     * Return: path relatif untuk asset('storage/'.$path)
     */
    private function makePlaceholderImage(string $text, int $w = 900, int $h = 900): string
    {
        // Butuh GD extension
        if (!function_exists('imagecreatetruecolor')) {
            // fallback: return path dummy (tidak error)
            return 'products/placeholder.jpg';
        }

        $dir = storage_path('app/public/products');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = 'products/'.Str::slug(Str::limit($text, 40, '')).'-'.Str::random(6).'.jpg';
        $path = storage_path('app/public/'.$file);

        $im = imagecreatetruecolor($w, $h);

        // background lembut random
        $bg = imagecolorallocate($im, rand(200, 245), rand(200, 245), rand(200, 245));
        imagefilledrectangle($im, 0, 0, $w, $h, $bg);

        // garis dekor tipis
        $line = imagecolorallocate($im, rand(150, 180), rand(150, 180), rand(150, 180));
        imagerectangle($im, 25, 25, $w-25, $h-25, $line);

        // teks
        $textColor = imagecolorallocate($im, rand(20, 60), rand(20, 60), rand(20, 60));
        $shadow = imagecolorallocate($im, 255, 255, 255);

        $line1 = mb_strimwidth($text, 0, 28, '…');
        $line2 = 'ilmishop demo';

        // font built-in GD (tanpa ttf)
        imagestring($im, 5, 40, (int)($h/2 - 25), $line1, $shadow);
        imagestring($im, 5, 41, (int)($h/2 - 24), $line1, $textColor);

        imagestring($im, 4, 40, (int)($h/2 + 15), $line2, $shadow);
        imagestring($im, 4, 41, (int)($h/2 + 16), $line2, $textColor);

        imagejpeg($im, $path, 88);
        imagedestroy($im);

        return $file;
    }
}