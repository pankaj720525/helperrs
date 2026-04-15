<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * List all users with filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $users = User::with('workerProfile')
            ->when($request->role, fn($q, $role) => $q->where('role', $role))
            ->when($request->status, function ($q, $status) {
                $q->where('is_active', $status === 'active');
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'users' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    /**
     * Show user detail.
     */
    public function show($id): JsonResponse
    {
        $user = User::with(['workerProfile', 'subscriptions', 'services.category'])
            ->findOrFail($id);

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $admin = $request->user();
        $oldStatus = $user->is_active;

        $user->update(['is_active' => !$user->is_active]);

        AdminAuditLog::record(
            $admin->id,
            $user->is_active ? 'user_activated' : 'user_deactivated',
            'User',
            $user->id,
            ['is_active' => $oldStatus],
            ['is_active' => $user->is_active]
        );

        return response()->json([
            'message' => $user->is_active ? 'User activated.' : 'User deactivated.',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Override user notification settings (admin penalty/mute).
     */
    public function notificationOverride(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'admin_email_override' => ['sometimes', 'boolean'],
            'admin_push_override'  => ['sometimes', 'boolean'],
        ]);

        $user = User::findOrFail($id);
        $admin = $request->user();
        $oldValues = $user->only(['admin_email_override', 'admin_push_override']);

        $user->update($validated);

        AdminAuditLog::record(
            $admin->id,
            'notification_override_changed',
            'User',
            $user->id,
            $oldValues,
            $validated
        );

        return response()->json([
            'message' => 'Notification override updated.',
            'user' => new UserResource($user),
        ]);
    }
}
