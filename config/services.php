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
        'project_id' => env('FIREBASE_PROJECT_ID', 'desi-saheli'),
        'credentials' => env('FIREBASE_CREDENTIALS')
            ? (file_exists(env('FIREBASE_CREDENTIALS'))
                ? env('FIREBASE_CREDENTIALS')
                : (file_exists(base_path(env('FIREBASE_CREDENTIALS')))
                    ? base_path(env('FIREBASE_CREDENTIALS'))
                    : storage_path('desi-saheli-firebase-adminsdk-fbsvc-9f3cdde7d7.json')))
            : (file_exists(storage_path('desi-saheli-firebase-adminsdk-fbsvc-9f3cdde7d7.json'))
                ? storage_path('desi-saheli-firebase-adminsdk-fbsvc-9f3cdde7d7.json')
                : base_path('storage/desi-saheli-firebase-adminsdk-fbsvc-9f3cdde7d7.json')),
    ],

    'spacepay' => [
        'public_key' => env('SPACEPAY_PUBLIC_KEY', 'pk_test_51xxxxxxxxxxxxx'),
        'secret_key' => env('SPACEPAY_SECRET_KEY', '02efwxsjxxxxxxxxxxx'),
        'base_url' => env('SPACEPAY_BASE_URL', 'https://spacepay.in/api/payment/v1'),
    ],
];
