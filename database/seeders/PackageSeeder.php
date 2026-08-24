<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [];

        foreach ($packages as $package) {
            Package::create([
                'title' => $package['title'],
                'location' => $package['location'],
                'price' => $package['price'],
                'rating' => $package['rating'],
                'reviews' => $package['reviews'],
                'duration' => $package['duration'],
                'image' => $package['image'],
                'category' => $package['category'],
                'badge' => $package['badge'],
                'agent' => $package['agent'],
                'status' => $package['status'] ?? 'Active',
                'created_at' => $package['created_at'] ?? now(),
                'updated_at' => now(),
            ]);
        }
    }
}
