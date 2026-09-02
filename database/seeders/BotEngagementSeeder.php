<?php

namespace Database\Seeders;

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
            ['name' => 'Riya Sen', 'age' => 22, 'city' => 'Mumbai', 'occupation' => 'Model & Creator', 'bio' => 'Bandra cafes, Marine drive breeze & spicy conversations ✨ Say hi!', 'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Khushi Kapoor', 'age' => 24, 'city' => 'Bangalore', 'occupation' => 'Software Engineer', 'bio' => 'Tech by day, party & deep talks by night 🎧 Tell me your wildest story!', 'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Simran Kaur', 'age' => 23, 'city' => 'Chandigarh', 'occupation' => 'Fitness Influencer', 'bio' => 'Gedi route, gym & bold vibes only 🔥 Are you ready to handle me?', 'photo' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Tanya Singhal', 'age' => 22, 'city' => 'Pune', 'occupation' => 'Content Creator', 'bio' => 'Spontaneous road trips & late night cravings 🍕 Let\'s see where this goes!', 'photo' => 'https://images.unsplash.com/photo-1589156280159-27698a70f29e?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Ishani Mukherjee', 'age' => 24, 'city' => 'Kolkata', 'occupation' => 'Stylist', 'bio' => 'Vintage music, chai & unfiltered late-night chats 🌙', 'photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Shruti Agarwal', 'age' => 23, 'city' => 'Jaipur', 'occupation' => 'Interior Designer', 'bio' => 'Sweet on the outside, a little wild on the inside 🙈✨', 'photo' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Radhika Joshi', 'age' => 25, 'city' => 'Ahmedabad', 'occupation' => 'Architect', 'bio' => 'Passionate about design, espresso & confident men ☕💋', 'photo' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Sonam Chawla', 'age' => 24, 'city' => 'Gurgaon', 'occupation' => 'Marketing Lead', 'bio' => 'Cyberhub nights, good wine & bold connections 🍷✨', 'photo' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Natasha Deshmukh', 'age' => 23, 'city' => 'Mumbai', 'occupation' => 'Dance Instructor', 'bio' => 'Salsa dancer 💃 Looking for someone who knows how to keep up!', 'photo' => 'https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Kritika Nair', 'age' => 22, 'city' => 'Kochi', 'occupation' => 'Photographer', 'bio' => 'Monsoon rains, beach vibes & private video calls 🌧️📹', 'photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Payal Kashyap', 'age' => 24, 'city' => 'Lucknow', 'occupation' => 'Journalist', 'bio' => 'Nawabi charm, shayari & late night secrets 🌙', 'photo' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Mehak Gill', 'age' => 23, 'city' => 'Amritsar', 'occupation' => 'Dentist', 'bio' => 'Cute smile, dangerous eyes 😉 Let\'s talk!', 'photo' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Kashish Malhotra', 'age' => 22, 'city' => 'Noida', 'occupation' => 'Graphic Artist', 'bio' => 'Don\'t be boring! Tell me your biggest guilty pleasure 🙈🔥', 'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Sanjana Reddy', 'age' => 24, 'city' => 'Hyderabad', 'occupation' => 'Data Scientist', 'bio' => 'Biryani, high heels & deep late-night chemistry 🥂', 'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Avani Patel', 'age' => 23, 'city' => 'Surat', 'occupation' => 'Event Planner', 'bio' => 'Living life on the edge. Looking for a partner in crime 😈', 'photo' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Divya Saxena', 'age' => 25, 'city' => 'Bhopal', 'occupation' => 'Lawyer', 'bio' => 'Sharp mind, softer heart. Win me over if you can ✨', 'photo' => 'https://images.unsplash.com/photo-1524502397800-2eeaad7c3fe5?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Muskaan Bhatia', 'age' => 22, 'city' => 'Delhi NCR', 'occupation' => 'Makeup Artist', 'bio' => 'Glamour, late night ice cream & fun vibes 🍨💋', 'photo' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Shilpa Rao', 'age' => 24, 'city' => 'Bangalore', 'occupation' => 'Brand Strategist', 'bio' => 'I love men who take the lead.. text me first! 😉', 'photo' => 'https://images.unsplash.com/photo-1548142813-c348350df52b?w=800&auto=format&fit=crop&q=80'],
            ['name' => 'Jasleen Sandhu', 'age' => 23, 'city' => 'Mohali', 'occupation' => 'Radio Host', 'bio' => 'Voice that will keep you up all night 🎧 Call me?', 'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80']
        ];

        foreach ($botProfiles as $index => $data) {
            $email = 'bot_' . Str::slug($data['name'], '_') . '_' . ($index + 1) . '@soulconnect.app';

            $user = User::firstOrCreate(
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

            // Primary profile photo with unique photo URL
            ProfilePhoto::updateOrCreate(
                ['user_id' => $user->id, 'is_primary' => true],
                [
                    'path' => 'photos/bot_avatar_' . ($index + 1) . '.jpg',
                    'url' => $data['photo'],
                    'status' => 'approved',
                    'sort_order' => 1
                ]
            );
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
            ['category' => 'flirty', 'body' => "Uff that smile.. kisi ko maar daloge kya? 😉"],
            ['category' => 'flirty', 'body' => "Can we skip the small talk? What's your wildest fantasy? ✨"],
            ['category' => 'flirty', 'body' => "Room me akele ho ya call pe baat ho sakti hai? 🙈"],
            ['category' => 'flirty', 'body' => "Your DP is doing things to my mind.. just saying 😉🔥"],
            ['category' => 'flirty', 'body' => "I was about to sleep, but then I saw your profile and got distracted 🙈"],
            ['category' => 'flirty', 'body' => "Are you always this charming or practicing on me? 💋"],
            ['category' => 'flirty', 'body' => "Do you believe in love at first swipe or should I message again? 😉"],
            ['category' => 'flirty', 'body' => "Kuch to baat hai tum me.. curiosity badh rahi hai ✨"],
            ['category' => 'flirty', 'body' => "Late night talks hit different when the connection is real 🔥"],

            // 👋 Normal / Cute Indian Openers
            ['category' => 'greeting', 'body' => "Hey! Just saw your profile and had to say hi ✨"],
            ['category' => 'greeting', 'body' => "Hey handsome 😊 Weekend pe kya plans hai?"],
            ['category' => 'greeting', 'body' => "Chai lover ho ya coffee person? ☕"],
            ['category' => 'greeting', 'body' => "Hey! Loved your profile vibe ✨ Are you from Delhi or Mumbai?"],
            ['category' => 'greeting', 'body' => "Hi! You have such a charming smile in your photos 😊"],
            ['category' => 'greeting', 'body' => "Hey! Glad we connected here ✨ What brings you to Soul Connect?"],
            ['category' => 'greeting', 'body' => "Hi there! What kind of music are you into lately? 🎧"],
            ['category' => 'greeting', 'body' => "Hey handsome! How is your week going so far? 🌸"],
            ['category' => 'greeting', 'body' => "Hi! Tell me your favorite travel destination ✈️"],
            ['category' => 'greeting', 'body' => "Hello! Hope you're having an amazing day 😊"],

            // ❓ Curiosity & Icebreaker Questions
            ['category' => 'question', 'body' => "Are you from around here or recently moved? 📍"],
            ['category' => 'question', 'body' => "What's your ideal Sunday evening plan? 🏖️"],
            ['category' => 'question', 'body' => "What's the best cafe you've visited recently in town?"],
            ['category' => 'question', 'body' => "Tell me one thing that made you smile today 😊"],
            ['category' => 'question', 'body' => "Late night long drives or cozy Netflix nights? 🚗🍿"],
            ['category' => 'question', 'body' => "What's one red flag and one green flag in you? 😉"],
            ['category' => 'question', 'body' => "If we hang out, who is picking the food spot? 🍕"],

            // 💬 Follow-Ups & Call Invitations
            ['category' => 'follow_up', 'body' => "Would love to get to know you better 😊"],
            ['category' => 'follow_up', 'body' => "Are you free to chat for a bit? ✨"],
            ['category' => 'follow_up', 'body' => "Let's catch up whenever you're free! 😊"],
            ['category' => 'follow_up', 'body' => "Drop me a text when you see this 💬"],
            ['category' => 'follow_up', 'body' => "Maybe we can do a quick 1-on-1 video call later? 📹💋"],
            ['category' => 'follow_up', 'body' => "Waiting for your reply.. don't keep a girl waiting! 😉"],
            ['category' => 'follow_up', 'body' => "Talk soon! Have an amazing night ahead 🌸"],
        ];

        foreach ($cannedMessages as $msg) {
            \App\Models\BotCannedMessage::firstOrCreate(
                ['body' => $msg['body']],
                ['category' => $msg['category'], 'is_active' => true]
            );
        }
    }
}
