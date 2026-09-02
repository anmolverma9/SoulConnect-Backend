<?php

namespace Tests\Feature;

use App\Models\Call;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CallTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Notification::fake();
    }

    public function test_user_with_balance_can_initiate_accept_and_end_call(): void
    {
        $caller = User::factory()->create();
        $receiver = User::factory()->create();

        // Credit caller wallet with coins for call
        app(WalletService::class)->credit($caller, 100, 'purchase');

        // Initiate call
        $initiateResponse = $this->actingAs($caller)->postJson('/api/v1/calls', [
            'receiver_id' => $receiver->id,
            'type' => 'voice',
        ]);

        $initiateResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'requested',
                    'type' => 'voice',
                ],
            ]);

        $callId = $initiateResponse->json('data.id');

        // Accept call
        $acceptResponse = $this->actingAs($receiver)->postJson("/api/v1/calls/{$callId}/accept");
        $acceptResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'accepted',
                ],
            ]);

        // End call
        $endResponse = $this->actingAs($caller)->postJson("/api/v1/calls/{$callId}/end");
        $endResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'ended',
                ],
            ]);

        $this->assertDatabaseHas('calls', [
            'id' => $callId,
            'status' => 'ended',
        ]);
    }
}
