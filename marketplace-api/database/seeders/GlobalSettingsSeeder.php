<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Notification master switches
            ['key' => 'global_email_enabled', 'value' => '1', 'group' => 'notifications', 'description' => 'Master switch: Enable/disable all outgoing emails system-wide'],
            ['key' => 'global_push_enabled', 'value' => '1', 'group' => 'notifications', 'description' => 'Master switch: Enable/disable all push notifications system-wide'],
            ['key' => 'global_audio_enabled', 'value' => '1', 'group' => 'notifications', 'description' => 'Master switch: Enable/disable audio alerts system-wide'],

            // Platform settings
            ['key' => 'platform_name', 'value' => 'Service Marketplace', 'group' => 'general', 'description' => 'Platform display name'],
            ['key' => 'platform_email', 'value' => 'support@servicemarketplace.com', 'group' => 'general', 'description' => 'Platform support email'],
            ['key' => 'default_search_radius', 'value' => '25', 'group' => 'search', 'description' => 'Default search radius in kilometers'],
            ['key' => 'max_search_radius', 'value' => '100', 'group' => 'search', 'description' => 'Maximum search radius in kilometers'],

            // Subscription settings
            ['key' => 'trial_duration_days', 'value' => '365', 'group' => 'subscriptions', 'description' => 'Trial period duration in days'],
            ['key' => 'trial_reminder_30_days', 'value' => '1', 'group' => 'subscriptions', 'description' => 'Send reminder 30 days before trial expiry'],
            ['key' => 'trial_reminder_7_days', 'value' => '1', 'group' => 'subscriptions', 'description' => 'Send reminder 7 days before trial expiry'],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general', 'description' => 'Enable maintenance mode for the platform'],
        ];

        foreach ($settings as $setting) {
            GlobalSetting::create($setting);
        }
    }
}
