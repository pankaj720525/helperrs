<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckExpiringSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Find subscriptions expiring within the next 7 days and notify their owners.
     */
    public function handle(): void
    {
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->with('user')
            ->get();

        foreach ($expiringSoon as $subscription) {
            if (!$subscription->user) {
                continue;
            }

            $daysLeft = now()->diffInDays($subscription->expires_at);

            // Create in-app notification
            $subscription->user->notifications()->create([
                'type' => 'subscription_expiring',
                'title' => 'Subscription Expiring Soon',
                'message' => "Your subscription expires in {$daysLeft} days. Renew now to continue listing services.",
                'data' => [
                    'subscription_id' => $subscription->hash_id,
                    'days_remaining' => $daysLeft,
                    'expires_at' => $subscription->expires_at->toDateString(),
                ],
            ]);

            Log::info("Subscription expiry notification sent", [
                'user_id' => $subscription->user_id,
                'days_left' => $daysLeft,
            ]);
        }

        // Auto-expire subscriptions past their expiry date
        Subscription::where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        Log::info("Expired subscriptions check completed", [
            'notified' => $expiringSoon->count(),
        ]);
    }
}
