<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $packages = Package::where('status', 'Active')->get();
            if ($packages->isEmpty()) {
                $packages = collect($this->getStaticPackages());
            }
        } catch (\Exception $e) {
            $packages = collect($this->getStaticPackages());
        }

        return view('welcome', compact('packages'));
    }

    public function profile()
    {
        try {
            $packages = Package::where('status', 'Active')->get();
            if ($packages->isEmpty()) {
                $packages = collect($this->getStaticPackages());
            }
        } catch (\Exception $e) {
            $packages = collect($this->getStaticPackages());
        }

        return view('profile', compact('packages'));
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
