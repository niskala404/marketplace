<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('promo_price');
            $table->unsignedInteger('quota')->nullable();
            $table->unsignedInteger('sold')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['flash_sale_id','product_id']);
            $table->index(['is_active','product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
