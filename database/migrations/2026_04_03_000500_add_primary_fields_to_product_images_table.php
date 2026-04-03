<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_images', 'image_path')) {
                $table->string('image_path')->nullable()->after('path');
            }

            if (!Schema::hasColumn('product_images', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('image_path');
                $table->index(['product_id', 'is_primary']);
            }
        });

        DB::table('product_images')->whereNull('image_path')->update(['image_path' => DB::raw('path')]);

        $firstIds = DB::table('product_images')
            ->selectRaw('MIN(id) as id')
            ->groupBy('product_id')
            ->pluck('id')
            ->all();

        if ($firstIds) {
            DB::table('product_images')->whereIn('id', $firstIds)->update(['is_primary' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'is_primary')) {
                $table->dropIndex(['product_id', 'is_primary']);
                $table->dropColumn('is_primary');
            }
            if (Schema::hasColumn('product_images', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
