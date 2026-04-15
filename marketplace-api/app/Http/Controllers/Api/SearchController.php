<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeoSearchService;
use App\Services\HashIdService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected GeoSearchService $geoSearchService,
        protected HashIdService $hashIdService
    ) {}

    /**
     * Search services by proximity to user's location.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat'      => ['required', 'numeric', 'between:-90,90'],
            'lng'      => ['required', 'numeric', 'between:-180,180'],
            'radius'   => ['nullable', 'integer', 'min:1', 'max:100'],
            'category' => ['nullable', 'string'],
            'keyword'  => ['nullable', 'string', 'max:255'],
            'price_min'=> ['nullable', 'numeric', 'min:0'],
            'price_max'=> ['nullable', 'numeric', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $radius = $validated['radius'] ?? 25;
        $perPage = $validated['per_page'] ?? 15;

        $filters = [
            'category_slug' => $validated['category'] ?? null,
            'keyword'       => $validated['keyword'] ?? null,
            'price_min'     => $validated['price_min'] ?? null,
            'price_max'     => $validated['price_max'] ?? null,
        ];

        $query = $this->geoSearchService->searchNearby(
            $validated['lat'],
            $validated['lng'],
            $radius,
            $filters
        );

        $results = $query->paginate($perPage);

        // Transform results with encrypted IDs and distance strings
        $transformed = collect($results->items())->map(function ($item) {
            return [
                'worker_profile_id' => $this->hashIdService->encodeForTable($item->worker_profile_id, 'worker_profiles'),
                'user_id'           => $this->hashIdService->encodeForTable($item->user_id, 'users'),
                'service_id'        => $this->hashIdService->encodeForTable($item->service_id, 'services'),
                'category_id'       => $this->hashIdService->encodeForTable($item->category_id, 'categories'),
                'worker_name'       => $item->worker_name,
                'worker_avatar'     => $item->worker_avatar ? asset('storage/' . $item->worker_avatar) : null,
                'address'           => $item->address,
                'bio'               => $item->bio,
                'specialties'       => json_decode($item->specialties, true),
                'is_available'      => (bool) $item->is_available,
                'service_title'     => $item->service_title,
                'service_description' => $item->service_description,
                'price_min'         => $item->price_min,
                'price_max'         => $item->price_max,
                'category_name'     => $item->category_name,
                'category_slug'     => $item->category_slug,
                'distance'          => $this->geoSearchService->formatDistance($item->distance_km),
                'distance_km'       => $item->distance_km,
            ];
        });

        return response()->json([
            'results' => $transformed,
            'meta' => [
                'current_page' => $results->currentPage(),
                'last_page'    => $results->lastPage(),
                'per_page'     => $results->perPage(),
                'total'        => $results->total(),
                'search_radius' => $radius,
            ],
        ]);
    }
}
