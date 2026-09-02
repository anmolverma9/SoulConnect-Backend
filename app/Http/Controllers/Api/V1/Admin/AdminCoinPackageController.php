<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoinPackageRequest;
use App\Http\Resources\CoinPackageResource;
use App\Models\CoinPackage;
use App\Services\Admin\AdminAuditService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCoinPackageController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * List all coin packages
     */
    public function index(): JsonResponse
    {
        $packages = CoinPackage::orderBy('sort_order', 'asc')->get();

        return ApiResponse::success(CoinPackageResource::collection($packages));
    }

    /**
     * Create coin package
     */
    public function store(CoinPackageRequest $request): JsonResponse
    {
        $package = CoinPackage::create($request->validated());

        $this->auditService->log(
            $request->user('admin'),
            'create_coin_package',
            'CoinPackage',
            $package->id,
            $package->toArray(),
            $request
        );

        return ApiResponse::success(new CoinPackageResource($package), 'Package created.', 201);
    }

    /**
     * Update coin package
     */
    public function update(CoinPackageRequest $request, CoinPackage $package): JsonResponse
    {
        $package->update($request->validated());

        $this->auditService->log(
            $request->user('admin'),
            'update_coin_package',
            'CoinPackage',
            $package->id,
            $package->toArray(),
            $request
        );

        return ApiResponse::success(new CoinPackageResource($package), 'Package updated.');
    }

    /**
     * Delete coin package
     */
    public function destroy(Request $request, CoinPackage $package): JsonResponse
    {
        $package->delete();

        $this->auditService->log(
            $request->user('admin'),
            'delete_coin_package',
            'CoinPackage',
            $package->id,
            ['name' => $package->name],
            $request
        );

        return ApiResponse::success(null, 'Package deleted.');
    }
}
