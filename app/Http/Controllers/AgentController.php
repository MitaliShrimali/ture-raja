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
        session(['agent_id' => $agent->id, 'agent_name' => $agent->name, 'agent_email' => $agent->email]);

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
        session()->forget(['agent_id', 'agent_name', 'agent_email']);
        return redirect()->route('agent.login')->with('success', 'You have been logged out.');
    }

    public function dashboard()
    {
        $agentId = session('agent_id');
        $agent = DB::table('agents')->where('id', $agentId)->first();
        
        $packagesCount = 0;
        if ($agent) {
            $allPackages = DB::table('packages')->get();
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
            'page_breadcrumb' => 'Pages / Add Branch'
        ]);
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
        return view('agent.pages.branch', [
            'page_title' => 'Branches',
            'page_breadcrumb' => 'Pages / Branches'
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
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        $hotels = DB::table('hotels')->orderBy('name', 'asc')->get();
        return view('agent.pages.create-package', [
            'page_title' => 'Create Package',
            'page_breadcrumb' => 'Pages / Create Package',
            'agents' => $agents,
            'hotels' => $hotels
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
        return view('agent.pages.edit-package', [
            'page_title' => 'Edit Package',
            'page_breadcrumb' => 'Pages / Edit Package',
            'pkg' => $pkg,
            'agents' => $agents,
            'hotels' => $hotels
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

        // Itinerary Days parsing
        $itinerary = [];
        if ($request->has('itinerary_titles')) {
            foreach ($request->itinerary_titles as $i => $dayTitle) {
                $dayDesc = $request->itinerary_descriptions[$i] ?? '';
                if (!empty($dayTitle)) {
                    $itinerary[] = ['title' => $dayTitle, 'desc' => $dayDesc];
                }
            }
        }

        DB::table('packages')->where('id', $request->id)->update([
            'title'      => $request->title,
            'location'   => $request->location ?? 'Global',
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
            'image'      => $imageUrl,
            'category'   => $request->category ?? 'domestic',
            'badge'      => $request->badge,
            'stock'      => $request->stock ? $request->stock . ' Left' : '10 Left',
            'gallery'    => json_encode($galleryUrls),
            'brochure'   => $brochureUrl,
            'included'   => json_encode($included),
            'excluded'   => json_encode($excluded),
            'itinerary'           => json_encode($itinerary),
            'editorial_itinerary' => $request->editorial_itinerary ?? null,
            'updated_at'          => now(),
        ]);

        return redirect()->route('agent.my-packages')->with('success', 'Package updated successfully!');
    }

    public function storePackage(Request $request)
    {
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

        // Itinerary Days parsing
        $itinerary = [];
        if ($request->has('itinerary_titles')) {
            foreach ($request->itinerary_titles as $i => $dayTitle) {
                $dayDesc = $request->itinerary_descriptions[$i] ?? '';
                if (!empty($dayTitle)) {
                    $itinerary[] = ['title' => $dayTitle, 'desc' => $dayDesc];
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
                'logo'     => $agentDb->logo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentDb->name),
                'name'     => $agentDb->name,
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
            'location'   => $request->location ?? 'Global',
            'price'      => $request->price,
            'old_price'  => $request->old_price ?: null,
            'rating'     => 4.8,
            'reviews'    => 0,
            'duration'   => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? 'Direct Flight',
            'image'      => $imageUrl ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'category'   => $request->category ?? 'domestic',
            'badge'      => $request->badge,
            'status'     => 'Pending',
            'stock'      => $request->stock ? $request->stock . ' Left' : '10 Left',
            'agent'      => $agentJson,
            'gallery'    => json_encode($galleryUrls),
            'brochure'   => $brochureUrl,
            'included'   => json_encode($included),
            'excluded'   => json_encode($excluded),
            'itinerary'           => json_encode($itinerary),
            'editorial_itinerary' => $request->editorial_itinerary ?? null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect()->route('agent.my-packages')->with('success', 'Package submitted successfully! It will be reviewed by admin before going live.');
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
        $hotels = DB::table('hotels')->orderBy('name', 'asc')->get();
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        return view('agent.pages.hotels', [
            'page_title'      => 'Hotels',
            'page_breadcrumb' => 'Pages / Hotels',
            'hotels'          => $hotels,
            'agents'          => $agents,
        ]);
    }

    public function invoice()
    {
        return view('agent.pages.invoice', [
            'page_title' => 'Invoice',
            'page_breadcrumb' => 'Pages / Invoice'
        ]);
    }

    public function leads()
    {
        if (DB::table('contacts')->count() == 0) {
            DB::table('contacts')->insert([
                [
                    'name' => 'John Doe',
                    'email' => 'john@gmail.com',
                    'phone' => '+91 98765 43210',
                    'subject' => 'Rajkot, Gujarat',
                    'message' => 'I would like to inquire about holiday packages.',
                    'status' => 'Convert',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Sarah Smith',
                    'email' => 'sarah@gmail.com',
                    'phone' => '+91 88888 88888',
                    'subject' => 'Morbi, Rajkot',
                    'message' => 'Looking for family trips.',
                    'status' => 'No use',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Michael Brown',
                    'email' => 'michael@gmail.com',
                    'phone' => '+91 77777 77777',
                    'subject' => 'Ahmedabad, Gujarat',
                    'message' => 'Need pricing for Honeymoon package.',
                    'status' => 'Pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Emma Wilson',
                    'email' => 'emma@gmail.com',
                    'phone' => '+91 66666 66666',
                    'subject' => 'Surat, Gujarat',
                    'message' => 'Are there any discounts available?',
                    'status' => 'Working',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        $leads = DB::table('contacts')->orderBy('created_at', 'desc')->get();

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

        DB::table('contacts')->where('id', $id)->update([
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
        DB::table('contacts')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function myPackages()
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');

        // Fetch packages belonging to this agent (by matching agent JSON name)
        $allPackages = DB::table('packages')->orderBy('created_at', 'desc')->get();
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
        return view('agent.pages.payment', [
            'page_title' => 'Payment',
            'page_breadcrumb' => 'Pages / Payment'
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
        session(['agent_name' => $request->input('name')]);

        return redirect()->back()->with('success', 'Profile settings updated successfully!');
    }
}
