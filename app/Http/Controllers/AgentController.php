<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\AgentFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use App\Models\AgentMedia;

class AgentController extends Controller
{
    // ─── AUTH ─────────────────────────────────────────────────────────────────

    public function login()
    {
        // Already logged in → go to dashboard
        if (session('agent_id')) {
            return redirect()->route('agent.dashboard');
        }
        return view('agent.auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        $agent = DB::table('agents')
                   ->where('email', $request->email)
                   ->first();

        if (!$agent || !$agent->password || !Hash::check($request->password, $agent->password)) {
            return back()->with('error', 'Invalid email or password. Please try again.');
        }

        if (isset($agent->status) && $agent->status == 0) {
            return back()->with('error', 'Your agent account has been suspended. Please contact support.');
        }

        // Store agent session (separate key from customer session 'user_id' and admin session 'admin_id')
        session([
            'agent_id' => $agent->id,
            'agent_name' => $agent->name,
            'agent_agency_name' => $agent->agency_name ?? $agent->name,
            'agent_email' => $agent->email
        ]);

        return redirect()->route('agent.dashboard')->with('success', 'Welcome back, ' . $agent->name . '!');
    }

    public function signup()
    {
        if (session('agent_id')) {
            return redirect()->route('agent.dashboard');
        }
        return view('agent.auth.signup');
    }

    public function signupSubmit(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'agency_name'  => 'required|string|max:255',
            'email'        => 'required|email|unique:agents,email',
            'phone'        => 'required|string|max:20',
            'password'     => 'required|min:8|confirmed',
        ]);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'status'      => 1,
            'pending'     => 1,
            'approved'    => 0,
            'plan_id'     => null,
            'plan_status' => 'Pending',
            'service_guaranteed' => 0,
            'service_guaranteed_expires_at' => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        // Add agency_name if column exists
        if (Schema::hasColumn('agents', 'agency_name')) {
            $data['agency_name'] = $request->agency_name;
        }

        $id = DB::table('agents')->insertGetId($data);

        // 1. Send Welcome Email to Agent
        try {
            \App\Services\MailService::sendView(
                $request->email,
                'Welcome to Tour Raja Partner Network!',
                'emails.welcome-agent',
                [
                    'name'       => $request->name,
                    'agencyName' => $request->agency_name ?? $request->name
                ]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Agent Welcome Email Exception: " . $e->getMessage());
        }

        // 2. Send Welcome SMS / Notification to Agent Mobile Phone
        try {
            if (!empty($request->phone)) {
                $msgClubService = app(\App\Services\MsgClubService::class);
                $smsMsg = "Welcome to Tour Raja Partner Network, " . $request->name . "! Your agency account has been created successfully. Login at " . url('/agent/login');
                $msgClubService->sendCustomSms($request->phone, $smsMsg);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Agent Welcome SMS Exception: " . $e->getMessage());
        }

        // 3. Create Welcome Notification in Agent Dashboard
        try {
            if (Schema::hasTable('agent_notifications')) {
                DB::table('agent_notifications')->insert([
                    'agent_id'   => $id,
                    'title'      => 'Welcome to Tour Raja!',
                    'message'    => 'Your account ' . ($request->agency_name ?? $request->name) . ' was registered successfully. Complete your profile to start listing packages!',
                    'is_read'    => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Agent Welcome Notification Exception: " . $e->getMessage());
        }

        // Auto-login the new agent
        session([
            'agent_id' => $id,
            'agent_name' => $request->name,
            'agent_agency_name' => $request->agency_name ?? $request->name,
            'agent_email' => $request->email
        ]);

        return redirect()->route('agent.settings')->with('success', 'Account created successfully! Please complete your profile first (at least 80% completion required).');
    }

    public function logout()
    {
        session()->forget(['agent_id', 'agent_name', 'agent_agency_name', 'agent_email']);
        return redirect()->route('agent.login')->with('success', 'You have been logged out.');
    }

    public function dashboard()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();

        // Fetch all packages and filter to this agent's packages
        $allPackages = DB::table('packages')->select('id', 'agent', 'status')->get();
        $agentPackages = $allPackages->filter(function ($pkg) use ($agentId, $agent) {
            if (!$pkg->agent) return false;
            $agentData = json_decode($pkg->agent, true);
            if (!$agentData) return false;
            return (isset($agentData['id']) && $agentData['id'] == $agentId)
                || ($agent && isset($agentData['name']) && $agentData['name'] === $agent->name);
        });

        // Real dynamic counts
        $totalPackages   = $agentPackages->count();
        $activePackages  = $agentPackages->where('status', 'Active')->count();
        $pendingPackages = $agentPackages->where('status', 'Pending')->count();
        $expiredPackages = $agentPackages->where('status', 'Inactive')->count();

        // Real leads count for this agent
        $totalLeads = DB::table('leads')->count();

        // Real reviews count
        $profileReviews = DB::table('agent_feedback')->count();

        return view('agent.pages.dashboard', [
            'page_title'      => 'Dashboard',
            'page_breadcrumb' => 'Pages / Dashboard',
            'agent'           => $agent,
            'totalPackages'   => $totalPackages,
            'activePackages'  => $activePackages,
            'pendingPackages' => $pendingPackages,
            'expiredPackages' => $expiredPackages,
            'totalLeads'      => $totalLeads,
            'profilePackages' => $totalPackages,
            'profileLeads'    => $totalLeads,
            'profileReviews'  => $profileReviews,
            'recentLeads'     => [],
            'chartData'       => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ]);
    }

    public function about()
    {
        return view('agent.pages.about', [
            'page_title' => 'About Tour Raja',
            'page_breadcrumb' => 'Pages / About'
        ]);
    }

    public function addBranch()
    {
        return view('agent.pages.add-branch', [
            'page_title' => 'Add Branch',
            'page_breadcrumb' => 'Pages / Add Branch',
            'branch' => null
        ]);
    }

    public function editBranch($id)
    {
        $agentId = session('agent_id');
        $branch = DB::table('branches')->where('id', $id)->first();
        if (!$branch) {
            return redirect()->route('agent.branch')->with('error', 'Branch not found or unauthorized.');
        }

        return view('agent.pages.add-branch', [
            'page_title' => 'Edit Branch',
            'page_breadcrumb' => 'Pages / Edit Branch',
            'branch' => $branch
        ]);
    }

    public function storeBranch(Request $request)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please log in.');
        }

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $branchesCount = DB::table('branches')->where('agent_id', $agentId)->count();
        $permService = new \App\Services\PlanPermissionService();
        if ($permService->hasReachedLimit($agent, 'limit_branches', $branchesCount)) {
            return redirect()->back()->with('error', 'You have reached your branch limit. Please upgrade your plan to add more branches.');
        }

        $request->validate([
            'agency_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string|in:Online,Offline'
        ]);

        DB::table('branches')->insert([
            'agent_id' => $agentId,
            'agency_name' => $request->agency_name,
            'phone' => $request->phone,
            'location' => $request->location,
            'state' => $request->state,
            'country' => $request->country,
            'address' => $request->address,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('agent.branch')->with('success', 'Branch created successfully!');
    }

    // ─── FORGOT & RESET PASSWORD ──────────────────────────────────────────
    public function forgotPassword()
    {
        return view('agent.auth.forgot-password');
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $agent = DB::table('agents')->where('email', $request->email)->first();
        if (!$agent) {
            return back()->with('error', 'No agent account found with this email.');
        }

        $token = \Illuminate\Support\Str::random(64);
        $tokenHash = 'agent_' . $token;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $tokenHash, 'created_at' => now()]
        );

        $resetUrl = url("/agent/reset-password/{$tokenHash}");

        try {
            \Illuminate\Support\Facades\Mail::send('emails.reset-password', ['resetUrl' => $resetUrl], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Your Tour Raja Agent Password');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset link. Please check email configuration.');
        }

        return back()->with('success', 'We have emailed your password reset link!');
    }

    public function resetPassword($token)
    {
        return view('agent.auth.reset-password', ['token' => $token]);
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

        $agent = DB::table('agents')->where('email', $reset->email)->first();
        if (!$agent) {
            return back()->with('error', 'Agent not found.');
        }

        DB::table('agents')->where('email', $reset->email)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        DB::table('password_reset_tokens')->where('email', $reset->email)->delete();

        return redirect()->route('agent.login')->with('success', 'Password reset successfully! You can now log in.');
    }

    public function updateBranch(Request $request, $id)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please log in.');
        }

