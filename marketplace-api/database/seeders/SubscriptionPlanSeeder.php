<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Monthly Basic',
                'type' => 'monthly',
                'price' => 299.00,
                'duration_days' => 30,
                'features' => 'Service listing, Customer chat, Profile page',
                'is_active' => true,
            ],
            [
                'name' => 'Yearly Pro',
                'type' => 'yearly',
                'price' => 2499.00,
                'duration_days' => 365,
                'features' => 'Service listing, Customer chat, Profile page, Priority search ranking, Featured badge',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
