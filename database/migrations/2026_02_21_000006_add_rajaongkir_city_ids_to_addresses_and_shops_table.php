<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedInteger('rajaongkir_city_id')->nullable()->after('city');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedInteger('origin_city_id')->nullable()->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('rajaongkir_city_id');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('origin_city_id');
        });
    }
};
