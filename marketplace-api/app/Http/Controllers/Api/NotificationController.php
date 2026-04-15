<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * List user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate($request->per_page ?? 15);

        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => NotificationResource::collection($notifications),
            'unread_count'  => $unreadCount,
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = Notification::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $notification->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Get/update notification preferences.
     */
    public function preferences(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($request->isMethod('PUT')) {
            $validated = $request->validate([
                'email' => ['sometimes', 'boolean'],
                'push'  => ['sometimes', 'boolean'],
                'audio' => ['sometimes', 'boolean'],
            ]);

            $preferences = $user->notification_preferences ?? [];
            $user->update([
                'notification_preferences' => array_merge($preferences, $validated),
            ]);
        }

        return response()->json([
            'preferences' => $user->fresh()->notification_preferences ?? [
                'email' => true,
                'push'  => true,
                'audio' => true,
            ],
        ]);
    }
}
