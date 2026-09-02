<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('duration', ['monthly', 'quarterly', 'yearly'])->index();
            $table->unsignedSmallInteger('duration_days')->default(30);
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('google_product_id')->unique();
            $table->json('features')->nullable();
            $table->unsignedInteger('coins_per_period')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->string('purchase_token', 512)->unique();
            $table->string('order_id')->nullable()->index();
            $table->enum('status', [
                'pending',
                'active',
                'cancelled',
                'expired',
                'paused',
                'grace_period'
            ])->default('pending')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable()->index();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['initial', 'renewal', 'refund', 'bonus'])->default('initial');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->string('purchase_token', 512)->nullable();
            $table->string('order_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
