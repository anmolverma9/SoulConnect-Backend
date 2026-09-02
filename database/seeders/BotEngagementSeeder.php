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
            ['name' => 'Ananya Sharma', 'age' => 23, 'city' => 'Mumbai', 'occupation' => 'UI/UX Designer', 'bio' => 'Coffee, beaches & exploring new cafes in Bandra ☕🌊 Let\'s connect!'],
            ['name' => 'Priya Patel', 'age' => 24, 'city' => 'Delhi NCR', 'occupation' => 'Architect', 'bio' => 'Architecture enthusiast & foodie. Looking for someone genuine ✨'],
            ['name' => 'Sneha Verma', 'age' => 22, 'city' => 'Bangalore', 'occupation' => 'Software Engineer', 'bio' => 'Tech by day, music festivals by night 🎧 Tell me your favorite song!'],
            ['name' => 'Riya Sen', 'age' => 25, 'city' => 'Kolkata', 'occupation' => 'Fashion Stylist', 'bio' => 'Living life in aesthetics and colors 🎨 Let\'s see where this goes!'],
            ['name' => 'Pooja Nair', 'age' => 23, 'city' => 'Pune', 'occupation' => 'Content Creator', 'bio' => 'Lover of spontaneous road trips & golden hour sunsets 🌅🚗'],
            ['name' => 'Tanvi Joshi', 'age' => 24, 'city' => 'Jaipur', 'occupation' => 'Interior Designer', 'bio' => 'Chai over coffee any day ☕ Book lover and vintage soul 📖'],
            ['name' => 'Diya Kapoor', 'age' => 22, 'city' => 'Chandigarh', 'occupation' => 'Fitness Trainer', 'bio' => 'Yoga, clean eating & good vibes only 🧘‍♀️ Let\'s hit the gym together!'],
            ['name' => 'Ishita Roy', 'age' => 25, 'city' => 'Hyderabad', 'occupation' => 'Product Manager', 'bio' => 'Biryani lover, startup enthusiast & avid traveler ✈️'],
            ['name' => 'Neha Malhotra', 'age' => 23, 'city' => 'Delhi NCR', 'occupation' => 'Marketing Lead', 'bio' => 'Always ready for late night drives and ice cream runs 🍦🚗'],
            ['name' => 'Simran Kaur', 'age' => 24, 'city' => 'Amritsar', 'occupation' => 'Dentist', 'bio' => 'Smiling is my profession and superpower 😁 Say hi!'],
            ['name' => 'Shreya Gupta', 'age' => 23, 'city' => 'Lucknow', 'occupation' => 'Journalist', 'bio' => 'Stories, poetry and deep midnight conversations 🌙'],
            ['name' => 'Kriti Menon', 'age' => 22, 'city' => 'Kochi', 'occupation' => 'Classical Dancer', 'bio' => 'Art in motion 💃 Love nature trails and cozy monsoon evenings 🌧️'],
            ['name' => 'Natasha Reddy', 'age' => 25, 'city' => 'Bangalore', 'occupation' => 'Data Analyst', 'bio' => 'Solving problems by day, dancing salsa by night 💃🥂'],
            ['name' => 'Aarohi Saxena', 'age' => 24, 'city' => 'Indore', 'occupation' => 'Photographer', 'bio' => 'Capturing candid moments and chasing sunsets 📸✨'],
            ['name' => 'Kavya Mehra', 'age' => 23, 'city' => 'Goa', 'occupation' => 'Event Planner', 'bio' => 'Beach sunsets, acoustic music and good conversations 🏖️'],
            ['name' => 'Avani Singhania', 'age' => 24, 'city' => 'Mumbai', 'occupation' => 'Investment Banker', 'bio' => 'Work hard, travel harder ✈️ Let\'s explore the city together!'],
            ['name' => 'Meera Chawla', 'age' => 22, 'city' => 'Noida', 'occupation' => 'Graphic Artist', 'bio' => 'Illustrator & coffee addict ☕ Let\'s share playlists 🎶'],
            ['name' => 'Tara Deshmukh', 'age' => 25, 'city' => 'Nagpur', 'occupation' => 'Pastry Chef', 'bio' => 'I bake the best chocolate croissants in town 🥐 Want to try?'],
            ['name' => 'Zoya Khan', 'age' => 23, 'city' => 'Bhopal', 'occupation' => 'Lawyer', 'bio' => 'Debating, witty humor and cozy movie nights 🍿'],
            ['name' => 'Sanya Oberoi', 'age' => 24, 'city' => 'Gurugram', 'occupation' => 'HR Specialist', 'bio' => 'Good vibes, cafe hopping and spontaneous weekend getaways ✨']
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
                    'interests' => ['Travel', 'Coffee', 'Music', 'Photography', 'Art'],
                    'is_completed' => true,
                ]
            );

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 500]
            );

            // Dummy primary photo
            ProfilePhoto::updateOrCreate(
                ['user_id' => $user->id, 'is_primary' => true],
                [
                    'file_path' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                    'is_approved' => true,
                    'sort_order' => 1
                ]
            );
        }
    }
}
