<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // status is aligned with order status milestones, but can be more granular later
            $table->string('status', 30); // e.g. paid, processing, shipped, delivered, received, completed
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->timestamp('happened_at');
            $table->timestamps();

            $table->index(['order_id', 'happened_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
