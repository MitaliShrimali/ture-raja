<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $dbPackages = \App\Models\Package::where('status', 'Active')->get()->toArray();
        } catch (\Exception $e) {
            $dbPackages = [];
        }

        $static = [];
        $staticAgents = [];
        
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

            if (empty($pkg['tour_type'])) {
                if (!empty($pkg['group_size']) && $pkg['group_size'] !== 'Any') {
                    $pkg['tour_type'] = $pkg['group_size'];
                } else {
                    $title = strtolower((string)($pkg['title'] ?? ''));
                    if (str_contains($title, 'flight') || str_contains($title, 'air')) {
                        $pkg['tour_type'] = 'Flight Package';
                    } elseif (str_contains($title, 'train') || str_contains($title, 'rail')) {
                        $pkg['tour_type'] = 'Train Package';
                    } elseif (str_contains($title, 'bus') || str_contains($title, 'coach')) {
                        $pkg['tour_type'] = 'Bus Package';
                    } elseif (str_contains($title, 'cruise') || str_contains($title, 'boat')) {
                        $pkg['tour_type'] = 'Cruise Package';
                    } else {
                        $pkg['tour_type'] = 'Land/Customised Packages';
                    }
                }
            }

            return $pkg;
        }, $dbPackages);

        $merged = $dbPackages;

        $packages = collect($merged);

        // Fetch all agents to check their tiers + location for card display
        try {
            $agentsList = \Illuminate\Support\Facades\DB::table('agents')
                ->select('id', 'name', 'service_guaranteed', 'plan_id', 'city', 'state', 'country', 'logo')
                ->get();
                
            $agentsById = $agentsList->keyBy('id')->toArray();
            $agentsByName = $agentsList->keyBy(function($item) {
                return strtolower(trim($item->name));
            })->toArray();
        } catch (\Exception $e) {
            $agentsById = [];
            $agentsByName = [];
        }

        // Enrich each package's agent field with city/state/logo from DB
        $packages = $packages->map(function($pkg) use ($agentsById, $agentsByName) {
            $pkg = (array) $pkg;
            $agentData = $pkg['agent'] ?? null;
            $agentId   = null;
            $agentName = null;

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

            $dbAgent = null;
            if ($agentId && isset($agentsById[$agentId])) {
                $dbAgent = (array) $agentsById[$agentId];
            } elseif ($agentName) {
                $key = strtolower(trim($agentName));
                if (isset($agentsByName[$key])) {
                    $dbAgent = (array) $agentsByName[$key];
                }
            }

            if ($dbAgent) {
                // Normalise agent to array and inject location fields
                if (is_string($pkg['agent'])) {
                    $decoded = json_decode($pkg['agent'], true);
                    $pkg['agent'] = is_array($decoded) ? $decoded : ['name' => $pkg['agent']];
                } elseif (!is_array($pkg['agent'])) {
                    $pkg['agent'] = ['name' => (string)($agentName ?? '')];
                }
                $pkg['agent']['city']  = $dbAgent['city']  ?? '';
                $pkg['agent']['state'] = $dbAgent['state'] ?? '';
                $pkg['agent']['country'] = $dbAgent['country'] ?? '';
                if (empty($pkg['agent']['logo']) && !empty($dbAgent['logo'])) {
                    $pkg['agent']['logo'] = $dbAgent['logo'];
                }
            }

            // Normalise/infer properties so they match between filter logic and count logic
            $titleLower = strtolower((string)($pkg['title'] ?? ''));

            // 1. Tour Type
            if (empty($pkg['tour_type'])) {
                if (str_contains($titleLower, 'flight') || str_contains($titleLower, 'air')) {
                    $pkg['tour_type'] = 'Flight Package';
                } elseif (str_contains($titleLower, 'train') || str_contains($titleLower, 'rail')) {
                    $pkg['tour_type'] = 'Train Package';
                } elseif (str_contains($titleLower, 'bus') || str_contains($titleLower, 'coach')) {
                    $pkg['tour_type'] = 'Bus Package';
                } elseif (str_contains($titleLower, 'cruise') || str_contains($titleLower, 'boat')) {
                    $pkg['tour_type'] = 'Cruise Package';
                } else {
                    $pkg['tour_type'] = 'Land/Customised Packages';
                }
            }

            // 2. Categories List
            if (empty($pkg['categories_list']) || $pkg['categories_list'] === '[]') {
                $cats = [];
                if (str_contains($titleLower, 'safari') || str_contains($titleLower, 'wildlife')) $cats[] = 'Safari';
                if (str_contains($titleLower, 'mountain') || str_contains($titleLower, 'hill') || str_contains($titleLower, 'valley')) $cats[] = 'Mountain';
                if (str_contains($titleLower, 'beach') || str_contains($titleLower, 'goa') || str_contains($titleLower, 'island')) $cats[] = 'Beach';
                if (str_contains($titleLower, 'desert') || str_contains($titleLower, 'dune')) $cats[] = 'Desert';
                if (str_contains($titleLower, 'temple') || str_contains($titleLower, 'yatra') || str_contains($titleLower, 'darshan')) $cats[] = 'Temples';
                if (str_contains($titleLower, 'yacht') || str_contains($titleLower, 'cruise')) $cats[] = 'Yacht';
                if (empty($cats)) $cats[] = 'City';
                $pkg['categories_list'] = json_encode($cats);
            }

            // 3. Theme
            if (empty($pkg['theme'])) {
                if (str_contains($titleLower, 'honeymoon') || str_contains($titleLower, 'couple')) {
                    $pkg['theme'] = 'Honeymoon';
                } elseif (str_contains($titleLower, 'adventure') || str_contains($titleLower, 'trek')) {
                    $pkg['theme'] = 'Adventure';
                } else {
                    $pkg['theme'] = 'Family/Group';
                }
            }

            // 4. Included (normalise to array)
            if (isset($pkg['included'])) {
                if (is_string($pkg['included'])) {
                    $pkg['included'] = json_decode($pkg['included'], true) ?: [];
                }
            } else {
                $pkg['included'] = [];
            }

            return $pkg;
        });

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

            $paidPlanIds = [];
            try {
                $paidPlanIds = \Illuminate\Support\Facades\DB::table('plans')->where('price', '>', 0)->pluck('id')->toArray();
            } catch (\Exception $e) {}

            if ($agentInfo) {
                // 2: Paid plan
                if (!empty($agentInfo->plan_id) && in_array($agentInfo->plan_id, $paidPlanIds)) {
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
            
            $packages = $packages->map(function($pkg) use ($search, $request) {
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
                
                $locationType = $request->input('location_type');
                $matchedSpecificType = false;

                if ($locationType && in_array($locationType, ['city', 'state', 'country'])) {
                    if (isset($pkgArray['keywords'])) {
                        $kws = is_string($pkgArray['keywords']) ? json_decode($pkgArray['keywords'], true) : $pkgArray['keywords'];
                        if (is_array($kws)) {
                            foreach ($kws as $kw) {
                                $kwParts = array_map('trim', explode(',', $kw));
                                $c_city = strtolower($kwParts[0] ?? '');
                                $c_state = strtolower($kwParts[1] ?? '');
                                $c_country = strtolower($kwParts[2] ?? '');
                                
                                if ($locationType === 'city' && str_contains($c_city, $search)) {
                                    $matchedSpecificType = true;
                                    $score += 2000;
                                } elseif ($locationType === 'state' && str_contains($c_state, $search)) {
                                    $matchedSpecificType = true;
                                    $score += 2000;
                                } elseif ($locationType === 'country' && str_contains($c_country, $search)) {
                                    $matchedSpecificType = true;
                                    $score += 2000;
                                }
                            }
                        }
                    }
                }

                if (!$locationType || ($locationType && $matchedSpecificType)) {
                    if ($search === $title || $search === $location || $search === $city) {
                        $score += 1000;
                    }
                    
                    if (str_contains($title, $search)) $score += 500;
                    if (str_contains($location, $search) || str_contains($city, $search)) $score += 400;
                    if ($agent && str_contains($agent, $search)) $score += 200;
                    if ($keywords && str_contains($keywords, $search)) $score += 100;

                    // --- Improved term-by-term matching ---
                    $searchTerms = array_filter(array_map('trim', preg_split('/[\s,]+/', $search)));
                    $keywordTerms = array_filter(array_map('trim', preg_split('/[\s,]+/', $keywords)));

                    foreach ($searchTerms as $term) {
                        if (strlen($term) > 2) {
                            if (str_contains($title, $term)) $score += 50;
                            if (str_contains($location, $term) || str_contains($city, $term)) $score += 40;
                            if ($agent && str_contains($agent, $term)) $score += 20;
                            if ($keywords && str_contains($keywords, $term)) $score += 80;
                        }
                    }

                    foreach ($keywordTerms as $kTerm) {
                        if (strlen($kTerm) > 2 && str_contains($search, $kTerm)) {
                            $score += 80;
                        }
                    }
                }
                
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
        if ($request->filled('price_radio') || $request->filled('min_price') || $request->filled('max_price')) {
            $radio = $request->input('price_radio', 'all');
            if ($radio !== 'all' || $request->filled('min_price') || $request->filled('max_price')) {
                $packages = $packages->filter(function($pkg) use ($request, $radio) {
                    $pkg = (array) $pkg;
                    $priceStr = $pkg['price'] ?? 0;
                    // Strip non-numeric characters before casting to integer (e.g., "25,000" -> 25000)
                    $price = (int) preg_replace('/[^0-9]/', '', (string)$priceStr);
                    
                    // If a specific price radio is selected, override min/max logic
                    if ($radio && $radio !== 'custom' && $radio !== 'all') {
                        if ($radio === 'under_20k') return $price < 20000;
                        if ($radio === '20k_40k') return $price >= 20000 && $price <= 40000;
                        if ($radio === '40k_60k') return $price >= 40000 && $price <= 60000;
                        if ($radio === 'above_60k') return $price > 60000;
                    }
                    
                    $minPrice = $request->filled('min_price') ? (int)$request->min_price : 0;
                    $maxPrice = $request->filled('max_price') ? (int)$request->max_price : 9999999;
                    
                    return $price >= $minPrice && $price <= $maxPrice;
                });
            }
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
                $minN = $request->filled('min_nights') ? (int)$request->min_nights : 0;
                $maxN = $request->filled('max_nights') ? (int)$request->max_nights : 999;
                return $nights >= $minN && $nights <= $maxN;
            });
        }

        // ── Tour Type filter ───────────────────────────────────────
        if ($request->filled('tour_type')) {
            $types = array_map('strtolower', (array) $request->tour_type);
            $normalizedTypes = array_map(function($t) {
                if (str_contains($t, 'land') || str_contains($t, 'custom')) return 'land';
                if (str_contains($t, 'bullet') || str_contains($t, 'bike')) return 'bullet';
                if (str_contains($t, 'flight') || str_contains($t, 'air')) return 'flight';
                if (str_contains($t, 'train') || str_contains($t, 'rail')) return 'train';
                if (str_contains($t, 'bus') || str_contains($t, 'coach')) return 'bus';
                if (str_contains($t, 'cruise') || str_contains($t, 'ship') || str_contains($t, 'boat')) return 'cruise';
                if (str_contains($t, 'track') || str_contains($t, 'hike') || str_contains($t, 'trek')) return 'tracking';
                if (str_contains($t, 'helicopter') || str_contains($t, 'sky')) return 'helicopter';
                return $t;
            }, $types);

            $packages = $packages->filter(function($pkg) use ($normalizedTypes) {
                $pkg = (array) $pkg;
                $t = strtolower($pkg['tour_type'] ?? '');
                $normT = $t;
                if (str_contains($t, 'land') || str_contains($t, 'custom')) $normT = 'land';
                elseif (str_contains($t, 'bullet') || str_contains($t, 'bike')) $normT = 'bullet';
                elseif (str_contains($t, 'flight') || str_contains($t, 'air')) $normT = 'flight';
                elseif (str_contains($t, 'train') || str_contains($t, 'rail')) $normT = 'train';
                elseif (str_contains($t, 'bus') || str_contains($t, 'coach')) $normT = 'bus';
                elseif (str_contains($t, 'cruise') || str_contains($t, 'ship') || str_contains($t, 'boat')) $normT = 'cruise';
                elseif (str_contains($t, 'track') || str_contains($t, 'hike') || str_contains($t, 'trek')) $normT = 'tracking';
                elseif (str_contains($t, 'helicopter') || str_contains($t, 'sky')) $normT = 'helicopter';

                return in_array($normT, $normalizedTypes);
            });
        }

        // ── Agent Location filter ────────────────────────────────────
        if ($request->filled('city')) {
            $searchTerms = explode(',', $request->city);
            $searchTerms = array_filter(array_map('trim', array_map('strtolower', $searchTerms)));

            $packages = $packages->filter(function($pkg) use ($searchTerms, $agentsById, $agentsByName) {
                $pkg = (array) $pkg;
                
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
                    $agentInfo = (array) $agentsById[$agentId];
                } else {
                    $agentKey = $agentName ? strtolower(trim($agentName)) : null;
                    if ($agentKey && isset($agentsByName[$agentKey])) {
                        $agentInfo = (array) $agentsByName[$agentKey];
                    }
                }

                if (!$agentInfo) {
                    return false;
                }
                
                $agentCity = strtolower(trim($agentInfo['city'] ?? ''));
                $agentState = strtolower(trim($agentInfo['state'] ?? ''));
                $agentCountry = strtolower(trim($agentInfo['country'] ?? ''));
                
                foreach ($searchTerms as $term) {
                    if (empty($term)) continue;
                    
                    $matchedTerm = false;
                    if ($agentCity && str_contains($agentCity, $term)) $matchedTerm = true;
                    if ($agentState && str_contains($agentState, $term)) $matchedTerm = true;
                    if ($agentCountry && str_contains($agentCountry, $term)) $matchedTerm = true;
                    
                    if (!$matchedTerm) {
                        return false;
                    }
                }
                
                return true;
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

        foreach ($packages as $pkg) {
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
            $tt = strtolower(trim($pkgArray['tour_type'] ?? ''));
            $normTt = $tt;
            if (str_contains($tt, 'land') || str_contains($tt, 'custom')) $normTt = 'land';
            elseif (str_contains($tt, 'bullet') || str_contains($tt, 'bike')) $normTt = 'bullet';
            elseif (str_contains($tt, 'flight') || str_contains($tt, 'air')) $normTt = 'flight';
            elseif (str_contains($tt, 'train') || str_contains($tt, 'rail')) $normTt = 'train';
            elseif (str_contains($tt, 'bus') || str_contains($tt, 'coach')) $normTt = 'bus';
            elseif (str_contains($tt, 'cruise') || str_contains($tt, 'ship') || str_contains($tt, 'boat')) $normTt = 'cruise';
            elseif (str_contains($tt, 'track') || str_contains($tt, 'hike') || str_contains($tt, 'trek')) $normTt = 'tracking';
            elseif (str_contains($tt, 'helicopter') || str_contains($tt, 'sky')) $normTt = 'helicopter';
            else $normTt = rtrim($tt, 's');

            if ($normTt) {
                $filterCounts['tour_type'][$normTt] = ($filterCounts['tour_type'][$normTt] ?? 0) + 1;
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
            if (is_string($included)) {
                $included = json_decode($included, true) ?: [];
            }
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

        // Sort Location Catalog Alphabetically
        ksort($locationCatalog);
        foreach ($locationCatalog as &$statesItem) {
            ksort($statesItem);
            foreach ($statesItem as &$citiesItem) {
                ksort($citiesItem);
            }
        }
        unset($statesItem, $citiesItem);

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
        if (strtolower($sort) === 'guaranteed service' || strtolower($sort) === 'recommended') {
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
        } elseif (strtolower($sort) === 'price (low to high)' || strtolower($sort) === 'price: low to high') {
            $packages = $packages->sortBy(fn($p) => ((array)$p)['price'] ?? 0);
        } elseif (strtolower($sort) === 'price (high to low)' || strtolower($sort) === 'price: high to low') {
            $packages = $packages->sortByDesc(fn($p) => ((array)$p)['price'] ?? 0);
        } elseif (strtolower($sort) === 'duration (low to high)') {
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
        } elseif (strtolower($sort) === 'duration (high to low)') {
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
            
            $paidPlanIds = [];
            try {
                $paidPlanIds = \Illuminate\Support\Facades\DB::table('plans')->where('price', '>', 0)->pluck('id')->toArray();
            } catch (\Exception $e) {}

            $paidPackages = $allPackages->filter(function($p) use ($existingIds, $agentsById, $agentsByName, $paidPlanIds) {
                $pkg = (array) $p;
                if (in_array($pkg['id'] ?? null, $existingIds)) return false;
                
                $agentId = null;
                $agentName = null;
                $agentData = $pkg['agent'] ?? null;
                if (is_string($agentData)) {
                    $decoded = json_decode($agentData, true);
                    if (is_array($decoded)) {
                        $agentId = $decoded['id'] ?? null;
                        $agentName = $decoded['name'] ?? null;
                    }
                } elseif (is_array($agentData)) {
                    $agentId = $agentData['id'] ?? null;
                    $agentName = $agentData['name'] ?? null;
                }
                
                $agentInfo = null;
                if ($agentId && isset($agentsById[$agentId])) {
                    $agentInfo = $agentsById[$agentId];
                } elseif ($agentName) {
                    $key = strtolower(trim($agentName));
                    if (isset($agentsByName[$key])) {
                        $agentInfo = $agentsByName[$key];
                    }
                }
                return $agentInfo && (!empty($agentInfo->plan_id) && in_array($agentInfo->plan_id, $paidPlanIds));
            });

            if ($paidPackages->count() > 0) {
                $suggestedPackages = $paidPackages->shuffle()->take(6)->values();
            } else {
                $suggestedPackages = $allPackages->filter(function($p) use ($existingIds) {
                    return !in_array(((array)$p)['id'] ?? null, $existingIds);
                })->shuffle()->take(6)->values();
            }
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

            $profile_images = \DB::table('agent_profile_images')
                ->where('agent_id', $agent->id)
                ->get();

            $feedbacks = \App\Models\AgentFeedback::where('agent_id', $agent->id)->latest()->get();

            return view('agent-showcase', [
                'packages' => $packages->values(),
                'suggestedPackages' => $suggestedPackages,
                'agent' => $agent,
                'sidebarAds' => $sidebarAds,
                'locationCatalog' => $locationCatalog,
                'filterCounts' => $filterCounts,
                'branches' => $branches,
                'profile_images' => $profile_images,
                'feedbacks' => $feedbacks
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
