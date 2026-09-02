<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_block_and_unblock_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Block user B
        $blockResponse = $this->actingAs($userA)->postJson("/api/v1/users/{$userB->id}/block", [
            'reason' => 'Inappropriate behavior',
        ]);

        $blockResponse->assertStatus(200);
        $this->assertDatabaseHas('blocks', [
            'blocker_id' => $userA->id,
            'blocked_id' => $userB->id,
        ]);

        // Unblock user B
        $unblockResponse = $this->actingAs($userA)->deleteJson("/api/v1/users/{$userB->id}/block");
        $unblockResponse->assertStatus(200);

        $this->assertDatabaseMissing('blocks', [
            'blocker_id' => $userA->id,
            'blocked_id' => $userB->id,
        ]);
    }

    public function test_user_can_report_violation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $reportResponse = $this->actingAs($userA)->postJson('/api/v1/reports', [
            'reported_id' => $userB->id,
            'reason' => 'Spamming',
            'details' => 'Sending unsolicited spam links.',
        ]);

        $reportResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $userA->id,
            'reported_id' => $userB->id,
            'reason' => 'Spamming',
            'status' => 'pending',
        ]);
    }
}
