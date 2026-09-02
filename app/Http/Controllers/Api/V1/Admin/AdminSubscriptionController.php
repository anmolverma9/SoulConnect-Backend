<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    /**
     * List user subscriptions
     */
    public function index(Request $request): JsonResponse
    {
        $query = Subscription::query()->with(['user.profile', 'plan', 'transactions']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(20);

        return ApiResponse::paginated(SubscriptionResource::collection($subscriptions));
    }
}
