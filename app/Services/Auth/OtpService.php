<?php

namespace App\Services\Auth;

use App\Exceptions\ApiException;
use App\Models\OtpVerification;
use App\Notifications\OtpEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class OtpService
{
    /**
     * Generate and dispatch a secure email OTP
     */
    public function requestOtp(string $email, ?string $ipAddress = null): array
    {
        $normalizedEmail = strtolower(trim($email));
        $cooldownSeconds = config('dating.otp.resend_cooldown_seconds', 60);
        $expiryMinutes = config('dating.otp.expiry_minutes', 5);

        // Check if there is a recent pending OTP within the cooldown period
        $recentOtp = OtpVerification::where('email', $normalizedEmail)
            ->whereNull('verified_at')
            ->where('created_at', '>', Carbon::now()->subSeconds($cooldownSeconds))
            ->first();

        if ($recentOtp) {
            $secondsRemaining = $cooldownSeconds - Carbon::now()->diffInSeconds($recentOtp->created_at);
            throw new ApiException(
                "Please wait {$secondsRemaining} seconds before requesting a new verification code.",
                429,
                ['seconds_remaining' => max(1, $secondsRemaining)]
            );
        }

        // Invalidate previous unverified OTPs for this email
        OtpVerification::where('email', $normalizedEmail)
            ->whereNull('verified_at')
            ->delete();

        // Generate cryptographically secure 6-digit numeric OTP
        $plainOtp = (string) random_int(100000, 999999);
        $otpHash = Hash::make($plainOtp);
        $expiresAt = Carbon::now()->addMinutes($expiryMinutes);

        // Save hashed OTP
        $otpRecord = OtpVerification::create([
            'email' => $normalizedEmail,
            'otp_hash' => $otpHash,
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'ip_address' => $ipAddress,
        ]);

        // Send OTP via notification queue
        Notification::route('mail', $normalizedEmail)->notify(new OtpEmailNotification($plainOtp, $expiryMinutes));

        return [
            'expires_at' => $expiresAt->toIso8601String(),
            'cooldown_seconds' => $cooldownSeconds,
        ];
    }

    /**
     * Verify the supplied OTP
     */
    public function verifyOtp(string $email, string $otp): bool
    {
        $normalizedEmail = strtolower(trim($email));
        $maxAttempts = config('dating.otp.max_attempts', 5);

        $record = OtpVerification::where('email', $normalizedEmail)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $record) {
            throw new ApiException('No active verification request found. Please request a new code.', 404);
        }

        if ($record->isExpired()) {
            $record->delete();
            throw new ApiException('The verification code has expired. Please request a new code.', 422);
        }

        if ($record->attempts >= $maxAttempts) {
            $record->delete();
            throw new ApiException('Maximum verification attempts exceeded. Please request a new code.', 429);
        }

        // Increment attempt count
        $record->increment('attempts');

        if (! Hash::check($otp, $record->otp_hash)) {
            $remaining = max(0, $maxAttempts - $record->attempts);
            throw new ApiException("Invalid verification code. {$remaining} attempts remaining.", 422, [
                'attempts_remaining' => $remaining,
            ]);
        }

        // Mark verified and clear record
        $record->update(['verified_at' => Carbon::now()]);

        return true;
    }
}
