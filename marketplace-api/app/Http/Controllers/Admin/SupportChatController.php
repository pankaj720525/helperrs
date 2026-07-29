<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatResource;
use App\Models\AdminAuditLog;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportChatController extends Controller
{
    /**
     * List all support chats in admin panel.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Chat::where('is_support', true)
            ->with(['user', 'latestMessage']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $chats = $query->latest('updated_at')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'chats' => ChatResource::collection($chats),
            'meta'  => [
                'current_page' => $chats->currentPage(),
                'last_page'    => $chats->lastPage(),
                'per_page'     => $chats->perPage(),
                'total'        => $chats->total(),
            ],
        ]);
    }

    /**
     * Get details and message log for a support chat.
     */
    public function messages(Request $request, $id): JsonResponse
    {
        $chat = Chat::where('is_support', true)
            ->with(['user'])
            ->findOrFail($id);

        // Mark user messages as read
        $chat->messages()
            ->whereNull('admin_id')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()
            ->with(['sender', 'admin'])
            ->reorder()
            ->latest()
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'chat'     => new ChatResource($chat),
            'messages' => ChatMessageResource::collection($messages),
            'meta'     => [
                'current_page' => $messages->currentPage(),
                'last_page'    => $messages->lastPage(),
                'per_page'     => $messages->perPage(),
                'total'        => $messages->total(),
            ],
        ]);
    }

    /**
     * Admin posts a reply message to user.
     */
    public function sendMessage(Request $request, $id): JsonResponse
    {
        $admin = $request->user();
        $chat = Chat::where('is_support', true)->findOrFail($id);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (empty($validated['message']) && !$request->hasFile('image')) {
            return response()->json(['message' => 'Please enter a message or attach an image.'], 422);
        }

        // Re-open chat if it was closed
        if ($chat->status === 'closed') {
            $chat->update(['status' => 'open']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_attachments', 'public');
        }

        $message = $chat->messages()->create([
            'admin_id'   => $admin->id,
            'sender_id'  => null,
            'message'    => $validated['message'] ?? '',
            'image_path' => $imagePath,
            'is_read'    => false,
        ]);

        $chat->touch();

        // Broadcast event to user safely (swallow WebSockets/Pusher connectivity errors)
        try {
            broadcast(new NewChatMessage($message->load('admin'), $chat->id, $chat->user_id));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcasting message failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => new ChatMessageResource($message->load(['sender', 'admin'])),
        ], 201);
    }

    /**
     * Admin closes/resolves a support chat.
     */
    public function closeChat(Request $request, $id): JsonResponse
    {
        $admin = $request->user();
        $chat = Chat::where('is_support', true)->findOrFail($id);

        $chat->update(['status' => 'closed']);

        AdminAuditLog::record(
            $admin->id,
            'support_chat_closed',
            'Chat',
            $chat->id,
            ['status' => 'open'],
            ['status' => 'closed']
        );

        return response()->json([
            'message' => 'Support chat closed & resolved successfully.',
            'chat'    => new ChatResource($chat->fresh()->load('user')),
        ]);
    }
}
