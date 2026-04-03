<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->foreignId('product_variant_option_id')->constrained('product_variant_options')->cascadeOnDelete();
            $table->string('value', 80); // contoh: merah, L
            $table->timestamps();

            $table->unique(['product_variant_id', 'product_variant_option_id'], 'variant_item_unique');
            $table->index(['product_variant_option_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_items');
    }
};
