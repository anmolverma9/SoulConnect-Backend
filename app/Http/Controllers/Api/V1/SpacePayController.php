<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\User;
use App\Services\Payment\SpacePayService;
use App\Services\Wallet\WalletService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpacePayController extends Controller
{
    public function __construct(
        protected SpacePayService $spacePayService,
        protected WalletService $walletService
    ) {}

    /**
     * Initiate SpacePay payment session for a coin package
     */
    public function initiate(Request $request): JsonResponse
    {
        $request->validate([
            'package_id' => 'required|integer',
            'mobile' => 'nullable|string|min:10|max:15',
        ]);

        $order = $this->spacePayService->initiateCoinPurchase(
            $request->user(),
            (int) $request->input('package_id'),
            $request->input('mobile')
        );

        return ApiResponse::success([
            'order_id' => $order->order_id,
            'payment_url' => $order->payment_url,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'coins' => $order->coins_to_credit,
            'status' => $order->status,
        ], 'SpacePay payment session initiated.');
    }

    /**
     * Check order status and credit wallet if completed
     */
    public function checkStatus(Request $request, string $orderId): JsonResponse
    {
        $order = $this->spacePayService->verifyAndCompleteOrder($orderId);
        $user = $request->user() ?: User::find($order->user_id);
        $wallet = $user ? $this->walletService->getWallet($user) : null;

        return ApiResponse::success([
            'order_id' => $order->order_id,
            'status' => $order->status,
            'coins_credited' => $order->coins_to_credit,
            'wallet_balance' => $wallet->balance,
            'paid_at' => $order->paid_at ? $order->paid_at->toIso8601String() : null,
        ], 'Payment status retrieved.');
    }

    /**
     * SpacePay Webhook Endpoint (Server-to-Server)
     * Webhook URL: https://dating.pview.site/api/v1/payments/spacepay/webhook
     */
    public function webhook(Request $request): JsonResponse
    {
        Log::info('SpacePay Webhook Notification Received', ['payload' => $request->all()]);

        $orderId = $request->input('order_id')
            ?? $request->input('orderId')
            ?? $request->input('ORDERID')
            ?? $request->input('order_details.ORDERID');

        $status = strtoupper(
            $request->input('status')
            ?? $request->input('STATUS')
            ?? $request->input('order_details.STATUS')
            ?? ''
        );

        if ($orderId) {
            $order = PaymentOrder::where('order_id', $orderId)->first();
            if ($order) {
                if ($status === 'SUCCESS' || $status === 'COMPLETED' || $status === 'PAID') {
                    $bankTxnId = $request->input('bank_txn_id')
                        ?? $request->input('BANKTXNID')
                        ?? $request->input('order_details.BANKTXNID');

                    $this->spacePayService->creditOrder($order, $bankTxnId, $request->all());
                } else {
                    $this->spacePayService->verifyAndCompleteOrder($orderId);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Webhook received and processed successfully',
        ], 200);
    }

    /**
     * SpacePay User Redirect Callback
     * Callback URL: https://dating.pview.site/api/v1/payments/spacepay/callback
     */
    public function callback(Request $request)
    {
        $orderId = $request->query('order_id') ?? $request->input('order_id');

        if ($orderId) {
            $order = $this->spacePayService->verifyAndCompleteOrder($orderId);
            $isSuccess = $order->status === 'success';

            return response()->view('payments.spacepay_result', [
                'order' => $order,
                'isSuccess' => $isSuccess,
            ]);
        }

        return response()->view('payments.spacepay_result', [
            'order' => null,
            'isSuccess' => false,
        ]);
    }
}
