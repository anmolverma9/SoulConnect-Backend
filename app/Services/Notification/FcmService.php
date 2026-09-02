<?php

namespace App\Services\Notification;

use App\Models\Device;
use App\Models\User;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send push notification to all active devices of a user
     */
    public function sendPushNotification(User $user, string $title, string $body, array $payload = []): void
    {
        $devices = Device::where('user_id', $user->id)
            ->whereNotNull('fcm_token')
            ->get();

        if ($devices->isEmpty()) {
            return;
        }

        foreach ($devices as $device) {
            $this->sendToToken($device, $title, $body, $payload);
        }
    }

    /**
     * Dispatch FCM v1 HTTP request to a device token
     */
    public function sendToToken(Device $device, string $title, string $body, array $payload = []): void
    {
        $fcmToken = $device->fcm_token;
        $projectId = config('services.firebase.project_id');
        $credentialsPath = config('services.firebase.credentials');

        // If in test or credentials missing, log and return
        if (empty($credentialsPath) || ! file_exists($credentialsPath) || config('app.env') === 'testing') {
            Log::info("FCM mock dispatch to device {$device->device_id}: {$title} - {$body}", $payload);
            return;
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'] ?? null;

            if (! $accessToken) {
                Log::warning('FCM: Unable to obtain Google OAuth access token.');
                return;
            }

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
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
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
            if ($statusCode === 404 || $statusCode === 410) {
                // Token expired/invalid, clear it
                Log::info("FCM Token expired for device {$device->id}, clearing.");
                $device->update(['fcm_token' => null]);
            }
        } catch (\Exception $e) {
            Log::error("FCM Send Error: " . $e->getMessage());
        }
    }
}
