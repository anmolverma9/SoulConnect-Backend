<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletTransactionResource;
use App\Models\WalletTransaction;
use App\Services\Wallet\WalletService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Get user wallet balance
     */
    public function show(Request $request): JsonResponse
    {
        $wallet = $this->walletService->getWallet($request->user());

        return ApiResponse::success(new WalletResource($wallet));
    }

    /**
     * Get user wallet transaction ledger history
     */
    public function transactions(Request $request): JsonResponse
    {
        $transactions = WalletTransaction::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return ApiResponse::paginated(WalletTransactionResource::collection($transactions));
    }
}
