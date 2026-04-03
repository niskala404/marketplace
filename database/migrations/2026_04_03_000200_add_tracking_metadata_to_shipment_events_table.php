<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipment_events', function (Blueprint $table) {
            if (!Schema::hasColumn('shipment_events', 'event_code')) {
                $table->string('event_code', 40)->nullable()->after('status');
                $table->index(['order_id', 'event_code']);
            }

            if (!Schema::hasColumn('shipment_events', 'location')) {
                $table->string('location', 160)->nullable()->after('description');
            }

            if (!Schema::hasColumn('shipment_events', 'meta')) {
                $table->json('meta')->nullable()->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipment_events', function (Blueprint $table) {
            if (Schema::hasColumn('shipment_events', 'event_code')) {
                $table->dropIndex(['order_id', 'event_code']);
                $table->dropColumn('event_code');
            }

            foreach (['location', 'meta'] as $column) {
                if (Schema::hasColumn('shipment_events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
