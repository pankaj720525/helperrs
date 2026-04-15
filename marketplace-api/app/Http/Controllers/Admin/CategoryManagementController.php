<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\AdminAuditLog;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryManagementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::withCount(['services', 'services as approved_services_count' => fn($q) => $q->approved()])
            ->ordered()
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'categories' => CategoryResource::collection($categories),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'total'        => $categories->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories'],
            'icon'        => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        AdminAuditLog::record($request->user()->id, 'category_created', 'Category', $category->id);

        return response()->json([
            'message'  => 'Category created.',
            'category' => new CategoryResource($category),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'icon'        => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['sometimes', 'boolean'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        AdminAuditLog::record($request->user()->id, 'category_updated', 'Category', $category->id);

        return response()->json([
            'message'  => 'Category updated.',
            'category' => new CategoryResource($category),
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($category->services()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with associated services.',
            ], 422);
        }

        AdminAuditLog::record($request->user()->id, 'category_deleted', 'Category', $category->id);
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
