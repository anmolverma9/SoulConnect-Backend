<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\AdminUser;
use App\Services\Admin\AdminAuditService;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * Admin login
     */
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $admin = AdminUser::where('email', $request->input('email'))->first();

        if (! $admin || ! Hash::check($request->input('password'), $admin->password)) {
            return ApiResponse::error('Invalid email or password.', 401);
        }

        if (! $admin->is_active) {
            return ApiResponse::forbidden('Your admin account has been deactivated.');
        }

        $admin->update(['last_login_at' => Carbon::now()]);
        $token = $admin->createToken('Admin Panel', ['admin'])->plainTextToken;

        $this->auditService->log($admin, 'admin_login', 'AdminUser', $admin->id, ['email' => $admin->email], $request);

        return ApiResponse::success([
            'token' => $token,
            'admin' => new AdminUserResource($admin),
        ], 'Admin logged in successfully.');
    }

    /**
     * Get current authenticated admin
     */
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(new AdminUserResource($request->user('admin')));
    }

    /**
     * Admin logout
     */
    public function logout(Request $request): JsonResponse
    {
        $admin = $request->user('admin');
        $admin->currentAccessToken()?->delete();

        $this->auditService->log($admin, 'admin_logout', 'AdminUser', $admin->id, [], $request);

        return ApiResponse::success(null, 'Admin logged out.');
    }
}
