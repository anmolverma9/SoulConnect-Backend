<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\Chat\ChatService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {}

    /**
     * Get messages in conversation paginated
     */
    public function index(Request $request, Conversation $conversation): JsonResponse
    {
        if (! $conversation->isParticipant($request->user()->id)) {
            return ApiResponse::forbidden('Unauthorized.');
        }

        // Mark incoming messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => \Carbon\Carbon::now(),
                'status' => 'read',
            ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with(['sender.profile'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return ApiResponse::paginated(MessageResource::collection($messages));
    }

    /**
     * Send message in conversation
     */
    public function store(SendMessageRequest $request, Conversation $conversation): JsonResponse
    {
        $message = $this->chatService->sendMessage(
            $request->user(),
            $conversation,
            $request->input('body'),
            $request->input('type', 'text'),
            $request->file('media')
        );

        return ApiResponse::success(new MessageResource($message), 'Message sent.', 201);
    }

    /**
     * Delete message
     */
    public function destroy(Request $request, Message $message): JsonResponse
    {
        $this->chatService->deleteMessage($request->user(), $message->id);

        return ApiResponse::success(null, 'Message deleted.');
    }
}
