<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class GeoSearchService
{
    /**
     * Search for worker profiles within a radius of a given point.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $radiusKm Radius in kilometers
     * @param array $filters Additional filters (category_id, etc.)
     * @return \Illuminate\Database\Query\Builder
     */
    public function searchNearby(float $latitude, float $longitude, int $radiusKm = 25, array $filters = [])
    {
        $query = DB::table('worker_profiles')
            ->join('users', 'worker_profiles.user_id', '=', 'users.id')
            ->join('services', 'services.user_id', '=', 'users.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->select([
                'worker_profiles.id as worker_profile_id',
                'worker_profiles.user_id',
                'worker_profiles.address',
                'worker_profiles.bio',
                'worker_profiles.phone_public',
                'worker_profiles.email_public',
                'worker_profiles.specialties',
                'worker_profiles.is_available',
                'users.name as worker_name',
                'users.avatar as worker_avatar',
                'services.id as service_id',
                'services.title as service_title',
                'services.description as service_description',
                'services.price_min',
                'services.price_max',
                'services.status as service_status',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.slug as category_slug',
                DB::raw("ROUND(
                    ST_Distance_Sphere(
                        worker_profiles.location,
                        ST_GeomFromText('POINT({$longitude} {$latitude})')
                    ) / 1000, 1
                ) AS distance_km"),
            ])
            ->where('users.is_active', true)
            ->where('worker_profiles.is_available', true)
            ->where('services.status', 'approved')
            ->whereNotNull('worker_profiles.location')
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km', 'asc');

        // Apply category filter
        if (!empty($filters['category_id'])) {
            $query->where('services.category_id', $filters['category_id']);
        }

        // Apply category slug filter
        if (!empty($filters['category_slug'])) {
            $query->where('categories.slug', $filters['category_slug']);
        }

        // Apply price range filter
        if (!empty($filters['price_min'])) {
            $query->where('services.price_min', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('services.price_max', '<=', $filters['price_max']);
        }

        // Apply search keyword
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('services.title', 'LIKE', "%{$keyword}%")
                    ->orWhere('services.description', 'LIKE', "%{$keyword}%")
                    ->orWhere('users.name', 'LIKE', "%{$keyword}%");
            });
        }

        return $query;
    }

    /**
     * Format distance for frontend display (no raw coordinates).
     */
    public function formatDistance(float $distanceKm): string
    {
        if ($distanceKm < 1) {
            return round($distanceKm * 1000) . ' m away';
        }

        return round($distanceKm, 1) . ' km away';
    }
}
