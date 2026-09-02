<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\VerifySubscriptionRequest;
use App\Http\Resources\SubscriptionPlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Services\Subscription\EntitlementService;
use App\Services\Subscription\SubscriptionService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected EntitlementService $entitlementService
    ) {}

    /**
     * Get active subscription plans
     */
    public function plans(): JsonResponse
    {
        $plans = $this->subscriptionService->getActivePlans();

        return ApiResponse::success(SubscriptionPlanResource::collection($plans));
    }

    /**
     * Get current user active subscription and entitlements
     */
    public function current(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $this->entitlementService->getActiveSubscription($user);

        return ApiResponse::success([
            'is_premium' => $subscription !== null,
            'subscription' => $subscription ? new SubscriptionResource($subscription) : null,
            'entitlements' => [
                'see_likes' => $this->entitlementService->can($user, 'see_likes'),
                'unlimited_likes' => $this->entitlementService->can($user, 'unlimited_likes'),
                'advanced_filters' => $this->entitlementService->can($user, 'advanced_filters'),
                'priority_discovery' => $this->entitlementService->can($user, 'priority_discovery'),
            ],
        ]);
    }

    /**
     * Server-side verification for Google Play Subscription
     */
    public function verifyGooglePlay(VerifySubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionService->verifyAndActivateSubscription(
            $request->user(),
            $request->input('product_id'),
            $request->input('purchase_token'),
            $request->input('order_id')
        );

        return ApiResponse::success(
            new SubscriptionResource($subscription),
            'Subscription activated successfully.'
        );
    }
}
