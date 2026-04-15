<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Get current user's subscription status.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $this->subscriptionService->getActive($user);

        return response()->json([
            'has_active_subscription' => $this->subscriptionService->hasActiveSubscription($user),
            'days_remaining' => $this->subscriptionService->daysRemaining($user),
            'subscription' => $subscription ? new SubscriptionResource($subscription) : null,
        ]);
    }

    /**
     * Subscribe to a plan (monthly or yearly).
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan_type' => ['required', 'in:monthly,yearly'],
        ]);

        $user = $request->user();

        // Prevent subscribing if already has active non-trial subscription
        if ($this->subscriptionService->hasActiveSubscription($user)) {
            $current = $this->subscriptionService->getActive($user);
            if ($current && $current->plan_type !== 'trial') {
                return response()->json([
                    'message' => 'You already have an active subscription.',
                ], 422);
            }
        }

        $days = $validated['plan_type'] === 'yearly' ? 365 : 30;

        $subscription = $user->subscriptions()->create([
            'plan_type'  => $validated['plan_type'],
            'starts_at'  => now(),
            'expires_at' => now()->addDays($days),
        ]);

        return response()->json([
            'message'      => 'Subscription activated successfully.',
            'subscription' => new SubscriptionResource($subscription),
        ], 201);
    }
}
