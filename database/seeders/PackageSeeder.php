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
        $packages = [
            [
                'id' => 1,
                'title' => 'Bali Luxury Villa Escape',
                'location' => 'Bali, Indonesia',
                'price' => 1299,
                'rating' => 4.8,
                'reviews' => 124,
                'duration' => '5 Days, 4 Nights',
                'image' => 'tourex/package_bali.png',
                'category' => 'Tropical',
                'badge' => 'Bestseller',
                'agent' => [
                    'name' => 'Sunrise Travels',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=ST',
                    'phone' => '+62 812-3456-7890',
                    'whatsapp' => '+62 812-3456-7890',
                ]
            ],
            [
                'id' => 2,
                'title' => 'Swiss Alps Adventure',
                'location' => 'Interlaken, Switzerland',
                'price' => 2499,
                'rating' => 4.9,
                'reviews' => 89,
                'duration' => '7 Days, 6 Nights',
                'image' => 'tourex/package_switzerland.png',
                'category' => 'Mountains',
                'badge' => 'Trending',
                'agent' => [
                    'name' => 'Alpine Tours',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=AT',
                    'phone' => '+41 33-123-4567',
                    'whatsapp' => '+41 33-123-4567',
                ]
            ],
            [
                'id' => 3,
                'title' => 'Paris Romantic Getaway',
                'location' => 'Paris, France',
                'price' => 1899,
                'rating' => 4.7,
                'reviews' => 210,
                'duration' => '4 Days, 3 Nights',
                'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',
                'category' => 'City',
                'badge' => 'Romantic',
                'agent' => [
                    'name' => 'Luxe Euro',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=LE',
                    'phone' => '+33 1-23-45-67-89',
                    'whatsapp' => '+33 1-23-45-67-89',
                ]
            ],
            [
                'id' => 4,
                'title' => 'Dubai Desert Safari & Burj',
                'location' => 'Dubai, UAE',
                'price' => 999,
                'rating' => 4.6,
                'reviews' => 345,
                'duration' => '3 Days, 2 Nights',
                'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=800',
                'category' => 'Adventure',
                'badge' => null,
                'agent' => [
                    'name' => 'Desert Kings',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=DK',
                    'phone' => '+971 4-123-4567',
                    'whatsapp' => '+971 4-123-4567',
                ]
            ],
            [
                'title' => 'Bali Serenity Expedition',
                'location' => 'Bali, Indonesia',
                'price' => 1200,
                'rating' => 4.8,
                'reviews' => 14,
                'duration' => '6 Days',
                'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=400',
                'category' => 'Tropical',
                'badge' => 'New',
                'status' => 'Draft',
                'agent' => [
                    'name' => 'Wanderlust Pro',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=WP',
                    'phone' => '+62 812-3456-7890',
                    'whatsapp' => '+62 812-3456-7890',
                ],
                'created_at' => now()->subMinutes(14),
            ],
            [
                'title' => 'Swiss Alps Winter Pass',
                'location' => 'Zermatt, Switzerland',
                'price' => 3450,
                'rating' => 4.9,
                'reviews' => 22,
                'duration' => '5 Days',
                'image' => 'https://images.unsplash.com/photo-1482862549707-f63cb32c5fd9?auto=format&fit=crop&q=80&w=400',
                'category' => 'Mountains',
                'badge' => 'Premium',
                'status' => 'Draft',
                'agent' => [
                    'name' => 'Peak Tours',
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=PT',
                    'phone' => '+41 33-123-4567',
                    'whatsapp' => '+41 33-123-4567',
                ],
                'created_at' => now()->subHours(2),
            ]
        ];

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
