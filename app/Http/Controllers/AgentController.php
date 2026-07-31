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
            'email'    => 'required|email',
            'password' => 'required|min:6',
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
            'plan_id'     => DB::table('plans')->where('price', 0)->where('status', 'Active')->value('id') ?? 5, // Dynamic free plan
            'plan_status' => 'Active',
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



        $branches = DB::table('branches')->orderBy('created_at', 'desc')->get();

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
        return view('agent.pages.create-package', [
            'page_title' => 'Create Package',
            'page_breadcrumb' => 'Pages / Create Package',
            'agents' => $agents,
            'hotels' => $hotels,
            'themes' => $themes,
            'holidayTypes' => $holidayTypes
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
        return view('agent.pages.edit-package', [
            'page_title' => 'Edit Package',
            'page_breadcrumb' => 'Pages / Edit Package',
            'pkg' => $pkg,
            'agents' => $agents,
            'hotels' => $hotels,
            'themes' => $themes,
            'holidayTypes' => $holidayTypes
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
        $galleryUrls = json_decode($pkg->gallery, true) ?: [];
        if ($request->has('gallery_urls') && is_array($request->gallery_urls)) {
            $galleryUrls = array_merge($galleryUrls, $request->gallery_urls);
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

        DB::table('packages')->where('id', $request->id)->update([
            'title'      => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'location'   => $request->location ?? 'Global',
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
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
                return redirect()->route('agent.payment')->with('error', 'You have reached your package limit. Please upgrade your plan to add more packages.');
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

        DB::table('packages')->insert([
            'title'      => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'location'   => $request->location ?? 'Global',
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'rating'     => 4.8,
            'reviews'    => 0,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
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
            'rating' => 'required|integer|min:1|max:5'
        ]);

        AgentFeedback::create([
            'agent_id' => session('agent_id'),
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'message' => $request->message
        ]);

        return redirect()->back()->with('success', 'Feedback added successfully.');
    }

    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required',
            'message' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $feedback = AgentFeedback::where('agent_id', session('agent_id'))->findOrFail($id);
        $feedback->update([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'message' => $request->message
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

        return view('agent.pages.hotels', [
            'page_title'      => 'Hotels',
            'page_breadcrumb' => 'Pages / Hotels',
            'hotels'          => $hotels,
            'hotelCategories' => $hotelCategories,
            'packages'        => $packages,
        ]);
    }
    
    public function storeHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required', 'location' => 'required', 'category' => 'required']);

        $agentId = session('agent_id');

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
                $q;
                if ($agent) {
                    $q->orWhere('email', $agent->email);
                }
            })->first();
            
        if (!$payment) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }
        
        $agent = DB::table('agents')->where('id', $agentId)->first();

        return view('agent.pages.print-invoice', [
            'payment' => $payment,
            'agent' => $agent
        ]);
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
        $allPackages = DB::table('packages')->select('id', 'title', 'location', 'image', 'price', 'agent', 'status', 'created_at', 'category', 'group_size', 'duration', 'stock', 'currency', 'badge')->orderBy('created_at', 'desc')->get();
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

        $plans = DB::table('plans')->where('status', 'Active')->orderBy('price', 'asc')->get();
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

        return view('agent.pages.payment', [
            'page_title' => 'Payment & Billing',
            'page_breadcrumb' => 'Pages / Payment',
            'agent' => $agent,
            'activePlan' => $activePlan,
            'plans' => $plans,
            'payments' => $payments,
            'agentPackages' => $agentPackages
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
            'website' => $request->input('website'),
            'gst_number' => $request->input('gst_number'),
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

        DB::table('agents')->where('id', $agentId)->update($data);

        // Update session name if changed
        session([
            'agent_name' => $request->input('name'),
            'agent_agency_name' => $request->input('name')
        ]);

        return redirect()->back()->with('success', 'Profile settings updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|same:confirm_new_password',
        ]);

        $agentId = session('agent_id');
        if (!$agentId) {
            return redirect()->route('agent.login')->with('error', 'Please login first.');
        }

        $agent = \DB::table('agents')->where('id', $agentId)->first();
        if (!$agent) {
            return redirect()->back()->with('error', 'Agent not found.');
        }

        if (!\Hash::check($request->current_password, $agent->password)) {
            return redirect()->back()->with('error', 'Current password does not match.');
        }

        \DB::table('agents')->where('id', $agentId)->update([
            'password' => \Hash::make($request->new_password),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
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
                $prices = [
                    'Home Hero Banner' => 999,
                    'Package Sidebar' => 499,
                    'Footer Banner' => 399,
                    'Under Domestic Packages' => 599,
                ];
                foreach ($names as $n) {
                    $amount += $prices[$n] ?? 499;
                }
                $itemName = implode(', ', $names);
            } else {
                $amount = $request->query('amount', 499);
                $itemName = $names ?? 'Ad Subscription';
            }
        } elseif ($type == 'boost') {
            $amount = 12.50; // Daily boost rate mock
            $itemName = 'Boost Tour Package';
        }

        $totalAmount = $amount * (1 + ($gst / 100));

        // Razorpay Order Generation
        $razorpayOrderId = null;
        $razorpayError = null;
        if ($totalAmount > 0) {
            try {
                if (!config('services.razorpay.key') || !config('services.razorpay.secret')) {
                    throw new \Exception('Razorpay keys are missing from configuration. Did you clear the config cache?');
                }
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $orderData = [
                    'receipt'         => 'rcptid_' . time(),
                    'amount'          => round($totalAmount * 100), // Amount in paise
                    'currency'        => 'INR'
                ];
                $razorpayOrder = $api->order->create($orderData);
                $razorpayOrderId = $razorpayOrder['id'];
            } catch (\Throwable $e) {
                \Log::error('Razorpay Error: ' . $e->getMessage());
                $razorpayError = $e->getMessage();
            }
        }

        return view('agent.pages.checkout', [
            'page_title' => 'Checkout',
            'page_breadcrumb' => 'Pages / Checkout',
            'type' => $type,
            'id' => $id,
            'amount' => $amount,
            'gst' => $gst,
            'totalAmount' => $totalAmount,
            'itemName' => $itemName,
            'razorpayOrderId' => $razorpayOrderId,
            'razorpayError' => $razorpayError
        ]);
    }

    public function processCheckout(Request $request)
    {
        $agentId = session('agent_id');
        $type = $request->input('type');
        $id = $request->input('id');
        $amount = $request->input('amount');
        $itemName = $request->input('item_name');

        // Razorpay Verification
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if ($razorpayPaymentId && $razorpayOrderId && $razorpaySignature) {
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $api->utility->verifyPaymentSignature(array(
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_signature' => $razorpaySignature
                ));
            } catch(\Exception $e) {
                return redirect()->route('agent.payment')->with('error', 'Payment verification failed: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('agent.payment')->with('error', 'Invalid payment details. Please try again.');
        }

        $gateway = 'Razorpay';
        $sender = $request->input('sender_details', 'Razorpay Checkout');
        $receiver = 'tourraja@upi';

        $agent = DB::table('agents')->where('id', $agentId)->first();
        $invoiceData = json_encode([
            'gateway' => $gateway,
            'sender' => $sender,
            'receiver' => $receiver,
            'item' => $itemName
        ]);

        if ($type == 'plan') {
            DB::table('agents')->where('id', $agentId)->update([
                'plan_id' => $id,
                'plan_status' => 'active',
                'updated_at' => now()
            ]);
        } elseif ($type == 'boost') {
            DB::table('packages')->where('id', $id)->update([
                'is_boosted' => 1,
                'boost_expires_at' => now()->addDays(7),
                'updated_at' => now()
            ]);
        } elseif ($type == 'ad') {
            if ($id == 'blue_tick') {
                DB::table('agents')->where('id', $agentId)->update([
                    'service_guaranteed' => 1,
                    'service_guaranteed_expires_at' => now()->addYear(),
                    'updated_at' => now()
                ]);
            } else {
                $packageId = $request->input('package_id');
                if ($packageId) {
                    DB::table('packages')->where('id', $packageId)->update([
                        'ad_placement' => $itemName,
                        'updated_at' => now()
                    ]);
                }
            }
        }

        DB::table('payments')->insert([
            'agent_id'       => $agentId,
            'user_name'      => $agent->name ?? 'Agent',
            'email'          => $agent->email ?? '',
            'plan_type'      => $itemName,
            'amount'         => $amount,
            'status'         => 'Success',
            'type'           => $type,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'payment_id'     => 'PAY-' . strtoupper(uniqid()),
            'date'           => now()->toDateString(),
            'created_at'     => now(),
            'updated_at'     => now(),
            'invoice_data'   => $invoiceData
        ]);

        return redirect()->route('agent.payment')->with('success', 'Payment successful for ' . $itemName . '!');
    }
}
