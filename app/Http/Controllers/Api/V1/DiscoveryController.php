<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscoveryProfileResource;
use App\Services\Dating\DiscoveryService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function __construct(
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Get candidate dating profiles based on preferences & geo-spatial distance
     */
    public function discover(Request $request): JsonResponse
    {
        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));
        $profiles = $this->discoveryService->discover($request->user(), $perPage);

        return ApiResponse::paginated(DiscoveryProfileResource::collection($profiles));
    }
}
