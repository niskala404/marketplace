<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add likes & share count to live_streams
        Schema::table('live_streams', function (Blueprint $table) {
            if (!Schema::hasColumn('live_streams', 'like_count')) {
                $table->unsignedInteger('like_count')->default(0)->after('viewer_count');
            }
            if (!Schema::hasColumn('live_streams', 'share_count')) {
                $table->unsignedInteger('share_count')->default(0)->after('like_count');
            }
        });

        // Comments / chat table
        Schema::create('live_stream_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['live_stream_id', 'created_at']);
        });

        // Likes table (one per user per stream)
        Schema::create('live_stream_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['live_stream_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_stream_likes');
        Schema::dropIfExists('live_stream_comments');
        Schema::table('live_streams', function (Blueprint $table) {
            foreach (['like_count', 'share_count'] as $col) {
                if (Schema::hasColumn('live_streams', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
