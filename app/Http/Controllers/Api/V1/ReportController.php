<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Safety\CreateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Report a user, profile, photo, or message
     */
    public function store(CreateReportRequest $request): JsonResponse
    {
        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $request->input('reported_id'),
            'reportable_type' => $request->input('reportable_type') ? "App\\Models\\{$request->input('reportable_type')}" : null,
            'reportable_id' => $request->input('reportable_id'),
            'reason' => $request->input('reason'),
            'details' => $request->input('details'),
            'status' => 'pending',
        ]);

        return ApiResponse::success(
            new ReportResource($report),
            'Thank you. Your report has been submitted and will be reviewed by our moderation team.',
            201
        );
    }
}
