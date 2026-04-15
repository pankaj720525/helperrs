<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Review;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard statistics.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'stats' => [
                'total_users'            => User::count(),
                'active_users'           => User::where('is_active', true)->count(),
                'total_workers'          => User::whereIn('role', ['worker', 'both'])->count(),
                'total_services'         => Service::count(),
                'pending_services'       => Service::pending()->count(),
                'approved_services'      => Service::approved()->count(),
                'rejected_services'      => Service::rejected()->count(),
                'total_categories'       => Category::count(),
                'active_subscriptions'   => Subscription::active()->count(),
                'expiring_soon'          => Subscription::active()
                    ->where('expires_at', '<=', Carbon::today()->addDays(30))
                    ->count(),
                'total_reviews'          => Review::count(),
                'unmoderated_reviews'    => Review::unmoderated()->count(),
            ],
            'recent_services' => Service::pending()
                ->with('user', 'category')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($s) => [
                    'id'       => $s->hash_id,
                    'title'    => $s->title,
                    'user'     => $s->user->name,
                    'category' => $s->category->name,
                    'created'  => $s->created_at->diffForHumans(),
                ]),
            'recent_users' => User::latest()
                ->take(5)
                ->get()
                ->map(fn($u) => [
                    'id'      => $u->hash_id,
                    'name'    => $u->name,
                    'email'   => $u->email,
                    'role'    => $u->role,
                    'created' => $u->created_at->diffForHumans(),
                ]),
        ]);
    }
}
