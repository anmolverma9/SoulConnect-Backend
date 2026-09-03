<?php

namespace App\Services\Payment;

use App\Exceptions\ApiException;
use App\Models\CoinPackage;
use App\Models\PaymentOrder;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpacePayService
{
    protected string $publicKey;
    protected string $secretKey;
    protected string $baseUrl;

    public function __construct(
        protected WalletService $walletService
    ) {
        $this->publicKey = config('services.spacepay.public_key') ?: env('SPACEPAY_PUBLIC_KEY', 'pk_test_51xxxxxxxxxxxxx');
        $this->secretKey = config('services.spacepay.secret_key') ?: env('SPACEPAY_SECRET_KEY', '02efwxsjxxxxxxxxxxx');
        $this->baseUrl = config('services.spacepay.base_url') ?: 'https://spacepay.in/api/payment/v1';
    }

    /**
     * Initiate a SpacePay payment order for coin package or VIP
     */
    public function initiateCoinPurchase(User $user, int $packageId, ?string $mobile = null): PaymentOrder
    {
        $package = CoinPackage::find($packageId);
        if (!$package) {
            throw new ApiException('Selected coin package not found.', 404);
        }

        $totalCoins = $package->coins + $package->bonus_coins;
        $orderId = 'ORD_SC_' . time() . '_' . $user->id . '_' . rand(100, 999);
        $amount = (float) $package->price;
        $customerMobile = $mobile ?: ($user->phone ?: '9999999999');
        $redirectUrl = url('/api/v1/payments/spacepay/callback?order_id=' . $orderId);

        // 1. Create local pending order record
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'gateway' => 'spacepay',
            'type' => 'coin_package',
            'package_id' => $package->id,
            'amount' => $amount,
            'currency' => $package->currency ?: 'INR',
            'coins_to_credit' => $totalCoins,
            'status' => 'pending',
        ]);

        // 2. Call SpacePay Create Transaction API
        $payload = [
            'public_key' => $this->publicKey,
            'secret_key' => $this->secretKey,
            'customer_mobile' => $customerMobile,
            'amount' => (string) $amount,
            'order_id' => $orderId,
            'redirect_url' => $redirectUrl,
            'note' => "Soul Connect {$totalCoins} Coins Package",
        ];

        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/pay", $payload);
            $data = $response->json();

            Log::info('SpacePay Pay API Response', ['orderId' => $orderId, 'response' => $data]);

            if ($response->successful() && !empty($data['status']) && !empty($data['result']['payment_url'])) {
                $order->update([
                    'gateway_order_id' => $data['result']['orderId'] ?? null,
                    'payment_url' => $data['result']['payment_url'],
                    'response_payload' => $data,
                ]);

                return $order;
            }

            $order->update([
                'status' => 'failed',
                'response_payload' => $data,
            ]);

            throw new ApiException($data['message'] ?? 'Failed to create payment session with SpacePay.', 400);
        } catch (\Exception $e) {
            Log::error('SpacePay Pay Request Error: ' . $e->getMessage(), ['order_id' => $orderId]);
            throw new ApiException('SpacePay Payment Gateway connection error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Check transaction status directly with SpacePay API & credit coins if SUCCESS
     */
    public function verifyAndCompleteOrder(string $orderId): PaymentOrder
    {
        $order = PaymentOrder::where('order_id', $orderId)->first();
        if (!$order) {
            throw new ApiException('Payment order not found.', 404);
        }

        if ($order->status === 'success') {
            return $order;
        }

        $payload = [
            'public_key' => $this->publicKey,
            'secret_key' => $this->secretKey,
            'order_id' => $order->order_id,
        ];

        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/order-status", $payload);
            $data = $response->json();

            Log::info('SpacePay Order Status API Response', ['orderId' => $orderId, 'response' => $data]);

            if ($response->successful() && !empty($data['status']) && !empty($data['order_details'])) {
                $details = $data['order_details'];
                $status = strtoupper($details['STATUS'] ?? '');

                if ($status === 'SUCCESS' || $status === 'COMPLETED' || $status === 'PAID') {
                    $this->creditOrder($order, $details['BANKTXNID'] ?? null, $data);
                } elseif ($status === 'FAILURE' || $status === 'FAILED') {
                    $order->update([
                        'status' => 'failed',
                        'response_payload' => $data,
                    ]);
                }
            }

            return $order->fresh();
        } catch (\Exception $e) {
            Log::error('SpacePay Order Status Check Error: ' . $e->getMessage(), ['order_id' => $orderId]);
            return $order;
        }
    }

    /**
     * Credit coins to user wallet and mark order as SUCCESS
     */
    public function creditOrder(PaymentOrder $order, ?string $bankTxnId = null, ?array $responsePayload = null): void
    {
        if ($order->status === 'success') {
            return;
        }

        $user = User::find($order->user_id);
        if ($user && $order->coins_to_credit > 0) {
            $this->walletService->credit(
                $user,
                $order->coins_to_credit,
                'purchase',
                $order->id,
                'PaymentOrder',
                "Purchased {$order->coins_to_credit} Soul Coins via SpacePay (Order: {$order->order_id})",
                ['gateway' => 'spacepay', 'bank_txn_id' => $bankTxnId]
            );
        }

        $order->update([
            'status' => 'success',
            'bank_txn_id' => $bankTxnId ?? $order->bank_txn_id,
            'paid_at' => Carbon::now(),
            'response_payload' => $responsePayload ?? $order->response_payload,
        ]);

        Log::info("SpacePay Order #{$order->order_id} credited {$order->coins_to_credit} coins to User #{$order->user_id}");
    }
}
