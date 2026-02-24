<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shop_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0);
            $table->timestamps();

            $table->unique('shop_id');
        });

        Schema::create('shop_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_wallet_id')->constrained('shop_wallets')->cascadeOnDelete();
            $table->string('type', 40); // order_release, payout_paid, adjustment
            $table->bigInteger('amount'); // positive=credit, negative=debit
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payout_id')->nullable()->constrained()->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['shop_wallet_id', 'type']);
        });

        Schema::create('platform_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40); // platform_fee, adjustment
            $table->bigInteger('amount');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['type']);
        });

        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('status', 20)->default('held'); // held|released|refunded
            $table->timestamp('held_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique('order_id');
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrows');
        Schema::dropIfExists('platform_wallet_transactions');
        Schema::dropIfExists('shop_wallet_transactions');
        Schema::dropIfExists('shop_wallets');
    }
};
