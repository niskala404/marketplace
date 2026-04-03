<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'product_variant_id')) {
                $table->foreignId('product_variant_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('product_variants')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('cart_items', 'sku_snapshot')) {
                $table->string('sku_snapshot', 120)->nullable()->after('product_variant_id');
            }
        });

        try {
            DB::statement('ALTER TABLE cart_items DROP INDEX cart_items_cart_id_product_id_unique');
        } catch (\Throwable $e) {
            // index may already be removed in some environments
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['cart_id', 'product_id', 'product_variant_id'], 'cart_items_cart_product_variant_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            try {
                $table->dropUnique('cart_items_cart_product_variant_unique');
            } catch (\Throwable $e) {
                // ignore
            }

            if (Schema::hasColumn('cart_items', 'sku_snapshot')) {
                $table->dropColumn('sku_snapshot');
            }

            if (Schema::hasColumn('cart_items', 'product_variant_id')) {
                $table->dropConstrainedForeignId('product_variant_id');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['cart_id', 'product_id']);
        });
    }
};

