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
            // 🌶️ Spicy / 18+ / Bold Indian Flirty Openers (Hinglish)
            ['category' => 'flirty', 'body' => "Hey handsome.. akele ho ya koi company hai? 😉🔥"],
            ['category' => 'flirty', 'body' => "Sach batao.. real life me bhi itne naughty ho kya? 🙈"],
            ['category' => 'flirty', 'body' => "Late night drive pe chalein? Kuch secrets batane hain 🚗✨"],
            ['category' => 'flirty', 'body' => "Sach sach batao.. single ho ya timepass ke liye aaye ho? 😉"],
            ['category' => 'flirty', 'body' => "Mujhe bold ladke pasand hain jo pehla step lete hain.. are you one? 💋"],
            ['category' => 'flirty', 'body' => "Mummy ko mat batana, par you are totally my type 🙈🔥"],
            ['category' => 'flirty', 'body' => "Late night cravings ho rahi hain.. aur khane ki baat nahi kar rahi 😉"],
            ['category' => 'flirty', 'body' => "Ek quick private video call karein? Sharmao mat 📹💋"],
            ['category' => 'flirty', 'body' => "Aapki photos dekh kar dil me kuch kuch hone laga 😈❤️"],
            ['category' => 'flirty', 'body' => "Secrets rakhna aata hai na? Mere paas kaafi hain 😉🔥"],
            ['category' => 'flirty', 'body' => "Dil chura ke le jaoge ya raat bhar baatein karoge? 💖"],
            ['category' => 'flirty', 'body' => "Aaj raat free ho kya? Achhi company chahiye thi 🌙"],
            ['category' => 'flirty', 'body' => "Bohot hot lag rahe ho sach me.. swipe karte hi dil ruk gaya 🔥"],
            ['category' => 'flirty', 'body' => "Ek baat batao.. midnight chemistry me believe karte ho? ✨"],
            ['category' => 'flirty', 'body' => "Bas sone hi wali thi, par aapka profile dekh ke neend udd gayi 🙈🔥"],
            ['category' => 'flirty', 'body' => "Kuch sweet ya naughty batao.. your choice 😉💋"],

            // 🌸 Sweet / Normal Hinglish Openers
            ['category' => 'greeting', 'body' => "Hey! Kya kar rahe ho? Vibe bohot achhi lagi aapki ✨"],
            ['category' => 'greeting', 'body' => "Hello ji! Kahan se ho aap? 📍"],
            ['category' => 'greeting', 'body' => "Hey handsome! Din kaisa chal raha hai aapka? 😊"],
            ['category' => 'greeting', 'body' => "Smile bohot pyari hai aapki photos me ❤️"],
            ['category' => 'greeting', 'body' => "Chai lover ho ya coffee lover? Important sawaal hai ☕🫖"],
            ['category' => 'greeting', 'body' => "Playlist me kaunsa gaana loop pe chal raha hai abhi? 🎶"],
            ['category' => 'greeting', 'body' => "Hey! Free ho to thodi baatein karein? 🌸"],
            ['category' => 'greeting', 'body' => "Spontaneous road trip chaloge ya cozy movie night? 🎬"],
            ['category' => 'greeting', 'body' => "Aap bohot genuine lag rahe ho.. socha hi bol dun 😊"],
            ['category' => 'greeting', 'body' => "Hi! Mujhse dosti karoge? ✨"],

            // 💬 Follow-ups & Questions (Hinglish)
            ['category' => 'follow_up', 'body' => "Arey reply to karo! Itna kyu bhav kha rahe ho? 🙈💬"],
            ['category' => 'follow_up', 'body' => "Busy ho ya mujhe ignore kar rahe ho? 😉"],
            ['category' => 'follow_up', 'body' => "Aapka WhatsApp number mil sakta hai kya? 🙈📱"],
            ['category' => 'follow_up', 'body' => "Ek quick voice call karein kya agar free ho? 📞🌸"],
            ['category' => 'follow_up', 'body' => "Jab bhi free ho jao to text karna, I'm waiting ✨"],
            ['category' => 'follow_up', 'body' => "Kabhi Mumbai/Delhi aao to coffee pe milte hain ☕😊"],
            ['category' => 'follow_up', 'body' => "Apna favorite romantic song share karo na 🎧❤️"],
            ['category' => 'follow_up', 'body' => "Lagta hai aap bohot busy person ho! Phir bhi text kar diya 🙈"]
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
