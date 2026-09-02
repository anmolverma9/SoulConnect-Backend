<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Notification::fake();
    }

    public function test_participant_can_send_and_read_message(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $conversation = Conversation::create(['type' => 'direct']);
        ConversationParticipant::create(['conversation_id' => $conversation->id, 'user_id' => $userA->id]);
        ConversationParticipant::create(['conversation_id' => $conversation->id, 'user_id' => $userB->id]);

        // User A sends message
        $sendResponse = $this->actingAs($userA)->postJson("/api/v1/conversations/{$conversation->id}/messages", [
            'body' => 'Hello there!',
            'type' => 'text',
        ]);

        $sendResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'body' => 'Hello there!',
                    'status' => 'sent',
                ],
            ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Hello there!',
        ]);

        // User B reads messages
        $readResponse = $this->actingAs($userB)->postJson("/api/v1/conversations/{$conversation->id}/read");
        $readResponse->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'status' => 'read',
        ]);
    }
}
