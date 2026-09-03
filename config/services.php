<?php

return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'google_play' => [
        'package_name' => env('GOOGLE_PLAY_PACKAGE_NAME', 'com.datingapp.android'),
        'service_account_json' => env('GOOGLE_PLAY_SERVICE_ACCOUNT_JSON'),
    ],

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'credentials' => env('FIREBASE_CREDENTIALS'),
    ],

    'spacepay' => [
        'public_key' => env('SPACEPAY_PUBLIC_KEY', 'pk_test_51xxxxxxxxxxxxx'),
        'secret_key' => env('SPACEPAY_SECRET_KEY', '02efwxsjxxxxxxxxxxx'),
        'base_url' => env('SPACEPAY_BASE_URL', 'https://spacepay.in/api/payment/v1'),
    ],
];
