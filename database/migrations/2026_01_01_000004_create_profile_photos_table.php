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
        Schema::create('profile_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('path');
            $table->string('url')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved')->index();
            $table->timestamps();

            $table->index(['user_id', 'is_primary']);
            $table->index(['user_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_photos');
    }
};
