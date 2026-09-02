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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('preferred_gender', 32)->default('all');
            $table->unsignedTinyInteger('minimum_age')->default(18);
            $table->unsignedTinyInteger('maximum_age')->default(50);
            $table->unsignedSmallInteger('maximum_distance')->default(50)->comment('Distance in kilometers');
            $table->string('relationship_goal', 50)->nullable();
            $table->json('interests')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
