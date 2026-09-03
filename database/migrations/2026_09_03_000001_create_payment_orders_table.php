<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_id')->unique();
            $table->string('gateway')->default('spacepay');
            $table->string('gateway_order_id')->nullable();
            $table->enum('type', ['coin_package', 'vip_subscription'])->default('coin_package');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->unsignedInteger('coins_to_credit')->default(0);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending')->index();
            $table->text('payment_url')->nullable();
            $table->string('bank_txn_id')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
