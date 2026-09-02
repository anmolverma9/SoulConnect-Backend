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
    protected $description = 'Seed authentic Indian bot conversations and messages in database for active users';

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

        $bots = User::where('is_bot', true)->where('status', 'active')->get();
        if ($bots->isEmpty()) {
            $this->error('No bot profiles found in database. Run `php artisan db:seed --class=BotEngagementSeeder` first.');
            return 1;
        }

        $cannedMessages = BotCannedMessage::where('is_active', true)->get();

        foreach ($realUsers as $realUser) {
            // Pick 3-5 random bots for this user
            $selectedBots = $bots->random(min($bots->count(), rand(3, 5)));

            foreach ($selectedBots as $bot) {
                // Ensure 1 Conversation thread per person
                $existingConvId = ConversationParticipant::where('user_id', $realUser->id)
                    ->whereIn('conversation_id', function ($q) use ($bot) {
                        $q->select('conversation_id')
                            ->from('conversation_participants')
                            ->where('user_id', $bot->id);
                    })
                    ->value('conversation_id');

                if ($existingConvId) {
                    $conversation = Conversation::find($existingConvId);
                } else {
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
                        'user_id' => $bot->id,
                    ]);
                }

                // Pick an opener message
                $opener = $cannedMessages->whereIn('category', ['flirty', 'greeting'])->random();
                $body = $opener ? $opener->body : "Hey handsome 😊 Loved your vibe! All alone tonight? 😉🔥";

                $msg = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $bot->id,
                    'type' => 'text',
                    'body' => $body,
                    'status' => 'delivered',
                ]);

                $conversation->update([
                    'last_message_id' => $msg->id,
                    'last_message_at' => Carbon::now(),
                ]);

                $this->info("Engaged {$realUser->name} with bot {$bot->name}: '{$body}'");
            }
        }

        $this->info('Engagement completed successfully!');
        return 0;
    }
}
