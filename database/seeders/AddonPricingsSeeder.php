<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonPricingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricings = [
            [
                'type' => 'boost',
                'name' => 'Boost Active Tours',
                'description' => 'Boost your tours for featured placements.',
                'price' => 12.00,
                'duration_days' => 1,
            ],
            [
                'type' => 'ad',
                'name' => 'Home Hero Banner',
                'description' => 'Main spotlight visibility',
                'price' => 50.00,
                'duration_days' => null,
            ],
            [
                'type' => 'ad',
                'name' => 'Package Sidebar',
                'description' => 'Targeted sidebar visibility',
                'price' => 25.00,
                'duration_days' => null,
            ],
            [
                'type' => 'ad',
                'name' => 'Footer Banner',
                'description' => 'Broad bottom visibility',
                'price' => 35.00,
                'duration_days' => null,
            ],
            [
                'type' => 'ad',
                'name' => 'Under Domestic Packages',
                'description' => 'High visibility placement',
                'price' => 40.00,
                'duration_days' => null,
            ],
            [
                'type' => 'trusted_agent',
                'name' => 'Trusted Agent Verification',
                'description' => 'Stand out with a Blue Tick and Service Guaranteed badge.',
                'price' => 1499.00,
                'duration_days' => null,
            ],
        ];

        foreach ($pricings as $pricing) {
            // Check if already exists to prevent duplicates
            $exists = DB::table('addon_pricings')
                ->where('name', $pricing['name'])
                ->where('type', $pricing['type'])
                ->exists();
                
            if (!$exists) {
                DB::table('addon_pricings')->insert(array_merge($pricing, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
