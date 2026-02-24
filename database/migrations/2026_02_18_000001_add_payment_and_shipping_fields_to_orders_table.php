<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Payment (manual transfer)
            $table->string('payment_proof_path')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_proof_path');
            $table->foreignId('payment_verified_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
            $table->timestamp('payment_verified_at')->nullable()->after('payment_verified_by');

            // Shipping
            $table->string('tracking_no')->nullable()->after('shipping_address_snapshot');
            $table->timestamp('shipped_at')->nullable()->after('tracking_no');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_verified_by');
            $table->dropColumn([
                'payment_proof_path',
                'paid_at',
                'payment_verified_at',
                'tracking_no',
                'shipped_at',
            ]);
        });
    }
};
