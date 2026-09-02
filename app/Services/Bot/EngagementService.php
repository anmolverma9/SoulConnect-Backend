<?php

namespace App\Services\Bot;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EngagementService
{
    /**
     * Curated Bank of Opener Messages (1,000 combinations across greetings, curiosity questions, and follow-ups)
     */
    protected static array $openers = [
        "Hey! Just saw your profile and had to say hi ✨",
        "Hey handsome 😊 Loved your vibe!",
        "Hello! Are you having a good day?",
        "Hey there! What brings you to Soul Connect? 💫",
        "Hi! You have such a charming smile in your photos 😊",
        "Hey! Are you from around here or visiting?",
        "Hey! Coffee person or late night drives person? ☕🚗",
        "Hi! Loved your profile pictures ✨",
        "Hey! Hope you're having an amazing week so far 😊",
        "Hey there! You look like fun to talk to ✨",
        "Hi! Just couldn't swipe past without dropping a message 😊",
        "Hey! What's your ideal weekend getaway? 🏖️",
        "Hey! Glad we connected here ✨",
        "Hi there! What kind of music are you into lately? 🎧",
        "Hey handsome! How is your day going? 🌸",
        "Hey! You seem really genuine, thought I'd say hello 😊",
        "Hi! Do you believe in instant connections? ✨",
        "Hey! What's the best cafe you've visited recently?",
        "Hey there! Tell me one thing that made you smile today 😊",
        "Hi! Are you ready for some fun conversations? ✨",
    ];

    protected static array $followUps1 = [
        "Would love to get to know you better 😊",
        "Tell me about your favorite travel spot! ✈️",
        "Are you free to chat for a bit? ✨",
        "I was actually looking for someone interesting to talk to 🌸",
        "What are you up to tonight? 😊",
        "I love exploring new food spots, what about you? 🍕",
        "Hope you don't mind me taking the first step! 🙈",
        "Let me know if you want to share playlists 🎶",
        "Tell me what's the best part of your city? 📍",
        "I'm usually shy but your profile caught my attention ✨",
    ];

    protected static array $followUps2 = [
        "Let's catch up whenever you're free! 😊",
        "Drop me a text when you see this 💬",
        "Looking forward to hearing from you! ✨",
        "Maybe we can do a quick video call later if you're up for it? 📹",
        "Talk soon! Have a great day ahead 🌸",
    ];

    /**
     * Dispatch automated welcome engagement messages from 10 to 15 female bot profiles
     */
    public static function engageNewUser(User $newUser): void
    {
        try {
            // Find bot profiles
            $botUsers = User::where('is_bot', true)
                ->where('id', '!=', $newUser->id)
                ->inRandomOrder()
                ->take(rand(10, 15))
                ->get();

            if ($botUsers->isEmpty()) {
                return;
            }

            $now = Carbon::now();

            foreach ($botUsers as $index => $bot) {
                // Check if conversation already exists between this bot and user
                $existingConvId = \App\Models\ConversationParticipant::where('user_id', $newUser->id)
                    ->whereIn('conversation_id', function ($query) use ($bot) {
                        $query->select('conversation_id')
                            ->from('conversation_participants')
                            ->where('user_id', $bot->id);
                    })
                    ->value('conversation_id');

                if ($existingConvId) {
                    $conversation = Conversation::find($existingConvId);
                } else {
                    $conversation = Conversation::create([
                        'type' => 'direct',
                        'last_message_at' => $now->copy()->subMinutes(rand(1, 20)),
                    ]);

                    \App\Models\ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $newUser->id,
                        'role' => 'member',
                    ]);

                    \App\Models\ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $bot->id,
                        'role' => 'member',
                    ]);
                }

                // Determine message count for this bot (1 to 3 messages)
                $messageCount = rand(1, 3);
                $messagesToSend = [
                    self::$openers[array_rand(self::$openers)]
                ];

                if ($messageCount >= 2) {
                    $messagesToSend[] = self::$followUps1[array_rand(self::$followUps1)];
                }
                if ($messageCount >= 3) {
                    $messagesToSend[] = self::$followUps2[array_rand(self::$followUps2)];
                }

                $messageTime = $now->copy()->subMinutes(rand(5, 30));
                $lastMsgId = null;

                foreach ($messagesToSend as $stepIndex => $body) {
                    $messageTime = $messageTime->copy()->addSeconds(rand(15, 60));

                    $msg = Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_id' => $bot->id,
                        'type' => 'text',
                        'body' => $body,
                        'status' => 'sent',
                        'created_at' => $messageTime,
                        'updated_at' => $messageTime,
                    ]);
                    $lastMsgId = $msg->id;
                }

                $conversation->update([
                    'last_message_id' => $lastMsgId,
                    'last_message_at' => $messageTime,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('EngagementService::engageNewUser failed: ' . $e->getMessage());
        }
    }
}
