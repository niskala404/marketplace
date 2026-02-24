<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });

        Schema::create('user_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_wallet_id')->constrained('user_wallets')->cascadeOnDelete();
            $table->string('type', 40); // refund_credit, adjustment
            $table->bigInteger('amount'); // positive=credit, negative=debit
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_wallet_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wallet_transactions');
        Schema::dropIfExists('user_wallets');
    }
};
