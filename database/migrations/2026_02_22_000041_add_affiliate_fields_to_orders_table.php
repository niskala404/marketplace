<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'affiliate_code')) {
                $table->string('affiliate_code', 20)->nullable()->after('voucher_code');
            }
            if (!Schema::hasColumn('orders', 'affiliate_user_id')) {
                $table->foreignId('affiliate_user_id')->nullable()->after('affiliate_code')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'affiliate_user_id')) {
                $table->dropConstrainedForeignId('affiliate_user_id');
            }
            if (Schema::hasColumn('orders', 'affiliate_code')) {
                $table->dropColumn('affiliate_code');
            }
        });
    }
};
