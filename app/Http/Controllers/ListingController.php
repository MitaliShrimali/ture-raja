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

        // Assign correct agent and city to DB packages if they are empty
        $dbPackages = array_map(function($pkg) use ($staticAgents, $stateMapping) {
            $pkg = (array) $pkg;
            $slug = strtolower($pkg['slug'] ?? '');
            if ((empty($pkg['agent']) || $pkg['agent'] === 'Nomad Ventures') && isset($staticAgents[$slug])) {
                $pkg['agent'] = $staticAgents[$slug];
            }
            
            // Resolve city if empty
            if (empty($pkg['city'])) {
                $city = '';
                $loc = strtolower($pkg['location'] ?? '');
                foreach (array_keys($stateMapping) as $c) {
                    if (str_contains($loc, $c)) {
                        $city = ucfirst($c);
                        break;
                    }
                }
                if (!$city) {
                    $city = trim($pkg['location'] ?? 'Other');
                }
                $pkg['city'] = $city;
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

        // Fetch all agents to check their tiers
        try {
            $agentsList = \Illuminate\Support\Facades\DB::table('agents')
                ->select('id', 'name', 'service_guaranteed', 'plan_id')
                ->get();
                
            $agentsById = $agentsList->keyBy('id')->toArray();
            $agentsByName = $agentsList->keyBy(function($item) {
                return strtolower(trim($item->name));
            })->toArray();
        } catch (\Exception $e) {
            $agentsById = [];
            $agentsByName = [];
        }

        // Shuffle all packages first for randomization, then sort by tier
        $packages = $packages->shuffle()->sortByDesc(function ($p) use ($agentsById, $agentsByName) {
            $pkg = (array) $p;
            $tier = 1; // Default: unpaid / basic account
            
            // 3: Ad Placement
            if (!empty($pkg['ad_placement'])) {
                $tier = max($tier, 2);
            }
            
            // 2: Boosted Package
            if (!empty($pkg['is_boosted'])) {
                $tier = max($tier, 3);
            }
            
            $agentId = null;
            $agentName = null;
            $agentData = $pkg['agent'] ?? null;
            if (is_string($agentData)) {
                $decoded = json_decode($agentData, true);
                if (is_array($decoded)) {
                    $agentId = $decoded['id'] ?? null;
                    $agentName = $decoded['name'] ?? null;
                } else {
                    $agentName = $agentData;
                }
            } elseif (is_array($agentData)) {
                $agentId = $agentData['id'] ?? null;
                $agentName = $agentData['name'] ?? null;
            } elseif (is_object($agentData)) {
                $agentId = $agentData->id ?? null;
                $agentName = $agentData->name ?? null;
            }
            
            $agentInfo = null;
            if ($agentId && isset($agentsById[$agentId])) {
                $agentInfo = $agentsById[$agentId];
            } else {
                $agentKey = $agentName ? strtolower(trim($agentName)) : null;
                if ($agentKey && isset($agentsByName[$agentKey])) {
                    $agentInfo = $agentsByName[$agentKey];
                }
            }

            if ($agentInfo) {
                // 2: Paid plan
                if (!empty($agentInfo->plan_id) && $agentInfo->plan_id > 1) {
                    $tier = max($tier, 3);
                }
                // 1: Verified / Service Guaranteed (Top priority)
                if (!empty($agentInfo->service_guaranteed)) {
                    $tier = max($tier, 4);
                }
            }
            
            return $tier;
        })->values();

        // Keep a backup of all packages sorted by tier, to use for suggestions
        $allPackages = clone $packages;

        // ── Search by destination / title / agent name / keywords ──
        $searchVal = $request->input('search') ?: $request->input('mobile_search');
        if (!empty($searchVal)) {
            $search = strtolower(is_array($searchVal) ? implode(' ', $searchVal) : (string)$searchVal);
            
            $packages = $packages->map(function($pkg) use ($search) {
                $pkgArray = (array) $pkg;
                $title = strtolower((string)($pkgArray['title'] ?? ''));
                $location = strtolower((string)($pkgArray['location'] ?? ''));
                $city = strtolower((string)($pkgArray['city'] ?? ''));
                
                $agentName = '';
                if (isset($pkgArray['agent'])) {
                    if (is_array($pkgArray['agent'])) {
                        $agentName = $pkgArray['agent']['name'] ?? '';
                    } elseif (is_string($pkgArray['agent'])) {
                        $decoded = json_decode($pkgArray['agent'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $agentName = $decoded['name'] ?? '';
                        } else {
                            $agentName = $pkgArray['agent'];
                        }
                    } elseif (is_object($pkgArray['agent'])) {
                        $agentName = $pkgArray['agent']->name ?? '';
                    }
                }
                $agent = strtolower((string)$agentName);
                
                $keywordsStr = '';
                if (isset($pkgArray['keywords'])) {
                    if (is_array($pkgArray['keywords'])) {
                        $keywordsStr = implode(' ', $pkgArray['keywords']);
                    } elseif (is_string($pkgArray['keywords'])) {
                        $decoded = json_decode($pkgArray['keywords'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $keywordsStr = implode(' ', $decoded);
                        } else {
                            $keywordsStr = $pkgArray['keywords'];
                        }
                    }
                }
                $keywords = strtolower($keywordsStr);
                
                $score = 0;
                
                if ($search === $title || $search === $location || $search === $city) {
                    $score += 1000;
                }
                
                if (str_contains($title, $search)) $score += 500;
                if (str_contains($location, $search) || str_contains($city, $search)) $score += 400;
                if ($agent && str_contains($agent, $search)) $score += 200;
                if ($keywords && str_contains($keywords, $search)) $score += 100;
                
                if ($score > 0) {
                    $tt = strtolower($pkgArray['tour_type'] ?? '');
                    if (str_contains($tt, 'land')) $score += 80;
                    elseif (str_contains($tt, 'flight')) $score += 70;
                    elseif (str_contains($tt, 'train')) $score += 60;
                    elseif (str_contains($tt, 'bus')) $score += 50;
                    elseif (str_contains($tt, 'bullet')) $score += 40;
                    elseif (str_contains($tt, 'cruise')) $score += 30;
                    elseif (str_contains($tt, 'trek') || str_contains($tt, 'track')) $score += 20;
                    elseif (str_contains($tt, 'helicopter')) $score += 10;
                }
                
                if (is_object($pkg)) {
                    $pkg->search_score = $score;
                } elseif (is_array($pkg)) {
                    $pkg['search_score'] = $score;
                }
                return $pkg;
            })->filter(function($pkg) {
                $score = is_object($pkg) ? ($pkg->search_score ?? 0) : ($pkg['search_score'] ?? 0);
                return $score > 0;
            })->sortByDesc(function($pkg) {
                return is_object($pkg) ? ($pkg->search_score ?? 0) : ($pkg['search_score'] ?? 0);
            })->values();
        }

        // Keep a base copy of packages after text search but before any facet filters are applied
        $basePackages = clone $packages;

        // ── Destination Type / Category filter ─────────────────────
        if ($request->filled('category')) {
            $catTypes = array_map('strtolower', (array) $request->category);
            $packages = $packages->filter(function($pkg) use ($catTypes) {
                $pkg = (array) $pkg;
                return in_array(strtolower($pkg['category'] ?? ''), $catTypes);
            });
        }
        // ✈️ 🏠 Category filter ✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠✈️ 🏠
        if ($request->filled('categories')) {
            $cats = array_map('strtolower', (array) $request->categories);
            $packages = $packages->filter(function($pkg) use ($cats) {
                $pkg = (array) $pkg;
                $pkgCategory = $pkg['categories_list'] ?? '';
                
                if (is_string($pkgCategory) && (str_starts_with(trim($pkgCategory), '[') || str_starts_with(trim($pkgCategory), '{'))) {
                    $decoded = json_decode($pkgCategory, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $c) {
                            if (in_array(strtolower(trim($c)), $cats)) return true;
                        }
                        return false;
                    }
                }
                
                if (is_array($pkgCategory)) {
                    foreach ($pkgCategory as $c) {
                        if (in_array(strtolower(trim($c)), $cats)) return true;
                    }
                    return false;
                }
                
                return in_array(strtolower(trim((string)$pkgCategory)), $cats);
            });
        }

        // ── Price filter (Complex: Radio + Min + Max) ──────────────
        if ($request->input('price_radio') !== 'all') {
            if ($request->filled('price_radio') || $request->filled('min_price') || $request->filled('max_price')) {
                $packages = $packages->filter(function($pkg) use ($request) {
                    $pkg = (array) $pkg;
                    $price = $pkg['price'] ?? 0;
                    
                    $minPrice = $request->min_price ? (int)$request->min_price : 0;
                    $maxPrice = $request->max_price ? (int)$request->max_price : 9999999;
                    
                    // If a specific price radio is selected, override min/max logic
                    $radio = $request->price_radio;
                    if ($radio && $radio !== 'custom') {
                        if ($radio === 'under_20k') return $price < 20000;
                        if ($radio === '20k_40k') return $price >= 20000 && $price <= 40000;
                        if ($radio === '40k_60k') return $price >= 40000 && $price <= 60000;
                        if ($radio === 'above_60k') return $price > 60000;
                    }
                    
                    return $price >= $minPrice && $price <= $maxPrice;
                });
            }
        }

        // ── Duration (Nights) filter ───────────────────────────────
        if ($request->input('duration_radio', 'all') !== 'all') {
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
        if ($request->filled('ratings')) {
            $ratings = (array) $request->ratings;
            $packages = $packages->filter(function($pkg) use ($ratings) {
                $pkg = (array) $pkg;
                $pkgRating = (float)($pkg['rating'] ?? 0);
                
                if ($pkgRating == 0 && in_array('0', $ratings)) {
                    return true;
                }
                
                $roundedRating = (string) floor($pkgRating);
                return in_array($roundedRating, $ratings);
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
        
        // ── Service filters (Private Chef / Tour Manager) ──────────
        if ($request->filled('private_chef') && $request->private_chef == 1) {
            $packages = $packages->filter(function($pkg) {
                $pkg = (array) $pkg;
                $included = $pkg['included'] ?? [];
                if (is_string($included)) {
                    $included = json_decode($included, true) ?: [];
                }
                if (!is_array($included)) $included = [];
                foreach ($included as $item) {
                    if (str_contains(strtolower($item), 'chef')) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($request->filled('tour_manager') && $request->tour_manager == 1) {
            $packages = $packages->filter(function($pkg) {
                $pkg = (array) $pkg;
                $included = $pkg['included'] ?? [];
                if (is_string($included)) {
                    $included = json_decode($included, true) ?: [];
                }
                if (!is_array($included)) $included = [];
                foreach ($included as $item) {
                    if (str_contains(strtolower($item), 'manager')) {
                        return true;
                    }
                }
                return false;
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
        if ($request->filled('company')) {
            $companyName = strtolower(trim($request->company));
            $packages = $packages->filter(function($pkg) use ($companyName) {
                $pkg = (array) $pkg;
                $pAgentName = '';
                if (isset($pkg['agent'])) {
                    if (is_array($pkg['agent'])) {
                        $pAgentName = $pkg['agent']['name'] ?? '';
                    } elseif (is_string($pkg['agent'])) {
                        $decoded = json_decode($pkg['agent'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $pAgentName = $decoded['name'] ?? '';
                        } else {
                            $pAgentName = $pkg['agent'];
                        }
                    } elseif (is_object($pkg['agent'])) {
                        $pAgentName = $pkg['agent']->name ?? '';
                    }
                }
                return str_contains(strtolower(trim($pAgentName)), $companyName);
            });
        } elseif ($request->filled('agent_id')) {
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
                        $pAgentId = null;
                        if (is_array($pkg['agent'])) {
                            $pAgentName = $pkg['agent']['name'] ?? '';
                            $pAgentId = $pkg['agent']['id'] ?? null;
                        } elseif (is_string($pkg['agent'])) {
                            $decoded = json_decode($pkg['agent'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $pAgentName = $decoded['name'] ?? '';
                                $pAgentId = $decoded['id'] ?? null;
                            } else {
                                $pAgentName = $pkg['agent'];
                            }
                        }

                        if ($pAgentId && isset($agent->id) && (int)$pAgentId === (int)$agent->id) {
                            return true;
                        }

                        $pNameMatch = strtolower(trim($pAgentName));
                        $agentNameMatch = strtolower(trim($agent->name ?? ''));
                        $agentAgencyMatch = strtolower(trim($agent->agency_name ?? ''));
                        return $pNameMatch === $agentNameMatch || ($agentAgencyMatch !== '' && $pNameMatch === $agentAgencyMatch);
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

        $countryMapping = [
            'Himachal Pradesh' => 'India',
            'Goa' => 'India',
            'Uttarakhand' => 'India',
            'Kerala' => 'India',
            'West Bengal' => 'India',
            'Gujarat' => 'India',
            'Monaco' => 'Monaco',
            'Vietnam' => 'Vietnam',
            'UAE' => 'UAE',
            'Indonesia' => 'Indonesia',
            'France' => 'France',
            'Europe' => 'Europe'
        ];

        $locationCatalog = []; // Country => [ State => [ City => Count ] ]
        $filterCounts = [
            'tour_type' => [],
            'category' => [],
            'theme' => [],
            'holiday_type' => [
                'most popular' => 0,
                'honeymoon' => 0,
                'budget' => 0,
                'multi city' => 0,
                'short tour' => 0
            ],
            'destination_type' => ['domestic' => 0, 'international' => 0],
            'services' => ['private_chef' => 0, 'tour_manager' => 0],
        ];

        foreach ($basePackages as $pkg) {
            $pkgArray = is_object($pkg) && method_exists($pkg, 'toArray') ? $pkg->toArray() : (array)$pkg;

            $title = strtolower((string)($pkgArray['title'] ?? ''));

            // Infer missing tour_type for DB records
            if (empty($pkgArray['tour_type'])) {
                if (str_contains($title, 'flight') || str_contains($title, 'air')) {
                    $pkgArray['tour_type'] = 'Flight Package';
                } elseif (str_contains($title, 'train') || str_contains($title, 'rail')) {
                    $pkgArray['tour_type'] = 'Train Package';
                } elseif (str_contains($title, 'bus') || str_contains($title, 'coach')) {
                    $pkgArray['tour_type'] = 'Bus Package';
                } elseif (str_contains($title, 'cruise') || str_contains($title, 'boat')) {
                    $pkgArray['tour_type'] = 'Cruise Package';
                } else {
                    $pkgArray['tour_type'] = 'Land/Customised Packages';
                }
            }

            // Infer missing categories_list
            if (empty($pkgArray['categories_list']) || $pkgArray['categories_list'] === '[]') {
                $cats = [];
                if (str_contains($title, 'safari') || str_contains($title, 'wildlife')) $cats[] = 'Safari';
                if (str_contains($title, 'mountain') || str_contains($title, 'hill') || str_contains($title, 'valley')) $cats[] = 'Mountain';
                if (str_contains($title, 'beach') || str_contains($title, 'goa') || str_contains($title, 'island')) $cats[] = 'Beach';
                if (str_contains($title, 'desert') || str_contains($title, 'dune')) $cats[] = 'Desert';
                if (str_contains($title, 'temple') || str_contains($title, 'yatra') || str_contains($title, 'darshan')) $cats[] = 'Temples';
                if (str_contains($title, 'yacht') || str_contains($title, 'cruise')) $cats[] = 'Yacht';
                if (empty($cats)) $cats[] = 'City';
                $pkgArray['categories_list'] = json_encode($cats);
            }

            // Infer missing theme
            if (empty($pkgArray['theme'])) {
                if (str_contains($title, 'honeymoon') || str_contains($title, 'couple')) {
                    $pkgArray['theme'] = 'Honeymoon';
                } elseif (str_contains($title, 'adventure') || str_contains($title, 'trek')) {
                    $pkgArray['theme'] = 'Adventure';
                } else {
                    $pkgArray['theme'] = 'Family/Group';
                }
            }

            // --- Compute Filter Counts ---
            // Tour Type
            $tt = strtolower(trim($pkgArray['tour_type']));
            $tt = rtrim($tt, 's'); // normalize trailing s
            if ($tt) {
                $filterCounts['tour_type'][$tt] = ($filterCounts['tour_type'][$tt] ?? 0) + 1;
            }

            // Destination Type (Category)
            $cat = strtolower(trim($pkgArray['category'] ?? ''));
            if ($cat === 'domestic' || $cat === 'international') {
                $filterCounts['destination_type'][$cat]++;
            }

            // Theme
            $theme = strtolower(trim($pkgArray['theme'] ?? ''));
            if ($theme) {
                $filterCounts['theme'][$theme] = ($filterCounts['theme'][$theme] ?? 0) + 1;
            }

            // Categories list
            $pkgCategory = $pkgArray['categories_list'] ?? '';
            $cats = [];
            if (is_string($pkgCategory) && (str_starts_with(trim($pkgCategory), '[') || str_starts_with(trim($pkgCategory), '{'))) {
                $decoded = json_decode($pkgCategory, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $c) {
                        if (is_string($c)) $cats[] = strtolower(trim($c));
                    }
                }
            } elseif (is_array($pkgCategory)) {
                foreach ($pkgCategory as $c) {
                    if (is_string($c)) $cats[] = strtolower(trim($c));
                }
            } else {
                $cats[] = strtolower(trim((string)$pkgCategory));
            }
            foreach ($cats as $c) {
                if ($c) {
                    $filterCounts['category'][$c] = ($filterCounts['category'][$c] ?? 0) + 1;
                }
            }
            
            // Holiday Types
            $price = $pkgArray['price'] ?? 0;
            $rating = (float)($pkgArray['rating'] ?? 0);
            $badge = strtolower($pkgArray['badge'] ?? '');
            $title = strtolower($pkgArray['title'] ?? '');
            $nights = $pkgArray['nights'] ?? 0;
            if (!$nights && isset($pkgArray['duration'])) {
                if (preg_match('/(\d+)\s*nights?/', strtolower($pkgArray['duration']), $matches)) {
                    $nights = (int)$matches[1];
                }
            }
            
            if (in_array($badge, ['popular', 'top rated']) || $rating >= 4.7 || (int)($pkgArray['reviews'] ?? 0) > 500) $filterCounts['holiday_type']['most popular']++;
            
            $ht = $pkgArray['holiday_type'] ?? [];
            if (is_string($ht)) {
                $decoded = json_decode($ht, true);
                if (is_string($decoded)) $decoded = json_decode($decoded, true);
                $ht = is_array($decoded) ? $decoded : [$ht];
            }
            if (!is_array($ht)) $ht = [];
            foreach ($ht as $type) {
                $typeStr = strtolower(trim($type));
                if (isset($filterCounts['holiday_type'][$typeStr])) {
                    $filterCounts['holiday_type'][$typeStr]++;
                }
            }
            
            if ($theme === 'honeymoon' || str_contains($title, 'honeymoon')) $filterCounts['holiday_type']['honeymoon']++;
            if ($price < 20000) $filterCounts['holiday_type']['budget']++;
            if (str_contains(strtolower($pkgArray['location'] ?? ''), 'europe') || str_contains($title, 'delight') || str_contains($title, 'multi') || str_contains(strtolower($pkgArray['location'] ?? ''), ',')) $filterCounts['holiday_type']['multi city']++;
            if ($nights > 0 && $nights <= 3) $filterCounts['holiday_type']['short tour']++;

            // Services
            $included = $pkgArray['included'] ?? [];
            if (!is_array($included)) $included = [];
            $hasChef = false;
            $hasManager = false;
            foreach ($included as $item) {
                if (str_contains(strtolower($item), 'chef')) $hasChef = true;
                if (str_contains(strtolower($item), 'manager')) $hasManager = true;
            }
            if ($hasChef) $filterCounts['services']['private_chef']++;
            if ($hasManager) $filterCounts['services']['tour_manager']++;
            // ---------------------------

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
            
            $country = $countryMapping[$state] ?? 'International';
            if ($state === 'Europe') $country = 'Europe';

            if (!isset($locationCatalog[$country])) {
                $locationCatalog[$country] = [];
            }
            if (!isset($locationCatalog[$country][$state])) {
                $locationCatalog[$country][$state] = [];
            }
            if (!isset($locationCatalog[$country][$state][$city])) {
                $locationCatalog[$country][$state][$city] = 0;
            }
            $locationCatalog[$country][$state][$city]++;
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
        $sort = $request->input('sort', 'SHOW ALL');

        // When GUARANTEED SERVICE is selected, filter to only show verified (blue-tick) agent packages
        if ($sort === 'GUARANTEED SERVICE' || $sort === 'Recommended') {
            $packages = $packages->filter(function($p) use ($agentsById, $agentsByName) {
                $pkg = (array) $p;
                $agentId   = null;
                $agentName = null;
                $agentData = $pkg['agent'] ?? null;

                if (is_string($agentData)) {
                    $decoded = json_decode($agentData, true);
                    if (is_array($decoded)) {
                        $agentId   = $decoded['id']   ?? null;
                        $agentName = $decoded['name'] ?? null;
                    } else {
                        $agentName = $agentData;
                    }
                } elseif (is_array($agentData)) {
                    $agentId   = $agentData['id']   ?? null;
                    $agentName = $agentData['name'] ?? null;
                } elseif (is_object($agentData)) {
                    $agentId   = $agentData->id   ?? null;
                    $agentName = $agentData->name ?? null;
                }

                $agentInfo = null;
                if ($agentId && isset($agentsById[$agentId])) {
                    $agentInfo = $agentsById[$agentId];
                } else {
                    $key = $agentName ? strtolower(trim($agentName)) : null;
                    if ($key && isset($agentsByName[$key])) {
                        $agentInfo = $agentsByName[$key];
                    }
                }

                return $agentInfo && !empty($agentInfo->service_guaranteed);
            });
        } elseif ($sort === 'PRICE (LOW TO HIGH)' || $sort === 'Price: Low to High') {
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

        // If no packages or limited packages (< 4), provide suggestions
        $suggestedPackages = collect();
        if ($packages->count() <= 3) {
            $existingIds = $packages->pluck('id')->toArray();
            // Since $allPackages is already sorted by tier (premium/boosted/guaranteed first),
            // we just take the top 6 that aren't already in the results.
            $suggestedPackages = $allPackages->filter(function($p) use ($existingIds) {
                return !in_array(((array)$p)['id'] ?? null, $existingIds);
            })->take(6)->values();
        }

        if ($agent) {
            $firstWord = '';
            if (!empty($agent->name)) {
                $parts = explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $agent->name));
                $firstWord = strtolower(trim($parts[0] ?? ''));
            }

            $branches = \DB::table('branches')
                ->where('status', 'Online')
                ->where(function($query) use ($agent) {
                    $query->where('agent_id', $agent->id)
                          ->orWhere('agency_name', $agent->name);
                })
                ->get();

            return view('agent-showcase', [
                'packages' => $packages->values(),
                'suggestedPackages' => $suggestedPackages,
                'agent' => $agent,
                'sidebarAds' => $sidebarAds,
                'locationCatalog' => $locationCatalog,
                'filterCounts' => $filterCounts,
                'branches' => $branches
            ]);
        }

        return view('listing', [
            'packages' => $packages->values(),
            'suggestedPackages' => $suggestedPackages,
            'agent' => $agent,
            'sidebarAds' => $sidebarAds,
            'locationCatalog' => $locationCatalog,
            'filterCounts' => $filterCounts
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
