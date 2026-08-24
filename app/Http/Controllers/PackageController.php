<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function show($id)
    {
        try {
            $pkg = Package::where('id', $id)->where('status', 'Active')->first();
            if (!$pkg) {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }

        // Track package view for logged-in users
        if (Auth::check() && $pkg instanceof Package) {
            try {
                DB::table('user_viewed_packages')->updateOrInsert(
                    ['user_id' => Auth::id(), 'package_id' => $pkg->id],
                    ['viewed_at' => now(), 'updated_at' => now()]
                );
            } catch (\Exception $e) {}
        }

        return view('package.show', compact('pkg'));
    }

    private function getStaticPackage($id)
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
            ]
        ];

        foreach ($packages as $p) {
            if ($p['id'] == $id) return $p;
        }

        return $packages[0];
    }
}
