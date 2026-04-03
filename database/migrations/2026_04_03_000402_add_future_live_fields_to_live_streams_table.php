<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            if (!Schema::hasColumn('live_streams', 'viewer_count')) {
                $table->unsignedInteger('viewer_count')->default(0)->after('ended_at');
            }
            if (!Schema::hasColumn('live_streams', 'chat_enabled')) {
                $table->boolean('chat_enabled')->default(true)->after('viewer_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            foreach (['viewer_count', 'chat_enabled'] as $column) {
                if (Schema::hasColumn('live_streams', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
