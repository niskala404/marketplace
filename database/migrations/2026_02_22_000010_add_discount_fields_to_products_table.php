<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('discount_type', 20)->default('none')->after('price');
            $table->unsignedBigInteger('discount_value')->default(0)->after('discount_type');
            $table->index(['discount_type', 'discount_value']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['discount_type', 'discount_value']);
            $table->dropColumn(['discount_type', 'discount_value']);
        });
    }
};
