<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')
                ->constrained('product_variants')->nullOnDelete();
        });

        // Replace unique constraint to include variant
        Schema::table('cart_items', function (Blueprint $table) {
            // MySQL can re-use the (cart_id, product_id) unique index to enforce
            // the foreign keys on cart_id/product_id. Dropping it can fail with:
            // "Cannot drop index ... needed in a foreign key constraint".
            // To avoid that, drop the FKs first, change the unique index, then re-add the FKs.

            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);

            $table->dropUnique(['cart_id', 'product_id']);
            $table->unique(['cart_id', 'product_id', 'product_variant_id']);

            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Same reasoning as in up(): avoid dropping an index that MySQL is
            // using to support a foreign key.
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);

            $table->dropUnique(['cart_id', 'product_id', 'product_variant_id']);
            $table->unique(['cart_id', 'product_id']);

            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            $table->dropConstrainedForeignId('product_variant_id');
        });
    }
};
