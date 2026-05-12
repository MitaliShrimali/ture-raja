<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $packages = collect($this->getStaticPackages());

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $packages = $packages->filter(function($pkg) use ($search) {
                return str_contains(strtolower($pkg['title']), $search) || str_contains(strtolower($pkg['location'] ?? ''), $search);
            });
        }

        if ($request->filled('categories')) {
            $cats = array_map('strtolower', $request->categories);
            $packages = $packages->filter(function($pkg) use ($cats) {
                return in_array(strtolower($pkg['category']), $cats);
            });
        }

        if ($request->filled('max_price')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                return $pkg['price'] <= $request->max_price;
            });
        }

        if ($request->filled('durations')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $durText = strtolower($pkg['duration']);
                foreach ($request->durations as $dur) {
                    if ($dur === '1-3 Days' && (str_contains($durText, '1 day') || str_contains($durText, '2 day') || str_contains($durText, '3 day') || str_contains($durText, '2 days') || str_contains($durText, '3 days'))) return true;
                    if ($dur === '4-7 Days' && (str_contains($durText, '4 day') || str_contains($durText, '5 day') || str_contains($durText, '6 day') || str_contains($durText, '7 day'))) return true;
                    if ($dur === '8-14 Days' && (str_contains($durText, '8 day') || str_contains($durText, '9 day') || str_contains($durText, '10 day') || str_contains($durText, '14 day'))) return true;
                    if ($dur === '15+ Days' && (str_contains($durText, '15 day') || str_contains($durText, 'month'))) return true;
                }
                return false;
            });
        }

        $sort = $request->input('sort', 'Recommended');
        if ($sort === 'Price: Low to High') {
            $packages = $packages->sortBy('price');
        } elseif ($sort === 'Price: High to Low') {
            $packages = $packages->sortByDesc('price');
        } elseif ($sort === 'Top Rated') {
            $packages = $packages->sortByDesc('rating');
        }

        return view('listing', ['packages' => $packages->values()]);
    }

    private function getStaticPackages()
    {
        return [
            ['id'=>1, 'slug'=>'monaco-luxury-tour', 'title'=>'Monaco Luxury Tour Package', 'location'=>'Monaco', 'price'=>44825, 'rating'=>'4.96', 'reviews'=>'672', 'duration'=>'2 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Top Rated'],
            ['id'=>2, 'slug'=>'vietnam-tour-package', 'title'=>'Vietnam Tour Package', 'location'=>'Vietnam', 'price'=>17320, 'rating'=>'4.91', 'reviews'=>'670', 'duration'=>'3 days 3 nights', 'groupSize'=>'2-3 guest', 'image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Best Sale'],
            ['id'=>3, 'slug'=>'char-dham-yatra', 'title'=>'Char Dham Yatra Package', 'location'=>'India', 'price'=>15463, 'rating'=>'4.86', 'reviews'=>'656', 'duration'=>'7 days 6 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=800', 'category'=>'Religious', 'badge'=>'25% Off'],
            ['id'=>4, 'slug'=>'goa-beach-package', 'title'=>'Goa Beach Holiday Package', 'location'=>'Goa, India', 'price'=>14755, 'rating'=>'4.74', 'reviews'=>'631', 'duration'=>'2 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Top Rated'],
            ['id'=>5, 'slug'=>'spiti-valley-adventure', 'title'=>'Spiti Valley Package', 'location'=>'Himachal, India', 'price'=>24840, 'rating'=>'4.51', 'reviews'=>'617', 'duration'=>'3 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Best Sale'],
            ['id'=>6, 'slug'=>'swiss-paris-delight', 'title'=>'Swiss Paris Delight', 'location'=>'Europe', 'price'=>51247, 'rating'=>'4.29', 'reviews'=>'608', 'duration'=>'7 days 6 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'25% Off'],
            ['id'=>7, 'slug'=>'kerala-backwaters', 'title'=>'Kerala Backwaters Escape', 'location'=>'Kerala, India', 'price'=>12500, 'rating'=>'4.65', 'reviews'=>'420', 'duration'=>'4 days 3 nights', 'groupSize'=>'2-4 guest', 'image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Popular'],
            ['id'=>8, 'slug'=>'dubai-desert-safari', 'title'=>'Dubai Desert Safari & Burj', 'location'=>'Dubai, UAE', 'price'=>29999, 'rating'=>'4.8', 'reviews'=>'890', 'duration'=>'4 days 3 nights', 'groupSize'=>'2-6 guest', 'image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Trending'],
            ['id'=>9, 'slug'=>'bali-luxury-villa', 'title'=>'Bali Luxury Villa Escape', 'location'=>'Bali, Indonesia', 'price'=>35000, 'rating'=>'4.9', 'reviews'=>'543', 'duration'=>'5 days 4 nights', 'groupSize'=>'2 guest', 'image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800', 'category'=>'Tropical', 'badge'=>'Honeymoon'],
            ['id'=>10, 'slug'=>'rishikesh-rafting', 'title'=>'Rishikesh Rafting & Yoga', 'location'=>'Rishikesh, India', 'price'=>8500, 'rating'=>'4.4', 'reviews'=>'312', 'duration'=>'2 days 1 nights', 'groupSize'=>'4-10 guest', 'image'=>'https://images.unsplash.com/photo-1596403204987-9323eb72322c?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Weekend'],
        ];
    }
}
