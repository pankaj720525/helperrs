<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\AdminAuditLog;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reviews = Review::with(['user', 'service', 'worker'])
            ->when($request->moderated, function ($q, $mod) {
                $q->where('is_moderated', $mod === 'yes');
            })
            ->when($request->search, function ($q, $search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'reviews' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    public function moderate(Request $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->update(['is_moderated' => true]);

        AdminAuditLog::record($request->user()->id, 'review_moderated', 'Review', $review->id);

        return response()->json([
            'message' => 'Review moderated.',
            'review'  => new ReviewResource($review),
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        AdminAuditLog::record($request->user()->id, 'review_deleted', 'Review', $review->id);
        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }
}
