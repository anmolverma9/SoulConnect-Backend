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
        Schema::create('coin_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('coins');
            $table->unsignedInteger('bonus_coins')->default(0);
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('google_product_id')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('coin_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('coin_packages')->nullOnDelete();
            $table->string('product_id')->index();
            $table->string('purchase_token', 512)->unique();
            $table->string('order_id')->nullable()->index();
            $table->unsignedInteger('coins');
            $table->enum('status', ['pending', 'verified', 'failed', 'refunded'])->default('pending')->index();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_purchases');
        Schema::dropIfExists('coin_packages');
    }
};
