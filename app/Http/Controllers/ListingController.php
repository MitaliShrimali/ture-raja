<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // Try to fetch from DB first
        try {
            $dbPackages = \App\Models\Package::where('status', 'Active')->get()->toArray();
        } catch (\Exception $e) {
            $dbPackages = [];
        }

        // Use DB packages and merge with static list to ensure fallback and demo packages are searchable and viewable!
        $static = $this->getStaticPackages();
        
        // Fast agent lookup map by slug
        $staticAgents = [];
        foreach ($static as $sPkg) {
            if (isset($sPkg['slug']) && isset($sPkg['agent'])) {
                $staticAgents[strtolower($sPkg['slug'])] = $sPkg['agent'];
            }
        }

        // Assign correct agent to DB packages if they are empty
        $dbPackages = array_map(function($pkg) use ($staticAgents) {
            $pkg = (array) $pkg;
            $slug = strtolower($pkg['slug'] ?? '');
            if ((empty($pkg['agent']) || $pkg['agent'] === 'Nomad Ventures') && isset($staticAgents[$slug])) {
                $pkg['agent'] = $staticAgents[$slug];
            }
            return $pkg;
        }, $dbPackages);

        $merged = $dbPackages;
        $dbTitles = array_map(fn($p) => strtolower($p['title'] ?? ''), $dbPackages);
        $dbSlugs = array_map(fn($p) => strtolower($p['slug'] ?? ''), $dbPackages);
        
        foreach ($static as $sPkg) {
            if (!in_array(strtolower($sPkg['title'] ?? ''), $dbTitles) && !in_array(strtolower($sPkg['slug'] ?? ''), $dbSlugs)) {
                $merged[] = $sPkg;
            }
        }

        $packages = collect($merged);

        // ── Search by destination / title ──────────────────────────
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $packages = $packages->filter(function($pkg) use ($search) {
                $pkg = (array) $pkg;
                return str_contains(strtolower($pkg['title'] ?? ''), $search)
                    || str_contains(strtolower($pkg['location'] ?? ''), $search);
            });
        }

        // ── Category filter ────────────────────────────────────────
        if ($request->filled('categories')) {
            $cats = array_map('strtolower', (array) $request->categories);
            $packages = $packages->filter(function($pkg) use ($cats) {
                $pkg = (array) $pkg;
                return in_array(strtolower($pkg['category'] ?? ''), $cats);
            });
        }

        // ── Price filter ───────────────────────────────────────────
        if ($request->filled('max_price')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $pkg = (array) $pkg;
                return ($pkg['price'] ?? 0) <= $request->max_price;
            });
        }

        // ── Duration filter ────────────────────────────────────────
        if ($request->filled('durations')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $pkg = (array) $pkg;
                $durText = strtolower($pkg['duration'] ?? '');
                foreach ((array) $request->durations as $dur) {
                    if ($dur === '1-3 Days' && preg_match('/^[1-3]\s*day/', $durText)) return true;
                    if ($dur === '4-7 Days' && preg_match('/^[4-7]\s*day/', $durText)) return true;
                    if ($dur === '8-14 Days' && preg_match('/^[8-9]|1[0-4]\s*day/', $durText)) return true;
                    if ($dur === '15+ Days' && preg_match('/^1[5-9]|[2-9]\d\s*day|month/', $durText)) return true;
                }
                return false;
            });
        }

        // ── Agent Filter ───────────────────────────────────────────
        $agent = null;
        if ($request->filled('agent_id')) {
            $agentId = intval($request->agent_id);
            $agent = \DB::table('agents')->where('id', $agentId)->first();
            
            if (!$agent) {
                // Defensive local mock data fallback
                $mocks = [
                    1 => (object)[
                        'id' => 1,
                        'name' => 'Nomad Ventures',
                        'email' => 'contact@nomad.com',
                        'phone' => '+1 (555) 019-2831',
                        'region' => 'Asia Pacific Region',
                        'tier' => 'Premium'
                    ],
                    2 => (object)[
                        'id' => 2,
                        'name' => 'Azure Horizons',
                        'email' => 'info@azure.com',
                        'phone' => '+44 (123) 456-7890',
                        'region' => 'London, United Kingdom',
                        'tier' => 'Standard'
                    ],
                    3 => (object)[
                        'id' => 3,
                        'name' => 'Globe Trotters',
                        'email' => 'contact@globetrotters.com',
                        'phone' => '+1 (555) 012-3456',
                        'region' => 'New York, USA',
                        'tier' => 'Enterprise'
                    ],
                    4 => (object)[
                        'id' => 4,
                        'name' => 'Atlas Global Travels',
                        'email' => 'info@atlasglobal.com',
                        'phone' => '+91 99999 88888',
                        'region' => 'New Delhi, India',
                        'tier' => 'Premium'
                    ],
                    64 => (object)[
                        'id' => 64,
                        'name' => 'Miths Holidays',
                        'email' => 'mithstours@gmail.com',
                        'phone' => '+91 7383682183',
                        'region' => '101 GF Nr Trikon Bagh Rajkot - Gujarat',
                        'tier' => 'Premium'
                    ]
                ];
                $agent = $mocks[$agentId] ?? $mocks[64];
            }

            if ($agent) {
                $packages = $packages->filter(function($pkg) use ($agent) {
                    $pkg = (array) $pkg;
                    if (isset($pkg['agent'])) {
                        $pAgentName = '';
                        if (is_array($pkg['agent'])) {
                            $pAgentName = $pkg['agent']['name'] ?? '';
                        } elseif (is_string($pkg['agent'])) {
                            $pAgentName = $pkg['agent'];
                        }
                        return strtolower(trim($pAgentName)) === strtolower(trim($agent->name));
                    }
                    return false;
                });
            }
        }

        // ── Sort ───────────────────────────────────────────────────
        $sort = $request->input('sort', 'Recommended');
        if ($sort === 'Price: Low to High')  $packages = $packages->sortBy(fn($p) => ((array)$p)['price'] ?? 0);
        elseif ($sort === 'Price: High to Low') $packages = $packages->sortByDesc(fn($p) => ((array)$p)['price'] ?? 0);
        elseif ($sort === 'Top Rated')       $packages = $packages->sortByDesc(fn($p) => ((array)$p)['rating'] ?? 0);

        if ($agent) {
            return view('agent-showcase', ['packages' => $packages->values(), 'agent' => $agent]);
        }

        return view('listing', ['packages' => $packages->values(), 'agent' => $agent]);
    }

    private function getStaticPackages()
    {
        return [
            ['id'=>1, 'slug'=>'monaco-luxury-tour', 'title'=>'Monaco Luxury Tour Package', 'location'=>'Monaco', 'price'=>44825, 'rating'=>'4.96', 'reviews'=>'672', 'duration'=>'2 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Top Rated', 'agent'=>'Azure Horizons'],
            ['id'=>2, 'slug'=>'vietnam-tour-package', 'title'=>'Vietnam Tour Package', 'location'=>'Vietnam', 'price'=>17320, 'rating'=>'4.91', 'reviews'=>'670', 'duration'=>'3 days 3 nights', 'groupSize'=>'2-3 guest', 'image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures'],
            ['id'=>3, 'slug'=>'char-dham-yatra', 'title'=>'Char Dham Yatra Package', 'location'=>'India', 'price'=>15463, 'rating'=>'4.86', 'reviews'=>'656', 'duration'=>'7 days 6 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=800', 'category'=>'Religious', 'badge'=>'25% Off', 'agent'=>'Miths Holidays'],
            ['id'=>4, 'slug'=>'goa-beach-package', 'title'=>'Goa Beach Holiday Package', 'location'=>'Goa, India', 'price'=>14755, 'rating'=>'4.74', 'reviews'=>'631', 'duration'=>'2 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Top Rated', 'agent'=>'Miths Holidays'],
            ['id'=>5, 'slug'=>'spiti-valley-adventure', 'title'=>'Spiti Valley Package', 'location'=>'Himachal, India', 'price'=>24840, 'rating'=>'4.51', 'reviews'=>'617', 'duration'=>'3 days 3 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures'],
            ['id'=>6, 'slug'=>'swiss-paris-delight', 'title'=>'Swiss Paris Delight', 'location'=>'Europe', 'price'=>51247, 'rating'=>'4.29', 'reviews'=>'608', 'duration'=>'7 days 6 nights', 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'25% Off', 'agent'=>'Globe Trotters'],
            ['id'=>7, 'slug'=>'kerala-backwaters', 'title'=>'Kerala Backwaters Escape', 'location'=>'Kerala, India', 'price'=>12500, 'rating'=>'4.65', 'reviews'=>'420', 'duration'=>'4 days 3 nights', 'groupSize'=>'2-4 guest', 'image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Popular', 'agent'=>'Miths Holidays'],
            ['id'=>8, 'slug'=>'dubai-desert-safari', 'title'=>'Dubai Desert Safari & Burj', 'location'=>'Dubai, UAE', 'price'=>29999, 'rating'=>'4.8', 'reviews'=>'890', 'duration'=>'4 days 3 nights', 'groupSize'=>'2-6 guest', 'image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Trending', 'agent'=>'Atlas Global Travels'],
            ['id'=>9, 'slug'=>'bali-luxury-villa', 'title'=>'Bali Luxury Villa Escape', 'location'=>'Bali, Indonesia', 'price'=>35000, 'rating'=>'4.9', 'reviews'=>'543', 'duration'=>'5 days 4 nights', 'groupSize'=>'2 guest', 'image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800', 'category'=>'Tropical', 'badge'=>'Honeymoon', 'agent'=>'Miths Holidays'],
            ['id'=>10, 'slug'=>'rishikesh-rafting', 'title'=>'Rishikesh Rafting & Yoga', 'location'=>'Rishikesh, India', 'price'=>8500, 'rating'=>'4.4', 'reviews'=>'312', 'duration'=>'2 days 1 nights', 'groupSize'=>'4-10 guest', 'image'=>'https://images.unsplash.com/photo-1596403204987-9323eb72322c?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Weekend', 'agent'=>'Atlas Global Travels'],
        ];
    }
}
