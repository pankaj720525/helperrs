<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * List reviews for a service (public).
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $reviews = Review::where('service_id', $validated['service_id'])
            ->with('user')
            ->latest()
            ->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'reviews' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    /**
     * Create a review for a service (authenticated).
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $service = Service::approved()->findOrFail($validated['service_id']);

        // Can't review own service
        if ($service->user_id === $user->id) {
            return response()->json([
                'message' => 'You cannot review your own service.',
            ], 422);
        }

        // Check if already reviewed
        $exists = Review::where('user_id', $user->id)
            ->where('service_id', $service->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You have already reviewed this service.',
            ], 422);
        }

        $review = Review::create([
            'user_id'    => $user->id,
            'service_id' => $service->id,
            'worker_id'  => $service->user_id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully.',
            'review'  => new ReviewResource($review->load('user')),
        ], 201);
    }
}
