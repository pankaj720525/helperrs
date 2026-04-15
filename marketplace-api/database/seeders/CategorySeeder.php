<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electrician', 'slug' => 'electrician', 'icon' => 'mdi:lightning-bolt', 'description' => 'Electrical wiring, repairs, installations, and maintenance', 'sort_order' => 1],
            ['name' => 'Plumber', 'slug' => 'plumber', 'icon' => 'mdi:pipe-wrench', 'description' => 'Plumbing installation, repairs, and water system maintenance', 'sort_order' => 2],
            ['name' => 'AC Repair', 'slug' => 'ac-repair', 'icon' => 'mdi:air-conditioner', 'description' => 'Air conditioning installation, servicing, and repair', 'sort_order' => 3],
            ['name' => 'Carpenter', 'slug' => 'carpenter', 'icon' => 'mdi:hammer-screwdriver', 'description' => 'Furniture making, wood repairs, and custom carpentry', 'sort_order' => 4],
            ['name' => 'Painter', 'slug' => 'painter', 'icon' => 'mdi:format-paint', 'description' => 'Interior and exterior painting services', 'sort_order' => 5],
            ['name' => 'Cleaning', 'slug' => 'cleaning', 'icon' => 'mdi:broom', 'description' => 'Deep cleaning, regular cleaning, and sanitization services', 'sort_order' => 6],
            ['name' => 'Pest Control', 'slug' => 'pest-control', 'icon' => 'mdi:bug', 'description' => 'Pest and termite control services', 'sort_order' => 7],
            ['name' => 'Appliance Repair', 'slug' => 'appliance-repair', 'icon' => 'mdi:washing-machine', 'description' => 'Repair and servicing of home appliances', 'sort_order' => 8],
            ['name' => 'Gardening', 'slug' => 'gardening', 'icon' => 'mdi:flower', 'description' => 'Lawn care, gardening, and landscaping services', 'sort_order' => 9],
            ['name' => 'Home Security', 'slug' => 'home-security', 'icon' => 'mdi:shield-home', 'description' => 'CCTV installation, security systems, and alarm setups', 'sort_order' => 10],
            ['name' => 'Interior Design', 'slug' => 'interior-design', 'icon' => 'mdi:sofa', 'description' => 'Interior design consultation and home decor', 'sort_order' => 11],
            ['name' => 'Moving & Packing', 'slug' => 'moving-packing', 'icon' => 'mdi:truck', 'description' => 'Household and office moving, packing, and relocation', 'sort_order' => 12],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
