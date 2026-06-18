<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Basic',
                'price' => 0.00,
                'duration' => 'Monthly',
                'features' => 'Up to 1 booking/mo',
                'description' => 'Perfect for getting started.',
                'package_limit' => 1,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard',
                'price' => 149.00,
                'duration' => 'Monthly',
                'features' => 'Up to 50 bookings/mo',
                'description' => 'Ideal for growing businesses.',
                'package_limit' => 50,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'price' => 299.00,
                'duration' => 'Monthly',
                'features' => 'Unlimited bookings & VIP support',
                'description' => 'For top performing agencies.',
                'package_limit' => 9999, // practically unlimited
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'price' => 999.00,
                'duration' => 'Yearly',
                'features' => 'Custom white-label solutions',
                'description' => 'Custom solutions for enterprises.',
                'package_limit' => 9999,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
