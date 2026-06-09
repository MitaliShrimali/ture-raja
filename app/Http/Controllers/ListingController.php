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

        // Sort packages to show popular tagged ones first, then by most reviewed/clicked
        $popularBadges = ['popular'];
        $packages = $packages
            ->sortByDesc(fn($p) => (int)(is_array($p) ? ($p['reviews'] ?? 0) : ($p->reviews ?? 0)))
            ->sortByDesc(fn($p) => (int)(is_array($p) ? ($p['clicks'] ?? 0) : ($p->clicks ?? 0)))
            ->sortByDesc(function ($p) use ($popularBadges) {
                $badge = strtolower(is_array($p) ? ($p['badge'] ?? '') : ($p->badge ?? ''));
                return in_array($badge, $popularBadges) ? 2 : (!empty($badge) ? 1 : 0);
            })->values();

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

        // ── Price filter (Complex: Radio + Min + Max) ──────────────
        if ($request->filled('price_radio') || $request->filled('min_price') || $request->filled('max_price')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $pkg = (array) $pkg;
                $price = $pkg['price'] ?? 0;
                
                $minPrice = $request->min_price ? (int)$request->min_price : 0;
                $maxPrice = $request->max_price ? (int)$request->max_price : 9999999;
                
                // If a specific price radio is selected, override min/max logic
                $radio = $request->price_radio;
                if ($radio && $radio !== 'all') {
                    if ($radio === 'under_20k') return $price < 20000;
                    if ($radio === '20k_40k') return $price >= 20000 && $price <= 40000;
                    if ($radio === '40k_60k') return $price >= 40000 && $price <= 60000;
                    if ($radio === 'above_60k') return $price > 60000;
                }
                
                return $price >= $minPrice && $price <= $maxPrice;
            });
        }

        // ── Duration (Nights) filter ───────────────────────────────
        if ($request->filled('min_nights') || $request->filled('max_nights')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $pkg = (array) $pkg;
                $nights = $pkg['nights'] ?? 0;
                if (!$nights && isset($pkg['duration'])) {
                    // Extract nights from string like "2 days 3 nights"
                    if (preg_match('/(\d+)\s*nights?/', strtolower($pkg['duration']), $matches)) {
                        $nights = (int)$matches[1];
                    }
                }
                $minN = $request->min_nights ? (int)$request->min_nights : 0;
                $maxN = $request->max_nights ? (int)$request->max_nights : 99;
                return $nights >= $minN && $nights <= $maxN;
            });
        }

        // ── Tour Type filter ───────────────────────────────────────
        if ($request->filled('tour_type')) {
            $types = array_map('strtolower', (array) $request->tour_type);
            $packages = $packages->filter(function($pkg) use ($types) {
                $pkg = (array) $pkg;
                return in_array(strtolower($pkg['tour_type'] ?? ''), $types);
            });
        }

        // ── City filter ────────────────────────────────────────────
        if ($request->filled('city')) {
            $cities = array_map('strtolower', (array) $request->city);
            $packages = $packages->filter(function($pkg) use ($cities) {
                $pkg = (array) $pkg;
                $pkgCity = strtolower($pkg['city'] ?? '');
                foreach ($cities as $city) {
                    if (str_contains($pkgCity, $city)) {
                        return true;
                    }
                }
                return false;
            });
        }

        // ── Rating filter ──────────────────────────────────────────
        if ($request->filled('min_rating')) {
            $packages = $packages->filter(function($pkg) use ($request) {
                $pkg = (array) $pkg;
                $minRating = (float)$request->min_rating;
                return ((float)($pkg['rating'] ?? 0)) >= $minRating;
            });
        }

        // ── Theme filter ───────────────────────────────────────────
        if ($request->filled('theme')) {
            $themes = array_map('strtolower', (array) $request->theme);
            $packages = $packages->filter(function($pkg) use ($themes) {
                $pkg = (array) $pkg;
                return in_array(strtolower($pkg['theme'] ?? ''), $themes);
            });
        }

        // ── Badge filter ───────────────────────────────────────────
        if ($request->filled('badge')) {
            $badges = array_map('strtolower', (array) $request->badge);
            $packages = $packages->filter(function($pkg) use ($badges) {
                $pkg = (array) $pkg;
                return in_array(strtolower($pkg['badge'] ?? ''), $badges);
            });
        }

        // ── Activities filter ──────────────────────────────────────
        if ($request->filled('activities')) {
            $activities = array_map('strtolower', (array) $request->activities);
            $packages = $packages->filter(function($pkg) use ($activities) {
                $pkg = (array) $pkg;
                $pkgActs = array_map('strtolower', (array)($pkg['activities'] ?? []));
                // Return true if package has ALL selected activities (or ANY, let's do ANY for broader match)
                return count(array_intersect($pkgActs, $activities)) > 0;
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

        // ── Holiday Types filter ────────────────────────────────────
        if ($request->filled('holiday_type')) {
            $htypes = array_map('strtolower', (array) $request->holiday_type);
            $packages = $packages->filter(function($pkg) use ($htypes) {
                $pkg = (array) $pkg;
                $price = $pkg['price'] ?? 0;
                $rating = (float)($pkg['rating'] ?? 0);
                $badge = strtolower($pkg['badge'] ?? '');
                $theme = strtolower($pkg['theme'] ?? '');
                $title = strtolower($pkg['title'] ?? '');
                
                // Extract nights
                $nights = $pkg['nights'] ?? 0;
                if (!$nights && isset($pkg['duration'])) {
                    if (preg_match('/(\d+)\s*nights?/', strtolower($pkg['duration']), $matches)) {
                        $nights = (int)$matches[1];
                    }
                }
                
                foreach ($htypes as $ht) {
                    if ($ht === 'most popular') {
                        if (in_array($badge, ['popular', 'top rated']) || $rating >= 4.7 || (int)($pkg['reviews'] ?? 0) > 500) {
                            return true;
                        }
                    }
                    if ($ht === 'honeymoon') {
                        if ($theme === 'honeymoon' || str_contains($title, 'honeymoon')) {
                            return true;
                        }
                    }
                    if ($ht === 'budget') {
                        if ($price < 20000) {
                            return true;
                        }
                    }
                    if ($ht === 'multi city') {
                        if (str_contains(strtolower($pkg['location'] ?? ''), 'europe') || str_contains($title, 'delight') || str_contains($title, 'multi') || str_contains(strtolower($pkg['location'] ?? ''), ',')) {
                            return true;
                        }
                    }
                    if ($ht === 'short tour') {
                        if ($nights > 0 && $nights <= 3) {
                            return true;
                        }
                    }
                }
                return false;
            });
        }

        // Build state and city counts from filtered packages (before applying the popup filter)
        $stateMapping = [
            'manali' => 'Himachal Pradesh',
            'shimla' => 'Himachal Pradesh',
            'kasol' => 'Himachal Pradesh',
            'goa' => 'Goa',
            'rishikesh' => 'Uttarakhand',
            'haridwar' => 'Uttarakhand',
            'munnar' => 'Kerala',
            'kochi' => 'Kerala',
            'darjeeling' => 'West Bengal',
            'rajkot' => 'Gujarat',
            'monaco' => 'Monaco',
            'hanoi' => 'Vietnam',
            'dubai' => 'UAE',
            'bali' => 'Indonesia',
            'paris' => 'France',
        ];

        $locationCatalog = []; // State => [ City => Count ]
        foreach ($packages as $pkg) {
            $pkgArray = (array)$pkg;
            $city = trim($pkgArray['city'] ?? '');
            
            if (!$city) {
                $loc = strtolower($pkgArray['location'] ?? '');
                foreach (array_keys($stateMapping) as $c) {
                    if (str_contains($loc, $c)) {
                        $city = ucfirst($c);
                        break;
                    }
                }
            }
            if (!$city) {
                $city = trim($pkgArray['location'] ?? 'Other');
            }

            $cityLower = strtolower($city);
            $state = $stateMapping[$cityLower] ?? 'Other';
            
            if ($state === 'Other') {
                $locLower = strtolower($pkgArray['location'] ?? '');
                if (str_contains($locLower, 'goa')) $state = 'Goa';
                elseif (str_contains($locLower, 'kerala')) $state = 'Kerala';
                elseif (str_contains($locLower, 'himachal')) $state = 'Himachal Pradesh';
                elseif (str_contains($locLower, 'uttarakhand')) $state = 'Uttarakhand';
                elseif (str_contains($locLower, 'gujarat')) $state = 'Gujarat';
                else $state = ucfirst(explode(',', $pkgArray['location'] ?? 'Other')[0]);
            }

            $state = trim($state);
            $city = trim($city);

            if (!isset($locationCatalog[$state])) {
                $locationCatalog[$state] = [];
            }
            if (!isset($locationCatalog[$state][$city])) {
                $locationCatalog[$state][$city] = 0;
            }
            $locationCatalog[$state][$city]++;
        }

        // Apply selected_cities filter if present
        if ($request->filled('selected_cities')) {
            $selectedCities = array_map('strtolower', (array) $request->selected_cities);
            $packages = $packages->filter(function($pkg) use ($selectedCities, $stateMapping) {
                $pkg = (array) $pkg;
                $city = strtolower($pkg['city'] ?? $pkg['location'] ?? '');
                
                if (in_array($city, $selectedCities)) {
                    return true;
                }
                foreach ($selectedCities as $sc) {
                    if (str_contains($city, $sc)) {
                        return true;
                    }
                }
                return false;
            });
        }

        // ── Sort ───────────────────────────────────────────────────
        $sort = $request->input('sort', 'GUARANTEED SERVICE');
        if ($sort === 'PRICE (LOW TO HIGH)' || $sort === 'Price: Low to High') {
            $packages = $packages->sortBy(fn($p) => ((array)$p)['price'] ?? 0);
        } elseif ($sort === 'PRICE (HIGH TO LOW)' || $sort === 'Price: High to Low') {
            $packages = $packages->sortByDesc(fn($p) => ((array)$p)['price'] ?? 0);
        } elseif ($sort === 'DURATION (LOW TO HIGH)') {
            $packages = $packages->sortBy(function($pkg) {
                $pkg = (array) $pkg;
                $nights = $pkg['nights'] ?? 0;
                if (!$nights && isset($pkg['duration'])) {
                    if (preg_match('/(\d+)\s*nights?/', strtolower($pkg['duration']), $matches)) {
                        $nights = (int)$matches[1];
                    }
                }
                return $nights;
            });
        } elseif ($sort === 'DURATION (HIGH TO LOW)') {
            $packages = $packages->sortByDesc(function($pkg) {
                $pkg = (array) $pkg;
                $nights = $pkg['nights'] ?? 0;
                if (!$nights && isset($pkg['duration'])) {
                    if (preg_match('/(\d+)\s*nights?/', strtolower($pkg['duration']), $matches)) {
                        $nights = (int)$matches[1];
                    }
                }
                return $nights;
            });
        } elseif ($sort === 'Top Rated') {
            $packages = $packages->sortByDesc(fn($p) => ((array)$p)['rating'] ?? 0);
        }

        // Fetch active ads for Package Sidebar
        try {
            $sidebarAds = \DB::table('ads')
                ->leftJoin('agents', 'ads.agent_id', '=', 'agents.id')
                ->select('ads.*', 'agents.logo as agent_logo', 'agents.name as agent_name')
                ->where('ads.status', 'Active')
                ->whereIn('ads.position', ['Package Sidebar', 'Package Details Sidebar'])
                ->orderBy('ads.id', 'desc')
                ->get();
        } catch (\Exception $e) {
            $sidebarAds = collect();
        }

        if ($agent) {
            return view('agent-showcase', [
                'packages' => $packages->values(),
                'agent' => $agent,
                'sidebarAds' => $sidebarAds,
                'locationCatalog' => $locationCatalog
            ]);
        }

        return view('listing', [
            'packages' => $packages->values(),
            'agent' => $agent,
            'sidebarAds' => $sidebarAds,
            'locationCatalog' => $locationCatalog
        ]);
    }

    private function getStaticPackages()
    {
        return [
            ['id'=>1, 'slug'=>'monaco-luxury-tour', 'title'=>'Monaco Luxury Tour Package', 'location'=>'Monaco', 'price'=>44825, 'rating'=>'4.96', 'reviews'=>'672', 'duration'=>'2 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Top Rated', 'agent'=>'Azure Horizons', 'tour_type'=>'Cruise Package', 'city'=>'Monaco', 'theme'=>'Honeymoon', 'activities'=>['Cable Car / Rope way', 'Nature']],
            ['id'=>2, 'slug'=>'vietnam-tour-package', 'title'=>'Vietnam Tour Package', 'location'=>'Vietnam', 'price'=>17320, 'rating'=>'4.91', 'reviews'=>'670', 'duration'=>'3 days 3 nights', 'nights'=>3, 'groupSize'=>'2-3 guest', 'image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures', 'tour_type'=>'Flight Package', 'city'=>'Hanoi', 'theme'=>'Adventure', 'activities'=>['Water Activities', 'Nature']],
            ['id'=>3, 'slug'=>'char-dham-yatra', 'title'=>'Char Dham Yatra Package', 'location'=>'India', 'price'=>15463, 'rating'=>'4.86', 'reviews'=>'656', 'duration'=>'7 days 6 nights', 'nights'=>6, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=800', 'category'=>'Religious', 'badge'=>'25% Off', 'agent'=>'Miths Holidays', 'tour_type'=>'Bus Package', 'city'=>'Haridwar', 'theme'=>'Religious', 'activities'=>['Hill Station', 'Religious']],
            ['id'=>4, 'slug'=>'goa-beach-package', 'title'=>'Goa Beach Holiday Package', 'location'=>'Goa, India', 'price'=>14755, 'rating'=>'4.74', 'reviews'=>'631', 'duration'=>'2 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Top Rated', 'agent'=>'Miths Holidays', 'tour_type'=>'Flight Package', 'city'=>'Goa', 'theme'=>'Honeymoon', 'activities'=>['Water Activities', 'Rides and Thrill']],
            ['id'=>5, 'slug'=>'spiti-valley-adventure', 'title'=>'Spiti Valley Package', 'location'=>'Himachal, India', 'price'=>24840, 'rating'=>'4.51', 'reviews'=>'617', 'duration'=>'3 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures', 'tour_type'=>'Train Package', 'city'=>'Manali', 'theme'=>'Adventure', 'activities'=>['Jeep Safari', 'Hill Station']],
            ['id'=>6, 'slug'=>'swiss-paris-delight', 'title'=>'Swiss Paris Delight', 'location'=>'Europe', 'price'=>51247, 'rating'=>'4.29', 'reviews'=>'608', 'duration'=>'7 days 6 nights', 'nights'=>6, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'25% Off', 'agent'=>'Globe Trotters', 'tour_type'=>'Flight Package', 'city'=>'Paris', 'theme'=>'Family/Group', 'activities'=>['Cable Car / Rope way', 'Nature']],
            ['id'=>7, 'slug'=>'kerala-backwaters', 'title'=>'Kerala Backwaters Escape', 'location'=>'Kerala, India', 'price'=>12500, 'rating'=>'4.65', 'reviews'=>'420', 'duration'=>'4 days 3 nights', 'nights'=>3, 'groupSize'=>'2-4 guest', 'image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=800', 'category'=>'Domestic', 'badge'=>'Popular', 'agent'=>'Miths Holidays', 'tour_type'=>'Train Package', 'city'=>'Kochi', 'theme'=>'Honeymoon', 'activities'=>['Nature', 'Water Activities']],
            ['id'=>8, 'slug'=>'dubai-desert-safari', 'title'=>'Dubai Desert Safari & Burj', 'location'=>'Dubai, UAE', 'price'=>29999, 'rating'=>'4.8', 'reviews'=>'890', 'duration'=>'4 days 3 nights', 'nights'=>3, 'groupSize'=>'2-6 guest', 'image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=800', 'category'=>'International', 'badge'=>'Trending', 'agent'=>'Atlas Global Travels', 'tour_type'=>'Flight Package', 'city'=>'Dubai', 'theme'=>'Family/Group', 'activities'=>['Jeep Safari', 'Rides and Thrill']],
            ['id'=>9, 'slug'=>'bali-luxury-villa', 'title'=>'Bali Luxury Villa Escape', 'location'=>'Bali, Indonesia', 'price'=>35000, 'rating'=>'4.9', 'reviews'=>'543', 'duration'=>'5 days 4 nights', 'nights'=>4, 'groupSize'=>'2 guest', 'image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800', 'category'=>'Tropical', 'badge'=>'Honeymoon', 'agent'=>'Miths Holidays', 'tour_type'=>'Flight Package', 'city'=>'Bali', 'theme'=>'Honeymoon', 'activities'=>['Nature', 'Water Activities']],
            ['id'=>10, 'slug'=>'rishikesh-rafting', 'title'=>'Rishikesh Rafting & Yoga', 'location'=>'Rishikesh, India', 'price'=>8500, 'rating'=>'4.4', 'reviews'=>'312', 'duration'=>'2 days 1 nights', 'nights'=>1, 'groupSize'=>'4-10 guest', 'image'=>'https://images.unsplash.com/photo-1596403204987-9323eb72322c?auto=format&fit=crop&q=80&w=800', 'category'=>'Adventure', 'badge'=>'Weekend', 'agent'=>'Atlas Global Travels', 'tour_type'=>'Bus Package', 'city'=>'Rishikesh', 'theme'=>'Adventure', 'activities'=>['Water Activities', 'Nature']],
        ];
    }
}
