<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Create a trial subscription for a newly registered worker.
     * 1-year trial period.
     */
    public function createTrial(User $user): Subscription
    {
        return Subscription::create([
            'user_id' => $user->id,
            'plan_type' => 'trial',
            'starts_at' => Carbon::today(),
            'expires_at' => Carbon::today()->addYear(),
            'is_active' => true,
            'amount_paid' => 0,
        ]);
    }

    /**
     * Check if user has an active subscription (trial or paid).
     */
    public function hasActiveSubscription(User $user): bool
    {
        return $user->subscriptions()
            ->where('is_active', true)
            ->where('expires_at', '>=', Carbon::today())
            ->exists();
    }

    /**
     * Get the current active subscription.
     */
    public function getActive(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->where('is_active', true)
            ->where('expires_at', '>=', Carbon::today())
            ->latest('expires_at')
            ->first();
    }

    /**
     * Get days remaining on active subscription.
     */
    public function daysRemaining(User $user): int
    {
        $subscription = $this->getActive($user);
        if (!$subscription) {
            return 0;
        }

        return max(0, Carbon::today()->diffInDays($subscription->expires_at, false));
    }

    /**
     * Extend a subscription by a given number of days.
     */
    public function extend(Subscription $subscription, int $days, ?string $adminNotes = null): Subscription
    {
        $subscription->update([
            'expires_at' => Carbon::parse($subscription->expires_at)->addDays($days),
            'admin_notes' => $adminNotes ?? $subscription->admin_notes,
        ]);

        return $subscription->fresh();
    }

    /**
     * Deactivate expired subscriptions.
     * Called by scheduled job daily.
     */
    public function deactivateExpired(): int
    {
        return Subscription::where('is_active', true)
            ->where('expires_at', '<', Carbon::today())
            ->update(['is_active' => false]);
    }

    /**
     * Get subscriptions expiring within N days (for reminder emails).
     */
    public function getExpiringWithin(int $days): \Illuminate\Database\Eloquent\Collection
    {
        return Subscription::with('user')
            ->where('is_active', true)
            ->whereBetween('expires_at', [
                Carbon::today(),
                Carbon::today()->addDays($days),
            ])
            ->get();
    }
}
