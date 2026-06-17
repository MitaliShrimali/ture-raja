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
    

    // ─── HOME PAGE ────────────────────────────────────────────────────
    public function home()
    {
        try {
            $packages = Package::where('status', 'Active')->get();
            if ($packages->isEmpty()) {
                $packages = collect($this->getStaticPackages());
            }
        } catch (\Exception $e) {
            $packages = collect($this->getStaticPackages());
        }

        // Sort packages to show popular tagged ones first, then by most reviewed/clicked
        $popularBadges = ['popular'];
        $packages = $packages
            ->sortByDesc(fn($p) => (int)(is_array($p) ? ($p['reviews'] ?? 0) : ($p->reviews ?? 0)))
            ->sortByDesc(fn($p) => (int)(is_array($p) ? ($p['clicks'] ?? 0) : ($p->clicks ?? 0)))
            ->sortByDesc(function ($p) use ($popularBadges) {
                $badge = strtolower(is_array($p) ? ($p['badge'] ?? '') : ($p->badge ?? ''));
                return in_array($badge, $popularBadges) ? 2 : (!empty($badge) ? 1 : 0);
            })->values();

        // Pull active home banners for the hero slider from DB
        try {
            $heroBanners = DB::table('banners')->where('status', 'Active')->get();
        } catch (\Exception $e) {
            $heroBanners = collect();
        }

        // Pull home packages for International and Domestic sections
        try {
            $homeInternational = DB::table('home_packages')->where('type', 'international')->where('status', 'Live')->orderBy('id', 'desc')->get();
            $homeDomestic = DB::table('home_packages')->where('type', 'domestic')->where('status', 'Live')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $homeInternational = collect();
            $homeDomestic = collect();
        }

        // Pull an active Ad for the home page from DB
        try {
            $homeAd = DB::table('ads')->where('status', 'Active')->where('position', 'Home Hero')->orderBy('id', 'desc')->first();
        } catch (\Exception $e) {
            $homeAd = null;
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

            // Store in user_inquiries
            DB::table('user_inquiries')->insert($data);

            // Mirror into contacts table so agent panel sees it
            DB::table('contacts')->insert([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'subject'    => $request->subject,
                'message'    => $request->message,
                'status'     => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mirror into leads table so admin lead panel sees it
            DB::table('leads')->insert([
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

        // Fallback static packages for wishlist when DB not available
        $packages = collect($this->getStaticPackages());

        // Merge DB packages if available
        try {
            $dbPackages = Package::where('status', 'Active')->get();
            if ($dbPackages->isNotEmpty()) {
                $packages = $dbPackages;
            }
        } catch (\Exception $e) {}

        return view('profile', compact(
            'user', 'profile', 'wishlist', 'bookings',
            'packages', 'unreadCount', 'userNotifications',
            'activePlan', 'userPayments', 'searchHistory'
        ));
    }

    // ─── UPDATE PROFILE ───────────────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $userId = $this->userId();

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
    // ─── SIGN UP ──────────────────────────────────────────────────────
    public function signupSubmit(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $name = $request->first_name . ' ' . $request->last_name;
        
        // Dynamic avatar generation based on name
        $avatar = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($name);

        $userId = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->type == 'admin' ? 'SUPER ADMIN' : ($request->type == 'agent' ? 'Agent' : 'Customer'),
            'avatar' => $avatar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create empty profile
        DB::table('user_profiles')->insert([
            'user_id' => $userId,
            'username' => strtolower($request->first_name . '_' . $request->last_name),
            'avatar' => $avatar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

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

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Account does not exist. Please create an account.']);
            }
            return redirect()->back()->with('error', 'Account does not exist. Please create an account.')->withInput();
        }

        if (Hash::check($request->password, $user->password)) {
            Auth::loginUsingId($user->id);
            
            $redirect = '/';
            if (in_array(strtoupper($user->role ?? ''), ['SUPER ADMIN', 'ADMIN', 'MANAGER', 'EDITOR'])) {
                $redirect = '/admin/dashboard';
            }

            if ($request->wantsJson()) {
                session()->flash('success', 'Logged in successfully! Welcome, ' . $user->name);
                return response()->json(['success' => true, 'redirect' => $redirect]);
            }

            // Normal user login redirects to home page
            return redirect($redirect)->with('success', 'Logged in successfully! Welcome, ' . $user->name);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid password. Please try again.']);
        }
        return redirect()->back()->with('error', 'Invalid password. Please try again.')->withInput();
    }

    public function logout()
    {
        // Capture role before logging out
        $role = Auth::check() ? Auth::user()->role : null;
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        // If admin role (any case) redirect to admin login
        if ($role && stripos($role, 'admin') !== false) {
            return redirect('/admin/login')
                ->with('success', 'Logged out successfully.');
        }
        // Default user logout redirects to public login
        return redirect('/')
            ->with('success', 'Logged out successfully.');
    }

    // ─── CAREER FORM SUBMISSION ─────────────────────────────────────────
    public function submitCareer(Request $request)
    {
        $request->validate([
            'role'           => 'required|string',
            'resume'         => 'required|file|mimes:pdf,doc,docx|max:5120',
            'first_name'     => 'required|string',
            'middle_name'    => 'nullable|string',
            'last_name'      => 'required|string',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'location'       => 'required|string',
            'location_other' => 'nullable|string',
            'notice_period'  => 'required|string',
            'gender'         => 'required|string',
            'education'      => 'required|string',
            'total_exp'      => 'required|string',
            'relevant_exp'   => 'nullable|string',
            'current_ctc'    => 'nullable|string',
            'expected_ctc'   => 'required|string',
        ]);

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
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'location'       => $request->location,
                'location_other' => $request->location_other,
                'notice_period'  => $request->notice_period,
                'gender'         => $request->gender,
                'education'      => $request->education,
                'total_exp'      => $request->total_exp,
                'relevant_exp'   => $request->relevant_exp,
                'current_ctc'    => $request->current_ctc,
                'expected_ctc'   => $request->expected_ctc,
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
                // Suggest cities and regions of active agents
                $cities = [];
                try {
                    $dbCities = \DB::table('agents')
                        ->where('status', 'Active')
                        ->whereNotNull('city')
                        ->where('city', '!=', '')
                        ->where('city', 'like', "%{$q}%")
                        ->pluck('city')
                        ->toArray();
                    $dbRegions = \DB::table('agents')
                        ->where('status', 'Active')
                        ->whereNotNull('region')
                        ->where('region', '!=', '')
                        ->where('region', 'like', "%{$q}%")
                        ->pluck('region')
                        ->toArray();
                    $cities = array_merge($cities, $dbCities, $dbRegions);
                } catch (\Exception $e) {}

                // Static backup of popular cities
                $staticCities = ['New Delhi', 'Delhi', 'Mumbai', 'Bangalore', 'Kolkata', 'Chennai', 'Pune', 'Hyderabad', 'Jaipur', 'Goa', 'Dehradun', 'Singapore', 'Thailand', 'Bali'];
                foreach ($staticCities as $sc) {
                    if (stripos($sc, $q) !== false) {
                        $cities[] = $sc;
                    }
                }

                $cities = array_values(array_unique(array_filter($cities)));
                foreach (array_slice($cities, 0, 8) as $city) {
                    $results[] = [
                        'text' => $city,
                        'type' => 'location',
                        'icon' => 'map-pin'
                    ];
                }
            } else {
                // 1. Suggest Places (Package Locations/Cities)
                $places = [];
                try {
                    $locations = \DB::table('packages')
                        ->where('status', 'Active')
                        ->pluck('location')
                        ->toArray();
                    foreach ($locations as $loc) {
                        $parts = explode(',', $loc);
                        foreach ($parts as $part) {
                            $part = trim($part);
                            if ($part && stripos($part, $q) !== false) {
                                $places[] = $part;
                            }
                        }
                    }
                } catch (\Exception $e) {}

                // Static backup of popular destinations
                $staticDestinations = ['Bali', 'Singapore', 'Monaco', 'Vietnam', 'Char Dham', 'Goa', 'Spiti Valley', 'Kerala', 'Dubai', 'Ubud', 'Seminyak', 'Uluwatu', 'New Delhi'];
                foreach ($staticDestinations as $sd) {
                    if (stripos($sd, $q) !== false) {
                        $places[] = $sd;
                    }
                }

                $places = array_values(array_unique(array_filter($places)));
                foreach (array_slice($places, 0, 5) as $place) {
                    $results[] = [
                        'text' => $place,
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
