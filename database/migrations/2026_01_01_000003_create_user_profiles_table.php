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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->date('date_of_birth')->nullable()->index();
            $table->enum('gender', ['male', 'female', 'non_binary', 'other'])->nullable()->index();
            $table->text('bio')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('country', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
            $table->unsignedSmallInteger('height')->nullable()->comment('Height in centimeters');
            $table->json('interests')->nullable();
            $table->string('relationship_goal', 50)->nullable()->index();
            $table->enum('profile_visibility', ['public', 'hidden', 'incognito'])->default('public')->index();
            $table->boolean('is_completed')->default(false)->index();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index(['gender', 'profile_visibility']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
