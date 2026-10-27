<?php

namespace App\Services\Notification;

use App\Models\Device;
use App\Models\NotificationModel;
use App\Models\User;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FcmService
{
    protected ?string $cachedAccessToken = null;

    /**
     * Send push notification to all active devices of a user (backward compatible)
     */
    public function sendPushNotification(User $user, string $title, string $body, array $payload = []): void
    {
        $this->sendToUser($user, $title, $body, $payload, true);
    }

    /**
     * Send push notification to a single user and optionally save to in-app notification box
     */
    public function sendToUser(User $user, string $title, string $body, array $payload = [], bool $saveInApp = true): array
    {
        $devices = Device::where('user_id', $user->id)
            ->whereNotNull('fcm_token')
            ->get();

        $sentCount = 0;
        $errors = [];

        foreach ($devices as $device) {
            $result = $this->sendToToken($device, $title, $body, $payload);
            if ($result['success'] ?? false) {
                $sentCount++;
            } else {
                $errors[] = $result['error'] ?? 'Unknown error';
            }
        }

        // Save in-app notification record
        if ($saveInApp) {
            try {
                NotificationModel::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'type' => $payload['type'] ?? 'admin_announcement',
                    'title' => $title,
                    'body' => $body,
                    'data' => $payload,
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                Log::warning("Could not persist in-app notification: " . $e->getMessage());
            }
        }

        return [
            'success' => $sentCount > 0 || $devices->isEmpty(),
            'user_id' => $user->id,
            'devices_targeted' => $devices->count(),
            'devices_sent' => $sentCount,
            'errors' => $errors,
        ];
    }

    /**
     * Send push notification to multiple users
     */
    public function sendToUsers(array|Collection $userIds, string $title, string $body, array $payload = [], bool $saveInApp = true): array
    {
        if ($userIds instanceof Collection) {
            $userIds = $userIds->toArray();
        }

        $users = User::whereIn('id', $userIds)->get();
        $totalDevices = 0;
        $totalSent = 0;
        $userResults = [];

        foreach ($users as $user) {
            $res = $this->sendToUser($user, $title, $body, $payload, $saveInApp);
            $totalDevices += $res['devices_targeted'];
            $totalSent += $res['devices_sent'];
            $userResults[$user->id] = $res;
        }

        return [
            'success' => true,
            'total_users' => $users->count(),
            'total_devices' => $totalDevices,
            'total_sent' => $totalSent,
            'details' => $userResults,
        ];
    }

    /**
     * Send push notification broadcast to a topic (e.g., 'all' members)
     * All devices subscribed to this topic will receive the notification simultaneously.
     */
    public function sendToTopic(string $topic, string $title, string $body, array $payload = []): array
    {
        $projectId = config('services.firebase.project_id', 'desi-saheli');
        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            Log::info("FCM mock topic broadcast: topic='{$topic}', title='{$title}'", $payload);
            return [
                'success' => true,
                'mock' => true,
                'topic' => $topic,
                'message' => 'FCM broadcast simulated (OAuth token or credentials not configured for local env).',
            ];
        }

        try {
            $httpClient = new HttpClient();
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $stringPayload = array_map(function ($val) {
                return is_array($val) || is_object($val) ? json_encode($val) : (string) $val;
            }, $payload);

            $message = [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $stringPayload,
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'desi_saheli_general',
                            'default_sound' => true,
                            'default_vibrate_timings' => true,
                        ],
                    ],
                ],
            ];

            $response = $httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode((string) $response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                Log::info("FCM Topic Broadcast Success to '{$topic}': " . ($responseBody['name'] ?? 'OK'));
                return [
                    'success' => true,
                    'topic' => $topic,
                    'message_id' => $responseBody['name'] ?? null,
                    'response' => $responseBody,
                ];
            }

            Log::error("FCM Topic Broadcast Failed ({$statusCode}): " . json_encode($responseBody));
            return [
                'success' => false,
                'topic' => $topic,
                'status_code' => $statusCode,
                'error' => $responseBody['error']['message'] ?? 'FCM Request failed',
            ];
        } catch (\Exception $e) {
            Log::error("FCM Topic Send Error: " . $e->getMessage());
            return [
                'success' => false,
                'topic' => $topic,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Dispatch FCM v1 HTTP request to a device token
     */
    public function sendToToken(Device|string $deviceOrToken, string $title, string $body, array $payload = []): array
    {
        $device = is_string($deviceOrToken) ? null : $deviceOrToken;
        $fcmToken = is_string($deviceOrToken) ? $deviceOrToken : $deviceOrToken->fcm_token;

        if (empty($fcmToken)) {
            return ['success' => false, 'error' => 'Device has no FCM token'];
        }

        $projectId = config('services.firebase.project_id', 'desi-saheli');
        $accessToken = $this->getAccessToken();

        // If credentials missing or in test, log and mock
        if (! $accessToken) {
            $identifier = $device ? "device {$device->device_id}" : "token " . substr($fcmToken, 0, 10);
            Log::info("FCM mock dispatch to {$identifier}: {$title} - {$body}", $payload);
            return ['success' => true, 'mock' => true];
        }

        try {
            $httpClient = new HttpClient();
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            // Format string data payload for Android
            $stringPayload = array_map(function ($val) {
                return is_array($val) || is_object($val) ? json_encode($val) : (string) $val;
            }, $payload);

            $message = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $stringPayload,
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'desi_saheli_general',
                            'default_sound' => true,
                            'default_vibrate_timings' => true,
                        ],
                    ],
                ],
            ];

            $response = $httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode((string) $response->getBody(), true);

            if ($statusCode === 404 || $statusCode === 410) {
                // Token expired/invalid, clear it
                if ($device) {
                    Log::info("FCM Token expired for device {$device->id}, clearing.");
                    $device->update(['fcm_token' => null]);
                }
                return ['success' => false, 'error' => 'FCM Token expired/unregistered'];
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'message_id' => $responseBody['name'] ?? null,
                ];
            }

            return [
                'success' => false,
                'status_code' => $statusCode,
                'error' => $responseBody['error']['message'] ?? 'FCM Request failed',
            ];
        } catch (\Exception $e) {
            Log::error("FCM Send Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Retrieve or generate Google OAuth2 Access Token for FCM v1 API
     */
    protected function getAccessToken(): ?string
    {
        if (config('app.env') === 'testing') {
            return null;
        }

        $credentialsPath = config('services.firebase.credentials');

        if (empty($credentialsPath) || ! file_exists($credentialsPath)) {
            return null;
        }

        // Return from in-memory cache if available
        if ($this->cachedAccessToken) {
            return $this->cachedAccessToken;
        }

        try {
            return Cache::remember('fcm_v1_access_token', 3300, function () use ($credentialsPath) {
                $client = new GoogleClient();
                $client->setAuthConfig($credentialsPath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $tokenInfo = $client->fetchAccessTokenWithAssertion();
                $token = $tokenInfo['access_token'] ?? null;
                $this->cachedAccessToken = $token;
                return $token;
            });
        } catch (\Exception $e) {
            Log::warning("FCM: Error generating Google OAuth token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get statistics for push notifications and devices
     */
    public function getDeviceStats(): array
    {
        $totalDevices = Device::count();
        $activeTokens = Device::whereNotNull('fcm_token')->count();
        $androidCount = Device::where('platform', 'android')->count();
        $iosCount = Device::where('platform', 'ios')->count();
        $totalUsers = User::count();
        $usersWithDevices = Device::whereNotNull('fcm_token')->distinct('user_id')->count('user_id');

        return [
            'total_devices' => $totalDevices,
            'active_tokens' => $activeTokens,
            'android_devices' => $androidCount,
            'ios_devices' => $iosCount,
            'total_users' => $totalUsers,
            'users_with_devices' => $usersWithDevices,
        ];
    }
}
