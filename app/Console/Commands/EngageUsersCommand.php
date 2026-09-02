<?php

namespace App\Console\Commands;

use App\Models\BotCannedMessage;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EngageUsersCommand extends Command
{
    protected $signature = 'bots:engage-users {user_id?}';
    protected $description = 'Deliver realistic Indian female chat engagement (up to 12 distinct girls) in database';

    public function handle(): int
    {
        $targetUserId = $this->argument('user_id');

        $query = User::where('is_bot', false)->where('status', 'active');
        if ($targetUserId) {
            $query->where('id', $targetUserId);
        }

        $realUsers = $query->get();
        if ($realUsers->isEmpty()) {
            $this->info('No real users found to engage.');
            return 0;
        }

        $bots = User::where('is_bot', true)->where('status', 'active')->with(['profile', 'photos'])->get();
        if ($bots->isEmpty()) {
            $this->error('No bot profiles found. Run `php artisan db:seed --class=BotEngagementSeeder`');
            return 1;
        }

        $cannedMessages = BotCannedMessage::where('is_active', true)->get();

        foreach ($realUsers as $realUser) {
            // Find which bots this user is ALREADY chatting with
            $existingBotIds = ConversationParticipant::where('user_id', '!=', $realUser->id)
                ->whereIn('conversation_id', function ($q) use ($realUser) {
                    $q->select('conversation_id')
                        ->from('conversation_participants')
                        ->where('user_id', $realUser->id);
                })
                ->pluck('user_id')
                ->toArray();

            $existingBotCount = count($existingBotIds);

            if ($existingBotCount < 12) {
                // Pick 1 new bot that the user has NEVER talked to before
                $availableBots = $bots->whereNotIn('id', $existingBotIds);
                if ($availableBots->isNotEmpty()) {
                    $chosenBot = $availableBots->random();

                    $conversation = Conversation::create([
                        'type' => 'direct',
                        'last_message_at' => Carbon::now(),
                    ]);

                    ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $realUser->id,
                    ]);

                    ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $chosenBot->id,
                    ]);

                    // Pick a bold spicy or sweet opener
                    $opener = $cannedMessages->whereIn('category', ['flirty', 'greeting'])->random();
                    $body = $opener ? $opener->body : "Hey handsome 😊 Loved your vibe! All alone tonight? 😉🔥";

                    $msg = Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_id' => $chosenBot->id,
                        'type' => 'text',
                        'body' => $body,
                        'status' => 'delivered',
                    ]);

                    $conversation->update([
                        'last_message_id' => $msg->id,
                        'last_message_at' => Carbon::now(),
                    ]);

                    $this->info("Delivered NEW chat to {$realUser->name} from {$chosenBot->name} (Total chats: " . ($existingBotCount + 1) . "/12)");
                }
            } else {
                // User already has 12 distinct girl chats -> send a spicy follow-up to an existing chat
                $randomExistingBotId = $existingBotIds[array_rand($existingBotIds)];
                $convId = ConversationParticipant::where('user_id', $realUser->id)
                    ->whereIn('conversation_id', function ($q) use ($randomExistingBotId) {
                        $q->select('conversation_id')
                            ->from('conversation_participants')
                            ->where('user_id', $randomExistingBotId);
                    })
                    ->value('conversation_id');

                if ($convId) {
                    $conv = Conversation::find($convId);
                    $followUp = $cannedMessages->whereIn('category', ['follow_up', 'flirty'])->random();
                    $body = $followUp ? $followUp->body : "Arey reply kab karoge? I'm waiting! 🙈💬";

                    $msg = Message::create([
                        'conversation_id' => $conv->id,
                        'sender_id' => $randomExistingBotId,
                        'type' => 'text',
                        'body' => $body,
                        'status' => 'delivered',
                    ]);

                    $conv->update([
                        'last_message_id' => $msg->id,
                        'last_message_at' => Carbon::now(),
                    ]);

                    $this->info("Delivered FOLLOW-UP to {$realUser->name} in conversation #{$convId}");
                }
            }
        }

        return 0;
    }
}
