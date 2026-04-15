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
}
