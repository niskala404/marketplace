<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // buyer
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            $table->enum('status', [
                'submitted',
                'seller_approved',
                'seller_rejected',
                'admin_approved',
                'admin_rejected',
                'buyer_shipped',
                'seller_received',
                'refunded',
                'cancelled',
            ])->default('submitted');

            $table->string('reason');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('requested_amount')->default(0);
            $table->unsignedBigInteger('approved_amount')->default(0);

            $table->text('seller_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->json('evidence_paths')->nullable();

            $table->string('return_tracking_no')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('seller_responded_at')->nullable();
            $table->timestamp('admin_decided_at')->nullable();
            $table->timestamp('buyer_shipped_at')->nullable();
            $table->timestamp('seller_received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->unique(['order_id']);
            $table->index(['shop_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
