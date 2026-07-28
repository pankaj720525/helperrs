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
        $service = Service::with(['category', 'user.workerProfile', 'reviews.user', 'draftUpdate'])
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
            ->whereNull('parent_id') // Only show root services
            ->with(['category', 'draftUpdate']) // Load the child draft/pending changes
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->status, function ($q, $status) {
                if ($status === 'approved') {
                    $q->where('status', 'approved');
                } elseif ($status === 'pending') {
                    $q->where(function ($q) {
                        $q->where('status', 'pending')
                          ->orWhereHas('draftUpdate', fn($q) => $q->where('status', 'pending'));
                    });
                } elseif ($status === 'rejected') {
                    $q->where(function ($q) {
                        $q->where('status', 'rejected')
                          ->orWhereHas('draftUpdate', fn($q) => $q->where('status', 'rejected'));
                    });
                }
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

        $user = $request->user();

        if ($service->isApproved()) {
            // Find or create a draft for this approved service
            $draft = Service::where('parent_id', $service->id)
                ->whereIn('status', ['pending', 'rejected'])
                ->first();

            $isNewDraft = false;
            if (!$draft) {
                $draft = new Service();
                $draft->parent_id = $service->id;
                $draft->user_id = $user->id;
                $isNewDraft = true;
            }

            // Copy parent fields if it is a new draft record
            if ($isNewDraft) {
                $draft->category_id = $service->category_id;
                $draft->title = $service->title;
                $draft->description = $service->description;
                $draft->image = $service->image;
                $draft->price_min = $service->price_min;
                $draft->price_max = $service->price_max;
            }

            // Apply validated updates
            if (isset($validated['category_id'])) $draft->category_id = $validated['category_id'];
            if (isset($validated['title'])) $draft->title = $validated['title'];
            if (isset($validated['description'])) $draft->description = $validated['description'];
            if (isset($validated['price_min'])) $draft->price_min = $validated['price_min'];
            if (isset($validated['price_max'])) $draft->price_max = $validated['price_max'];

            if ($request->hasFile('image')) {
                // If draft had a custom image (different from parent), delete it
                if (!$isNewDraft && $draft->image && $draft->image !== $service->image) {
                    Storage::disk('public')->delete($draft->image);
                }
                $draft->image = $request->file('image')->store('services', 'public');
            }

            $draft->status = 'pending';
            $draft->rejection_reason = null;
            $draft->save();

            return response()->json([
                'message' => 'Changes submitted for admin approval.',
                'service' => new ServiceResource($service->fresh()->load(['category', 'draftUpdate'])),
            ]);
        } else {
            // Updating an unapproved service (root pending/rejected, or a child draft)
            if ($request->hasFile('image')) {
                if ($service->image) {
                    $parent = $service->parent_id ? Service::find($service->parent_id) : null;
                    if (!$parent || $parent->image !== $service->image) {
                        Storage::disk('public')->delete($service->image);
                    }
                }
                $validated['image'] = $request->file('image')->store('services', 'public');
            }

            $validated['status'] = 'pending';
            $validated['rejection_reason'] = null;

            $service->update($validated);

            return response()->json([
                'message' => 'Service updated successfully.',
                'service' => new ServiceResource($service->fresh()->load('category')),
            ]);
        }
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
