<?php

use App\Models\Boost;
use App\Models\Call;
use App\Models\Device;
use App\Models\OtpVerification;
use App\Models\Subscription;
use App\Services\Engagement\EngagementService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console & Scheduled Cron Tasks
|--------------------------------------------------------------------------
*/

// Cleanup expired OTP verification requests every 15 minutes
Schedule::call(function () {
    OtpVerification::where('expires_at', '<', Carbon::now())->delete();
})->everyFifteenMinutes()->name('cleanup:otp');

// Mark expired boosts every 5 minutes
Schedule::call(function () {
    Boost::where('status', 'active')
        ->where('expires_at', '<', Carbon::now())
        ->update(['status' => 'expired']);
})->everyFiveMinutes()->name('cleanup:boosts');

// Process expired subscriptions daily
Schedule::call(function () {
    Subscription::whereIn('status', ['active', 'grace_period'])
        ->where('ends_at', '<', Carbon::now())
        ->update(['status' => 'expired']);
})->daily()->name('subscriptions:process-expirations');

// Cancel stale ringing/requested calls after 2 minutes
Schedule::call(function () {
    Call::whereIn('status', ['requested', 'ringing'])
        ->where('created_at', '<', Carbon::now()->subMinutes(2))
        ->update([
            'status' => 'missed',
            'ended_at' => Carbon::now(),
            'billing_status' => 'free',
        ]);
})->everyMinute()->name('cleanup:calls');

// Process inactive user engagement reminders daily at 10:00 UTC
Schedule::call(function () {
    app(EngagementService::class)->processInactiveUsersBatch(3, 100);
})->dailyAt('10:00')->name('engagement:process');

// Clean up stale device tokens inactive for over 90 days
Schedule::call(function () {
    Device::where('last_seen_at', '<', Carbon::now()->subDays(90))->delete();
})->weekly()->name('cleanup:stale-tokens');
