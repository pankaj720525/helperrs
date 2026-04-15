<?php

namespace App\Services;

use App\Models\GlobalSetting;
use App\Models\User;

class NotificationGatekeeper
{
    /**
     * Check if a notification can be sent to a user via a specific channel.
     *
     * Three-tier check:
     * 1. Global master switch (admin controls)
     * 2. Admin user-override (admin can mute specific users)
     * 3. User preferences (user's own settings)
     *
     * @param User $user
     * @param string $channel 'email' | 'push' | 'audio'
     * @return bool
     */
    public function canSend(User $user, string $channel): bool
    {
        // Tier 1: Global Master Switch
        if (!$this->isGlobalEnabled($channel)) {
            return false;
        }

        // Tier 2: Admin User-Override
        if ($this->isAdminOverridden($user, $channel)) {
            return false;
        }

        // Tier 3: User Preferences
        if (!$this->isUserPreferenceEnabled($user, $channel)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the global master switch is enabled for a channel.
     */
    protected function isGlobalEnabled(string $channel): bool
    {
        $key = "global_{$channel}_enabled";
        $setting = GlobalSetting::where('key', $key)->first();

        // Default to enabled if setting doesn't exist
        return $setting ? (bool) $setting->value : true;
    }

    /**
     * Check if admin has overridden (muted) this user for a channel.
     */
    protected function isAdminOverridden(User $user, string $channel): bool
    {
        return match ($channel) {
            'email' => $user->admin_email_override,
            'push', 'audio' => $user->admin_push_override,
            default => false,
        };
    }

    /**
     * Check if the user has enabled this channel in their preferences.
     */
    protected function isUserPreferenceEnabled(User $user, string $channel): bool
    {
        $preferences = $user->notification_preferences ?? [];

        // Default all channels to enabled
        return $preferences[$channel] ?? true;
    }

    /**
     * Get the full notification status for a user across all channels.
     */
    public function getStatus(User $user): array
    {
        return [
            'email' => [
                'global' => $this->isGlobalEnabled('email'),
                'admin_override' => $this->isAdminOverridden($user, 'email'),
                'user_preference' => $this->isUserPreferenceEnabled($user, 'email'),
                'can_send' => $this->canSend($user, 'email'),
            ],
            'push' => [
                'global' => $this->isGlobalEnabled('push'),
                'admin_override' => $this->isAdminOverridden($user, 'push'),
                'user_preference' => $this->isUserPreferenceEnabled($user, 'push'),
                'can_send' => $this->canSend($user, 'push'),
            ],
            'audio' => [
                'global' => $this->isGlobalEnabled('audio'),
                'admin_override' => $this->isAdminOverridden($user, 'audio'),
                'user_preference' => $this->isUserPreferenceEnabled($user, 'audio'),
                'can_send' => $this->canSend($user, 'audio'),
            ],
        ];
    }
}
