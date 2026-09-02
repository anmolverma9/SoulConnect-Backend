<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Delete user account permanently / anonymize
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->authService->deleteAccount($request->user());

        return ApiResponse::success(null, 'Your account has been deleted successfully.');
    }
}
