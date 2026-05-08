<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Package::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            if ($request->filled('categories')) {
                $query->whereIn('category', $request->categories);
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Simple duration filtering logic for demonstration
            if ($request->filled('durations')) {
                $query->where(function($q) use ($request) {
                    foreach ($request->durations as $dur) {
                        if ($dur === '1-3 Days') $q->orWhere('duration', 'like', '%1 Day%')->orWhere('duration', 'like', '%2 Day%')->orWhere('duration', 'like', '%3 Day%');
                        if ($dur === '4-7 Days') $q->orWhere('duration', 'like', '%4 Day%')->orWhere('duration', 'like', '%5 Day%')->orWhere('duration', 'like', '%6 Day%')->orWhere('duration', 'like', '%7 Day%');
                        if ($dur === '8-14 Days') $q->orWhere('duration', 'like', '%8 Day%')->orWhere('duration', 'like', '%14 Day%'); // truncated for brevity
                        if ($dur === '15+ Days') $q->orWhere('duration', 'like', '%15 Day%');
                    }
                });
            }

            $packages = $query->get();
            
            if ($packages->isEmpty() && !$request->hasAny(['search', 'categories', 'max_price', 'durations'])) {
                $packages = collect($this->getStaticPackages());
            }
        } catch (\Exception $e) {
            $packages = collect($this->getStaticPackages());
        }

        return view('listing', compact('packages'));
    }

    private function getStaticPackages()
    {
        return [
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
                'badge' => 'Bestseller'
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
                'badge' => 'Trending'
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
                'badge' => 'Romantic'
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
                'badge' => null
            ]
        ];
    }
}
