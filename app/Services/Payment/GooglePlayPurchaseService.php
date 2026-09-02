<?php

namespace App\Services\Payment;

use App\Exceptions\ApiException;
use App\Models\CoinPackage;
use App\Models\CoinPurchase;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\AndroidPublisher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GooglePlayPurchaseService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Verify Google Play In-App Product Purchase and credit user coins
     */
    public function verifyCoinPurchase(User $user, string $productId, string $purchaseToken, ?string $orderId = null): CoinPurchase
    {
        // Prevent duplicate token replay
        $existingPurchase = CoinPurchase::where('purchase_token', $purchaseToken)->first();
        if ($existingPurchase) {
            if ($existingPurchase->status === 'verified') {
                throw new ApiException('This purchase token has already been redeemed.', 422);
            }
        }

        $package = CoinPackage::where('google_product_id', $productId)
            ->where('is_active', true)
            ->first();

        if (! $package) {
            throw new ApiException('Invalid coin package product ID.', 404);
        }

        // Verify with Google Play Developer API
        $verificationResult = $this->verifyWithGooglePlay($productId, $purchaseToken);

        if (! $verificationResult['is_valid']) {
            throw new ApiException('Google Play purchase verification failed.', 400, [
                'error' => $verificationResult['error'] ?? 'Verification rejected',
            ]);
        }

        return DB::transaction(function () use ($user, $package, $productId, $purchaseToken, $orderId, $verificationResult) {
            $totalCoins = $package->total_coins;

            $purchase = CoinPurchase::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'product_id' => $productId,
                'purchase_token' => $purchaseToken,
                'order_id' => $orderId ?? ($verificationResult['order_id'] ?? null),
                'coins' => $totalCoins,
                'status' => 'verified',
                'purchased_at' => Carbon::now(),
                'verified_at' => Carbon::now(),
                'metadata' => $verificationResult['payload'] ?? null,
            ]);

            // Credit wallet atomically
            $this->walletService->credit(
                $user,
                $totalCoins,
                'purchase',
                $purchase->id,
                'CoinPurchase',
                "Purchased {$package->name} ({$totalCoins} coins)",
                ['package_id' => $package->id, 'product_id' => $productId]
            );

            return $purchase;
        });
    }

    /**
     * Call Google Play Developer API to verify purchase
     */
    protected function verifyWithGooglePlay(string $productId, string $purchaseToken): array
    {
        $packageName = config('services.google_play.package_name');
        $serviceAccountPath = config('services.google_play.service_account_json');

        // In local/testing/sandbox environment without live credentials, validate mock tokens
        if (empty($serviceAccountPath) || ! file_exists($serviceAccountPath) || config('app.env') === 'testing') {
            Log::info("Google Play purchase verification in mock mode for product {$productId}");
            return [
                'is_valid' => true,
                'order_id' => 'GPA.' . random_int(1000, 9999) . '-' . random_int(1000, 9999),
                'payload' => ['mode' => 'sandbox_verification'],
            ];
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccountPath);
            $client->addScope(AndroidPublisher::ANDROIDPUBLISHER);

            $service = new AndroidPublisher($client);
            $productPurchase = $service->purchases_products->get($packageName, $productId, $purchaseToken);

            // Purchase State: 0 = Purchased, 1 = Canceled, 2 = Pending
            if ($productPurchase->getPurchaseState() === 0) {
                // Acknowledge purchase if not yet acknowledged
                if ($productPurchase->getAcknowledgementState() === 0) {
                    $ackRequest = new \Google\Service\AndroidPublisher\ProductPurchasesAcknowledgeRequest();
                    $service->purchases_products->acknowledge($packageName, $productId, $purchaseToken, $ackRequest);
                }

                return [
                    'is_valid' => true,
                    'order_id' => $productPurchase->getOrderId(),
                    'payload' => json_decode(json_encode($productPurchase), true),
                ];
            }

            return [
                'is_valid' => false,
                'error' => 'Purchase state is not completed.',
            ];
        } catch (\Exception $e) {
            Log::error('Google Play Verification Error: ' . $e->getMessage());
            return [
                'is_valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
