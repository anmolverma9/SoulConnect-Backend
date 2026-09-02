<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
        protected AuthService $authService
    ) {}

    /**
     * Request 6-digit email OTP
     */
    public function requestOtp(RequestOtpRequest $request): JsonResponse
    {
        $result = $this->otpService->requestOtp($request->input('email'), $request->ip());

        return ApiResponse::success($result, 'Verification code sent to your email.');
    }

    /**
     * Verify email OTP and authenticate/register
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $this->otpService->verifyOtp($request->input('email'), $request->input('otp'));

        $authData = $this->authService->authenticateWithOtp(
            $request->input('email'),
            $request->only(['device_id', 'platform', 'device_name', 'app_version', 'fcm_token'])
        );

        return ApiResponse::success([
            'token' => $authData['token'],
            'is_new_user' => $authData['is_new_user'],
            'user' => new UserResource($authData['user']),
        ], 'Successfully authenticated.');
    }

    /**
     * Return authenticated user profile and details
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load([
            'profile',
            'photos',
            'preferences',
            'wallet',
            'activeSubscription.plan',
            'activeBoost',
        ]);

        return ApiResponse::success(new UserResource($user));
    }

    /**
     * Logout and revoke Sanctum access token
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user(), $request->input('device_id'));

        return ApiResponse::success(null, 'Successfully logged out.');
    }
}
