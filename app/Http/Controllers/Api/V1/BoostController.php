<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoostResource;
use App\Services\Dating\BoostService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function __construct(
        protected BoostService $boostService
    ) {}

    /**
     * Purchase and activate a profile boost
     */
    public function store(Request $request): JsonResponse
    {
        $boost = $this->boostService->purchaseBoost($request->user());

        return ApiResponse::success(new BoostResource($boost), 'Profile boost activated!', 201);
    }

    /**
     * Get active profile boost status
     */
    public function active(Request $request): JsonResponse
    {
        $boost = $this->boostService->getActiveBoost($request->user());

        return ApiResponse::success([
            'is_active' => $boost !== null,
            'boost' => $boost ? new BoostResource($boost) : null,
        ]);
    }
}
