<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * List all approved services (public).
     */
    public function index(Request $request): JsonResponse
    {
        $services = Service::approved()
            ->with(['category', 'user.workerProfile'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->category_id, fn($q, $catId) => $q->where('category_id', $catId))
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
     * Show single service detail (public for approved services).
     */
    public function show($id): JsonResponse
    {
        $service = Service::with(['category', 'user.workerProfile', 'reviews.user'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        return response()->json([
            'service' => new ServiceResource($service),
        ]);
    }

    /**
     * Create a new service (authenticated worker).
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isWorker()) {
            return response()->json([
                'message' => 'You must have a worker profile to create services.',
            ], 403);
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service = $user->services()->create([
            'category_id' => $validated['category_id'],
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image'       => $validated['image'] ?? null,
            'price_min'   => $validated['price_min'] ?? null,
            'price_max'   => $validated['price_max'] ?? null,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Service created successfully. It will be visible after admin approval.',
            'service' => new ServiceResource($service->load('category')),
        ], 201);
    }

    /**
     * List current user's own services.
     */
    public function myServices(Request $request): JsonResponse
    {
        $services = $request->user()
            ->services()
            ->with(['category'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
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
     * Update own service.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $service = $request->user()->services()->findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:categories,id'],
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'price_min'   => ['nullable', 'numeric', 'min:0'],
            'price_max'   => ['nullable', 'numeric', 'min:0', 'gte:price_min'],
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // If content changes, reset to pending for re-approval
        $contentChanged = isset($validated['title']) || isset($validated['description']);
        if ($contentChanged && $service->isApproved()) {
            $validated['status'] = 'pending';
            $validated['rejection_reason'] = null;
        }

        $service->update($validated);

        return response()->json([
            'message' => $contentChanged ? 'Service updated. Re-approval required.' : 'Service updated successfully.',
            'service' => new ServiceResource($service->fresh()->load('category')),
        ]);
    }

    /**
     * Delete own service.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $service = $request->user()->services()->findOrFail($id);

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully.',
        ]);
    }
}
