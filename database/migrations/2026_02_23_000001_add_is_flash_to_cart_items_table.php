<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'is_flash')) {
                return;
            }

            if (Schema::hasColumn('cart_items', 'variant_id')) {
                $table->boolean('is_flash')->default(false)->after('variant_id');
            } else {
                $table->boolean('is_flash')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'is_flash')) {
                $table->dropColumn('is_flash');
            }
        });
    }
};