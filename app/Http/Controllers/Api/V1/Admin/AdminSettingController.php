<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\AppSetting;
use App\Services\Admin\AdminAuditService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * Get all app settings
     */
    public function index(): JsonResponse
    {
        $settings = AppSetting::all();

        return ApiResponse::success($settings);
    }

    /**
     * Update app settings in batch
     */
    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $settings = $request->input('settings');
        $admin = $request->user();

        foreach ($settings as $item) {
            AppSetting::set($item['key'], $item['value']);
        }

        $this->auditService->log(
            $admin,
            'update_settings',
            'AppSetting',
            null,
            ['updated_count' => count($settings)],
            $request
        );

        return ApiResponse::success(AppSetting::all(), 'Settings updated successfully.');
    }
}