        $request->validate([
            'agency_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string|in:Online,Offline'
        ]);

        DB::table('branches')->where('id', $id)->update([
            'agency_name' => $request->agency_name,
            'phone' => $request->phone,
            'location' => $request->location,
            'state' => $request->state,
            'country' => $request->country,
            'address' => $request->address,
            'status' => $request->status,
            'updated_at' => now()
        ]);

        return redirect()->route('agent.branch')->with('success', 'Branch updated successfully!');
    }

    public function deleteBranch($id)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please log in.');
        }

        DB::table('branches')->where('id', $id)->delete();
        return redirect()->route('agent.branch')->with('success', 'Branch deleted successfully!');
    }

    public function addHotel()
    {
        return view('agent.pages.add-hotel', [
            'page_title' => 'Add Hotel',
            'page_breadcrumb' => 'Pages / Add Hotel'
        ]);
    }

    public function branch()
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please log in.');
        }

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $branches = DB::table('branches')->where('agent_id', $agentId)->orderBy('created_at', 'desc')->get();

        $mainBranch = (object)[
            'id' => 0,
            'agency_name' => $agent->agency_name ?: $agent->name,
            'location' => $agent->city,
            'state' => $agent->state,
            'country' => $agent->country,
            'status' => 'Online',
            'is_main' => true
        ];
        $branches->prepend($mainBranch);

        return view('agent.pages.branch', [
            'page_title' => 'Branches',
            'page_breadcrumb' => 'Pages / Branches',
            'branches' => $branches
        ]);
    }

    public function editImages()
    {
        return view('agent.pages.edit-images', [
            'page_title' => 'Edit Images',
            'page_breadcrumb' => 'Pages / Edit Images'
        ]);
    }

    public function editItinerary()
    {
        return view('agent.pages.edit-itinerary', [
            'page_title' => 'Edit Itinerary',
            'page_breadcrumb' => 'Pages / Edit Itinerary'
        ]);
    }

    public function createPackage()
    {
        $agentId = session('agent_id');
        if ($agentId) {
            $agent = DB::table('agents')->where('id', $agentId)->first();
            $freePlanId = DB::table('plans')->where('price', 0)->where('status', 'Active')->value('id') ?? 5;
            $plan = DB::table('plans')->where('id', $agent->plan_id ?? $freePlanId)->first();
            $limit = $plan ? $plan->package_limit : 1;
            
            $allPackages = DB::table('packages')->select('agent')->get();
            $packagesCount = $allPackages->filter(function ($pkg) use ($agentId) {
                if (!$pkg->agent) return false;
                $agentData = json_decode($pkg->agent, true);
                if (!$agentData) return false;
                return isset($agentData['id']) && $agentData['id'] == $agentId;
            })->count();

            if ($packagesCount >= $limit) {
                return redirect()->route('agent.payment')->with('show_upgrade_modal', true)->with('error', 'You have reached your package limit. Please upgrade your plan to add more packages.');
            }
        }

        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        $hotels = DB::table('hotels')->orderBy('name', 'asc')->get();
        $themes = DB::table('themes')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $holidayTypes = DB::table('holiday_types')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $transits = DB::table('transits')->where('status', 'Active')->orderBy('sr_no', 'asc')->get();
        return view('agent.pages.create-package', [
            'page_title' => 'Create Package',
            'page_breadcrumb' => 'Pages / Create Package',
            'agents' => $agents,
            'hotels' => $hotels,
            'themes' => $themes,
            'holidayTypes' => $holidayTypes,
            'transits' => $transits
        ]);
    }

    public function editPackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if (!$pkg) {
            return redirect()->route('agent.my-packages')->with('error', 'Package not found.');
        }

        // Verify package belongs to this agent
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');
        $agentData = json_decode($pkg->agent, true);
        $isOwner = false;
        if ($agentData) {
            if ((isset($agentData['id']) && $agentData['id'] == $agentId) || (isset($agentData['name']) && $agentData['name'] === $agentName)) {
                $isOwner = true;
            }
        }
        if (!$isOwner) {
            return redirect()->route('agent.my-packages')->with('error', 'Unauthorized access.');
        }

        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        $hotels = DB::table('hotels')->orderBy('name', 'asc')->get();
        $themes = DB::table('themes')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $holidayTypes = DB::table('holiday_types')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $transits = DB::table('transits')->where('status', 'Active')->orderBy('sr_no', 'asc')->get();
        return view('agent.pages.edit-package', [
            'page_title' => 'Edit Package',
            'page_breadcrumb' => 'Pages / Edit Package',
            'pkg' => $pkg,
            'agents' => $agents,
            'hotels' => $hotels,
            'themes' => $themes,
            'holidayTypes' => $holidayTypes,
            'transits' => $transits
        ]);
    }

    public function updatePackage(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required', 'price' => 'required|numeric']);

        $pkg = DB::table('packages')->where('id', $request->id)->first();
        if (!$pkg) {
            return redirect()->route('agent.my-packages')->with('error', 'Package not found.');
        }

        // Verify ownership
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');
        $agentData = json_decode($pkg->agent, true);
        $isOwner = false;
        if ($agentData) {
            if ((isset($agentData['id']) && $agentData['id'] == $agentId) || (isset($agentData['name']) && $agentData['name'] === $agentName)) {
                $isOwner = true;
            }
        }
        if (!$isOwner) {
            return redirect()->route('agent.my-packages')->with('error', 'Unauthorized access.');
        }

        // Main Image Upload
        $imageUrl = $pkg->image;
        if ($request->has('image') && !empty($request->image)) {
            $imageUrl = $request->image;
        }
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['image_file' => 'Upload failed: ' . $file->getErrorMessage()])->withInput();
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/packages'), $fileName);
            $imageUrl = 'uploads/packages/' . $fileName;
        }

        $galleryUrls = [];
        if ($request->has('existing_gallery_urls')) {
            $galleryUrls = is_array($request->existing_gallery_urls) ? $request->existing_gallery_urls : [];
        } else {
            if ($request->has('title')) {
                $galleryUrls = []; // Form submitted but no gallery images left
            } else {
                $galleryUrls = json_decode($pkg->gallery, true) ?: []; // Fallback
            }
        }
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/packages/gallery'), $fileName);
                    $galleryUrls[] = 'uploads/packages/gallery/' . $fileName;
                }
            }
        }

        if (count($galleryUrls) > 0) {
            $imageUrl = $galleryUrls[0];
        }

        // Brochure Upload
        $brochureUrl = $pkg->brochure;
        if ($request->hasFile('brochure_file')) {
            $file = $request->file('brochure_file');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages/brochures'), $fileName);
                $brochureUrl = 'uploads/packages/brochures/' . $fileName;
            }
        }

        // Inclusions & Exclusions parsing
        $included = [];
        if ($request->has('included')) {
            if (is_array($request->included)) {
                $included = array_values(array_filter(array_map('trim', $request->included)));
            } else {
                $included = array_values(array_filter(array_map('trim', explode("\n", $request->included))));
            }
        }
        $excluded = [];
        if ($request->has('excluded')) {
            if (is_array($request->excluded)) {
                $excluded = array_values(array_filter(array_map('trim', $request->excluded)));
            } else {
                $excluded = array_values(array_filter(array_map('trim', explode("\n", $request->excluded))));
            }
        }

        // New fields parsing
        $hotels = [];
        if ($request->has('hotels')) {
            foreach ($request->hotels as $hotelJson) {
                $hotelData = json_decode($hotelJson, true);
                if ($hotelData) $hotels[] = $hotelData;
            }
        }
        
        $keywords = [];
        if ($request->has('keywords') && !empty($request->keywords)) {
            if (is_array($request->keywords)) {
                $keywords = array_values(array_filter(array_map('trim', $request->keywords)));
            } else {
                $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
            }
        }

        $amenities = $request->input('amenities', []);
        $transfers = $request->input('transfers', []);
        $meals = $request->input('meals', []);

        // Sightseeing List parsing
        $sightseeing_list = [];
        if ($request->has('sightseeing_list')) {
            if (is_array($request->sightseeing_list)) {
                $sightseeing_list = array_values(array_filter(array_map('trim', $request->sightseeing_list)));
            } else {
                $sightseeing_list = array_values(array_filter(array_map('trim', explode("\n", $request->sightseeing_list))));
            }
        }
    

        // Itinerary Days parsing
        $itinerary = [];
        if ($request->has('itinerary_titles')) {
            foreach ($request->itinerary_titles as $i => $dayTitle) {
                $dayDesc = $request->itinerary_descriptions[$i] ?? '';
                $dayDur = $request->itinerary_durations[$i] ?? '';
                if (!empty($dayTitle)) {
                    $itinerary[] = ['title' => $dayTitle, 'desc' => $dayDesc, 'duration' => $dayDur];
                }
            }
        }

        $locParts = array_filter([$request->departure_city, $request->departure_state, $request->departure_country]);
        $calculatedLocation = !empty($locParts) ? implode(', ', $locParts) : 'Global';

        DB::table('packages')->where('id', $request->id)->update([
            'title'      => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'location'   => $calculatedLocation,
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
            'hide_price' => $request->has('hide_price') ? 1 : 0,
            'about_tours' => $request->about_tours ?? null,
            'overview' => $request->overview ?? null,
            'highlights' => $request->highlights ? json_encode($request->highlights) : null,
            'image'      => $imageUrl,
            'theme'      => $request->theme ?? '',
            'holiday_type' => $request->holiday_type ?? '',
            'category'   => $request->category ?? 'domestic',
            'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,
            'badge'      => $request->badge ?? '',
            'stock'      => $request->stock ?? '10 Left',
            'validity'   => $request->validity ?? '',
            'sightseeing'=> $request->sightseeing ?? '',
            'gallery'    => json_encode($galleryUrls),
            'brochure'   => $brochureUrl,
            'departure_dates' => $request->departure_dates ?? null,
            'included'   => json_encode($included),
            'excluded'   => json_encode($excluded),
            'hotels'     => json_encode($hotels),
            'keywords'   => json_encode($keywords),
            'amenities'  => json_encode($amenities),
            'transfers'  => json_encode($transfers),
            'meals'      => json_encode($meals),
            'itinerary'           => json_encode($itinerary),
            'editorial_itinerary' => $request->editorial_itinerary ?? null,
            'validity'            => $request->validity ?? $pkg->validity,
            'sightseeing'         => $request->sightseeing ?? $pkg->sightseeing,
            'updated_at'          => now(),
        ]);

        return redirect()->route('agent.my-packages')->with('success', 'Package updated successfully!');
    }

    public function storePackage(Request $request)
    {
        $agentId = session('agent_id');
        if ($agentId) {
            $agent = DB::table('agents')->where('id', $agentId)->first();
            
            $allPackages = DB::table('packages')->select('agent')->get();
            $packagesCount = $allPackages->filter(function ($pkg) use ($agentId) {
                $pkgAgentIds = json_decode($pkg->agent, true);
                return is_array($pkgAgentIds) && in_array($agentId, $pkgAgentIds);
            })->count();

            $permService = new \App\Services\PlanPermissionService();
            if ($permService->hasReachedLimit($agent, 'limit_packages', $packagesCount)) {
                return redirect()->back()->with('error', 'You have reached your package limit. Please upgrade your plan to add more packages.');
            }
        }

        $request->validate(['title' => 'required', 'price' => 'required|numeric']);

        // Create uploads directories if not exist
        foreach (['uploads/packages', 'uploads/packages/gallery', 'uploads/packages/brochures'] as $dir) {
            if (!file_exists(public_path($dir))) {
                mkdir(public_path($dir), 0775, true);
            }
        }

        // Main Image Upload
        $imageUrl = null;
        if ($request->has('image_url') && !empty($request->image_url)) {
            $imageUrl = $request->image_url;
        }
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['image_file' => 'Upload failed: ' . $file->getErrorMessage()])->withInput();
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/packages'), $fileName);
            $imageUrl = 'uploads/packages/' . $fileName;
        }

        // Gallery Images Upload
        $galleryUrls = [];
        if ($request->has('gallery_urls') && is_array($request->gallery_urls)) {
            $galleryUrls = array_merge($galleryUrls, $request->gallery_urls);
        }
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if (!$file->isValid()) continue;
                $fileName = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages/gallery'), $fileName);
                $galleryUrls[] = '/uploads/packages/gallery/' . $fileName;
            }
        }

        if (count($galleryUrls) > 0) {
            $imageUrl = $galleryUrls[0];
        }

        // Brochure Upload
        $brochureUrl = null;
        if ($request->hasFile('brochure_file')) {
            $file = $request->file('brochure_file');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages/brochures'), $fileName);
                $brochureUrl = 'uploads/packages/brochures/' . $fileName;
            }
        }

        // Inclusions & Exclusions parsing
        $included = [];
        if ($request->has('included')) {
            if (is_array($request->included)) {
                $included = array_values(array_filter(array_map('trim', $request->included)));
            } else {
                $included = array_values(array_filter(array_map('trim', explode("\n", $request->included))));
            }
        }
        $excluded = [];
        if ($request->has('excluded')) {
            if (is_array($request->excluded)) {
                $excluded = array_values(array_filter(array_map('trim', $request->excluded)));
            } else {
                $excluded = array_values(array_filter(array_map('trim', explode("\n", $request->excluded))));
            }
        }
        // New fields parsing
        $hotels = [];
        if ($request->has('hotels')) {
            foreach ($request->hotels as $hotelJson) {
                $hotelData = json_decode($hotelJson, true);
                if ($hotelData) $hotels[] = $hotelData;
            }
        }
        
        $keywords = [];
        if ($request->has('keywords') && !empty($request->keywords)) {
            if (is_array($request->keywords)) {
                $keywords = array_values(array_filter(array_map('trim', $request->keywords)));
            } else {
                $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
            }
        }

        $amenities = $request->input('amenities', []);
        $transfers = $request->input('transfers', []);
        
        // Meals might be mixed in included, but let's see if we have explicit meals. If not, fallback to included.
        $meals = $request->input('meals', []);

        // Sightseeing List parsing
        $sightseeing_list = [];
        if ($request->has('sightseeing_list')) {
            if (is_array($request->sightseeing_list)) {
                $sightseeing_list = array_values(array_filter(array_map('trim', $request->sightseeing_list)));
            } else {
                $sightseeing_list = array_values(array_filter(array_map('trim', explode("\n", $request->sightseeing_list))));
            }
        }
    

        // Itinerary Days parsing
        $itinerary = [];
        if ($request->has('itinerary_titles')) {
            foreach ($request->itinerary_titles as $i => $dayTitle) {
                $dayDesc = $request->itinerary_descriptions[$i] ?? '';
                $dayDur = $request->itinerary_durations[$i] ?? '';
                if (!empty($dayTitle)) {
                    $itinerary[] = ['title' => $dayTitle, 'desc' => $dayDesc, 'duration' => $dayDur];
                }
            }
        }

        // Auto-set agent from session (the logged-in agent)
        $agentId   = session('agent_id');
        $agentName = session('agent_name', 'Agent');
        $agentDb   = $agentId ? DB::table('agents')->where('id', $agentId)->first() : null;

        if ($agentDb) {
            $agentJson = json_encode([
                'id'       => $agentDb->id,
                'logo'     => $agentDb->logo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentDb->agency_name ?? $agentDb->name),
                'name'     => $agentDb->agency_name ?? $agentDb->name,
                'phone'    => $agentDb->phone ?? '',
                'whatsapp' => $agentDb->phone ?? ''
            ]);
        } else {
            $agentJson = json_encode([
                'id'       => null,
                'logo'     => 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName),
                'name'     => $agentName,
                'phone'    => '',
                'whatsapp' => ''
            ]);
        }

        $locParts = array_filter([$request->departure_city, $request->departure_state, $request->departure_country]);
        $calculatedLocation = !empty($locParts) ? implode(', ', $locParts) : 'Global';

        DB::table('packages')->insert([
            'title'      => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'location'   => $calculatedLocation,
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'rating'     => 4.8,
            'reviews'    => 0,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
            'hide_price' => $request->has('hide_price') ? 1 : 0,
            'about_tours' => $request->about_tours ?? null,
            'overview' => $request->overview ?? null,
            'highlights' => $request->highlights ? json_encode($request->highlights) : null,
            'image'      => $imageUrl ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'category'   => $request->category ?? 'domestic',
            'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,
            'badge'      => $request->badge,
            'theme'      => $request->theme,
            'holiday_type'=> $request->holiday_type,
            'status'     => 'Pending',
            'stock'      => $request->stock ?? '10 Left',
            'agent'      => $agentJson,
            'gallery'    => json_encode($galleryUrls),
            'brochure'   => $brochureUrl,
            'departure_dates' => $request->departure_dates ?? null,
            'included'   => json_encode($included),
            'excluded'   => json_encode($excluded),
            'hotels'     => json_encode($hotels),
            'keywords'   => json_encode($keywords),
            'amenities'  => json_encode($amenities),
            'transfers'  => json_encode($transfers),
            'meals'      => json_encode($meals),
            'itinerary'           => json_encode($itinerary),
            'editorial_itinerary' => $request->editorial_itinerary ?? null,
            'validity'            => $request->validity ?? null,
            'sightseeing'         => $request->sightseeing ?? null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect()->route('agent.my-packages')->with('success', 'Package submitted successfully! It will be reviewed by admin before going live.');
    }

    public function togglePackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if (!$pkg) {
            return response()->json(['success' => false, 'message' => 'Package not found']);
        }

        if ($pkg->status === 'Pending') {
            return response()->json(['success' => false, 'message' => 'Cannot toggle a pending package. Please wait for admin approval.']);
        }

        // Verify ownership
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');
        $agentData = json_decode($pkg->agent, true);
        $isOwner = false;
        if ($agentData) {
            if ((isset($agentData['id']) && $agentData['id'] == $agentId) || (isset($agentData['name']) && $agentData['name'] === $agentName)) {
                $isOwner = true;
            }
        }

        if (!$isOwner) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $newStatus = $pkg->status === 'Active' ? 'Inactive' : 'Active';
        DB::table('packages')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);

        return response()->json(['success' => true, 'new_status' => $newStatus]);
    }

    public function feedback()
    {
        $feedbacks = AgentFeedback::where('agent_id', session('agent_id'))->orderBy('created_at', 'desc')->get();
        return view('agent.pages.feedback', [
            'page_title' => 'Feedback',
            'page_breadcrumb' => 'Pages / Feedback',
            'feedbacks' => $feedbacks
        ]);
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'message' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $agentId = session('agent_id');
        if ($agentId) {
            $agent = DB::table('agents')->where('id', $agentId)->first();
            $feedbackCount = DB::table('agent_feedback')->where('agent_id', $agentId)->count();
            $permService = new \App\Services\PlanPermissionService();
            if ($permService->hasReachedLimit($agent, 'limit_customer_feedback', $feedbackCount)) {
                return redirect()->back()->with('error', 'You have reached your feedback limit. Please upgrade your plan to add more feedback.');
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/feedback'), $imageName);
            $imagePath = 'uploads/feedback/' . $imageName;
        }

        AgentFeedback::create([
            'agent_id' => session('agent_id'),
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'message' => $request->message,
            'image_path' => $imagePath,
            'package_id' => $request->package_id
        ]);

        return redirect()->back()->with('success', 'Feedback added successfully.');
    }

    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required',
            'message' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $feedback = AgentFeedback::where('agent_id', session('agent_id'))->findOrFail($id);

        $imagePath = $feedback->image_path;
        if ($request->hasFile('image')) {
            // Optionally delete old image here
            if ($imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/feedback'), $imageName);
            $imagePath = 'uploads/feedback/' . $imageName;
        }

        $feedback->update([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'message' => $request->message,
            'image_path' => $imagePath,
            'package_id' => $request->package_id ?? $feedback->package_id
        ]);

        return redirect()->back()->with('success', 'Feedback updated successfully.');
    }

    public function deleteFeedback($id)
    {
        $feedback = AgentFeedback::where('agent_id', session('agent_id'))->findOrFail($id);
        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }

    public function gallery(Request $request)
    {
        $agentId = session('agent_id');
        $parentId = $request->query('folder', null);

        // Current folder if any
        $currentFolder = null;
        $breadcrumbs = [];
        
        if ($parentId) {
            $currentFolder = AgentMedia::where('type', 'folder')
                ->where('id', $parentId)
                ->first();
                
            if (!$currentFolder) {
                return redirect()->route('agent.gallery')->with('error', 'Folder not found.');
            }

            // Build breadcrumbs
            $parent = $currentFolder;
            while ($parent) {
                array_unshift($breadcrumbs, $parent);
                $parent = $parent->parent;
            }
        }

        // Fetch contents
        $media = AgentMedia::where('parent_id', $parentId)
            ->orderBy('type') // Folders first
            ->orderBy('name')
            ->get();
            
        $folders = $media->where('type', 'folder');
        $images = $media->where('type', 'image');

        // All folders for the "Move to" dropdown
        $allFolders = AgentMedia::where('type', 'folder')->orderBy('name')->get();

        return view('agent.pages.gallery', [
            'page_title'      => 'Gallery',
            'page_breadcrumb' => 'Pages / Gallery',
            'folders'         => $folders,
            'images'          => $images,
            'currentFolder'   => $currentFolder,
            'breadcrumbs'     => $breadcrumbs,
            'allFolders'      => $allFolders,
        ]);
    }

    public function apiGallery(Request $request)
    {
        $agentId = session('agent_id');
        $parentId = $request->query('folder', null);

        $media = AgentMedia::where('parent_id', $parentId)
            ->orderBy('type') // Folders first
            ->orderBy('name')
            ->get();
            
        $folders = $media->where('type', 'folder')->values();
        $images = $media->where('type', 'image')->values();

        $breadcrumbs = [];
        if ($parentId) {
            $currentFolder = AgentMedia::where('type', 'folder')
                ->where('id', $parentId)
                ->first();
                
            if ($currentFolder) {
                $parent = $currentFolder;
                while ($parent) {
                    array_unshift($breadcrumbs, [
                        'id' => $parent->id,
                        'name' => $parent->name
                    ]);
                    $parent = $parent->parent;
                }
            }
        }

        return response()->json([
            'folders' => $folders,
            'images' => $images,
            'breadcrumbs' => $breadcrumbs
        ]);
    }


    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:agent_media,id'
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please log in again.');
        }

        // Check if parent folder belongs to agent
        if ($request->parent_id) {
            $parent = AgentMedia::where('id', $request->parent_id)->first();
            if (!$parent) {
                return redirect()->back()->with('error', 'Invalid parent folder.');
            }
        }

        AgentMedia::create([
            'agent_id' => $agentId,
            'type' => 'folder',
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'parent_id' => 'nullable|exists:agent_media,id'
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please log in again.');
        }

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $mediaCount = \App\Models\AgentMedia::where('agent_id', $agentId)->count();
        $permService = new \App\Services\PlanPermissionService();
        if ($permService->hasReachedLimit($agent, 'limit_gallery_images', $mediaCount)) {
            return redirect()->back()->with('error', 'You have reached your gallery limit. Please upgrade your plan to upload more images.');
        }

        if ($request->parent_id) {
            $parent = AgentMedia::where('id', $request->parent_id)->first();
            if (!$parent) {
                return redirect()->back()->with('error', 'Invalid folder.');
            }
        }

        if ($request->hasFile('files')) {
            $uploadPath = public_path('uploads/shared_gallery');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true);
            }

            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $size = $file->getSize();
                    $mimeType = $file->getClientMimeType();
                    $fileName = time() . '_' . rand(1000, 9999) . '_' . $originalName;
                    
                    $file->move($uploadPath, $fileName);
                    
                    AgentMedia::create([
                        'agent_id'  => $agentId,
                        'type'      => 'image',
                        'name'      => $originalName,
                        'file_path' => 'uploads/shared_gallery' . '/' . $fileName,
                        'size'      => $size,
                        'mime_type' => $mimeType,
                        'parent_id' => $request->parent_id,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Images uploaded successfully!');
    }

    public function moveMedia(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'target_folder_id' => 'nullable' // null means root
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please log in again.');
        }
        $targetId = $request->target_folder_id === 'root' ? null : $request->target_folder_id;

        if ($targetId) {
            $folder = AgentMedia::where('id', $targetId)->where('type', 'folder')->first();
            if (!$folder) {
                return redirect()->back()->with('error', 'Target folder not found.');
            }
        }

        AgentMedia::whereIn('id', $request->selected_ids)
            
            ->update(['parent_id' => $targetId]);

        return redirect()->back()->with('success', 'Items moved successfully!');
    }

    public function deleteMedia(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array'
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please log in again.');
        }
        
        $items = AgentMedia::whereIn('id', $request->selected_ids)
            
            ->get();

        foreach ($items as $item) {
            $this->deleteMediaItemRecursively($item);
        }

        return redirect()->back()->with('success', 'Selected items deleted successfully!');
    }

    private function deleteMediaItemRecursively(AgentMedia $item)
    {
        if ($item->isFolder()) {
            foreach ($item->children as $child) {
                $this->deleteMediaItemRecursively($child);
            }
        } else {
            // Delete physical file
            $filePath = public_path($item->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $item->delete();
    }

    public function hotels()
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');

        // Only show hotels that belong to THIS agent
        $hotels = DB::table('hotels')
            ->leftJoin('packages', 'hotels.package_id', '=', 'packages.id')
            ->where('hotels.agent_id', $agentId)
            ->select('hotels.*', 'packages.title as package_title')
            ->orderBy('hotels.id', 'desc')
            ->get();

        $hotelCategories = DB::table('hotel_categories')->orderBy('id', 'asc')->get();

        $allPackages = DB::table('packages')->select('id', 'title', 'agent')->orderBy('created_at', 'desc')->get();
        $packages = $allPackages->filter(function ($pkg) use ($agentId, $agentName) {
            if (!$pkg->agent) return false;
            $agentData = json_decode($pkg->agent, true);
            if (!$agentData) return false;
            return (isset($agentData['id']) && $agentData['id'] == $agentId)
                || (isset($agentData['name']) && $agentData['name'] === $agentName);
        })->values();

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $hotelsCount = DB::table('hotels')->where('agent_id', $agentId)->count();
        $permService = new \App\Services\PlanPermissionService();
        $hotelLimitReached = $permService->hasReachedLimit($agent, 'limit_hotel_options', $hotelsCount);

        return view('agent.pages.hotels', [
            'page_title'      => 'Hotels',
            'page_breadcrumb' => 'Pages / Hotels',
            'hotels'          => $hotels,
            'hotelCategories' => $hotelCategories,
            'packages'        => $packages,
            'hotelLimitReached' => $hotelLimitReached,
        ]);
    }
    
    public function storeHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required', 'location' => 'required', 'category' => 'required']);

        $agentId = session('agent_id');
        
        $agent = DB::table('agents')->where('id', $agentId)->first();
        $hotelsCount = DB::table('hotels')->where('agent_id', $agentId)->count();
        $permService = new \App\Services\PlanPermissionService();
        if ($permService->hasReachedLimit($agent, 'limit_hotel_options', $hotelsCount)) {
            return redirect()->back()->with('error', 'You have reached your hotel limit. Please upgrade your plan to add more hotels.');
        }

        $hotelId = DB::table('hotels')->insertGetId([
            'agent_id'   => $agentId,
            'package_id' => $request->package_id ?: null,
            'name'       => $request->name,
            'category'   => $request->category,
            'location'   => $request->location,
            'state'      => $request->state,
            'country'    => $request->country,
            'rating'     => 5,
            'status'     => $request->status ?? 'Published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->filled('package_id')) {
            $this->addHotelToPackage($request->package_id, $request->name, $request->category);
        }

        return redirect()->back()->with('success', 'Hotel added successfully!');
    }

    public function updateHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required', 'category' => 'required']);

        $agentId = session('agent_id');

        $oldHotel = DB::table('hotels')
            ->where('id', $request->id)
            
            ->first();

        if (!$oldHotel) {
            return redirect()->back()->with('error', 'Hotel not found.');
        }

        // 1. Update the hotels database record
        DB::table('hotels')
            ->where('id', $request->id)
            
            ->update([
                'name'       => $request->name,
                'category'   => $request->category,
                'location'   => $request->location,
                'state'      => $request->state,
                'country'    => $request->country,
                'package_id' => $request->package_id ?: null,
                'status'     => $request->status ?? 'Published',
                'updated_at' => now(),
            ]);

        // 2. Synchronize with Packages JSON
        // If it was linked to an old package, clean it up
        if ($oldHotel->package_id) {
            $this->removeHotelFromPackage($oldHotel->package_id, $oldHotel->name);
        }

        // Add to new package if selected
        if ($request->filled('package_id')) {
            $this->addHotelToPackage($request->package_id, $request->name, $request->category);
        }

        return redirect()->back()->with('success', 'Hotel updated successfully!');
    }

    public function deleteHotel($id)
    {
        $agentId = session('agent_id');

        $hotel = DB::table('hotels')
            ->where('id', $id)
            
            ->first();

        if ($hotel) {
            if ($hotel->package_id) {
                $this->removeHotelFromPackage($hotel->package_id, $hotel->name);
            }

            DB::table('hotels')
                ->where('id', $id)
                ->delete();
        }

        return redirect()->back()->with('success', 'Hotel deleted successfully!');
    }

    private function removeHotelFromPackage($packageId, $hotelName)
    {
        $package = DB::table('packages')->where('id', $packageId)->first();
        if ($package) {
            $hotels = json_decode($package->hotels, true) ?? [];
            $newHotels = [];
            foreach ($hotels as $h) {
                if ($h['name'] !== $hotelName) {
                    $newHotels[] = $h;
                }
            }
            DB::table('packages')->where('id', $packageId)->update([
                'hotels' => json_encode($newHotels)
            ]);
        }
    }

    private function addHotelToPackage($packageId, $hotelName, $hotelCategory)
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');
        
        $package = DB::table('packages')->where('id', $packageId)->first();
        if ($package) {
            $agentData = json_decode($package->agent, true);
            $belongsToAgent = false;
            if ($agentData) {
                if ((isset($agentData['id']) && $agentData['id'] == $agentId) || 
                    (isset($agentData['name']) && $agentData['name'] === $agentName)) {
                    $belongsToAgent = true;
                }
            }
            if ($belongsToAgent) {
                $hotels = json_decode($package->hotels, true) ?? [];
                // Check if already exists
                $exists = false;
                foreach($hotels as $h) {
                    if ($h['name'] == $hotelName) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $hotels[] = [
                        'name' => $hotelName,
                        'room' => $hotelCategory,
                        'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=200&auto=format&fit=crop'
                    ];
                    DB::table('packages')->where('id', $packageId)->update([
                        'hotels' => json_encode($hotels)
                    ]);
                }
            }
        }
    }

    public function invoice()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        $payments = DB::table('payments')
            ->where(function($q) use ($agentId, $agent) {
                $q;
                if ($agent) {
                    $q->orWhere('email', $agent->email);
                }
            })
            ->orderBy('created_at', 'desc')->get();
            
        return view('agent.pages.invoice', [
            'page_title' => 'Invoice',
            'page_breadcrumb' => 'Pages / Invoice',
            'payments' => $payments
        ]);
    }

    public function downloadInvoice($id)
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        $payment = DB::table('payments')
            ->where('id', $id)
            ->where(function($q) use ($agentId, $agent) {
                if ($agent) {
                    $q->where('email', $agent->email)->orWhere('agent_id', $agentId);
                } else {
                    $q->where('agent_id', $agentId);
                }
            })->first();
            
        if (!$payment) {
            return redirect()->route('agent.invoice')->with('error', 'Invoice not found.');
        }

        $invoiceData = \App\Http\Controllers\AdminController::prepareInvoiceData($payment);
        $amountInWords = convertNumberToWords($invoiceData['grand_total'] ?? 0);

        return view('agent.pages.print-invoice', [
            'payment' => $payment,
            'agent' => $agent,
            'invoiceData' => $invoiceData,
            'amountInWords' => $amountInWords
        ]);
    }

    public function showInvoice($id)
    {
        return $this->downloadInvoice($id);
    }

    public function leads()
    {
        $leads = DB::table('leads')
                    ->where('agent_id', session('agent_id'))
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('agent.pages.leads', [
            'page_title' => 'Leads',
            'page_breadcrumb' => 'Pages / Leads',
            'leads' => $leads
        ]);
    }

    public function updateLead(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $status = $request->input('status');

        DB::table('leads')->where('id', $id)->update([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteLead($id)
    {
        DB::table('leads')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function agentContact()
    {
        $agentId = session('agent_id');
        $agent   = DB::table('agents')->where('id', $agentId)->first();

        // Fetch all contacts that belong to this agent OR match agent email
        $contacts = DB::table('contacts')
            ->where(function ($q) use ($agentId, $agent) {
                $q
                  ->orWhereNull('agent_id');
                if ($agent) {
                    $q->orWhere('email', $agent->email);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.pages.contact', [
            'page_title'      => 'Contact Inquiries',
            'page_breadcrumb' => 'Pages / Contact',
            'contacts'        => $contacts,
        ]);
    }

    public function updateContact(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:contacts,id',
            'status' => 'required|in:New,Contacted,Pending,Booked,Lost',
        ]);

        DB::table('contacts')->where('id', $request->id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Contact status updated successfully!');
    }

    public function myPackages()
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');

        // Fetch packages belonging to this agent (by matching agent JSON name)
        $allPackages = DB::table('packages')->select('id', 'title', 'location', 'image', 'price', 'agent', 'status', 'created_at', 'category', 'group_size', 'duration', 'stock', 'currency', 'badge', 'validity', 'expiry_date')->orderBy('created_at', 'desc')->get();
        $packages = $allPackages->filter(function ($pkg) use ($agentId, $agentName) {
            if (!$pkg->agent) return false;
            $agentData = json_decode($pkg->agent, true);
            if (!$agentData) return false;
            return (isset($agentData['id']) && $agentData['id'] == $agentId)
                || (isset($agentData['name']) && $agentData['name'] === $agentName);
        })->values();

        return view('agent.pages.my-packages', [
            'page_title'      => 'My Packages',
            'page_breadcrumb' => 'Pages / My Packages',
            'packages'        => $packages,
        ]);
    }

    public function notifications()
    {
        $agentId = session('agent_id');
        $notifications = DB::table('agent_notifications')
            
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.pages.notifications', [
            'page_title' => 'Notifications',
            'page_breadcrumb' => 'Pages / Notifications',
            'notifications' => $notifications
        ]);
    }

    public function markNotificationsRead()
    {
        $agentId = session('agent_id');
        DB::table('agent_notifications')
            
            ->update(['is_read' => true, 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read!'
        ]);
    }

    public function payment()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        $activePlan = null;
        $freePlanId = DB::table('plans')->where('price', 0)->where('status', 'Active')->value('id') ?? 5;
        if ($agent && $agent->plan_id) {
            $activePlan = DB::table('plans')->where('id', $agent->plan_id)->first();
        } else {
            $activePlan = DB::table('plans')->where('id', $freePlanId)->first(); // Fallback to Basic
        }

        $plans = \App\Models\Plan::where('status', 'Active')->orderBy('price', 'asc')->with('permissions')->get();
        $payments = DB::table('payments')
            ->where(function($q) use ($agentId, $agent) {
                $q;
                if ($agent) {
                    $q->orWhere('email', $agent->email);
                }
            })
            ->orderByDesc('id')->take(5)->get();
        
        $agentName = session('agent_name');
        $allPackages = DB::table('packages')->get();
        $agentPackages = $allPackages->filter(function ($pkg) use ($agentId, $agentName) {
            $agentData = json_decode($pkg->agent, true);
            if (!$agentData) return false;
            return (isset($agentData['id']) && $agentData['id'] == $agentId) 
                || (isset($agentData['name']) && $agentData['name'] === $agentName);
        })->values();

        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        $addonPricings = \App\Models\AddonPricing::all();
        $boosts = $addonPricings->where('type', 'boost');
        $ads = $addonPricings->where('type', 'ad');
        $trustedAgents = $addonPricings->where('type', 'trusted_agent');

        return view('agent.pages.payment', [
            'page_title' => 'Payment & Billing',
            'page_breadcrumb' => 'Pages / Payment',
            'agent' => $agent,
            'activePlan' => $activePlan,
            'plans' => $plans,
            'payments' => $payments,
            'agentPackages' => $agentPackages,
            'settings' => $settings,
            'boosts' => $boosts,
            'ads' => $ads,
            'trustedAgents' => $trustedAgents
        ]);
    }

    public function profile()
    {
        return redirect()->route('agent.settings');
    }

    public function services()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        return view('agent.pages.services', [
            'page_title' => 'Services',
            'page_breadcrumb' => 'Pages / Services',
            'agent' => $agent
        ]);
    }

    public function toggleAgentService(Request $request)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $name = $request->input('name');
        $icon = $request->input('icon');
        $checked = filter_var($request->input('checked'), FILTER_VALIDATE_BOOLEAN);

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $services = json_decode($agent->services ?? '[]', true) ?: [];

        if ($checked) {
            // Check if already exists
            $exists = false;
            foreach ($services as $s) {
                if ($s['name'] === $name) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $services[] = ['name' => $name, 'icon' => $icon];
            }
        } else {
            // Remove
            $services = array_filter($services, function ($s) use ($name) {
                return $s['name'] !== $name;
            });
            $services = array_values($services); // reindex
        }

        DB::table('agents')->where('id', $agentId)->update([
            'services' => json_encode($services),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function addAgentService(Request $request)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please log in.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:100'
        ]);

        $name = $request->input('name');
        $icon = $request->input('icon');

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $services = json_decode($agent->services ?? '[]', true) ?: [];

        $exists = false;
        foreach ($services as $s) {
            if (strtolower($s['name']) === strtolower($name)) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $services[] = ['name' => $name, 'icon' => $icon];
            DB::table('agents')->where('id', $agentId)->update([
                'services' => json_encode($services),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Service added successfully!');
    }

    public function settings()
    {
        return view('agent.pages.settings', [
            'page_title' => 'Settings',
            'page_breadcrumb' => 'Pages / Settings'
        ]);
    }

    public function updateSettings(Request $request)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please login first.');
        }

        $data = [
            'name' => $request->input('name'),
            'agency_name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'secondary_phone' => $request->input('secondary_phone'),
            'landline' => $request->input('landline'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'pincode' => $request->input('pincode'),
            'why_us' => $request->input('why_us'),
            'website' => $request->input('website'),
            'gst_number' => $request->input('gst_number'),
            'sac_hsn_code' => $request->input('sac_hsn_code'),
            'about' => $request->input('about'),
            'facebook' => $request->input('facebook'),
            'twitter' => $request->input('twitter'),
            'linkedin' => $request->input('linkedin'),
            'instagram' => $request->input('instagram'),
            'since' => $request->input('since'),
            'updated_at' => now()
        ];

        // Create uploads directory if not exists
        if (!file_exists(public_path('uploads/agents'))) {
            mkdir(public_path('uploads/agents'), 0775, true);
        }

        $agent = DB::table('agents')->where('id', $agentId)->first();

        if ($request->input('delete_logo') == '1') {
            if ($agent && $agent->logo && file_exists(public_path($agent->logo))) {
                @unlink(public_path($agent->logo));
            }
            $data['logo'] = null;
        }

        if ($request->input('delete_card_front') == '1') {
            if ($agent && $agent->business_card_front && file_exists(public_path($agent->business_card_front))) {
                @unlink(public_path($agent->business_card_front));
            }
            $data['business_card_front'] = null;
        }

        if ($request->input('delete_card_back') == '1') {
            if ($agent && $agent->business_card_back && file_exists(public_path($agent->business_card_back))) {
                @unlink(public_path($agent->business_card_back));
            }
            $data['business_card_back'] = null;
        }

        if ($request->input('delete_banner') == '1') {
            if ($agent && $agent->banner && file_exists(public_path($agent->banner))) {
                @unlink(public_path($agent->banner));
            }
            $data['banner'] = null;
        }

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            if ($file->isValid()) {
                // Delete old logo
                if ($agent && $agent->logo && file_exists(public_path($agent->logo))) {
                    @unlink(public_path($agent->logo));
                }
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents'), $fileName);
                $data['logo'] = '/uploads/agents/' . $fileName;
            }
        }

        if ($request->hasFile('business_card_front_file')) {
            $file = $request->file('business_card_front_file');
            if ($file->isValid()) {
                // Delete old card
                if ($agent && $agent->business_card_front && file_exists(public_path($agent->business_card_front))) {
                    @unlink(public_path($agent->business_card_front));
                }
                $fileName = time() . '_card_front_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents'), $fileName);
                $data['business_card_front'] = '/uploads/agents/' . $fileName;
            }
        }

        if ($request->hasFile('business_card_back_file')) {
            $file = $request->file('business_card_back_file');
            if ($file->isValid()) {
                // Delete old card
                if ($agent && $agent->business_card_back && file_exists(public_path($agent->business_card_back))) {
                    @unlink(public_path($agent->business_card_back));
                }
                $fileName = time() . '_card_back_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents'), $fileName);
                $data['business_card_back'] = '/uploads/agents/' . $fileName;
            }
        }

        if ($request->hasFile('banner_file')) {
            $file = $request->file('banner_file');
            if ($file->isValid()) {
                // Delete old banner
                if ($agent && $agent->banner && file_exists(public_path($agent->banner))) {
                    @unlink(public_path($agent->banner));
                }
                $fileName = time() . '_banner_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents'), $fileName);
                $data['banner'] = '/uploads/agents/' . $fileName;
            }
        }

        DB::table('agents')->where('id', $agentId)->update($data);

        // Update session name if changed
        session([
            'agent_name' => $request->input('name'),
            'agent_agency_name' => $request->input('name')
        ]);

        // Calculate new percentage to determine redirect
        $fields = ['name', 'phone', 'email', 'address', 'city', 'state', 'country', 'pincode', 'logo', 'about'];
        $filled = 0;
        
        // Refetch agent to get latest data including uploads
        $agentUpdated = DB::table('agents')->where('id', $agentId)->first();
        if ($agentUpdated) {
            foreach ($fields as $field) {
                if (!empty($agentUpdated->$field)) {
                    $filled++;
                }
            }
        }
        $percentage = round(($filled / count($fields)) * 100);

        if ($percentage >= 80 && empty($agentUpdated->plan_id)) {
            return redirect()->route('agent.payment')->with('show_upgrade_modal', true)->with('success', 'Profile settings updated successfully! Please select a plan to continue.');
        }

        return redirect()->back()->with('success', 'Profile settings updated successfully!');
    }

    public function profileImages()
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please login first.');
        }

        $images = \DB::table('agent_profile_images')->where('agent_id', $agentId)->get();

        return view('agent.pages.profile_images', [
            'page_title' => 'Profile Images',
            'page_breadcrumb' => 'Pages / Profile Images',
            'images' => $images
        ]);
    }

    public function storeProfileImage(Request $request)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $fileName = time() . '_profile_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents/profile'), $fileName);
                $imagePath = '/uploads/agents/profile/' . $fileName;

                \DB::table('agent_profile_images')->insert([
                    'agent_id' => $agentId,
                    'image_path' => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return redirect()->back()->with('success', 'Image uploaded successfully.');
            }
        }
        return redirect()->back()->with('error', 'Failed to upload image.');
    }

    public function deleteProfileImage($id)
    {
        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please login first.');
        }

        $image = \DB::table('agent_profile_images')->where('id', $id)->where('agent_id', $agentId)->first();
        if ($image) {
            if (file_exists(public_path($image->image_path))) {
                @unlink(public_path($image->image_path));
            }
            \DB::table('agent_profile_images')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        return redirect()->back()->with('error', 'Image not found.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|same:confirm_new_password',
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return response()->json(['success' => false, 'message' => 'Please login first.']);
        }

        $agent = \DB::table('agents')->where('id', $agentId)->first();
        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent not found.']);
        }

        if (!\Hash::check($request->current_password, $agent->password)) {
            return response()->json(['success' => false, 'message' => 'Current password does not match.']);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Store OTP and new password in session
        session([
            'password_update_otp' => $otp,
            'password_update_new' => \Hash::make($request->new_password),
            'password_update_expires' => now()->addMinutes(5)
        ]);

        // Send OTP email
        \Illuminate\Support\Facades\Mail::send('emails.update-password-otp', ['otp' => $otp], function($message) use ($agent) {
            $message->to($agent->email);
            $message->subject('Your Password Update OTP Code');
        });

        return response()->json([
            'success' => true, 
            'message' => 'An OTP has been sent to your email. Please enter it to verify.'
        ]);
    }

    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return response()->json(['success' => false, 'message' => 'Please login first.']);
        }

        $sessionOtp = session('password_update_otp');
        $sessionExpires = session('password_update_expires');
        $sessionNewPass = session('password_update_new');

        if (!$sessionOtp || !$sessionExpires || !$sessionNewPass) {
            return response()->json(['success' => false, 'message' => 'OTP session not found. Please try updating your password again.']);
        }

        if (now()->greaterThan($sessionExpires)) {
            // Expired
            session()->forget(['password_update_otp', 'password_update_expires', 'password_update_new']);
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please try updating your password again.']);
        }

        if ((string)$request->otp !== (string)$sessionOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check the code and try again.']);
        }

        $agent = \DB::table('agents')->where('id', $agentId)->first();

        // Valid OTP! Update the password
        \DB::table('agents')->where('id', $agentId)->update([
            'password' => $sessionNewPass,
            'updated_at' => now()
        ]);

        // Clear session data
        session()->forget(['password_update_otp', 'password_update_expires', 'password_update_new']);

        // Send Success Email
        \Illuminate\Support\Facades\Mail::send('emails.update-password-success', [], function($message) use ($agent) {
            $message->to($agent->email);
            $message->subject('Password Updated Successfully');
        });

        return response()->json(['success' => true, 'message' => 'Password updated successfully!']);
    }

    public function upgradePlan(Request $request)
    {
        return redirect()->route('checkout', ['type' => 'plan', 'id' => $request->plan_id]);
    }

    public function checkout(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $amount = 0;
        $gst = 18.00;
        $itemName = '';

        if ($type == 'plan') {
            $plan = DB::table('plans')->where('id', $id)->first();
            if ($plan) {
                $amount = $plan->price;
                $gst = $plan->gst ?? 18.00;
                $itemName = $plan->name . ' Plan';
            }
        } elseif ($type == 'ad') {
            $names = $request->query('name');
            if (is_array($names)) {
                $amount = 0;
                $addonPricings = \App\Models\AddonPricing::where('type', 'ad')->pluck('price', 'name')->toArray();
                foreach ($names as $name) {
                    if (isset($addonPricings[$name])) {
                        $amount += $addonPricings[$name];
                    }
                }
                $itemName = 'AD Placements: ' . implode(', ', $names);
            } else {
                // Check if it's a trusted agent purchase
                $names = $request->query('name');
                if ($names) {
                    $taPricing = \App\Models\AddonPricing::where('type', 'trusted_agent')->where('name', $names)->first();
                    if ($taPricing) {
                        $amount = $taPricing->price;
                    } else {
                        $amount = $request->query('amount') ?? 0;
                    }
                } else {
                    $amount = $request->query('amount') ?? 0;
                }
                $itemName = $request->query('name') ?? 'AD Placement';
            }
        } elseif ($type == 'boost') {
            $boostId = $request->query('boost_id'); // We need to fetch by boost ID now or fall back
            if ($boostId) {
                $boostPricing = \App\Models\AddonPricing::where('type', 'boost')->where('id', $boostId)->first();
                $pricePerDay = $boostPricing ? $boostPricing->price : 12.50;
            } else {
                $pricePerDay = \App\Models\AddonPricing::where('type', 'boost')->value('price') ?? 12.50;
            }
            $days = $request->query('days', 1);
            $pkgId = $request->query('id') ?? $request->query('package_id');
            $amount = $pricePerDay * $days;
            $pkgName = DB::table('packages')->where('id', $pkgId)->value('title') ?? DB::table('packages')->where('id', $pkgId)->value('name');
            $itemName = 'Boost Tour: ' . $pkgName . ' (' . $days . ' days)';
        }

        $totalAmount = $amount * (1 + ($gst / 100));

        // PayU Configuration
        $payuService = new \App\Services\PayUService();
        $payuKey = $payuService->getMerchantKey();
        
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        $firstname = !empty(trim($agent->name ?? '')) ? str_replace('|', '', trim($agent->name)) : 'Agent';
        $email = !empty(trim($agent->email ?? '')) ? str_replace('|', '', trim($agent->email)) : 'agent@tour raja.com';
        $phone = $agent->phone ?? '9999999999';
        
        $productinfo = $type . '-' . $id;

        if ($totalAmount == 0 && $type == 'plan') {
            DB::table('agents')->where('id', $agentId)->update([
                'plan_id' => $id,
                'plan_status' => 'Active',
                'updated_at' => now()
            ]);
            $freeTxnId = 'FREE-' . $txnid;
            DB::table('payments')->insert([
                'agent_id' => $agentId,
                'user_name' => $firstname,
                'email' => $email,
                'plan_type' => $itemName,
                'type' => 'plan_upgrade',
                'amount' => 0,
                'payment_id' => $freeTxnId,
                'date' => date('Y-m-d'),
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Send Payment Confirmation Email
            if (!empty($email)) {
                \App\Services\MailService::sendView(
                    $email,
                    'Payment Receipt: ' . $itemName . ' - Tour Raja',
                    'emails.payment-confirmation',
                    [
                        'name' => $firstname,
                        'itemName' => $itemName,
                        'paymentId' => $freeTxnId,
                        'invoiceNumber' => '',
                        'amount' => 0,
                        'date' => date('M d, Y')
                    ]
                );
            }

            return redirect()->route('agent.dashboard')->with('success', 'Free plan activated successfully!');
        }

        $udf2 = str_replace('|', '', $itemName);
        $payuAmount = number_format($totalAmount, 2, '.', '');
        
        $posted = [
            'key' => $payuKey,
            'txnid' => $txnid,
            'amount' => $payuAmount,
            'productinfo' => $productinfo,
            'firstname' => $firstname,
            'email' => $email,
            'udf1' => $agentId,
            'udf2' => $udf2,
            'udf3' => '',
            'udf4' => '',
            'udf5' => '',
            'udf6' => '',
            'udf7' => '',
            'udf8' => '',
            'udf9' => '',
            'udf10' => ''
        ];
        
        $hashData = $payuService->generatePaymentHash($posted);
        $hash = $hashData['hash'];
        $hashString = $hashData['hashString'];
        
        $surl = route('agent.payment.payu-success');
        $furl = route('agent.payment.payu-failure');
        $endpoint = $payuService->getBaseUrl();

        // Log the complete POST payload exactly as it will be submitted (excluding Merchant Salt)
        \Log::info('PayU Complete POST Payload (Before Submit):', [
            'endpoint' => $endpoint,
            'hash_string_used' => $hashString,
            'payload' => array_merge($posted, [
                'phone' => $phone,
                'surl' => $surl,
                'furl' => $furl,
                'hash' => $hash,
                'service_provider' => 'payu_paisa'
            ])
        ]);

        return view('agent.pages.checkout', [
            'page_title' => 'Checkout',
            'page_breadcrumb' => 'Pages / Checkout',
            'type' => $type,
            'id' => $id,
            'amount' => $amount,
            'gst' => $gst,
            'totalAmount' => $totalAmount,
            'itemName' => $itemName,
            'payuKey' => $payuKey,
            'txnid' => $txnid,
            'hash' => $hash,
            'productinfo' => $productinfo,
            'udf2' => $udf2,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $phone,
            'surl' => $surl,
            'furl' => $furl,
            'hash' => $hash,
            'udf1' => $posted['udf1'],
            'udf2' => $posted['udf2'],
            'udf3' => $posted['udf3'],
            'udf4' => $posted['udf4'],
            'udf5' => $posted['udf5'],
            'udf6' => $posted['udf6'],
            'udf7' => $posted['udf7'],
            'udf8' => $posted['udf8'],
            'udf9' => $posted['udf9'],
            'udf10' => $posted['udf10'],
        ]);
    }

    public function payuSuccess(Request $request)
    {
        $payuService = new \App\Services\PayUService();
        
        if (!$payuService->verifyResponseHash($request->all())) {
            return redirect()->route('agent.payment')->with('error', 'Invalid Transaction Hash. Please try again');
        }

        $status = $request->input('status');
        $txnid = $request->input('txnid');
        $productinfo = $request->input('productinfo');
        $email = $request->input('email');
        $firstname = $request->input('firstname');
        $amount = $request->input('amount');
        $udf2 = $request->input('udf2');
        $mihpayid = $request->input('mihpayid');
        
        if ($status !== 'success') {
            return redirect()->route('agent.payment')->with('error', 'Payment was not successful. Status: ' . $status);
        }

        $parts = explode('-', $productinfo);
        $type = $parts[0] ?? '';
        $id = $parts[1] ?? '';
        $itemName = $udf2 ?? 'Payment';
        
        $agentId = session('agent_id');
        if (!$agentId) {
            $returnedAgentId = $request->input('udf1');
            $agent = null;
            if ($returnedAgentId) {
                $agent = DB::table('agents')->where('id', $returnedAgentId)->first();
            }
            if (!$agent && $email) {
                $agent = DB::table('agents')->where('email', $email)->first();
            }
            if ($agent) {
                $agentId = $agent->id;
                session(['agent_id' => $agentId, 'agent_name' => $agent->name, 'agent_email' => $agent->email]);
            }
        } else {
            $agent = DB::table('agents')->where('id', $agentId)->first();
        }

        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please login.');
        }

        $existing = DB::table('payments')->where('payment_id', $txnid)->first();
        if ($existing) {
            return redirect()->route('agent.payment')->with('success', 'Payment was already processed!');
        }

        if ($type == 'plan') {
            $planRecordForDuration = DB::table('plans')->where('id', $id)->first();
            $durationDays = $planRecordForDuration ? intval($planRecordForDuration->duration) : 0;
            $plan_expires_at = $durationDays > 0 ? now()->addDays($durationDays) : null;

            DB::table('agents')->where('id', $agentId)->update([
                'plan_id' => $id,
                'plan_status' => 'active',
                'plan_expires_at' => $plan_expires_at,
                'updated_at' => now()
            ]);
        } elseif ($type == 'boost') {
            DB::table('packages')->where('id', $id)->update([
                'is_boosted' => 1,
                'boost_expires_at' => now()->addDays(7),
                'updated_at' => now()
            ]);
        } elseif ($type == 'ad') {
            if ($id == 'blue_tick' || strpos($itemName, 'Trusted Agent') !== false) {
                DB::table('agents')->where('id', $agentId)->update([
                    'service_guaranteed' => 1,
                    'service_guaranteed_expires_at' => now()->addYear(),
                    'updated_at' => now()
                ]);
            } else {
                if ($id) {
                    DB::table('packages')->where('id', $id)->update([
                        'ad_placement' => $itemName,
                        'updated_at' => now()
                    ]);
                }
            }
        }

        $invoiceSettings = DB::table('settings')->pluck('value', 'key')->toArray();
        $invPrefix   = rtrim($invoiceSettings['invoice_prefix'] ?? 'INV', '-') . '-';
        $invYear     = date('Y');
        $invCount    = DB::table('payments')->whereYear('created_at', $invYear)->count();
        $invSequence = str_pad($invCount + 1, 2, '0', STR_PAD_LEFT);
        $invoiceNumber = $invPrefix . $invYear . '-' . $invSequence;

        $invoiceData = json_encode([
            'gateway' => 'PayU',
            'sender' => $firstname,
            'receiver' => 'Tour Raja',
            'item' => $itemName,
            'mihpayid' => $mihpayid
        ]);

        $paymentId = DB::table('payments')->insertGetId([
            'agent_id'       => $agentId,
            'user_name'      => $firstname,
            'email'          => $email,
            'plan_type'      => $itemName,
            'amount'         => $amount,
            'status'         => 'Success',
            'type'           => $type,
            'invoice_number' => $invoiceNumber,
            'payment_id'     => $txnid,
            'date'           => now()->toDateString(),
            'created_at'     => now(),
            'updated_at'     => now(),
            'invoice_data'   => $invoiceData
        ]);

        // Send Payment Receipt Email to Agent
        if (!empty($email)) {
            \App\Services\MailService::sendView(
                $email,
                'Payment Receipt: ' . $itemName . ' - Tour Raja',
                'emails.payment-confirmation',
                [
                    'name' => $firstname,
                    'itemName' => $itemName,
                    'paymentId' => $txnid,
                    'invoiceNumber' => $invoiceNumber,
                    'amount' => (float)$amount,
                    'date' => now()->format('M d, Y')
                ]
            );
        }

        return redirect()->route('checkout.success')->with('payment_id', $paymentId);
    }

    public function checkoutSuccess()
    {
        $paymentId = session('payment_id');
        if (!$paymentId) {
            return redirect()->route('agent.payment');
        }

        $payment = DB::table('payments')->where('id', $paymentId)->first();
        if (!$payment) {
            return redirect()->route('agent.payment');
        }

        return view('agent.pages.checkout-success', compact('payment'));
    }

    public function payuFailure(Request $request)
    {
        $returnedAgentId = $request->input('udf1');
        $email = $request->input('email');
        $agentId = session('agent_id');
        
        if (!$agentId) {
            $agent = null;
            if ($returnedAgentId) {
                $agent = DB::table('agents')->where('id', $returnedAgentId)->first();
            }
            if (!$agent && $email) {
                $agent = DB::table('agents')->where('email', $email)->first();
            }
            if ($agent) {
                session(['agent_id' => $agent->id, 'agent_name' => $agent->name, 'agent_email' => $agent->email]);
            }
        }

        $payuService = new \App\Services\PayUService();
        
        if (!$payuService->verifyResponseHash($request->all())) {
            return redirect()->route('agent.payment')->with('error', 'Invalid Transaction Hash. Please try again');
        }
        
        $txnid = $request->input('txnid');
        $amount = $request->input('amount');
        
        \Log::warning('PayU Payment Failed. TxnID: ' . $txnid . ' Amount: ' . $amount);
        return redirect()->route('agent.payment')->with('error', 'Payment failed or was cancelled. Please try again.');
    }
}
