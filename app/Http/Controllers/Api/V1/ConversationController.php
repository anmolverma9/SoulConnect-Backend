<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Services\Chat\ChatService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {}

    /**
     * Get user conversations
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(50, max(1, (int) $request->query('per_page', 20)));
        $user = $request->user();

        $conversations = $this->chatService->getUserConversations($user, $perPage);

        return ApiResponse::paginated(ConversationResource::collection($conversations));
    }

    /**
     * Start/get 1-on-1 conversation
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $conversation = $this->chatService->getOrCreateConversation($request->user(), (int) $request->input('user_id'));

        return ApiResponse::success(new ConversationResource($conversation), 'Conversation ready.', 201);
    }

    /**
     * Get specific conversation details
     */
    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        if (! $conversation->isParticipant($request->user()->id)) {
            return ApiResponse::forbidden('Unauthorized.');
        }

        $conversation->load(['participants.user.profile', 'participants.user.photos', 'lastMessage']);

        return ApiResponse::success(new ConversationResource($conversation));
    }

    /**
     * Mark conversation as read
     */
    public function read(Request $request, Conversation $conversation): JsonResponse
    {
        $count = $this->chatService->markAsRead($request->user(), $conversation);

        return ApiResponse::success(['read_count' => $count], 'Messages marked as read.');
    }
}
