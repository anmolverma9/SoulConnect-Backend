<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Admin\AdminAuditService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * List all users with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->with(['profile', 'photos', 'wallet', 'activeSubscription.plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return ApiResponse::paginated(UserResource::collection($users));
    }

    /**
     * Get detailed user info
     */
    public function show(User $user): JsonResponse
    {
        $user->load([
            'profile',
            'photos',
            'preferences',
            'wallet.transactions',
            'activeSubscription.plan',
            'activeBoost',
            'devices',
        ]);

        return ApiResponse::success(new UserResource($user));
    }

    /**
     * Update user status (active, suspended, banned, deleted)
     */
    public function updateStatus(UpdateUserStatusRequest $request, User $user): JsonResponse
    {
        $oldStatus = $user->status;
        $newStatus = $request->input('status');

        $user->update(['status' => $newStatus]);

        if (in_array($newStatus, ['banned', 'suspended', 'deleted'])) {
            $user->tokens()->delete();
        }

        $admin = $request->user();
        $this->auditService->log(
            $admin,
            'user_status_update',
            'User',
            $user->id,
            ['old_status' => $oldStatus, 'new_status' => $newStatus, 'reason' => $request->input('reason')],
            $request
        );

        return ApiResponse::success(new UserResource($user), "User status changed to {$newStatus}.");
    }
}
