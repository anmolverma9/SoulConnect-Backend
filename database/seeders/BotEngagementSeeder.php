<?php

namespace Database\Seeders;

use App\Models\BotCannedMessage;
use App\Models\ProfilePhoto;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BotEngagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $botProfiles = [
            ['name' => 'Aaradhya Sharma', 'age' => 23, 'city' => 'Delhi NCR', 'occupation' => 'Fashion Designer', 'bio' => 'Coffee, late night drives & exploring Hauz Khas ☕🚗 Let\'s connect!', 'photo' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Riya Sen', 'age' => 22, 'city' => 'Mumbai', 'occupation' => 'Model & Creator', 'bio' => 'Bandra cafes, Marine drive breeze & spicy conversations ✨ Say hi!', 'photo' => 'https://images.unsplash.com/photo-1597223557154-721c1cecc4b0?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Khushi Kapoor', 'age' => 24, 'city' => 'Bangalore', 'occupation' => 'Software Engineer', 'bio' => 'Tech by day, party & deep talks by night 🎧 Tell me your wildest story!', 'photo' => 'https://images.unsplash.com/photo-1609137144827-0c6759c9918b?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Simran Kaur', 'age' => 23, 'city' => 'Chandigarh', 'occupation' => 'Fitness Influencer', 'bio' => 'Gedi route, gym & bold vibes only 🔥 Are you ready to handle me?', 'photo' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Tanya Singhal', 'age' => 22, 'city' => 'Pune', 'occupation' => 'Content Creator', 'bio' => 'Spontaneous road trips & late night cravings 🍕 Let\'s see where this goes!', 'photo' => 'https://images.unsplash.com/photo-1621784563330-caee0b138a00?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Ishani Mukherjee', 'age' => 24, 'city' => 'Kolkata', 'occupation' => 'Stylist', 'bio' => 'Vintage music, chai & unfiltered late-night chats 🌙', 'photo' => 'https://images.unsplash.com/photo-1567186937675-a5131c8a89ea?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Shruti Agarwal', 'age' => 23, 'city' => 'Jaipur', 'occupation' => 'Interior Designer', 'bio' => 'Sweet on the outside, a little wild on the inside 🙈✨', 'photo' => 'https://images.unsplash.com/photo-1618721405821-80ebc4b63d26?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Radhika Joshi', 'age' => 25, 'city' => 'Ahmedabad', 'occupation' => 'Architect', 'bio' => 'Passionate about design, espresso & confident men ☕💋', 'photo' => 'https://images.unsplash.com/photo-1614289371518-722f2615943d?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Sonam Chawla', 'age' => 24, 'city' => 'Gurgaon', 'occupation' => 'Marketing Lead', 'bio' => 'Cyberhub nights, good wine & bold connections 🍷✨', 'photo' => 'https://images.unsplash.com/photo-1623091410901-00e2d2689add?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Natasha Deshmukh', 'age' => 23, 'city' => 'Mumbai', 'occupation' => 'Dance Instructor', 'bio' => 'Salsa dancer 💃 Looking for someone who knows how to keep up!', 'photo' => 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Kritika Nair', 'age' => 22, 'city' => 'Kochi', 'occupation' => 'Photographer', 'bio' => 'Monsoon rains, beach vibes & private video calls 🌧️📹', 'photo' => 'https://images.unsplash.com/photo-1616766098956-c81f12114571?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Payal Kashyap', 'age' => 24, 'city' => 'Lucknow', 'occupation' => 'Journalist', 'bio' => 'Nawabi charm, shayari & late night secrets 🌙', 'photo' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Mehak Gill', 'age' => 23, 'city' => 'Amritsar', 'occupation' => 'Dentist', 'bio' => 'Cute smile, dangerous eyes 😉 Let\'s talk!', 'photo' => 'https://images.unsplash.com/photo-1589156280159-27698a70f29e?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Kashish Malhotra', 'age' => 22, 'city' => 'Noida', 'occupation' => 'Graphic Artist', 'bio' => 'Don\'t be boring! Tell me your biggest guilty pleasure 🙈🔥', 'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Sanjana Reddy', 'age' => 24, 'city' => 'Hyderabad', 'occupation' => 'Data Scientist', 'bio' => 'Biryani, high heels & deep late-night chemistry 🥂', 'photo' => 'https://images.unsplash.com/photo-1604004555489-723a93d6ce74?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Avani Patel', 'age' => 23, 'city' => 'Surat', 'occupation' => 'Event Planner', 'bio' => 'Living life on the edge. Looking for a partner in crime 😈', 'photo' => 'https://images.unsplash.com/photo-1592621385612-4d7129426394?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Divya Saxena', 'age' => 25, 'city' => 'Bhopal', 'occupation' => 'Lawyer', 'bio' => 'Sharp mind, softer heart. Win me over if you can ✨', 'photo' => 'https://images.unsplash.com/photo-1563306406-e66174fa3787?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Muskaan Bhatia', 'age' => 22, 'city' => 'Delhi NCR', 'occupation' => 'Makeup Artist', 'bio' => 'Glamour, late night ice cream & fun vibes 🍨💋', 'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Shilpa Rao', 'age' => 24, 'city' => 'Bangalore', 'occupation' => 'Brand Strategist', 'bio' => 'I love men who take the lead.. text me first! 😉', 'photo' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Jasleen Sandhu', 'age' => 23, 'city' => 'Mohali', 'occupation' => 'Radio Host', 'bio' => 'Voice that will keep you up all night 🎧 Call me?', 'photo' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80']
        ];

        foreach ($botProfiles as $index => $data) {
            $email = 'bot_' . Str::slug($data['name'], '_') . '_' . ($index + 1) . '@soulconnect.app';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'status' => 'active',
                    'is_bot' => true,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => Carbon::now(),
                    'profile_completed_at' => Carbon::now(),
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $data['name'],
                    'date_of_birth' => Carbon::now()->subYears($data['age'])->subMonths(rand(1, 10))->toDateString(),
                    'gender' => 'female',
                    'city' => $data['city'],
                    'country' => 'India',
                    'occupation' => $data['occupation'],
                    'bio' => $data['bio'],
                    'interests' => ['Travel', 'Coffee', 'Late Night Drives', 'Music', 'Fitness'],
                    'is_completed' => true,
                ]
            );

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 500]
            );

            // Clean previous photos & update primary profile photo with distinct photo URL
            ProfilePhoto::where('user_id', $user->id)->delete();
            ProfilePhoto::create([
                'user_id' => $user->id,
                'path' => 'photos/bot_avatar_' . ($index + 1) . '.jpg',
                'url' => $data['photo'],
                'is_primary' => true,
                'status' => 'approved',
                'sort_order' => 1
            ]);
        }

        $cannedMessages = [
            // 🌶️ Spicy / 18+ / Bold Indian Flirty Openers
            ['category' => 'flirty', 'body' => "Hey handsome.. all alone tonight or got company? 😉🔥"],
            ['category' => 'flirty', 'body' => "Are you as naughty in real life as you look in your photos? 🙈"],
            ['category' => 'flirty', 'body' => "Coffee is fine, but what about a late night drive and secrets? 🚗✨"],
            ['category' => 'flirty', 'body' => "Sach batao.. single ho ya just looking for fun? 😉"],
            ['category' => 'flirty', 'body' => "I love bold guys who take control.. are you one? 💋"],
            ['category' => 'flirty', 'body' => "Mummy ko mat batana, but you are totally my type 🙈🔥"],
            ['category' => 'flirty', 'body' => "Late night cravings.. and no I don't mean food 😉"],
            ['category' => 'flirty', 'body' => "A quick private video call? Don't be shy 📹💋"],
            ['category' => 'flirty', 'body' => "Hey trouble.. what's your wildest fantasy? Tell me in DM 😈"],
            ['category' => 'flirty', 'body' => "You have such tempting lips in your picture.. just saying 🙈"],
            ['category' => 'flirty', 'body' => "Are you good at keeping secrets? Because I have a few 😉🔥"],
            ['category' => 'flirty', 'body' => "Dil leke jaoge ya raat bhar baatein karoge? 💖"],
            ['category' => 'flirty', 'body' => "Bandra side rehte ho kya? Let's catch up tonight 🍸✨"],
            ['category' => 'flirty', 'body' => "I don't usually message first, but damn you look hot! 🔥"],
            ['category' => 'flirty', 'body' => "Are you free later tonight? I need good company 🌙"],
            ['category' => 'flirty', 'body' => "Can you handle a girl who loves to take risks? 😈💋"],
            ['category' => 'flirty', 'body' => "Ek baat batao.. do you believe in midnight chemistry? ✨"],
            ['category' => 'flirty', 'body' => "You look like someone who knows how to treat a girl right 🌸😉"],
            ['category' => 'flirty', 'body' => "I was just about to sleep, but your profile woke me up 🙈🔥"],
            ['category' => 'flirty', 'body' => "Tell me something dirty or sweet.. your choice 😉"],

            // 🌸 Sweet / Normal Indian Openers
            ['category' => 'greeting', 'body' => "Hey! Saw your profile and loved your vibe ✨"],
            ['category' => 'greeting', 'body' => "Hi there! How is your week going so far? 😊"],
            ['category' => 'greeting', 'body' => "Hey handsome! What's your favourite weekend cafe? ☕"],
            ['category' => 'greeting', 'body' => "Hello! Are you from around Delhi/Mumbai or visiting? 📍"],
            ['category' => 'greeting', 'body' => "Hey! You have such a pleasant smile in your photos 😊"],
            ['category' => 'greeting', 'body' => "Hi! Coffee lover or chai lover? Important question ☕🫖"],
            ['category' => 'greeting', 'body' => "Hey! What kind of music is on your playlist right now? 🎶"],
            ['category' => 'greeting', 'body' => "Hi there! Tell me one thing that made you smile today 🌸"],
            ['category' => 'greeting', 'body' => "Hey! Spontaneous road trips or cozy movie nights? 🎬"],
            ['category' => 'greeting', 'body' => "Hey! Hope you are having an amazing evening ✨"],

            // 💬 Follow-ups & Questions (Indian context)
            ['category' => 'follow_up', 'body' => "Arey reply kab karoge? I'm waiting! 🙈💬"],
            ['category' => 'follow_up', 'body' => "Busy ho ya ignore kar rahe ho? 😉"],
            ['category' => 'follow_up', 'body' => "Tell me about your favorite travel spot in India! 🏔️🏖️"],
            ['category' => 'follow_up', 'body' => "Are you free to do a quick voice call later? 📞🌸"],
            ['category' => 'follow_up', 'body' => "Drop me a message whenever you get free ✨"],
            ['category' => 'follow_up', 'body' => "Sach batao, what made you join Soul Connect? 💫"],
            ['category' => 'follow_up', 'body' => "I'm planning a trip next month, need suggestions! ✈️"],
            ['category' => 'follow_up', 'body' => "Would love to catch up for coffee sometime ☕😊"],
            ['category' => 'follow_up', 'body' => "Hope your day was as charming as you! 🌸"],
            ['category' => 'follow_up', 'body' => "Let's share playlists.. send me your favorite track 🎧"]
        ];

        BotCannedMessage::truncate();
        foreach ($cannedMessages as $msg) {
            BotCannedMessage::create([
                'category' => $msg['category'],
                'body' => $msg['body'],
                'is_active' => true,
            ]);
        }
    }
}
