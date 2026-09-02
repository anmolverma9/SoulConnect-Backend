<?php

namespace App\Services\Auth;

use App\Exceptions\ApiException;
use App\Models\Device;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserProfile;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Authenticate or register a user after OTP verification and issue a Sanctum token
     */
    public function authenticateWithOtp(string $email, array $deviceData = []): array
    {
        $normalizedEmail = strtolower(trim($email));

        $result = DB::transaction(function () use ($normalizedEmail, $deviceData) {
            $user = User::firstOrCreate(
                ['email' => $normalizedEmail],
                [
                    'status' => 'active',
                    'email_verified_at' => Carbon::now(),
                    'last_login_at' => Carbon::now(),
                    'last_active_at' => Carbon::now(),
                ]
            );

            // Ensure profile exists
            if (! $user->profile) {
                UserProfile::create([
                    'user_id' => $user->id,
                    'profile_visibility' => 'public',
                ]);
            }

            // Ensure preferences exist
            if (! $user->preferences) {
                UserPreference::create([
                    'user_id' => $user->id,
                    'preferred_gender' => 'all',
                    'minimum_age' => 18,
                    'maximum_age' => 50,
                    'maximum_distance' => 50,
                ]);
            }

            // Ensure wallet exists
            if (! $user->wallet) {
                Wallet::create([
                    'user_id' => $user->id,
                    'balance' => config('dating.defaults.initial_wallet_balance', 0),
                ]);
            }

            if ($user->isBanned()) {
                throw new ApiException('Your account has been banned due to terms violations.', 403);
            }

            if ($user->isSuspended()) {
                throw new ApiException('Your account is temporarily suspended.', 403);
            }

            $user->update([
                'last_login_at' => Carbon::now(),
                'last_active_at' => Carbon::now(),
                'email_verified_at' => $user->email_verified_at ?? Carbon::now(),
            ]);

            // Register or update device
            if (! empty($deviceData['device_id'])) {
                Device::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'device_id' => $deviceData['device_id'],
                    ],
                    [
                        'platform' => $deviceData['platform'] ?? 'android',
                        'device_name' => $deviceData['device_name'] ?? null,
                        'app_version' => $deviceData['app_version'] ?? null,
                        'fcm_token' => $deviceData['fcm_token'] ?? null,
                        'last_seen_at' => Carbon::now(),
                    ]
                );
            }

            // Generate token
            $deviceName = $deviceData['device_name'] ?? 'Android Client';
            $token = $user->createToken($deviceName)->plainTextToken;

            return [
                'user' => $user->load(['profile', 'photos', 'preferences', 'wallet']),
                'token' => $token,
                'is_new_user' => $user->wasRecentlyCreated,
            ];
        });

        return $result;
    }

    /**
     * Logout user by revoking current access token
     */
    public function logout(User $user, ?string $deviceId = null): void
    {
        $user->currentAccessToken()?->delete();

        if ($deviceId) {
            Device::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->update(['fcm_token' => null]);
        }
    }

    /**
     * Soft delete/anonymize user account and revoke all tokens
     */
    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->devices()->delete();

            // Mark account as deleted and anonymize personal identifiers
            $user->update([
                'status' => 'deleted',
                'name' => 'Deleted User',
                'email' => 'deleted_' . $user->id . '_' . time() . '@anonymized.local',
                'profile_completed_at' => null,
            ]);

            if ($user->profile) {
                $user->profile->update([
                    'name' => 'Deleted User',
                    'bio' => null,
                    'city' => null,
                    'country' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'occupation' => null,
                    'education' => null,
                    'interests' => null,
                    'profile_visibility' => 'hidden',
                ]);
            }
        });
    }
}
