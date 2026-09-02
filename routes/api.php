<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminCoinPackageController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminReportController;
use App\Http\Controllers\Api\V1\Admin\AdminSettingController;
use App\Http\Controllers\Api\V1\Admin\AdminSubscriptionController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Admin\AdminWalletController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\BoostController;
use App\Http\Controllers\Api\V1\CallController;
use App\Http\Controllers\Api\V1\CoinPackageController;
use App\Http\Controllers\Api\V1\ConversationController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\DiscoveryController;
use App\Http\Controllers\Api\V1\GiftController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\MatchController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PreferenceController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Dating App Backend (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ---------------------------------------------------------------------
    // PUBLIC AUTHENTICATION (EMAIL OTP)
    // ---------------------------------------------------------------------
    Route::prefix('auth')->group(function () {
        Route::post('request-otp', [AuthController::class, 'requestOtp'])->middleware('throttle:otp-request');
        Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:otp-verify');
    });

    // ---------------------------------------------------------------------
    // PUBLIC / CATALOG LOOKUPS
    // ---------------------------------------------------------------------
    Route::get('coin-packages', [CoinPackageController::class, 'index']);
    Route::get('subscription/plans', [SubscriptionController::class, 'plans']);
    Route::get('gifts', [GiftController::class, 'catalog']);

    // ---------------------------------------------------------------------
    // ADMIN AUTHENTICATION
    // ---------------------------------------------------------------------
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:admin');

        Route::middleware(['admin.auth'])->group(function () {
            Route::get('me', [AdminAuthController::class, 'me']);
            Route::post('logout', [AdminAuthController::class, 'logout']);

            // Dashboard
            Route::get('dashboard', [AdminDashboardController::class, 'index']);

            // User moderation
            Route::get('users', [AdminUserController::class, 'index']);
            Route::get('users/{user}', [AdminUserController::class, 'show']);
            Route::patch('users/{user}/status', [AdminUserController::class, 'updateStatus']);
            Route::post('users/{user}/wallet/adjust', [AdminWalletController::class, 'adjust']);

            // Reports
            Route::get('reports', [AdminReportController::class, 'index']);
            Route::patch('reports/{report}', [AdminReportController::class, 'review']);

            // Finance & Subscriptions
            Route::get('wallet-transactions', [AdminWalletController::class, 'transactions']);
            Route::get('subscriptions', [AdminSubscriptionController::class, 'index']);

            // Coin Packages CRUD
            Route::get('coin-packages', [AdminCoinPackageController::class, 'index']);
            Route::post('coin-packages', [AdminCoinPackageController::class, 'store']);
            Route::patch('coin-packages/{package}', [AdminCoinPackageController::class, 'update']);
            Route::delete('coin-packages/{package}', [AdminCoinPackageController::class, 'destroy']);

            // Calls & Gifts
            Route::get('calls', [AdminDashboardController::class, 'calls']);
            Route::get('gifts', [AdminDashboardController::class, 'gifts']);
            Route::post('gifts', [AdminDashboardController::class, 'storeGift']);
            Route::delete('gifts/{gift}', [AdminDashboardController::class, 'destroyGift']);

            // System Settings
            Route::get('settings', [AdminSettingController::class, 'index']);
            Route::patch('settings', [AdminSettingController::class, 'update']);
        });
    });

    // ---------------------------------------------------------------------
    // AUTHENTICATED USER ROUTES
    // ---------------------------------------------------------------------
    Route::middleware(['auth:sanctum', 'user.active', 'throttle:api'])->group(function () {

        // Session & Identity
        Route::get('me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::delete('account', [AccountController::class, 'destroy']);

        // Profile & Photos
        Route::get('profile', [ProfileController::class, 'show']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::post('profile/photos', [ProfileController::class, 'uploadPhoto']);
        Route::delete('profile/photos/{id}', [ProfileController::class, 'deletePhoto']);
        Route::post('profile/photos/{id}/primary', [ProfileController::class, 'setPrimaryPhoto']);

        // Preferences
        Route::get('preferences', [PreferenceController::class, 'show']);
        Route::put('preferences', [PreferenceController::class, 'update']);

        // Discovery & Likes
        Route::get('discover', [DiscoveryController::class, 'discover']);
        Route::post('profiles/{user}/like', [LikeController::class, 'like'])->middleware('throttle:likes');
        Route::post('profiles/{user}/pass', [LikeController::class, 'pass']);
        Route::post('profiles/{user}/super-like', [LikeController::class, 'superLike']);
        Route::get('likes', [LikeController::class, 'getLikes']);

        // Matches
        Route::get('matches', [MatchController::class, 'index']);
        Route::get('matches/{match}', [MatchController::class, 'show']);
        Route::delete('matches/{match}', [MatchController::class, 'destroy']);

        // Chat & Conversations
        Route::get('conversations', [ConversationController::class, 'index']);
        Route::post('conversations', [ConversationController::class, 'store']);
        Route::get('conversations/{conversation}', [ConversationController::class, 'show']);
        Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);
        Route::post('conversations/{conversation}/read', [ConversationController::class, 'read']);
        Route::delete('messages/{message}', [MessageController::class, 'destroy']);

        // Voice & Video Calls
        Route::post('calls', [CallController::class, 'store']);
        Route::get('calls/{call}', [CallController::class, 'show']);
        Route::post('calls/{call}/accept', [CallController::class, 'accept']);
        Route::post('calls/{call}/reject', [CallController::class, 'reject']);
        Route::post('calls/{call}/end', [CallController::class, 'end']);

        // Wallet & Transactions
        Route::get('wallet', [WalletController::class, 'show']);
        Route::get('wallet/transactions', [WalletController::class, 'transactions']);

        // Payment Verification (Google Play)
        Route::post('payments/google-play/verify', [PaymentController::class, 'verifyGooglePlay']);
        Route::get('subscription', [SubscriptionController::class, 'current']);
        Route::post('subscription/google-play/verify', [SubscriptionController::class, 'verifyGooglePlay']);

        // Boosts & Gifts
        Route::post('boosts', [BoostController::class, 'store']);
        Route::get('boosts/active', [BoostController::class, 'active']);
        Route::post('gifts/send', [GiftController::class, 'send']);

        // Notifications & Devices
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::post('devices', [DeviceController::class, 'store']);
        Route::delete('devices/{device}', [DeviceController::class, 'destroy']);

        // Safety, Blocking & Reporting
        Route::get('blocks', [BlockController::class, 'index']);
        Route::post('users/{user}/block', [BlockController::class, 'store']);
        Route::delete('users/{user}/block', [BlockController::class, 'destroy']);
        Route::post('reports', [ReportController::class, 'store']);
    });
});
