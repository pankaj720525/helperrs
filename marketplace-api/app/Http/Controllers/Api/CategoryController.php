<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * List all active categories (public).
     */
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->ordered()
            ->withCount(['services' => fn($q) => $q->where('status', 'approved')])
            ->get();

        return response()->json([
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Show single category with its approved services count.
     */
    public function show($id): JsonResponse
    {
        $category = Category::withCount(['services' => fn($q) => $q->where('status', 'approved')])
            ->findOrFail($id);

        return response()->json([
            'category' => new CategoryResource($category),
        ]);
    }
}
