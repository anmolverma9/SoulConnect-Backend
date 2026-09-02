<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Safety\RegisterDeviceRequest;
use App\Models\Device;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Register or update FCM device token
     */
    public function store(RegisterDeviceRequest $request): JsonResponse
    {
        $device = Device::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'device_id' => $request->input('device_id'),
            ],
            [
                'platform' => $request->input('platform', 'android'),
                'device_name' => $request->input('device_name'),
                'app_version' => $request->input('app_version'),
                'fcm_token' => $request->input('fcm_token'),
                'last_seen_at' => Carbon::now(),
            ]
        );

        return ApiResponse::success($device, 'Device registered successfully.');
    }

    /**
     * Remove device registration
     */
    public function destroy(Request $request, string $deviceId): JsonResponse
    {
        Device::where('user_id', $request->user()->id)
            ->where('device_id', $deviceId)
            ->delete();

        return ApiResponse::success(null, 'Device unregistered.');
    }
}
