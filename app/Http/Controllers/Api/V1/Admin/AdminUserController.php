<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserProfile;
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
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
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
     * Update user details (Name, Bio, Gender, etc.)
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'gender' => 'nullable|string|in:male,female,non_binary,other',
            'bio' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:active,suspended,banned,deleted',
        ]);

        $fullName = null;
        if (!empty($validated['first_name'])) {
            $fullName = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));
        } elseif (!empty($request->input('name'))) {
            $fullName = trim($request->input('name'));
        }

        if ($fullName) {
            $user->name = $fullName;
        }

        if (isset($validated['email'])) {
            $user->email = strtolower(trim($validated['email']));
        }
        if (isset($validated['status'])) {
            $user->status = $validated['status'];
        }
        $user->save();

        if ($user->profile) {
            $profileData = array_filter([
                'name' => $fullName ?? $user->profile->name,
                'gender' => $validated['gender'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'city' => $validated['city'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
            ], fn ($v) => $v !== null);

            $user->profile->update($profileData);
        }

        $admin = $request->user();
        $this->auditService->log(
            $admin,
            'admin_update_user',
            'User',
            $user->id,
            $validated,
            $request
        );

        $user->load(['profile', 'wallet', 'activeSubscription.plan']);

        return ApiResponse::success(new UserResource($user), 'User profile updated successfully.');
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

    /**
     * Delete/Anonymize user account
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $userId = $user->id;
        $user->tokens()->delete();
        $user->devices()->delete();

        $user->update([
            'status' => 'deleted',
            'name' => 'Deleted User',
            'email' => 'deleted_' . $userId . '_' . time() . '@anonymized.local',
        ]);

        if ($user->profile) {
            $user->profile->update([
                'first_name' => 'Deleted',
                'last_name' => 'User',
                'bio' => null,
                'profile_visibility' => 'hidden',
            ]);
        }

        $admin = $request->user();
        $this->auditService->log(
            $admin,
            'admin_delete_user',
            'User',
            $userId,
            ['action' => 'deleted_by_admin'],
            $request
        );

        return ApiResponse::success(null, 'User account has been deleted.');
    }
}
