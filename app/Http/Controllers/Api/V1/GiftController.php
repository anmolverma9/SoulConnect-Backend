<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gift\SendGiftRequest;
use App\Http\Resources\GiftCatalogResource;
use App\Http\Resources\GiftTransactionResource;
use App\Services\Dating\GiftService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function __construct(
        protected GiftService $giftService
    ) {}

    /**
     * Get available gift catalog
     */
    public function catalog(): JsonResponse
    {
        $gifts = $this->giftService->getCatalog();

        return ApiResponse::success(GiftCatalogResource::collection($gifts));
    }

    /**
     * Send a virtual gift to a user
     */
    public function send(SendGiftRequest $request): JsonResponse
    {
        $transaction = $this->giftService->sendGift(
            $request->user(),
            (int) $request->input('receiver_id'),
            (int) $request->input('gift_id'),
            $request->input('message')
        );

        return ApiResponse::success(new GiftTransactionResource($transaction), 'Gift sent successfully!', 201);
    }
}
