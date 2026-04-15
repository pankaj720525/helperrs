<?php

namespace App\Http\Controllers\Api;

use App\Events\NewChatMessage;
use App\Events\NewInquiry;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List current user's chats.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $chats = Chat::forUser($user->id)
            ->with(['user', 'worker', 'service', 'latestMessage'])
            ->latest('updated_at')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'chats' => ChatResource::collection($chats),
            'meta' => [
                'current_page' => $chats->currentPage(),
                'last_page'    => $chats->lastPage(),
                'per_page'     => $chats->perPage(),
                'total'        => $chats->total(),
            ],
        ]);
    }

    /**
     * Start a new chat with a worker about a service.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'message'    => ['required', 'string', 'max:2000'],
        ]);

        $service = Service::approved()->findOrFail($validated['service_id']);

        // Can't chat with yourself
        if ($service->user_id === $user->id) {
            return response()->json([
                'message' => 'You cannot start a chat with yourself.',
            ], 422);
        }

        // Create or find existing chat
        $chat = Chat::firstOrCreate([
            'user_id'    => $user->id,
            'worker_id'  => $service->user_id,
            'service_id' => $service->id,
        ], [
            'status' => 'open',
        ]);

        // Create initial message
        $message = $chat->messages()->create([
            'sender_id' => $user->id,
            'message'   => $validated['message'],
        ]);

        $chat->touch(); // Update chat's updated_at

        // Notify worker of new inquiry only if chat was just created
        if ($chat->wasRecentlyCreated) {
            broadcast(new NewInquiry(
                $chat->load(['user', 'service']),
                $service->user_id,
                $service->title,
            ));
        }

        return response()->json([
            'message' => 'Chat started successfully.',
            'chat'    => new ChatResource($chat->load(['user', 'worker', 'service'])),
        ], 201);
    }

    /**
     * Get messages for a specific chat.
     */
    public function messages(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chat = Chat::forUser($user->id)->findOrFail($id);

        // Mark messages as read
        $chat->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()
            ->with('sender')
            ->latest()
            ->paginate($request->per_page ?? 30);

        return response()->json([
            'messages' => ChatMessageResource::collection($messages),
            'chat'     => new ChatResource($chat->load(['user', 'worker', 'service'])),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page'    => $messages->lastPage(),
                'per_page'     => $messages->perPage(),
                'total'        => $messages->total(),
            ],
        ]);
    }

    /**
     * Send a message in a chat.
     */
    public function sendMessage(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chat = Chat::forUser($user->id)->findOrFail($id);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $chat->messages()->create([
            'sender_id' => $user->id,
            'message'   => $validated['message'],
        ]);

        $chat->touch();

        // Determine recipient (the other party)
        $recipientId = $user->id === $chat->user_id ? $chat->worker_id : $chat->user_id;

        // Broadcast real-time event
        broadcast(new NewChatMessage($message->load('sender'), $chat->id, $recipientId));

        return response()->json([
            'message' => new ChatMessageResource($message->load('sender')),
        ], 201);
    }
}
