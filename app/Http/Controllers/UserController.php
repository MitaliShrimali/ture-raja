<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Package;

class UserController extends Controller
{
    // ─── Helpers ─────────────────────────────────────────────────────
    private function userId(): int
    {
        // In production this would be Auth::id().
        // During demo we use user id 1 (the seeded demo user).
        return Auth::check() ? Auth::id() : 1;
    }

    private function getStaticPackages(): array
    {
        return [
            ['id'=>1, 'slug'=>'monaco-luxury-tour', 'title'=>'Monaco Luxury Tour Package', 'location'=>'Monaco', 'price'=>44825, 'old_price'=>59825, 'rating'=>'4.96', 'reviews'=>'672', 'duration'=>'2 days 3 nights', 'duration_days'=>2, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=600', 'category'=>'international', 'badge'=>'Top Rated', 'agent'=>'Azure Horizons'],
            ['id'=>2, 'slug'=>'vietnam-tour-package', 'title'=>'Vietnam Tour Package', 'location'=>'Vietnam', 'price'=>17320, 'old_price'=>25320, 'rating'=>'4.91', 'reviews'=>'670', 'duration'=>'3 days 3 nights', 'duration_days'=>3, 'groupSize'=>'2-3 guest', 'image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=600', 'category'=>'international', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures'],
            ['id'=>3, 'slug'=>'char-dham-yatra', 'title'=>'Char Dham Yatra Package', 'location'=>'India', 'price'=>15463, 'old_price'=>19000, 'rating'=>'4.86', 'reviews'=>'656', 'duration'=>'7 days 6 nights', 'duration_days'=>7, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=600', 'category'=>'religious', 'badge'=>'25% Off', 'agent'=>'Miths Holidays'],
            ['id'=>4, 'slug'=>'goa-beach-package', 'title'=>'Goa Beach Holiday Package', 'location'=>'Goa, India', 'price'=>14755, 'old_price'=>19825, 'rating'=>'4.74', 'reviews'=>'631', 'duration'=>'2 days 3 nights', 'duration_days'=>2, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600', 'category'=>'domestic', 'badge'=>'Top Rated', 'agent'=>'Miths Holidays'],
            ['id'=>5, 'slug'=>'spiti-valley-adventure', 'title'=>'Spiti Valley Package', 'location'=>'Himachal, India', 'price'=>24840, 'old_price'=>31825, 'rating'=>'4.51', 'reviews'=>'617', 'duration'=>'3 days 3 nights', 'duration_days'=>3, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=600', 'category'=>'adventure', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures'],
            ['id'=>6, 'slug'=>'swiss-paris-delight', 'title'=>'Swiss Paris Delight', 'location'=>'Europe', 'price'=>51247, 'old_price'=>null, 'rating'=>'4.29', 'reviews'=>'608', 'duration'=>'7 days 6 nights', 'duration_days'=>7, 'groupSize'=>'4-6 guest', 'image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600', 'category'=>'international', 'badge'=>'25% Off', 'agent'=>'Globe Trotters'],
            ['id'=>7, 'slug'=>'kerala-backwaters', 'title'=>'Kerala Backwaters Escape', 'location'=>'Kerala, India', 'price'=>12500, 'old_price'=>15000, 'rating'=>'4.65', 'reviews'=>'420', 'duration'=>'4 days 3 nights', 'duration_days'=>4, 'groupSize'=>'2-4 guest', 'image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=600', 'category'=>'domestic', 'badge'=>'Popular', 'agent'=>'Miths Holidays'],
            ['id'=>8, 'slug'=>'dubai-desert-safari', 'title'=>'Dubai Desert Safari & Burj', 'location'=>'Dubai, UAE', 'price'=>29999, 'old_price'=>35000, 'rating'=>'4.80', 'reviews'=>'890', 'duration'=>'4 days 3 nights', 'duration_days'=>4, 'groupSize'=>'2-6 guest', 'image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=600', 'category'=>'international', 'badge'=>'Trending', 'agent'=>'Atlas Global Travels'],
            ['id'=>9, 'slug'=>'bali-luxury-villa', 'title'=>'Bali Luxury Villa Escape', 'location'=>'Bali, Indonesia', 'price'=>35000, 'old_price'=>42000, 'rating'=>'4.90', 'reviews'=>'543', 'duration'=>'5 days 4 nights', 'duration_days'=>5, 'groupSize'=>'2 guest', 'image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=600', 'category'=>'international', 'badge'=>'Honeymoon', 'agent'=>'Miths Holidays'],
            ['id'=>10, 'slug'=>'rishikesh-rafting', 'title'=>'Rishikesh Rafting & Yoga', 'location'=>'Rishikesh, India', 'price'=>8500, 'old_price'=>null, 'rating'=>'4.40', 'reviews'=>'312', 'duration'=>'2 days 1 nights', 'duration_days'=>2, 'groupSize'=>'4-10 guest', 'image'=>'https://images.unsplash.com/photo-1596403204987-9323eb72322c?auto=format&fit=crop&q=80&w=600', 'category'=>'adventure', 'badge'=>'Weekend', 'agent'=>'Atlas Global Travels'],
        ];
    }

    public function login()
    {
        return view('login');
    }
    


    public function showCmsPage($slug)
    {
        $page = DB::table('cms_pages')
            ->where('slug', $slug)
            ->where('status', 'Published')
            ->first();

        if (!$page) {
            abort(404);
        }

        return view('page', compact('page'));
    }

    // ─── HOME PAGE ────────────────────────────────────────────────────
    public function home()
    {
        try {
            $packages = Package::where('status', 'Active')->get();
        } catch (\Exception $e) {
            $packages = collect();
        }

        // Fetch all agents to check their tiers
        try {
            $agentsList = \Illuminate\Support\Facades\DB::table('agents')
                ->select('id', 'name', 'status', 'service_guaranteed', 'plan_id')
                ->get();
                
            $agentsById = $agentsList->keyBy('id')->toArray();
            $agentsByName = $agentsList->keyBy(function($item) {
                return strtolower(trim($item->name));
            })->toArray();
        } catch (\Exception $e) {
            $agentsById = [];
            $agentsByName = [];
        }

        $paidPlanIds = [];
        try {
            $paidPlanIds = \Illuminate\Support\Facades\DB::table('plans')->where('price', '>', 0)->pluck('id')->toArray();
        } catch (\Exception $e) {}

        // Filter out packages of inactive agents
        $packages = $packages->filter(function($p) use ($agentsById, $agentsByName) {
            $pkg = (array) $p;
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

            $dbAgent = null;
            if ($agentId && isset($agentsById[$agentId])) {
                $dbAgent = (array) $agentsById[$agentId];
            } elseif ($agentName) {
                $key = strtolower(trim($agentName));
                if (isset($agentsByName[$key])) {
                    $dbAgent = (array) $agentsByName[$key];
                }
            }

            if ($dbAgent && isset($dbAgent['status'])) {
                $st = strtolower((string)$dbAgent['status']);
                if ($st === 'inactive' || $st === '0' || $st === 'disabled' || $st === 'blocked') {
                    return false;
                }
            }

            return true;
        });

        // Shuffle all packages first for randomization, then sort by tier
        $packages = $packages->shuffle()->sortByDesc(function ($p) use ($agentsById, $agentsByName, $paidPlanIds) {
            $pkg = (array) $p;
            $tier = 1; // Default: unpaid / basic account
            
            // 3: Ad Placement
            if (!empty($pkg['ad_placement'])) {
                $tier = max($tier, 2);
            }
            
            // 2: Boosted Package (Check expiration)
            if (!empty($pkg['is_boosted'])) {
                $isExpired = false;
                if (!empty($pkg['boost_expires_at'])) {
                    try {
                        if (\Carbon\Carbon::parse($pkg['boost_expires_at'])->isPast()) {
                            $isExpired = true;
                        }
                    } catch (\Exception $e) {}
                }
                if (!$isExpired) {
                    $tier = max($tier, 3);
                }
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

        // Pull active home banners for the hero slider from DB
        try {
            $heroBanners = DB::table('banners')->where('status', 'Active')->get();
        } catch (\Exception $e) {
            $heroBanners = collect();
        }

        // Pull home packages for International and Domestic sections
        try {
            $homeInternational = DB::table('packages')->where('category', 'international')->where('status', 'Active')->orderBy('id', 'desc')->get();
            $homeDomestic = DB::table('packages')->where('category', 'domestic')->where('status', 'Active')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $homeInternational = collect();
            $homeDomestic = collect();
        }

        // Pull an active Ad for the home page from DB
        try {
            $homeAd = DB::table('ads')->where('status', 'Active')->where('position', 'Home Hero')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $homeAd = collect();
        }

        // Pull active ads under domestic packages
        try {
            $domesticAds = DB::table('ads')
                ->leftJoin('agents', 'ads.agent_id', '=', 'agents.id')
                ->select('ads.*', 'agents.logo as agent_logo')
                ->where('ads.status', 'Active')
                ->where('ads.position', 'Under Domestic Packages')
                ->orderBy('ads.id', 'desc')
                ->get();
        } catch (\Exception $e) {
            $domesticAds = collect();
        }

        // Pull active ads for footer banner
        try {
            $footerAds = DB::table('ads')
                ->leftJoin('agents', 'ads.agent_id', '=', 'agents.id')
                ->select('ads.*', 'agents.logo as agent_logo', 'agents.name as agent_name')
                ->where('ads.status', 'Active')
                ->where('ads.position', 'Footer Banner')
                ->orderBy('ads.id', 'desc')
                ->get();
        } catch (\Exception $e) {
            $footerAds = collect();
        }

        // Pull live offer stickers
        try {
            $offerStickers = DB::table('offer_stickers')->where('status', 'Live')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $offerStickers = collect();
        }

        // Pull active themes
        try {
            $themes = DB::table('themes')->where('status', 'Active')->orderBy('id', 'asc')->get();
        } catch (\Exception $e) {
            $themes = collect();
        }

        return view('welcome', compact('packages', 'heroBanners', 'homeAd', 'homeInternational', 'homeDomestic', 'offerStickers', 'domesticAds', 'footerAds', 'themes'));
    }

    // ─── SEARCH ───────────────────────────────────────────────────────
    public function search(Request $request)
    {
        $destination = $request->input('destination', '');
        $fromCity    = $request->input('from_city', '');

        // Log the search query for analytics
        try {
            DB::table('user_search_queries')->insert([
                'user_id'       => Auth::check() ? Auth::id() : null,
                'destination'   => $destination,
                'from_city'     => $fromCity,
                'results_count' => 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        } catch (\Exception $e) {
            // silently ignore DB errors
        }

        // Redirect to discover/listing page with the search terms
        $params = [];
        if (!empty($destination)) {
            $params['search'] = $destination;
        }
        if (!empty($fromCity)) {
            $params['city'] = $fromCity;
        }
        return redirect()->route('discover', $params);
    }

    // ─── AD CLICK TRACKING ────────────────────────────────────────────
    public function trackAdClick($id)
    {
        try {
            $ad = DB::table('ads')->where('id', $id)->first();
            if ($ad) {
                DB::table('ads')->where('id', $id)->increment('clicks');
                return redirect($ad->link ?: '/discover');
            }
        } catch (\Exception $e) {}
        
        return redirect('/discover');
    }

    // ─── NEWSLETTER SUBSCRIBE ─────────────────────────────────────────
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->input('email');

        try {
            // Insert into user_newsletter_subscriptions (user side)
            DB::table('user_newsletter_subscriptions')->updateOrInsert(
                ['email' => $email],
                ['status' => 'Subscribed', 'updated_at' => now(), 'created_at' => now()]
            );

            // Mirror into admin subscribers table so admin can see it
            DB::table('subscribers')->updateOrInsert(
                ['email' => $email],
                ['status' => 'Subscribed', 'updated_at' => now(), 'created_at' => now()]
            );
        } catch (\Exception $e) {
            // silently continue
        }

        return redirect()->back()->with('success', 'Thank you for subscribing! 🎉 You\'ll receive exclusive deals in your inbox.');
    }

    // ─── CONTACT FORM SUBMIT ──────────────────────────────────────────
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:30',
            'message' => 'required|string',
        ]);

        try {
            $data = [
                'user_id'    => Auth::check() ? Auth::id() : null,
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'subject'    => $request->subject,
                'message'    => $request->message,
                'status'     => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Determine if it's a Package Inquiry or a General Contact
            $isPackageInquiry = $request->has('package_name') || $request->has('agent_id');

            if ($isPackageInquiry) {
                // It's a package inquiry -> It goes to Leads
                DB::table('leads')->insert([
                    'agent_id'   => $request->agent_id,
                    'name'       => $request->name,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                    'agent'      => $request->agent_name ?? 'Website Inquiry',
                    'package'    => $request->package_name ?? ($request->subject ?? 'Website Inquiry'),
                    'status'     => 'New',
                    'message'    => $request->message,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // It's a General Contact from /contact -> It goes to Contacts
                DB::table('contacts')->insert([
                    'agent_id'   => null,
                    'name'       => $request->name,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                    'subject'    => $request->subject ?? 'General Contact',
                    'message'    => $request->message,
                    'status'     => 'New',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not send your message. Please try again.');
        }

        return redirect()->back()->with('success', 'Your message has been received! Our team will respond within 24 hours. ✅');
    }

    // ─── PACKAGE BOOKING REQUEST ──────────────────────────────────────
    public function bookPackage(Request $request)
    {
        $request->validate([
            'package_id'     => 'required',
            'traveler_name'  => 'required|string',
            'traveler_email' => 'required|email',
            'traveler_phone' => 'required|string',
            'guests'         => 'required|integer|min:1',
            'travel_date'    => 'required|date',
        ]);

        $userId = Auth::check() ? Auth::id() : null;

        try {
            DB::table('user_bookings')->insert([
                'user_id'        => $userId,
                'package_id'     => $request->package_id,
                'package_title'  => $request->package_title,
                'package_image'  => $request->package_image,
                'package_price'  => $request->package_price ?? 0,
                'traveler_name'  => $request->traveler_name,
                'traveler_email' => $request->traveler_email,
                'traveler_phone' => $request->traveler_phone,
                'guests'         => $request->guests,
                'travel_date'    => $request->travel_date,
                'special_request'=> $request->special_request,
                'status'         => 'Pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Mirror into leads for the admin
            DB::table('leads')->insert([
                'name'       => $request->traveler_name,
                'email'      => $request->traveler_email,
                'phone'      => $request->traveler_phone,
                'agent'      => 'Auto-assigned',
                'package'    => $request->package_title,
                'status'     => 'New',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Booking request failed. Please try again.');
        }

        return redirect()->back()->with('success', 'Booking request submitted! We will confirm your trip shortly. ✅');
    }

    // ─── WISHLIST TOGGLE ─────────────────────────────────────────────
    public function toggleWishlist(Request $request)
    {
        $userId    = $this->userId();
        $packageId = $request->input('package_id');

        try {
            $exists = DB::table('user_wishlists')
                        ->where('user_id', $userId)
                        ->where('package_id', $packageId)
                        ->exists();

            if ($exists) {
                DB::table('user_wishlists')
                    ->where('user_id', $userId)
                    ->where('package_id', $packageId)
                    ->delete();
                $saved = false;
            } else {
                DB::table('user_wishlists')->insert([
                    'user_id'        => $userId,
                    'package_id'     => $packageId,
                    'package_title'  => $request->input('package_title', ''),
                    'package_image'  => $request->input('package_image', ''),
                    'package_price'  => $request->input('package_price', 0),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $saved = true;
            }

            return response()->json(['success' => true, 'saved' => $saved]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    // ─── WISHLIST REMOVE (from profile page) ─────────────────────────
    public function removeWishlist(int $packageId)
    {
        try {
            DB::table('user_wishlists')
                ->where('user_id', $this->userId())
                ->where('package_id', $packageId)
                ->delete();
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', 'Removed from wishlist.');
    }

    public function profile()
    {
        if (Auth::check() && Auth::user()->role !== 'Customer') {
            return redirect('/');
        }

        $userId = $this->userId();

        try {
            $user = DB::table('users')->find($userId);
            $profile = DB::table('user_profiles')->where('user_id', $userId)->first();
            $wishlist = DB::table('user_wishlists as wl')
                          ->leftJoin('packages as p', 'p.id', '=', 'wl.package_id')
                          ->where('wl.user_id', $userId)
                          ->orderByDesc('wl.created_at')
                          ->select([
                              'wl.*',
                              'p.location as package_location',
                              'p.rating   as package_rating',
                              'p.duration as package_duration',
                          ])
                          ->paginate(6, ['*'], 'wpage');
            $bookings = DB::table('user_bookings')
                          ->where('user_id', $userId)
                          ->orderByDesc('created_at')
                          ->paginate(5, ['*'], 'bpage');
            $unreadCount = DB::table('user_notifications')
                             ->where('user_id', $userId)
                             ->where('is_read', false)
                             ->count();
            $userNotifications = DB::table('user_notifications')
                                   ->where('user_id', $userId)
                                   ->orderByDesc('created_at')
                                   ->limit(10)
                                   ->get();

            // Query plans and payments based on logged-in user email!
            $activePlan = DB::table('user_plans')
                            ->where('email', $user->email)
                            ->first();

            $userPayments = DB::table('payments')
                              ->where('email', $user->email)
                              ->orderByDesc('date')
                              ->get();

            // Pull the user's past search queries (unique destinations, most recent first)
            $searchHistory = DB::table('user_search_queries')
                               ->where('user_id', $userId)
                               ->whereNotNull('destination')
                               ->where('destination', '!=', '')
                               ->orderByDesc('created_at')
                               ->get()
                               ->unique('destination');
        } catch (\Exception $e) {
            $user = null; $profile = null; $wishlist = collect(); $bookings = collect();
            $unreadCount = 0; $userNotifications = collect();
            $activePlan = null; $userPayments = collect();
            $searchHistory = collect();
        }

        $packages = collect();

        // Merge DB packages if available
        try {
            $dbPackages = Package::where('status', 'Active')->get();
            if ($dbPackages->isNotEmpty()) {
                $packages = $dbPackages;
            }
        } catch (\Exception $e) {}

        // Fetch recently viewed packages
        $viewedPackages = collect();
        try {
            $viewedIds = DB::table('user_viewed_packages')
                ->where('user_id', $userId)
                ->orderBy('viewed_at', 'desc')
                ->limit(30)
                ->pluck('package_id');
                
            $dbPkgs = \App\Models\Package::where('status', 'Active')->get();

            if ($viewedIds->isNotEmpty()) {
                $viewedPackages = $viewedIds->map(function ($id) use ($dbPkgs) {
                    return $dbPkgs->firstWhere('id', $id);
                })->filter();
            }

            // Also include packages matching their recent searches
            $searchDestinations = DB::table('user_search_queries')
                ->where('user_id', $userId)
                ->whereNotNull('destination')
                ->where('destination', '!=', '')
                ->orderByDesc('created_at')
                ->pluck('destination')
                ->unique()
                ->take(5);

            foreach ($searchDestinations as $dest) {
                // Find first package matching the destination search
                $matchedPkg = $dbPkgs->first(function($p) use ($dest) {
                    return stripos($p->location, $dest) !== false || stripos($p->title, $dest) !== false;
                });
                
                // Add it to the list if not already there
                if ($matchedPkg && !$viewedPackages->contains('id', $matchedPkg->id)) {
                    $viewedPackages->push($matchedPkg);
                }
            }
        } catch (\Exception $e) {}

        $myReviews = collect();
        try {
            $myReviews = \App\Models\AgentFeedback::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get();
        } catch (\Exception $e) {}

        return view('profile', compact(
            'user', 'profile', 'wishlist', 'bookings',
            'packages', 'unreadCount', 'userNotifications',
            'activePlan', 'userPayments', 'searchHistory', 'viewedPackages',
            'myReviews'
        ));
    }

    // ─── UPDATE PROFILE ───────────────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $userId = $this->userId();

        // Handle avatar-only submission
        if ($request->hasFile('avatar') && !$request->has('name')) {
            $request->validate([
                'avatar' => 'image|max:2048'
            ]);
            
            try {
                $file = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('avatars'), $filename);
                $path = 'avatars/' . $filename;
                
                $exists = DB::table('user_profiles')->where('user_id', $userId)->exists();
                if ($exists) {
                    DB::table('user_profiles')->where('user_id', $userId)->update(['avatar' => $path]);
                } else {
                    DB::table('user_profiles')->insert([
                        'user_id' => $userId,
                        'avatar' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                return redirect()->back()->with('success', 'Avatar updated successfully! ✅');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to update avatar.');
            }
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        try {
            // Update users table
            DB::table('users')->where('id', $userId)->update([
                'name'       => $request->name,
                'email'      => $request->email,
                'updated_at' => now(),
            ]);

            // Update or create user_profiles entry
            $exists = DB::table('user_profiles')->where('user_id', $userId)->exists();
            $profileData = [
                'phone'         => $request->phone,
                'city'          => $request->city,
                'country'       => $request->country,
                'date_of_birth' => $request->date_of_birth,
                'gender'        => $request->gender,
                'updated_at'    => now(),
            ];

            if ($exists) {
                DB::table('user_profiles')->where('user_id', $userId)->update($profileData);
            } else {
                DB::table('user_profiles')->insert(array_merge($profileData, [
                    'user_id'    => $userId,
                    'created_at' => now(),
                ]));
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                DB::table('user_profiles')->where('user_id', $userId)->update(['avatar' => $path]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->back()->with('success', 'Profile updated successfully! ✅');
    }

    // ─── CHANGE PASSWORD ──────────────────────────────────────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $userId = $this->userId();
        $user   = DB::table('users')->find($userId);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        DB::table('users')->where('id', $userId)->update([
            'password'   => Hash::make($request->new_password),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully! ✅');
    }

    // ─── CANCEL BOOKING ───────────────────────────────────────────────
    public function cancelBooking(int $bookingId)
    {
        try {
            DB::table('user_bookings')
                ->where('id', $bookingId)
                ->where('user_id', $this->userId())
                ->update(['status' => 'Cancelled', 'updated_at' => now()]);
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }

    // ─── MARK NOTIFICATION READ ───────────────────────────────────────
    public function markNotificationRead(int $notifId)
    {
        try {
            DB::table('user_notifications')
                ->where('id', $notifId)
                ->where('user_id', $this->userId())
                ->update(['is_read' => true, 'updated_at' => now()]);
        } catch (\Exception $e) {}

        return redirect()->back();
    }

    // ─── SUBMIT REVIEW ────────────────────────────────────────────────
    public function submitReview(Request $request)
    {
        $request->validate([
            'package_id'    => 'required',
            'package_title' => 'required',
            'rating'        => 'required|integer|between:1,5',
            'review_body'   => 'required|string|min:10',
        ]);

        try {
            DB::table('user_reviews')->insert([
                'user_id'       => $this->userId(),
                'package_id'    => $request->package_id,
                'package_title' => $request->package_title,
                'rating'        => $request->rating,
                'review_title'  => $request->review_title,
                'review_body'   => $request->review_body,
                'status'        => 'Pending',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not submit review. Please try again.');
        }

        return redirect()->back()->with('success', 'Your review has been submitted and is pending approval. Thank you! ⭐');
    }

    // ─── SUBMIT PACKAGE FEEDBACK ──────────────────────────────────────
    public function storePackageFeedback(Request $request)
    {
        $request->validate([
            'agent_id'      => 'required|exists:agents,id',
            'package_id'    => 'required|exists:packages,id',
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'message'       => 'required|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/feedback'), $imageName);
            $imagePath = 'uploads/feedback/' . $imageName;
        }

        \App\Models\AgentFeedback::create([
            'user_id'       => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null,
            'agent_id'      => $request->agent_id,
            'customer_name' => $request->customer_name,
            'rating'        => $request->rating,
            'message'       => $request->message,
            'image_path'    => $imagePath,
            'package_id'    => $request->package_id
        ]);

        return redirect()->back()->with('feedback_success', 'Thank you! Your feedback has been submitted successfully.');
    }
    // ─── API EXISTENCE CHECKS ─────────────────────────────────────────
    public function checkEmail(Request $request)
    {
        $type = $request->get('type', 'customer');
        $table = $type === 'agent' ? 'agents' : 'users';
        $exists = DB::table($table)->where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkMobile(Request $request)
    {
        $type = $request->get('type', 'customer');
        $table = $type === 'agent' ? 'agents' : 'users'; // Actually, users uses user_profiles for phone. But agents has phone column.
        $phone = $request->phone;
        
        if ($type === 'agent') {
            $exists = DB::table('agents')->where('phone', $phone)->exists();
        } else {
            $exists = DB::table('user_profiles')->where('phone', $phone)->exists();
        }
        return response()->json(['exists' => $exists]);
    }

    // ─── SIGN UP ──────────────────────────────────────────────────────
    public function signupSubmit(Request $request)
    {
        $emailTable = $request->type == 'admin' ? 'admins' : 'users';
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:'.$emailTable.',email',
            'phone' => 'required|string',
            'country_code' => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        $name = $request->first_name . ' ' . $request->last_name;
        
        // Dynamic avatar generation based on name
        $avatar = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($name);

        $table = $request->type == 'admin' ? 'admins' : 'users';
        $userId = DB::table($table)->insertGetId([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->type == 'admin' ? 'SUPER ADMIN' : ($request->type == 'agent' ? 'Agent' : 'Customer'),
            'avatar' => $avatar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $fullPhone = $request->country_code . ' ' . $request->phone;

        if ($request->type != 'admin') {
            // Create empty profile
            DB::table('user_profiles')->insert([
                'user_id' => $userId,
                'username' => strtolower($request->first_name . '_' . $request->last_name),
                'phone' => $fullPhone,
                'avatar' => $avatar,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Send Welcome Email & SMS to Customer / User
        if ($request->type != 'admin') {
            try {
                \App\Services\MailService::sendView(
                    $email,
                    'Welcome to Tour Raja!',
                    'emails.welcome-customer',
                    ['name' => $name]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Customer Welcome Email Exception: " . $e->getMessage());
            }

            try {
                if (!empty($phone)) {
                    $msgClubService = app(\App\Services\MsgClubService::class);
                    $welcomeText = "Welcome to Tour Raja, {$name}! Your account has been registered successfully. Explore tour packages at " . url('/');
                    $msgClubService->sendCustomSms($phone, $welcomeText);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Customer Welcome SMS Exception: " . $e->getMessage());
            }
        }

        // If the user is signing up as an admin, do not log them in automatically.
        if ($request->type == 'admin') {
            return redirect('/admin/login')
                ->with('success', 'Admin account created successfully! Please log in.');
        }

        // For agents and customers, log them in automatically.
        Auth::loginUsingId($userId);

        if ($request->type == 'agent') {
            return redirect('/admin/dashboard')
                ->with('success', 'Account created successfully! Welcome, ' . $name);
        }

        return redirect('/')
            ->with('success', 'Account created successfully! Welcome, ' . $name);
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $isAdminRoute = $request->routeIs('admin.login.submit');
        $tableName = $isAdminRoute ? 'admins' : 'users';

        $user = DB::table($tableName)->where('email', $request->email)->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Account does not exist. Please create an account.']);
            }
            return redirect()->back()->with('error', 'Account does not exist. Please create an account.')->withInput();
        }

        if (Hash::check($request->password, $user->password)) {
            if ($isAdminRoute) {
                Auth::guard('admin')->loginUsingId($user->id);
                $redirect = '/admin/dashboard';
            } else {
                Auth::loginUsingId($user->id);
                $redirect = '/';
            }

            if ($request->wantsJson()) {
                session()->flash('success', 'Logged in successfully! Welcome, ' . $user->name);
                return response()->json(['success' => true, 'redirect' => $redirect]);
            }
            return redirect($redirect)->with('success', 'Logged in successfully! Welcome, ' . $user->name);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid password. Please try again.']);
        }
        return redirect()->back()->with('error', 'Invalid password. Please try again.')->withInput();
    }

    // ─── FORGOT & RESET PASSWORD ──────────────────────────────────────────
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No account found with this email.'], 404);
            }
            return back()->with('error', 'No account found with this email.');
        }

        $token = \Illuminate\Support\Str::random(64);
        $tokenHash = 'user_' . $token;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $tokenHash, 'created_at' => now()]
        );

        $resetUrl = url("/reset-password/{$tokenHash}");

        try {
            \Illuminate\Support\Facades\Mail::send('emails.reset-password', ['resetUrl' => $resetUrl], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Your Tour Raja Password');
            });
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to send reset link. Please check email configuration.'], 500);
            }
            return back()->with('error', 'Failed to send reset link. Please check email configuration.');
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'We have emailed your password reset link!']);
        }
        return back()->with('success', 'We have emailed your password reset link!');
    }

    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPasswordSubmit(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$reset) {
            return back()->with('error', 'Invalid or expired password reset token.');
        }

        $user = DB::table('users')->where('email', $reset->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        DB::table('users')->where('email', $reset->email)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        DB::table('password_reset_tokens')->where('email', $reset->email)->delete();

        return redirect('/login')->with('success', 'Password reset successfully! You can now log in.');
    }

    public function logout(Request $request)
    {
        if ($request->routeIs('admin.logout') || $request->is('admin/*')) {
            Auth::guard('admin')->logout();
            // Don't invalidate the entire session to keep customer logged in if they are
            return redirect('/admin/login')
                ->with('success', 'Logged out from Admin panel successfully.');
        }

        Auth::logout();
        // Just logout the customer, don't invalidate entire session as admin might be logged in
        return redirect('/')
            ->with('success', 'Logged out successfully.');
    }

    // ─── CAREERS FRONTEND ───────────────────────────────────────────────
    public function careers()
    {
        $positions = \App\Models\OpenPosition::with('department')
            ->where('status', 'Active')
            ->orderBy('id', 'desc')
            ->get();

        $departments = \App\Models\JobDepartment::whereHas('positions', function($q) {
            $q->where('status', 'Active');
        })->orderBy('name', 'asc')->get();

        $locations = \App\Models\JobLocation::orderBy('name', 'asc')->get();

        $formSetting = DB::table('settings')->where('key', 'career_form_enabled')->first();
        $careerFormEnabled = $formSetting ? (bool) $formSetting->value : true;

        $titleSetting = DB::table('settings')->where('key', 'career_form_title')->first();
        $careerFormTitle = $titleSetting ? $titleSetting->value : 'Application Form';

        $fieldsSetting = DB::table('settings')->where('key', 'career_form_fields')->first();
        $careerFormFields = $fieldsSetting ? json_decode($fieldsSetting->value, true) : [
            'middle_name', 'phone', 'gender', 'education', 'notice_period', 'current_ctc', 'expected_ctc', 'relevant_exp'
        ];

        $customFieldsSetting = DB::table('settings')->where('key', 'career_custom_fields')->first();
        $careerCustomFields = $customFieldsSetting ? json_decode($customFieldsSetting->value, true) : [];

        return view('careers', compact(
            'positions', 'departments', 'locations', 
            'careerFormEnabled', 'careerFormTitle', 'careerFormFields', 'careerCustomFields'
        ));
    }

    // ─── CAREER FORM SUBMISSION ─────────────────────────────────────────
    public function submitCareer(Request $request)
    {
        $fieldsSetting = DB::table('settings')->where('key', 'career_form_fields')->first();
        $enabledFields = $fieldsSetting ? json_decode($fieldsSetting->value, true) : [
            'middle_name', 'phone', 'gender', 'education', 'notice_period', 'current_ctc', 'expected_ctc', 'relevant_exp'
        ];

        $rules = [
            'role'           => 'required|string',
            'resume'         => 'required|file|mimes:pdf,doc,docx|max:5120',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'location'       => 'required|string',
            'location_other' => 'nullable|string',
            'total_exp'      => 'required|string',
        ];

        if (in_array('middle_name', $enabledFields)) $rules['middle_name'] = 'nullable|string';
        if (in_array('gender', $enabledFields)) $rules['gender'] = 'required|string';
        if (in_array('education', $enabledFields)) $rules['education'] = 'required|string';
        if (in_array('notice_period', $enabledFields)) $rules['notice_period'] = 'required|string';
        if (in_array('relevant_exp', $enabledFields)) $rules['relevant_exp'] = 'nullable|string';
        if (in_array('current_ctc', $enabledFields)) $rules['current_ctc'] = 'nullable|string';
        if (in_array('expected_ctc', $enabledFields)) $rules['expected_ctc'] = 'required|string';

        $request->validate($rules);

        try {
            $resumePath = '';
            if ($request->hasFile('resume')) {
                // Store resume in public/resumes directory
                $resumePath = $request->file('resume')->store('resumes', 'public');
            }

            \App\Models\CareerApplication::create([
                'role'           => $request->role,
                'resume_path'    => $resumePath,
                'first_name'     => $request->first_name,
                'middle_name'    => in_array('middle_name', $enabledFields) ? $request->middle_name : null,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'location'       => $request->location,
                'location_other' => $request->location_other,
                'notice_period'  => in_array('notice_period', $enabledFields) ? $request->notice_period : 'N/A',
                'gender'         => in_array('gender', $enabledFields) ? $request->gender : 'N/A',
                'education'      => in_array('education', $enabledFields) ? $request->education : 'N/A',
                'total_exp'      => $request->total_exp,
                'relevant_exp'   => in_array('relevant_exp', $enabledFields) ? $request->relevant_exp : null,
                'current_ctc'    => in_array('current_ctc', $enabledFields) ? $request->current_ctc : null,
                'expected_ctc'   => in_array('expected_ctc', $enabledFields) ? $request->expected_ctc : 'N/A',
                'custom_fields'  => $request->input('custom_fields') // automatically cast to json
            ]);

            return redirect()->back()->with('success', 'Your application has been submitted successfully! We will review your profile and get back to you soon. ✅');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error submitting your application. Please try again.')->withInput();
        }
    }

        // ─── SIGNUP METHOD ──────────────────────────────────────────────────────
        public function signup(Request $request)
        {
            // Determine the signup type from query parameter 'tab'
            $type = $request->query('tab', 'customer');
            if ($type === 'admin') {
                // Render the admin signup view
                return view('admin.signup', ['type' => 'admin']);
            }
            // Render a generic signup view for customers/agents if it exists
            return view('signup', ['type' => $type]);
        }

        // ─── SEARCH SUGGESTIONS API ──────────────────────────────────────────────
        public function suggestions(Request $request)
        {
            $q = trim(strtolower($request->query('q', '')));
            $type = $request->query('type', 'destination'); // destination, agent_location, or company

            if (strlen($q) < 1) {
                return response()->json([]);
            }

            $results = [];

            if ($type === 'company') {
                $companies = [];
                try {
                    $dbCompanies = \DB::table('agents')
                        ->where('status', 'Active')
                        ->where('name', 'like', "%{$q}%")
                        ->pluck('name')
                        ->toArray();
                    $companies = array_merge($companies, $dbCompanies);
                } catch (\Exception $e) {}

                $staticCompanies = ['Miths Holidays', 'Nomad Ventures', 'Explore Horizons', 'Trek & Trail Co.', 'Vikas Travels', 'Shikhar Tour'];
                foreach ($staticCompanies as $sc) {
                    if (stripos($sc, $q) !== false) {
                        $companies[] = $sc;
                    }
                }

                $companies = array_values(array_unique(array_filter($companies)));
                foreach (array_slice($companies, 0, 8) as $company) {
                    $results[] = [
                        'text' => $company,
                        'type' => 'company',
                        'icon' => 'building'
                    ];
                }
            } elseif ($type === 'agent_location') {
                // Suggest Agent Locations (City, State) based on agents table
                $locations = [];
                try {
                    $dbAgents = \DB::table('agents')
                        ->select('city', 'state', 'country')
                        ->where(function ($query) use ($q) {
                            $query->where('city', 'like', "%{$q}%")
                                  ->orWhere('state', 'like', "%{$q}%")
                                  ->orWhere('country', 'like', "%{$q}%");
                        })
                        ->get();

                    foreach ($dbAgents as $agent) {
                        $c = trim($agent->city ?? '');
                        $s = trim($agent->state ?? '');
                        
                        if ($c && $s) {
                            $locations[] = "{$c}, {$s}";
                        } elseif ($c) {
                            $locations[] = $c;
                        } elseif ($s) {
                            $locations[] = $s;
                        }
                    }
                } catch (\Exception $e) {}

                $locations = array_values(array_unique(array_filter($locations)));
                
                foreach (array_slice($locations, 0, 15) as $loc) {
                    $results[] = [
                        'text' => $loc,
                        'type' => 'location',
                        'icon' => 'map-pin'
                    ];
                }
            } else {
                // 1. Suggest Places (Package Locations/Cities)
                $places = [];
                try {
                    $packagesData = \DB::table('packages')
                        ->where('status', 'Active')
                        ->select('location', 'keywords')
                        ->get();
                    
                    foreach ($packagesData as $pkg) {
                        // Match Location (fallback if no precise keywords)
                        if (!empty($pkg->location)) {
                            $parts = explode(',', $pkg->location);
                            foreach ($parts as $part) {
                                $part = trim($part);
                                if ($part && stripos($part, $q) !== false) {
                                    $places[] = [
                                        'text' => $part,
                                        'value' => $part,
                                        'location_type' => 'city',
                                        'priority' => 1
                                    ];
                                }
                            }
                        }
                        
                        // Match JSON Keywords
                        if (!empty($pkg->keywords)) {
                            $keywords = json_decode($pkg->keywords, true);
                            if (is_array($keywords)) {
                                foreach ($keywords as $kw) {
                                    $kwParts = array_map('trim', explode(',', $kw));
                                    $c_city = $kwParts[0] ?? '';
                                    $c_state = $kwParts[1] ?? '';
                                    $c_country = $kwParts[2] ?? '';

                                    if ($c_city && stripos($c_city, $q) !== false) {
                                        $label = $c_city;
                                        if ($c_state) $label .= ", $c_state";
                                        $places[] = [
                                            'text' => $label,
                                            'value' => $c_city,
                                            'location_type' => 'city',
                                            'priority' => 1
                                        ];
                                    }
                                    if ($c_state && stripos($c_state, $q) !== false) {
                                        $label = $c_state;
                                        if ($c_country) $label .= ", $c_country";
                                        $places[] = [
                                            'text' => $label,
                                            'value' => $c_state,
                                            'location_type' => 'state',
                                            'priority' => 2
                                        ];
                                    }
                                    if ($c_country && stripos($c_country, $q) !== false) {
                                        $places[] = [
                                            'text' => $c_country,
                                            'value' => $c_country,
                                            'location_type' => 'country',
                                            'priority' => 3
                                        ];
                                    }
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {}

                // Static backup of popular destinations
                $staticDestinations = ['Bali', 'Singapore', 'Monaco', 'Vietnam', 'Char Dham', 'Goa', 'Spiti Valley', 'Kerala', 'Dubai', 'Ubud', 'Seminyak', 'Uluwatu', 'New Delhi'];
                foreach ($staticDestinations as $sd) {
                    if (stripos($sd, $q) !== false) {
                        $places[] = [
                            'text' => $sd,
                            'value' => $sd,
                            'location_type' => 'city',
                            'priority' => 1
                        ];
                    }
                }

                // Filter out duplicates based on text
                $uniquePlaces = [];
                foreach ($places as $p) {
                    $uniquePlaces[$p['text']] = $p;
                }
                $places = array_values($uniquePlaces);
                
                // Prioritize Indian destinations and then by priority
                usort($places, function($a, $b) {
                    $indianKeywords = ['india', 'delhi', 'mumbai', 'bangalore', 'kolkata', 'chennai', 'pune', 'hyderabad', 'jaipur', 'goa', 'dehradun', 'kerala', 'ahmedabad', 'gujarat', 'maharashtra', 'kashmir', 'ladakh', 'spiti', 'rishikesh', 'manali', 'shimla', 'kasol'];
                    $aIsIndian = false;
                    $bIsIndian = false;
                    foreach ($indianKeywords as $kw) {
                        if (stripos($a['text'], $kw) !== false) $aIsIndian = true;
                        if (stripos($b['text'], $kw) !== false) $bIsIndian = true;
                    }
                    if ($aIsIndian && !$bIsIndian) return -1;
                    if (!$aIsIndian && $bIsIndian) return 1;
                    
                    if ($a['priority'] != $b['priority']) return $a['priority'] <=> $b['priority'];
                    
                    return 0;
                });

                foreach (array_slice($places, 0, 15) as $place) {
                    $results[] = [
                        'text' => $place['text'],
                        'value' => $place['value'],
                        'location_type' => $place['location_type'],
                        'type' => 'place',
                        'icon' => 'map-pin'
                    ];
                }

                // 2. Suggest Agents
                $agents = [];
                try {
                    $dbAgents = \DB::table('agents')
                        ->where('status', 'Active')
                        ->where('name', 'like', "%{$q}%")
                        ->pluck('name')
                        ->toArray();
                    $agents = array_merge($agents, $dbAgents);
                } catch (\Exception $e) {}

                $staticAgents = ['Miths Holidays', 'Nomad Ventures', 'Explore Horizons', 'Trek & Trail Co.', 'Vikas Travels', 'Shikhar Tour'];
                foreach ($staticAgents as $sa) {
                    if (stripos($sa, $q) !== false) {
                        $agents[] = $sa;
                    }
                }

                $agents = array_values(array_unique(array_filter($agents)));
                foreach (array_slice($agents, 0, 5) as $agent) {
                    $results[] = [
                        'text' => $agent,
                        'type' => 'agent',
                        'icon' => 'user'
                    ];
                }
            }

            return response()->json($results);
        }

}
