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
            try {
                broadcast(new NewInquiry(
                    $chat->load(['user', 'service']),
                    $service->user_id,
                    $service->title,
                ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Broadcasting inquiry failed: ' . $e->getMessage());
            }
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
            ->reorder()
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
     * Start or open a live support chat with Admin support.
     */
    public function storeSupport(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        // Find open support chat or create a new one
        $chat = Chat::where('user_id', $user->id)
            ->where('is_support', true)
            ->where('status', 'open')
            ->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_id'    => $user->id,
                'worker_id'  => null,
                'service_id' => null,
                'is_support' => true,
                'status'     => 'open',
            ]);
        }

        // Create initial message if provided
        if (!empty($validated['message'])) {
            $chat->messages()->create([
                'sender_id' => $user->id,
                'message'   => $validated['message'],
            ]);
            $chat->touch();
        }

        return response()->json([
            'message' => 'Support chat connected successfully.',
            'chat'    => new ChatResource($chat->load(['user', 'service', 'latestMessage'])),
        ], 200);
    }

    /**
     * Close/resolve a chat (User side).
     */
    public function closeChat(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chat = Chat::forUser($user->id)->findOrFail($id);

        $chat->update(['status' => 'closed']);

        return response()->json([
            'message' => 'Chat conversation closed successfully.',
            'chat'    => new ChatResource($chat->fresh()->load(['user', 'worker', 'service'])),
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
            'message' => ['nullable', 'string', 'max:2000'],
            'image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (empty($validated['message']) && !$request->hasFile('image')) {
            return response()->json(['message' => 'Please enter a message or attach an image.'], 422);
        }

        // If chat was closed, re-open when user sends a new message
        if ($chat->status === 'closed') {
            $chat->update(['status' => 'open']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_attachments', 'public');
        }

        $message = $chat->messages()->create([
            'sender_id'  => $user->id,
            'message'    => $validated['message'] ?? '',
            'image_path' => $imagePath,
        ]);

        $chat->touch();

        // Determine recipient (the other party)
        $recipientId = $user->id === $chat->user_id ? $chat->worker_id : $chat->user_id;

        if ($recipientId) {
            try {
                broadcast(new NewChatMessage($message->load('sender'), $chat->id, $recipientId));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Broadcasting chat message failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => new ChatMessageResource($message->load(['sender', 'admin'])),
        ], 201);
    }
}
