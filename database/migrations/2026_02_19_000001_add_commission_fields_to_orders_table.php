<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'platform_fee')) {
                $table->unsignedBigInteger('platform_fee')->default(0)->after('shipping_fee');
            }
            if (!Schema::hasColumn('orders', 'seller_earnings')) {
                $table->unsignedBigInteger('seller_earnings')->default(0)->after('platform_fee');
            }
            if (!Schema::hasColumn('orders', 'commission_percent')) {
                $table->unsignedSmallInteger('commission_percent')->default(0)->after('seller_earnings');
            }
            if (!Schema::hasColumn('orders', 'settled_at')) {
                $table->timestamp('settled_at')->nullable()->after('commission_percent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = ['platform_fee','seller_earnings','commission_percent','settled_at'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('orders', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
