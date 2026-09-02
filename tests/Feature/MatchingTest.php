<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\MatchModel;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MatchingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Notification::fake();
    }

    public function test_user_can_like_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $response = $this->actingAs($userA)->postJson("/api/v1/profiles/{$userB->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'is_match' => false,
                ],
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $userA->id,
            'liked_user_id' => $userB->id,
        ]);
    }

    public function test_mutual_like_creates_match_and_conversation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // User B likes User A first
        Like::create(['user_id' => $userB->id, 'liked_user_id' => $userA->id]);

        // User A likes User B (Mutual Match trigger)
        $response = $this->actingAs($userA)->postJson("/api/v1/profiles/{$userB->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'is_match' => true,
                ],
            ]);

        $userOneId = min($userA->id, $userB->id);
        $userTwoId = max($userA->id, $userB->id);

        $this->assertDatabaseHas('matches', [
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
            'status' => 'active',
        ]);

        $match = MatchModel::first();
        $this->assertDatabaseHas('conversations', [
            'match_id' => $match->id,
        ]);
    }
}
