<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Delivery milestones (future-proof for tracking integration)
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('received_at')->nullable()->after('delivered_at');
            $table->timestamp('completed_at')->nullable()->after('received_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivered_at', 'received_at', 'completed_at']);
        });
    }
};
