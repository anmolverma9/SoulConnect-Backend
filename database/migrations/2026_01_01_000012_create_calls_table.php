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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->nullOnDelete();
            $table->enum('type', ['voice', 'video'])->default('voice')->index();
            $table->enum('status', [
                'requested',
                'ringing',
                'accepted',
                'rejected',
                'missed',
                'cancelled',
                'ended',
                'failed'
            ])->default('requested')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedInteger('coin_cost')->default(0);
            $table->enum('billing_status', ['pending', 'billed', 'refunded', 'free'])->default('pending')->index();
            $table->string('channel_name')->nullable()->unique();
            $table->json('signaling_data')->nullable();
            $table->timestamps();

            $table->index(['caller_id', 'status']);
            $table->index(['receiver_id', 'status']);
            $table->index(['created_at', 'status']);
        });

        Schema::create('call_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['caller', 'receiver'])->default('caller');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->unique(['call_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_participants');
        Schema::dropIfExists('calls');
    }
};
