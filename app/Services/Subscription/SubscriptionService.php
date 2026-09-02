<?php

namespace App\Services\Subscription;

use App\Exceptions\ApiException;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTransaction;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\AndroidPublisher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Get all active subscription plans
     */
    public function getActivePlans()
    {
        return SubscriptionPlan::where('is_active', true)->get();
    }

    /**
     * Verify Google Play subscription and activate plan
     */
    public function verifyAndActivateSubscription(User $user, string $productId, string $purchaseToken, ?string $orderId = null): Subscription
    {
        $plan = SubscriptionPlan::where('google_product_id', $productId)
            ->where('is_active', true)
            ->first();

        if (! $plan) {
            throw new ApiException('Subscription plan not found.', 404);
        }

        // Verify with Google Play
        $verification = $this->verifyWithGooglePlay($productId, $purchaseToken);

        if (! $verification['is_valid']) {
            throw new ApiException('Google Play subscription verification failed.', 400, [
                'error' => $verification['error'] ?? 'Verification rejected',
            ]);
        }

        return DB::transaction(function () use ($user, $plan, $purchaseToken, $orderId, $verification) {
            $startsAt = Carbon::now();
            $endsAt = Carbon::now()->addDays($plan->duration_days);

            $subscription = Subscription::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'purchase_token' => $purchaseToken,
                ],
                [
                    'plan_id' => $plan->id,
                    'order_id' => $orderId ?? ($verification['order_id'] ?? null),
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'auto_renew' => true,
                ]
            );

            // Record transaction
            SubscriptionTransaction::create([
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'type' => 'initial',
                'amount' => $plan->price,
                'currency' => $plan->currency,
                'purchase_token' => $purchaseToken,
                'order_id' => $subscription->order_id,
                'metadata' => $verification['payload'] ?? null,
            ]);

            // Credit period coins if plan comes with bonus coins
            if ($plan->coins_per_period > 0) {
                $this->walletService->credit(
                    $user,
                    $plan->coins_per_period,
                    'subscription_bonus',
                    $subscription->id,
                    'Subscription',
                    "Subscription bonus coins for {$plan->name}"
                );
            }

            return $subscription->load('plan');
        });
    }

    /**
     * Verify Google Play Subscription Token
     */
    protected function verifyWithGooglePlay(string $subscriptionId, string $purchaseToken): array
    {
        $packageName = config('services.google_play.package_name');
        $serviceAccountPath = config('services.google_play.service_account_json');

        if (empty($serviceAccountPath) || ! file_exists($serviceAccountPath) || config('app.env') === 'testing') {
            Log::info("Google Play subscription verification in mock mode for {$subscriptionId}");
            return [
                'is_valid' => true,
                'order_id' => 'GPA.SUB-' . random_int(1000, 9999) . '-' . random_int(1000, 9999),
                'payload' => ['mode' => 'sandbox_verification'],
            ];
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccountPath);
            $client->addScope(AndroidPublisher::ANDROIDPUBLISHER);

            $service = new AndroidPublisher($client);
            $subPurchase = $service->purchases_subscriptionsv2->get($packageName, $purchaseToken);

            $state = $subPurchase->getSubscriptionState();
            // SUBSCRIPTION_STATE_ACTIVE = 2
            if (in_array($state, ['SUBSCRIPTION_STATE_ACTIVE', 'SUBSCRIPTION_STATE_IN_GRACE_PERIOD', 2])) {
                return [
                    'is_valid' => true,
                    'order_id' => $subPurchase->getLatestOrderId(),
                    'payload' => json_decode(json_encode($subPurchase), true),
                ];
            }

            return [
                'is_valid' => false,
                'error' => "Subscription state is {$state}",
            ];
        } catch (\Exception $e) {
            Log::error('Google Play Subscription Verification Error: ' . $e->getMessage());
            return [
                'is_valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
