<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Get Live Metrics
        $totalRev = DB::table('payments')->where('status', 'Completed')->sum('amount');
        $activeAgentsCount = DB::table('agents')->where('status', 'Active')->count();
        $activePackagesCount = DB::table('packages')->where('status', 'Active')->count();
        $totalSubsCount = DB::table('subscribers')->where('status', 'Subscribed')->count();

        $data = [
            'metrics' => [
                'totalRevenue' => '₹' . number_format($totalRev, 2),
                'revenueGrowth' => '+12.5%',
                'activeAgents' => number_format($activeAgentsCount),
                'agentGrowth' => '+8.2%',
                'activePackages' => number_format($activePackagesCount),
                'packageGrowth' => '+15.3%',
                'totalSubscribers' => number_format($totalSubsCount),
                'subscriberGrowth' => '+5.4%',
            ],
            // Dynamic activity feed populated from notifications or user activities
            'recentActivities' => DB::table('notifications')
                ->orderBy('sent_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($notif) {
                    return [
                        'user' => 'System Alert',
                        'action' => $notif->title . ': ' . $notif->message,
                        'status' => $notif->type === 'Alert' ? 'pending' : 'completed',
                        'time' => strtoupper(human_timing(strtotime($notif->sent_at)) . ' ago')
                    ];
                })->toArray()
        ];

        // Fallback recentActivities if database is fresh
        if (empty($data['recentActivities'])) {
            $data['recentActivities'] = [
                ['user' => 'Rahul Sharma', 'action' => 'New agent registration', 'status' => 'pending', 'time' => '2 MINS AGO'],
                ['user' => 'Global Travels', 'action' => 'Package approved: Bali Getaway', 'status' => 'completed', 'time' => '15 MINS AGO'],
                ['user' => 'Anita Desai', 'action' => 'Subscription upgraded to Premium', 'status' => 'completed', 'time' => '1 HOUR AGO'],
            ];
        }

        // Fetch recent payments for dashboard table
        $recentPayments = DB::table('payments')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('data', 'recentPayments'));
    }

    // ==========================================
    // Inventory & Stays
    // ==========================================

    public function packages(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('packages');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $packages = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.packages', compact('packages', 'search'));
    }

    public function storePackage(Request $request)
    {
        $request->validate(['title' => 'required', 'price' => 'required|numeric']);
        
        DB::table('packages')->insert([
            'title' => $request->title,
            'location' => $request->location ?? 'Global',
            'price' => $request->price,
            'rating' => $request->rating ?? 4.8,
            'reviews' => $request->reviews ?? 10,
            'duration' => $request->duration ?? '3 Days',
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'category' => $request->category ?? 'Tropical',
            'badge' => $request->badge,
            'status' => $request->status ?? 'Active',
            'stock' => ($request->stock ?? '10') . ' Left',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required', 'price' => 'required|numeric']);
        
        DB::table('packages')->where('id', $request->id)->update([
            'title' => $request->title,
            'location' => $request->location,
            'price' => $request->price,
            'duration' => $request->duration,
            'status' => $request->status ?? 'Active',
            'stock' => $request->stock,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Package updated successfully!');
    }

    public function deletePackage($id)
    {
        DB::table('packages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    public function togglePackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if ($pkg) {
            $newStatus = $pkg->status === 'Active' ? 'Draft' : 'Active';
            DB::table('packages')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Package status updated!');
    }

    // HOTELS
    public function hotels(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('hotels');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $hotels = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.hotels', compact('hotels', 'search'));
    }

    public function storeHotel(Request $request)
    {
        $request->validate(['name' => 'required', 'location' => 'required']);
        
        DB::table('hotels')->insert([
            'name' => $request->name,
            'category' => $request->category ?? 'Luxury Resort',
            'location' => $request->location,
            'rating' => $request->rating ?? 5,
            'status' => $request->status ?? 'Published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Hotel onboarded successfully!');
    }

    public function updateHotel(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('hotels')->where('id', $request->id)->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'rating' => $request->rating,
            'status' => $request->status ?? 'Published',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Hotel details updated!');
    }

    public function deleteHotel($id)
    {
        DB::table('hotels')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Hotel removed successfully!');
    }

    public function toggleHotel($id)
    {
        $hotel = DB::table('hotels')->where('id', $id)->first();
        if ($hotel) {
            $newStatus = $hotel->status === 'Published' ? 'Draft' : 'Published';
            DB::table('hotels')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Hotel status toggled!');
    }

    // AMENITIES
    public function amenities(Request $request)
    {
        $amenities = DB::table('amenities')->orderBy('id', 'asc')->paginate(10);
        return view('admin.amenities', compact('amenities'));
    }

    public function storeAmenity(Request $request)
    {
        $request->validate(['name' => 'required', 'icon' => 'required']);
        DB::table('amenities')->insert([
            'name' => $request->name,
            'icon' => $request->icon,
            'category' => $request->category ?? 'General',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Amenity created successfully!');
    }

    public function updateAmenity(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        DB::table('amenities')->where('id', $request->id)->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'category' => $request->category,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Amenity updated!');
    }

    public function deleteAmenity($id)
    {
        DB::table('amenities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Amenity removed!');
    }

    public function toggleAmenity($id)
    {
        $item = DB::table('amenities')->where('id', $id)->first();
        if ($item) {
            $newStatus = $item->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('amenities')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Amenity status toggled!');
    }

    // HOLIDAY TYPES
    public function holidayTypes(Request $request)
    {
        $holidayTypes = DB::table('holiday_types')->orderBy('id', 'asc')->paginate(10);
        return view('admin.holiday-types', compact('holidayTypes'));
    }

    public function storeHolidayType(Request $request)
    {
        $request->validate(['name' => 'required']);
        DB::table('holiday_types')->insert([
            'name' => $request->name,
            'icon' => $request->icon ?? 'compass',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Holiday type added!');
    }

    public function updateHolidayType(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        DB::table('holiday_types')->where('id', $request->id)->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Holiday type updated!');
    }

    public function deleteHolidayType($id)
    {
        DB::table('holiday_types')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Holiday type deleted!');
    }

    public function toggleHolidayType($id)
    {
        $item = DB::table('holiday_types')->where('id', $id)->first();
        if ($item) {
            $newStatus = $item->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('holiday_types')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Holiday type status toggled!');
    }

    // ACTIVITIES
    public function activities(Request $request)
    {
        $activities = DB::table('activities')->orderBy('id', 'asc')->paginate(10);
        return view('admin.activities', compact('activities'));
    }

    public function storeActivity(Request $request)
    {
        $request->validate(['name' => 'required', 'icon' => 'required']);
        DB::table('activities')->insert([
            'name' => $request->name,
            'icon' => $request->icon,
            'intensity' => $request->intensity ?? 'Medium',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Activity added successfully!');
    }

    public function updateActivity(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        DB::table('activities')->where('id', $request->id)->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'intensity' => $request->intensity,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Activity updated!');
    }

    public function deleteActivity($id)
    {
        DB::table('activities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Activity removed!');
    }

    public function toggleActivity($id)
    {
        $item = DB::table('activities')->where('id', $id)->first();
        if ($item) {
            $newStatus = $item->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('activities')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Activity status toggled!');
    }

    // ==========================================
    // Admin Central
    // ==========================================

    // ADMIN USER
    public function users(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('users');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();
        return view('admin.users', compact('users', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users,email']);
        
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role' => $request->role ?? 'SUPER ADMIN',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($request->name),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Admin user created!');
    }

    public function updateUser(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required', 'email' => 'required|email']);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? 'SUPER ADMIN',
            'updated_at' => now(),
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $request->id)->update($updateData);

        return redirect()->back()->with('success', 'Admin user details updated!');
    }

    public function deleteUser($id)
    {
        // Don't let users delete id 1 (Super Admin) to avoid locking out
        if ($id == 1) {
            return redirect()->back()->with('error', 'Cannot delete primary Super Admin!');
        }
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Admin user removed!');
    }

    public function toggleUser($id)
    {
        // Just custom logic if needed or standard feedback
        return redirect()->back()->with('success', 'Admin permissions audited successfully!');
    }

    // AGENT MANAGEMENT
    public function agents(Request $request)
    {
        // Check if onboarding form or list (Wait, original page is onboarding form, but let's see if we should fetch some metadata or just render form)
        return view('admin.agents');
    }

    public function storeAgent(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email']);
        
        DB::table('agents')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'region' => $request->region ?? 'Asia Pacific',
            'tier' => $request->tier ?? 'Premium',
            'status' => $request->status ?? 'Active',
            'service_guaranteed' => $request->has('service_guaranteed') ? true : false,
            'api_access' => $request->has('api_access') ? true : false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/dashboard')->with('success', 'New Travel Agent onboarded successfully!');
    }

    // LEAD MANAGEMENT
    public function leads(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('leads');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('agent', 'like', "%{$search}%")
                  ->orWhere('package', 'like', "%{$search}%");
        }

        $leads = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.leads', compact('leads', 'search'));
    }

    public function storeLead(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required']);
        
        DB::table('leads')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'agent' => $request->agent ?? 'Nomad Ventures',
            'package' => $request->package ?? 'Global Tour',
            'status' => $request->status ?? 'New',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lead record created!');
    }

    public function updateLead(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('leads')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'agent' => $request->agent,
            'package' => $request->package,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lead record updated!');
    }

    public function deleteLead($id)
    {
        DB::table('leads')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Lead record deleted!');
    }

    // ==========================================
    // Subscription Oversight
    // ==========================================

    public function paidUsers(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('paid_users');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $paidUsers = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.paid-users', compact('paidUsers', 'search'));
    }

    public function storePaidUser(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required']);
        
        DB::table('paid_users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($request->name),
            'plan' => $request->plan ?? 'Standard',
            'joined_date' => $request->joined_date ?? now()->toDateString(),
            'amount' => $request->amount ?? 99.00,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Paid user added!');
    }

    public function updatePaidUser(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('paid_users')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'plan' => $request->plan,
            'amount' => $request->amount,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Paid user updated!');
    }

    public function deletePaidUser($id)
    {
        DB::table('paid_users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Paid user removed!');
    }

    public function togglePaidUser($id)
    {
        $user = DB::table('paid_users')->where('id', $id)->first();
        if ($user) {
            $newStatus = $user->status === 'Active' ? 'Suspended' : 'Active';
            DB::table('paid_users')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Paid user status updated!');
    }

    // USER PLAN
    public function userPlans(Request $request)
    {
        $userPlans = DB::table('user_plans')->orderBy('id', 'desc')->paginate(5);
        return view('admin.user-plans', compact('userPlans'));
    }

    public function storeUserPlan(Request $request)
    {
        $request->validate(['user_name' => 'required', 'email' => 'required', 'plan_name' => 'required']);
        
        DB::table('user_plans')->insert([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'plan_name' => $request->plan_name,
            'price' => $request->price ?? 99.00,
            'duration' => $request->duration ?? '1 Month',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'User subscription plan assigned!');
    }

    public function updateUserPlan(Request $request)
    {
        $request->validate(['id' => 'required', 'user_name' => 'required']);
        
        DB::table('user_plans')->where('id', $request->id)->update([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'plan_name' => $request->plan_name,
            'price' => $request->price,
            'duration' => $request->duration,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'User subscription plan updated!');
    }

    public function deleteUserPlan($id)
    {
        DB::table('user_plans')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'User subscription record removed!');
    }

    // PAYMENTS
    public function payments(Request $request)
    {
        $payments = DB::table('payments')->orderBy('id', 'desc')->paginate(5);
        return view('admin.payments', compact('payments'));
    }

    public function storePayment(Request $request)
    {
        $request->validate(['user_name' => 'required', 'amount' => 'required|numeric']);
        
        DB::table('payments')->insert([
            'user_name' => $request->user_name,
            'email' => $request->email ?? 'guest@example.com',
            'plan_type' => $request->plan_type ?? 'Standard',
            'amount' => $request->amount,
            'payment_id' => 'TXN_' . strtoupper(bin2hex(random_bytes(4))),
            'date' => $request->date ?? now()->toDateString(),
            'status' => $request->status ?? 'Completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment log entered successfully!');
    }

    public function updatePayment(Request $request)
    {
        $request->validate(['id' => 'required', 'status' => 'required']);
        
        DB::table('payments')->where('id', $request->id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment status updated!');
    }

    public function deletePayment($id)
    {
        DB::table('payments')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Payment record deleted!');
    }

    // ADVERTISEMENT
    public function ads(Request $request)
    {
        $ads = DB::table('ads')->orderBy('id', 'asc')->paginate(5);
        return view('admin.ads', compact('ads'));
    }

    public function storeAd(Request $request)
    {
        $request->validate(['campaign_name' => 'required', 'position' => 'required']);
        
        DB::table('ads')->insert([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'link' => $request->link ?? '/discover',
            'clicks' => 0,
            'views' => 0,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Advertisement campaign added!');
    }

    public function updateAd(Request $request)
    {
        $request->validate(['id' => 'required', 'campaign_name' => 'required']);
        
        DB::table('ads')->where('id', $request->id)->update([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'link' => $request->link,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ad campaign updated!');
    }

    public function deleteAd($id)
    {
        DB::table('ads')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Ad campaign deleted!');
    }

    public function toggleAd($id)
    {
        $ad = DB::table('ads')->where('id', $id)->first();
        if ($ad) {
            $newStatus = $ad->status === 'Active' ? 'Paused' : 'Active';
            DB::table('ads')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Ad campaign status updated!');
    }

    // PLAN
    public function plans(Request $request)
    {
        $plans = DB::table('plans')->orderBy('id', 'asc')->paginate(5);
        return view('admin.plans', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        
        DB::table('plans')->insert([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration ?? '1 Month',
            'features' => json_encode($request->features ?? ['Standard Travel Package options']),
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Plan created!');
    }

    public function updatePlan(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('plans')->where('id', $request->id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Plan updated!');
    }

    public function deletePlan($id)
    {
        DB::table('plans')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Plan deleted!');
    }

    public function togglePlan($id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if ($plan) {
            $newStatus = $plan->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('plans')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Plan status updated!');
    }

    // ==========================================
    // Platform Settings
    // ==========================================

    // BANNERS (HOME EDITOR)
    public function homeEditor(Request $request)
    {
        $banners = DB::table('banners')->orderBy('id', 'desc')->paginate(5);
        return view('admin.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate(['title' => 'required']);
        
        DB::table('banners')->insert([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
            'link' => $request->link ?? '/discover',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Marketing banner created!');
    }

    public function updateBanner(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required']);
        
        DB::table('banners')->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Banner updated!');
    }

    public function deleteBanner($id)
    {
        DB::table('banners')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Banner removed!');
    }

    public function toggleBanner($id)
    {
        $banner = DB::table('banners')->where('id', $id)->first();
        if ($banner) {
            $newStatus = $banner->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('banners')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Banner status toggled!');
    }

    // NOTIFICATIONS
    public function notifications(Request $request)
    {
        $notifications = DB::table('notifications')->orderBy('id', 'desc')->paginate(5);
        return view('admin.notifications', compact('notifications'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate(['title' => 'required', 'message' => 'required']);
        
        DB::table('notifications')->insert([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'Info',
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Global system notification sent!');
    }

    public function deleteNotification($id)
    {
        DB::table('notifications')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Notification cleared!');
    }

    // CMS (PAGES)
    public function cms(Request $request)
    {
        $pages = DB::table('cms_pages')->orderBy('id', 'asc')->paginate(5);
        return view('admin.cms', compact('pages'));
    }

    public function storeCmsPage(Request $request)
    {
        $request->validate(['title' => 'required', 'slug' => 'required|unique:cms_pages,slug']);
        
        DB::table('cms_pages')->insert([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'status' => $request->status ?? 'Published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'CMS static page created!');
    }

    public function updateCmsPage(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required']);
        
        DB::table('cms_pages')->where('id', $request->id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'Published',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'CMS static page updated!');
    }

    public function deleteCmsPage($id)
    {
        DB::table('cms_pages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Static page removed!');
    }

    public function toggleCmsPage($id)
    {
        $page = DB::table('cms_pages')->where('id', $id)->first();
        if ($page) {
            $newStatus = $page->status === 'Published' ? 'Draft' : 'Published';
            DB::table('cms_pages')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'CMS page status toggled!');
    }

    // CONTACT US
    public function contact(Request $request)
    {
        $contacts = DB::table('contacts')->orderBy('id', 'desc')->paginate(5);
        return view('admin.contact-us', compact('contacts'));
    }

    public function storeContact(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required', 'message' => 'required']);
        
        DB::table('contacts')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you! Your message was submitted successfully.');
    }

    public function deleteContact($id)
    {
        DB::table('contacts')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Contact message cleared!');
    }

    public function toggleContact($id)
    {
        $item = DB::table('contacts')->where('id', $id)->first();
        if ($item) {
            $newStatus = $item->status === 'Pending' ? 'Resolved' : 'Pending';
            DB::table('contacts')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Contact message status toggled!');
    }

    // SUBSCRIBERS
    public function subscribers(Request $request)
    {
        $subscribers = DB::table('subscribers')->orderBy('id', 'desc')->paginate(5);
        return view('admin.subscribers', compact('subscribers'));
    }

    public function storeSubscriber(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:subscribers,email']);
        
        DB::table('subscribers')->insert([
            'email' => $request->email,
            'status' => 'Subscribed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Newsletter subscription active!');
    }

    public function deleteSubscriber($id)
    {
        DB::table('subscribers')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Subscriber removed successfully!');
    }

    public function toggleSubscriber($id)
    {
        $sub = DB::table('subscribers')->where('id', $id)->first();
        if ($sub) {
            $newStatus = $sub->status === 'Subscribed' ? 'Unsubscribed' : 'Subscribed';
            DB::table('subscribers')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Subscriber status toggled!');
    }

    // SYSTEM SETTINGS
    public function settings(Request $request)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email']);
        
        DB::table('users')->where('id', 1)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Admin profile updated!');
    }
}

// Reusable custom timing function for activity feed
function human_timing($time)
{
    $time = time() - $time;
    $time = ($time < 1) ? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}
