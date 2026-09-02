<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoinPackageResource;
use App\Models\CoinPackage;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CoinPackageController extends Controller
{
    /**
     * Get active coin purchase packages
     */
    public function index(): JsonResponse
    {
        $packages = CoinPackage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return ApiResponse::success(CoinPackageResource::collection($packages));
    }
}
