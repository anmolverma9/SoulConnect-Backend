<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Call\AcceptCallRequest;
use App\Http\Requests\Call\InitiateCallRequest;
use App\Http\Resources\CallResource;
use App\Models\Call;
use App\Services\Call\CallService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function __construct(
        protected CallService $callService
    ) {}

    /**
     * Initiate a new voice or video call
     */
    public function store(InitiateCallRequest $request): JsonResponse
    {
        $call = $this->callService->initiateCall(
            $request->user(),
            (int) $request->input('receiver_id'),
            $request->input('type', 'voice'),
            $request->input('conversation_id')
        );

        return ApiResponse::success(new CallResource($call), 'Call initiated.', 201);
    }

    /**
     * Get call status
     */
    public function show(Request $request, Call $call): JsonResponse
    {
        if (! $call->isParticipant($request->user()->id)) {
            return ApiResponse::forbidden('Unauthorized.');
        }

        $call->load(['caller.profile', 'receiver.profile']);

        return ApiResponse::success(new CallResource($call));
    }

    /**
     * Accept incoming call
     */
    public function accept(AcceptCallRequest $request, Call $call): JsonResponse
    {
        $acceptedCall = $this->callService->acceptCall(
            $request->user(),
            $call->id,
            $request->input('signaling_data')
        );

        return ApiResponse::success(new CallResource($acceptedCall), 'Call accepted.');
    }

    /**
     * Reject incoming call
     */
    public function reject(Request $request, Call $call): JsonResponse
    {
        $rejectedCall = $this->callService->rejectCall($request->user(), $call->id);

        return ApiResponse::success(new CallResource($rejectedCall), 'Call rejected.');
    }

    /**
     * End active call and finalize billing
     */
    public function end(Request $request, Call $call): JsonResponse
    {
        $endedCall = $this->callService->endCall($request->user(), $call->id);

        return ApiResponse::success(new CallResource($endedCall), 'Call ended.');
    }
}
