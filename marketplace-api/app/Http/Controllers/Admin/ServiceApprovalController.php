<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\AdminAuditLog;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceApprovalController extends Controller
{
    /**
     * List all services with filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $services = Service::with(['user', 'category', 'parent'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->category_id, fn($q, $catId) => $q->where('category_id', $catId))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'services' => ServiceResource::collection($services),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page'    => $services->lastPage(),
                'per_page'     => $services->perPage(),
                'total'        => $services->total(),
            ],
        ]);
    }

    /**
     * Show single service detail.
     */
    public function show($id): JsonResponse
    {
        $service = Service::with(['user.workerProfile', 'category', 'reviews.user', 'parent'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        return response()->json([
            'service' => new ServiceResource($service),
        ]);
    }

    /**
     * Approve a service.
     */
    public function approve(Request $request, $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $admin = $request->user();

        $oldStatus = $service->status;

        if ($service->parent_id) {
            $parent = Service::findOrFail($service->parent_id);

            // Copy changes to parent
            $oldParentData = $parent->toArray();

            // Handle image replacement
            if ($service->image && $parent->image !== $service->image) {
                if ($parent->image) {
                    Storage::disk('public')->delete($parent->image);
                }
                $parent->image = $service->image;
            }

            $parent->update([
                'category_id' => $service->category_id,
                'title'       => $service->title,
                'description' => $service->description,
                'price_min'   => $service->price_min,
                'price_max'   => $service->price_max,
                'status'      => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            // Delete the draft update record
            $service->delete();

            AdminAuditLog::record(
                $admin->id,
                'service_approved_update',
                'Service',
                $parent->id,
                ['status' => $parent->status, 'data' => $oldParentData],
                ['status' => 'approved', 'data' => $parent->toArray()]
            );

            return response()->json([
                'message' => 'Service changes approved successfully.',
                'service' => new ServiceResource($parent->load(['user', 'category'])),
            ]);
        } else {
            // Approve the new service in-place
            $service->update([
                'status'      => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            AdminAuditLog::record(
                $admin->id,
                'service_approved',
                'Service',
                $service->id,
                ['status' => $oldStatus],
                ['status' => 'approved']
            );

            return response()->json([
                'message' => 'Service approved successfully.',
                'service' => new ServiceResource($service->fresh()->load(['user', 'category'])),
            ]);
        }
    }

    /**
     * Reject a service (requires reason).
     */
    public function reject(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $service = Service::findOrFail($id);
        $admin = $request->user();
        $oldStatus = $service->status;

        $service->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by'      => $admin->id,
            'approved_at'      => now(),
        ]);

        AdminAuditLog::record(
            $admin->id,
            $service->parent_id ? 'service_update_rejected' : 'service_rejected',
            'Service',
            $service->id,
            ['status' => $oldStatus],
            ['status' => 'rejected', 'reason' => $validated['rejection_reason']]
        );

        return response()->json([
            'message' => 'Service changes rejected.',
            'service' => new ServiceResource($service->fresh()->load(['user', 'category'])),
        ]);
    }
}
