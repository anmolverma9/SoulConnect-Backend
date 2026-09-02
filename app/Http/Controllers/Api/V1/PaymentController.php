<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\VerifyGooglePlayPurchaseRequest;
use App\Http\Resources\WalletResource;
use App\Services\Payment\GooglePlayPurchaseService;
use App\Services\Wallet\WalletService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected GooglePlayPurchaseService $googlePlayService,
        protected WalletService $walletService
    ) {}

    /**
     * Server-side verification for Google Play Coin In-App Purchase
     */
    public function verifyGooglePlay(VerifyGooglePlayPurchaseRequest $request): JsonResponse
    {
        $purchase = $this->googlePlayService->verifyCoinPurchase(
            $request->user(),
            $request->input('product_id'),
            $request->input('purchase_token'),
            $request->input('order_id')
        );

        $wallet = $this->walletService->getWallet($request->user());

        return ApiResponse::success([
            'purchase_id' => $purchase->id,
            'coins_credited' => $purchase->coins,
            'wallet' => new WalletResource($wallet),
        ], 'Purchase verified and coins credited successfully.');
    }
}
