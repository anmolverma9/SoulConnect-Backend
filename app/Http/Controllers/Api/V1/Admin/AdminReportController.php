<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Services\Admin\AdminAuditService;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * List abuse reports with status filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::query()->with(['reporter', 'reported', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(20);

        return ApiResponse::paginated(ReportResource::collection($reports));
    }

    /**
     * Review/resolve a report
     */
    public function review(ReviewReportRequest $request, Report $report): JsonResponse
    {
        $admin = $request->user('admin');

        $report->update([
            'status' => $request->input('status'),
            'reviewed_by' => $admin->id,
            'resolution_notes' => $request->input('resolution_notes'),
            'resolved_at' => $request->input('status') === 'resolved' ? Carbon::now() : $report->resolved_at,
        ]);

        $this->auditService->log(
            $admin,
            'report_reviewed',
            'Report',
            $report->id,
            ['status' => $report->status, 'notes' => $report->resolution_notes],
            $request
        );

        return ApiResponse::success(new ReportResource($report), 'Report updated.');
    }
}
