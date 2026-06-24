<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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
            'plan_id'     => 1, // Default Basic plan
            'plan_status' => 'Active',
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        // Add agency_name if column exists
        if (Schema::hasColumn('agents', 'agency_name')) {
            $data['agency_name'] = $request->agency_name;
        }

        $id = DB::table('agents')->insertGetId($data);

        return redirect()->route('agent.login')->with('success', 'Agent account created successfully! Please login to continue.');
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
        
        $packagesCount = 0;
        if ($agent) {
            $allPackages = DB::table('packages')->select('agent')->get();
            $packagesCount = $allPackages->filter(function ($pkg) use ($agentId, $agent) {
                if (!$pkg->agent) return false;
                $agentData = json_decode($pkg->agent, true);
                if (!$agentData) return false;
                return (isset($agentData['id']) && $agentData['id'] == $agentId)
                    || (isset($agentData['name']) && $agentData['name'] === $agent->name);
            })->count();
        }

        $isNew = ($packagesCount === 0);

        return view('agent.pages.dashboard', [
            'page_title' => 'Dashboard',
            'page_breadcrumb' => 'Pages / Dashboard',
            'agent' => $agent,
            'isNew' => $isNew,
            'totalPackages' => $isNew ? 0 : 200,
            'activePackages' => $isNew ? 0 : 20,
            'pendingPackages' => $isNew ? 0 : 2,
            'expiredPackages' => $isNew ? 0 : 0,
            'totalLeads' => $isNew ? 0 : 682,
            'profilePackages' => $isNew ? 0 : 28,
            'profileLeads' => $isNew ? 0 : 643,
            'profileReviews' => $isNew ? 0 : 76,
            'recentLeads' => $isNew ? [] : [
                ['name' => 'Chakra Soft UI Version', 'icon' => 'fab fa-xbox text-purple-500', 'budget' => '$14,000', 'prog' => '60%', 'color' => 'bg-primary'],
                ['name' => 'Add Progress Track', 'icon' => 'fas fa-chart-line text-blue-500', 'budget' => '$3,000', 'prog' => '10%', 'color' => 'bg-blue-500'],
                ['name' => 'Fix Platform Errors', 'icon' => 'fas fa-exclamation-triangle text-red-500', 'budget' => 'Not set', 'prog' => '100%', 'color' => 'bg-green-500'],
                ['name' => 'Launch our Mobile App', 'icon' => 'fab fa-spotify text-green-500', 'budget' => '$32,000', 'prog' => '100%', 'color' => 'bg-green-500'],
            ],
            'chartData' => $isNew ? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] : [40, 70, 55, 65, 50, 90, 60, 80, 45, 75, 55, 65]
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
        $branch = DB::table('branches')->where('id', $id)->where('agent_id', $agentId)->first();
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
            'address' => 'required|string',
            'status' => 'required|string|in:Online,Offline'
        ]);

        DB::table('branches')->insert([
            'agent_id' => $agentId,
            'agency_name' => $request->agency_name,
            'phone' => $request->phone,
            'location' => $request->location,
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
            'address' => 'required|string',
            'status' => 'required|string|in:Online,Offline'
        ]);

        DB::table('branches')->where('id', $id)->where('agent_id', $agentId)->update([
            'agency_name' => $request->agency_name,
            'phone' => $request->phone,
            'location' => $request->location,
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

        DB::table('branches')->where('id', $id)->where('agent_id', $agentId)->delete();
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

        if (DB::table('branches')->where('agent_id', $agentId)->count() == 0) {
            DB::table('branches')->insert([
                [
                    'agent_id' => $agentId,
                    'agency_name' => 'Miths Holidays',
                    'phone' => '+91 7383682183',
                    'location' => 'Rajkot, Gujarat',
                    'address' => '101 GF Nr Trikon Bagh Rajkot - Gujarat',
                    'status' => 'Online',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        $branches = DB::table('branches')->where('agent_id', $agentId)->orderBy('created_at', 'desc')->get();

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
            $plan = DB::table('plans')->where('id', $agent->plan_id ?? 1)->first();
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
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages'), $fileName);
                $imageUrl = '/uploads/packages/' . $fileName;
            }
        }

        // Gallery Images Upload
        $galleryUrls = json_decode($pkg->gallery, true) ?: [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/packages/gallery'), $fileName);
                    $galleryUrls[] = '/uploads/packages/gallery/' . $fileName;
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
                $brochureUrl = '/uploads/packages/brochures/' . $fileName;
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
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
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
            $plan = DB::table('plans')->where('id', $agent->plan_id ?? 1)->first();
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
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages'), $fileName);
                $imageUrl = '/uploads/packages/' . $fileName;
            }
        }

        // Gallery Images Upload
        $galleryUrls = [];
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
                $brochureUrl = '/uploads/packages/brochures/' . $fileName;
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
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
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
        return view('agent.pages.feedback', [
            'page_title' => 'Feedback',
            'page_breadcrumb' => 'Pages / Feedback'
        ]);
    }

    public function gallery()
    {
        return view('agent.pages.gallery', [
            'page_title' => 'Gallery',
            'page_breadcrumb' => 'Pages / Gallery'
        ]);
    }

    public function hotels()
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');

        $hotels = DB::table('hotels')->orderBy('id', 'desc')->get();
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        
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
            'agents'          => $agents,
            'hotelCategories' => $hotelCategories,
            'packages'        => $packages,
        ]);
    }
    
    public function storeHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required', 'location' => 'required']);
        
        $hotelId = DB::table('hotels')->insertGetId([
            'name' => $request->name,
            'category' => $request->category ?? 'Luxury Resort',
            'location' => $request->location,
            'rating' => 5,
            'status' => $request->status ?? 'Published',
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
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('hotels')->where('id', $request->id)->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'status' => $request->status ?? 'Published',
            'updated_at' => now(),
        ]);

        if ($request->filled('package_id')) {
            $this->addHotelToPackage($request->package_id, $request->name, $request->category);
        }

        return redirect()->back()->with('success', 'Hotel updated successfully!');
    }

    public function deleteHotel($id)
    {
        DB::table('hotels')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Hotel deleted successfully!');
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
                $q->where('agent_id', $agentId);
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
                $q->where('agent_id', $agentId);
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
                $q->where('agent_id', $agentId)
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
        return view('agent.pages.notifications', [
            'page_title' => 'Notifications',
            'page_breadcrumb' => 'Pages / Notifications'
        ]);
    }

    public function payment()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        $activePlan = null;
        if ($agent && $agent->plan_id) {
            $activePlan = DB::table('plans')->where('id', $agent->plan_id)->first();
        } else {
            $activePlan = DB::table('plans')->where('id', 1)->first(); // Fallback to Basic
        }

        $plans = DB::table('plans')->where('status', 'Active')->orderBy('price', 'asc')->get();
        $payments = DB::table('payments')
            ->where(function($q) use ($agentId, $agent) {
                $q->where('agent_id', $agentId);
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
        return view('agent.pages.services', [
            'page_title' => 'Services',
            'page_breadcrumb' => 'Pages / Services'
        ]);
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
            'landline' => $request->input('landline'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'pincode' => $request->input('pincode'),
            'updated_at' => now()
        ];

        // Create uploads directory if not exists
        if (!file_exists(public_path('uploads/agents'))) {
            mkdir(public_path('uploads/agents'), 0775, true);
        }

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/agents'), $fileName);
                $data['logo'] = '/uploads/agents/' . $fileName;
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

    public function upgradePlan(Request $request)
    {
        return redirect()->route('checkout', ['type' => 'plan', 'id' => $request->plan_id]);
    }

    public function checkout(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $amount = 0;
        $itemName = '';

        if ($type == 'plan') {
            $plan = DB::table('plans')->where('id', $id)->first();
            if ($plan) {
                $amount = $plan->price;
                $itemName = $plan->name . ' Plan';
            }
        } elseif ($type == 'ad') {
            $amount = $request->query('amount', 499);
            $itemName = $request->query('name', 'Ad Subscription');
        } elseif ($type == 'boost') {
            $amount = 12.50; // Daily boost rate mock
            $itemName = 'Boost Tour Package';
        }

        return view('agent.pages.checkout', [
            'page_title' => 'Checkout',
            'page_breadcrumb' => 'Pages / Checkout',
            'type' => $type,
            'id' => $id,
            'amount' => $amount,
            'itemName' => $itemName
        ]);
    }

    public function processCheckout(Request $request)
    {
        $agentId = session('agent_id');
        $type = $request->input('type');
        $id = $request->input('id');
        $amount = $request->input('amount');
        $itemName = $request->input('item_name');
        $gateway = $request->input('gateway', 'UPI');
        $sender = $request->input('sender_details', 'agent@upi');
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
