<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\AdminAuditLog;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionManagementController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * List all subscriptions.
     */
    public function index(Request $request): JsonResponse
    {
        $subscriptions = Subscription::with('user')
            ->when($request->status, function ($q, $status) {
                match ($status) {
                    'active'  => $q->active(),
                    'expired' => $q->expired(),
                    default   => $q,
                };
            })
            ->when($request->plan_type, fn($q, $type) => $q->where('plan_type', $type))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'subscriptions' => SubscriptionResource::collection($subscriptions),
            'meta' => [
                'current_page' => $subscriptions->currentPage(),
                'last_page'    => $subscriptions->lastPage(),
                'total'        => $subscriptions->total(),
            ],
        ]);
    }

    /**
     * Extend a subscription.
     */
    public function extend(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'days'        => ['required', 'integer', 'min:1', 'max:365'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subscription = Subscription::findOrFail($id);
        $admin = $request->user();

        $oldExpiry = $subscription->expires_at->toDateString();
        $subscription = $this->subscriptionService->extend(
            $subscription,
            $validated['days'],
            $validated['admin_notes'] ?? null
        );

        AdminAuditLog::record(
            $admin->id,
            'subscription_extended',
            'Subscription',
            $subscription->id,
            ['expires_at' => $oldExpiry],
            ['expires_at' => $subscription->expires_at->toDateString(), 'days_added' => $validated['days']]
        );

        return response()->json([
            'message'      => "Subscription extended by {$validated['days']} days.",
            'subscription' => new SubscriptionResource($subscription->load('user')),
        ]);
    }

    /**
     * List subscription plans.
     */
    public function plans(): JsonResponse
    {
        $plans = SubscriptionPlan::all();

        return response()->json([
            'plans' => SubscriptionPlanResource::collection($plans),
        ]);
    }

    /**
     * Update subscription plan.
     */
    public function updatePlan(Request $request, $id): JsonResponse
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name'          => ['sometimes', 'string', 'max:255'],
            'price'         => ['sometimes', 'numeric', 'min:0'],
            'duration_days' => ['sometimes', 'integer', 'min:1'],
            'features'      => ['nullable', 'string'],
            'is_active'     => ['sometimes', 'boolean'],
        ]);

        $plan->update($validated);

        AdminAuditLog::record($request->user()->id, 'subscription_plan_updated', 'SubscriptionPlan', $plan->id);

        return response()->json([
            'message' => 'Plan updated.',
            'plan'    => new SubscriptionPlanResource($plan),
        ]);
    }
}
