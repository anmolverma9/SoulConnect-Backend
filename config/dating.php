<?php

return [
    'otp' => [
        'length' => 6,
        'expiry_minutes' => (int) env('OTP_EXPIRY_MINUTES', 5),
        'max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 5),
        'resend_cooldown_seconds' => (int) env('OTP_RESEND_COOLDOWN_SECONDS', 60),
    ],

    'defaults' => [
        'initial_wallet_balance' => 0,
        'boost_cost' => (int) env('BOOST_COIN_COST', 50),
        'boost_duration_minutes' => (int) env('BOOST_DURATION_MINUTES', 30),
        'super_like_cost' => (int) env('SUPER_LIKE_COIN_COST', 10),
        'call_cost_per_minute' => (int) env('CALL_COIN_COST_PER_MINUTE', 20),
        'min_age' => 18,
        'max_distance_km' => 100,
        'free_daily_likes' => 50,
    ],

    'upload' => [
        'max_photo_size_kb' => 10240, // 10MB
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],
        'max_photos_per_user' => 6,
    ],
];
