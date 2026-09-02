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
        Schema::create('engagement_rules', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 64)->index();
            $table->string('title');
            $table->text('message_template');
            $table->boolean('is_enabled')->default(true)->index();
            $table->unsignedInteger('min_interval_hours')->default(24);
            $table->unsignedSmallInteger('daily_limit')->default(1);
            $table->unsignedSmallInteger('cooldown_hours')->default(24);
            $table->unsignedSmallInteger('priority')->default(1)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('engagement_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rule_id')->nullable()->constrained('engagement_rules')->nullOnDelete();
            $table->string('event_type', 64)->index();
            $table->json('payload')->nullable();
            $table->dateTime('triggered_at')->useCurrent();
            $table->enum('status', ['pending', 'sent', 'skipped', 'failed'])->default('pending')->index();
            $table->timestamps();

            $table->index(['user_id', 'event_type', 'triggered_at']);
            $table->index(['status', 'triggered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_events');
        Schema::dropIfExists('engagement_rules');
    }
};
