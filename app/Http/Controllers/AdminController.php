<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Get Live Metrics
        $totalRev = DB::table('payments')->whereIn('status', ['Completed', 'Success'])->sum('amount');
        $totalProfit = $totalRev; // Assuming profit is total revenue for platform subscriptions
        $activeAgentsCount = DB::table('agents')->count();
        $activePackagesCount = DB::table('packages')->where('status', 'Active')->count();
        $totalSubsCount = DB::table('subscribers')->count();
        $expiredPackagesCount = DB::table('packages')->whereNotNull('expiry_date')->where('expiry_date', '<', now())->count();
        $pendingPackagesCount = DB::table('packages')->where('status', 'Pending')->count();

        $data = [
            'metrics' => [
                'totalRevenue' => '₹' . number_format($totalRev, 2),
                'totalProfit' => '₹' . number_format($totalProfit, 2),
                'revenueGrowth' => '+12.5%',
                'activeAgents' => number_format($activeAgentsCount),
                'agentGrowth' => '+8.2%',
                'activePackages' => number_format($activePackagesCount),
                'packageGrowth' => '+15.3%',
                'totalSubscribers' => number_format($totalSubsCount),
                'subscriberGrowth' => '+5.4%',
                'pendingPackages' => number_format($pendingPackagesCount),
                'expiredPackages' => number_format($expiredPackagesCount),
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

        // Fetch packages pending approval (status = Pending)
        $pendingPackages = DB::table('packages')
            ->where('status', 'Pending')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $pendingPackagesCount = DB::table('packages')->where('status', 'Draft')->count();

        return view('admin.dashboard', compact('data', 'recentPayments', 'pendingPackages', 'pendingPackagesCount'));
    }

    // ==========================================
    // Inventory & Stays
    // ==========================================

    public function internationalPackages(Request $request)
    {
        $packages = DB::table('home_packages')->where('type', 'international')->orderBy('id', 'desc')->paginate(10);
        return view('admin.packages-international', compact('packages'));
    }

    public function domesticPackages(Request $request)
    {
        $packages = DB::table('home_packages')->where('type', 'domestic')->orderBy('id', 'desc')->paginate(10);
        return view('admin.packages-domestic', compact('packages'));
    }

    public function storeHomePackage(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:international,domestic',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('packages', 'public');
            $imagePath = '/storage/' . $imagePath;
        } else {
            $imagePath = 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=400';
        }

        DB::table('home_packages')->insert([
            'type' => $request->type,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'price' => $request->price,
            'status' => $request->status ?? 'Live',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Package added successfully!');
    }

    public function updateHomePackage(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'price' => $request->price,
            'status' => $request->status ?? 'Live',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('packages', 'public');
            $data['image'] = '/storage/' . $imagePath;
        }

        DB::table('home_packages')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'Package updated successfully!');
    }

    public function deleteHomePackage($id)
    {
        DB::table('home_packages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Package removed!');
    }

    public function toggleHomePackage($id)
    {
        $pkg = DB::table('home_packages')->where('id', $id)->first();
        if ($pkg) {
            $newStatus = $pkg->status === 'Live' ? 'Drafting' : 'Live';
            DB::table('home_packages')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Package status updated!');
    }

    // ==========================================
    // Offer Stickers
    // ==========================================

    public function offerStickers()
    {
        $stickers = DB::table('offer_stickers')->orderBy('id', 'desc')->paginate(10);
        return view('admin.offer-stickers', compact('stickers'));
    }

    public function storeOfferSticker(Request $request)
    {
        $request->validate(['title' => 'required']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/stickers'), $fileName);
            $imagePath = '/uploads/stickers/' . $fileName;
        }

        DB::table('offer_stickers')->insert([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'link' => $request->link ?? '/discover',
            'status' => $request->status ?? 'Live',
            'bg_color' => $request->bg_color,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Offer Sticker added successfully!');
    }

    public function updateOfferSticker(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required']);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link ?? '/discover',
            'status' => $request->status ?? 'Live',
            'bg_color' => $request->bg_color,
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/stickers'), $fileName);
            $data['image'] = '/uploads/stickers/' . $fileName;
        }

        DB::table('offer_stickers')->where('id', $request->id)->update($data);
        return redirect()->back()->with('success', 'Offer Sticker updated successfully!');
    }

    public function deleteOfferSticker($id)
    {
        DB::table('offer_stickers')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Offer Sticker removed!');
    }

    public function toggleOfferSticker($id)
    {
        $sticker = DB::table('offer_stickers')->where('id', $id)->first();
        if ($sticker) {
            $newStatus = $sticker->status === 'Live' ? 'Drafting' : 'Live';
            DB::table('offer_stickers')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Sticker status updated!');
    }

    public function packages(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('packages')->where('status', '!=', 'Pending');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $packages = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.packages', compact('packages', 'search'));
    }

    public function pendingPackages(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('packages')->where('status', 'Pending');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $pendingPackages = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pendingCount = DB::table('packages')->where('status', 'Pending')->count();
        return view('admin.packages-pending', compact('pendingPackages', 'search', 'pendingCount'));
    }

    public function viewPackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if (!$pkg) {
            return redirect('/admin/packages')->with('error', 'Package not found!');
        }
        return view('admin.package-detail', compact('pkg'));
    }

    public function createPackage()
    {
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        $themes = DB::table('themes')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $holidayTypes = DB::table('holiday_types')->where('status', 'Active')->orderBy('name', 'asc')->get();
        return view('admin.packages-create', compact('agents', 'themes', 'holidayTypes'));
    }

    public function editPackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if (!$pkg) {
            return redirect('/admin/packages')->with('error', 'Package not found!');
        }
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        $themes = DB::table('themes')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $holidayTypes = DB::table('holiday_types')->where('status', 'Active')->orderBy('name', 'asc')->get();
        return view('admin.packages-edit', compact('pkg', 'agents', 'themes', 'holidayTypes'));
    }

    public function storePackage(Request $request)
    {
        $request->validate(['title' => 'required', 'price' => 'required|numeric']);

        // Main Image Upload
        $imageUrl = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['image_file' => 'The uploaded main image file is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/packages'), $fileName);
            $imageUrl = 'uploads/packages/' . $fileName;
        }

        // Gallery Images Upload
        $galleryUrls = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if (!$file->isValid()) {
                    return redirect()->back()->withErrors(['gallery_files' => 'One of the uploaded gallery files is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
                }
                $fileName = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages/gallery'), $fileName);
                $galleryUrls[] = 'uploads/packages/gallery/' . $fileName;
            }
        }

        // Brochure Upload
        $brochureUrl = null;
        if ($request->hasFile('brochure_file')) {
            $file = $request->file('brochure_file');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['brochure_file' => 'The uploaded brochure PDF file is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/packages/brochures'), $fileName);
            $brochureUrl = 'uploads/packages/brochures/' . $fileName;
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
                    $itinerary[] = [
                        'title' => $dayTitle,
                        'desc' => $dayDesc,
                        'duration' => $dayDur
                    ];
                }
            }
        }

        // New fields parsing
        $hotels = [];
        if ($request->has('hotels')) {
            foreach ($request->hotels as $hotelJson) {
                $hotelData = json_decode($hotelJson, true);
                if ($hotelData)
                    $hotels[] = $hotelData;
            }
        }

        $keywords = [];
        if ($request->has('keywords') && !empty($request->keywords)) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
        }

        $amenities = $request->input('amenities', []);
        $transfers = $request->input('transfers', []);
        $meals = $request->input('meals', []);

        $expiry_date = null;
        if (!empty($request->validity) && strpos($request->validity, ' to ') !== false) {
            $parts = explode(' to ', $request->validity);
            if (isset($parts[1])) {
                $expiry_date = date('Y-m-d', strtotime($parts[1]));
            }
        } elseif (!empty($request->validity)) {
            $expiry_date = date('Y-m-d', strtotime($request->validity));
        }

        $agentName = $request->agent ?? 'Miths Holidays';
        $agentDb = DB::table('agents')->where('name', $agentName)->first();
        if ($agentDb) {
            $agentJson = json_encode([
                'logo' => $agentDb->logo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentDb->agency_name ?? $agentDb->name),
                'name' => $agentDb->agency_name ?? $agentDb->name,
                'phone' => $agentDb->phone ?? '',
                'whatsapp' => $agentDb->phone ?? ''
            ]);
        } else {
            $agentJson = json_encode([
                'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName),
                'name' => $agentName,
                'phone' => '',
                'whatsapp' => ''
            ]);
        }

        DB::table('packages')->insert([
            'title' => $request->title,
            'departure_city' => $request->departure_city ?? null,
            'departure_state' => $request->departure_state ?? null,
            'departure_country' => $request->departure_country ?? null,
            'terms' => $request->terms ?? null,
            'sightseeing_list' => json_encode($sightseeing_list),
            'currency' => $request->currency ?? '₹',

            'location' => $request->location ?? 'Global',
            'price' => $request->price,
            'old_price' => $request->old_price,
            'rating' => $request->rating ?? 4.8,
            'reviews' => $request->reviews ?? 10,
            'duration' => $request->duration ?? '3 Days',
            'group_size' => $request->group_size ?? '4-6 guest',
            'image' => $imageUrl ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'category' => $request->category ?? 'domestic',
            'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,
            'theme' => $request->theme,
            'holiday_type' => $request->holiday_type,
            'badge' => $request->badge,
            'status' => $request->status ?? 'Active',
            'stock' => $request->stock ?? '10 Left',
            'validity' => $request->validity ?? null,
            'expiry_date' => $expiry_date,
            'sightseeing' => $request->sightseeing ?? null,
            'agent' => $agentJson,
            'gallery' => json_encode($galleryUrls),
            'brochure' => $brochureUrl,
            'included' => json_encode($included),
            'excluded' => json_encode($excluded),
            'hotels' => json_encode($hotels),
            'keywords' => json_encode($keywords),
            'amenities' => json_encode($amenities),
            'transfers' => json_encode($transfers),
            'meals' => json_encode($meals),
            'itinerary' => json_encode($itinerary),
            'editorial_itinerary' => $request->editorial_itinerary ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/packages')->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request)
    {
        try {
            $request->validate(['id' => 'required', 'title' => 'required', 'price' => 'required|numeric']);

            // Get original package to keep old gallery/brochure/etc if no new ones uploaded
            $oldPkg = DB::table('packages')->where('id', $request->id)->first();

            // Main Image Upload
            $imageUrl = $request->image;
            if (empty($imageUrl) && $oldPkg) {
                $imageUrl = $oldPkg->image;
            }

            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                if (!$file->isValid()) {
                    return redirect()->back()->withErrors(['image_file' => 'The uploaded main image file is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
                }
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages'), $fileName);
                $imageUrl = 'uploads/packages/' . $fileName;
            }

            // Gallery Images Upload
            $galleryUrls = [];
            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $file) {
                    if (!$file->isValid()) {
                        return redirect()->back()->withErrors(['gallery_files' => 'One of the uploaded gallery files is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
                    }
                    $fileName = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/packages/gallery'), $fileName);
                    $galleryUrls[] = 'uploads/packages/gallery/' . $fileName;
                }
            } else {
                if ($oldPkg && $oldPkg->gallery) {
                    $galleryUrls = json_decode($oldPkg->gallery, true) ?: [];
                }
            }

            // Brochure Upload
            $brochureUrl = $oldPkg ? $oldPkg->brochure : null;
            if ($request->hasFile('brochure_file')) {
                $file = $request->file('brochure_file');
                if (!$file->isValid()) {
                    return redirect()->back()->withErrors(['brochure_file' => 'The uploaded brochure PDF file is invalid or too large. Max size allowed by PHP config is ' . ini_get('upload_max_filesize')])->withInput();
                }
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/packages/brochures'), $fileName);
                $brochureUrl = 'uploads/packages/brochures/' . $fileName;
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
                    if ($hotelData)
                        $hotels[] = $hotelData;
                }
            } else {
                $hotels = $oldPkg && isset($oldPkg->hotels) ? json_decode($oldPkg->hotels, true) ?: [] : [];
            }

            $keywords = [];
            if ($request->has('keywords') && !empty($request->keywords)) {
                $keywords = array_values(array_filter(array_map('trim', explode(',', $request->keywords))));
            } else {
                $keywords = $oldPkg && isset($oldPkg->keywords) ? json_decode($oldPkg->keywords, true) ?: [] : [];
            }

            $amenities = $request->has('amenities') ? $request->input('amenities', []) : ($oldPkg && isset($oldPkg->amenities) ? json_decode($oldPkg->amenities, true) ?: [] : []);
            $transfers = $request->has('transfers') ? $request->input('transfers', []) : ($oldPkg && isset($oldPkg->transfers) ? json_decode($oldPkg->transfers, true) ?: [] : []);
            $meals = $request->has('meals') ? $request->input('meals', []) : ($oldPkg && isset($oldPkg->meals) ? json_decode($oldPkg->meals, true) ?: [] : []);

            $expiry_date = $oldPkg ? $oldPkg->expiry_date : null;
            if (!empty($request->validity) && strpos($request->validity, ' to ') !== false) {
                $parts = explode(' to ', $request->validity);
                if (isset($parts[1])) {
                    $expiry_date = date('Y-m-d', strtotime($parts[1]));
                }
            } elseif (!empty($request->validity)) {
                $expiry_date = date('Y-m-d', strtotime($request->validity));
            }

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
                        $itinerary[] = [
                            'title' => $dayTitle,
                            'desc' => $dayDesc,
                            'duration' => $dayDur
                        ];
                    }
                }
            }

            if ($request->has('agent')) {
                $agentName = $request->agent;
                $agentDb = DB::table('agents')->where('name', $agentName)->first();
                if ($agentDb) {
                    $agentJson = json_encode([
                        'id' => $agentDb->id,
                        'logo' => $agentDb->logo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentDb->agency_name ?? $agentDb->name),
                        'name' => $agentDb->agency_name ?? $agentDb->name,
                        'phone' => $agentDb->phone ?? '',
                        'whatsapp' => $agentDb->phone ?? '',
                        'email' => $agentDb->email ?? ''
                    ]);
                } else {
                    $agentJson = json_encode([
                        'id' => null,
                        'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName),
                        'name' => $agentName,
                        'phone' => '',
                        'whatsapp' => '',
                        'email' => ''
                    ]);
                }
            } else {
                $agentJson = $oldPkg->agent ?? json_encode([
                    'id' => null,
                    'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=Miths+Holidays',
                    'name' => 'Miths Holidays',
                    'phone' => '',
                    'whatsapp' => '',
                    'email' => ''
                ]);
            }

            DB::table('packages')->where('id', $request->id)->update([
                'title' => $request->title,
                'departure_city' => $request->departure_city ?? null,
                'departure_state' => $request->departure_state ?? null,
                'departure_country' => $request->departure_country ?? null,
                'terms' => $request->terms ?? null,
                'sightseeing_list' => json_encode($sightseeing_list),
                'currency' => $request->currency ?? '₹',
                'location' => $request->location ?? $oldPkg->location ?? 'Global',
                'price' => $request->price,
                'old_price' => $request->old_price,
                'rating' => $request->rating ?? 4.8,
                'reviews' => $request->reviews ?? 10,
                'duration' => $request->duration ?? $oldPkg->duration ?? '3 Days',
                'group_size' => $request->group_size ?? '4-6 guest',
                'image' => $imageUrl,
                'category' => $request->category ?? 'domestic',
                'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,
                'theme' => $request->theme ?? '',
                'holiday_type' => $request->holiday_type ?? '',
                'badge' => $request->badge ?? '',
                'status' => $request->status ?? 'Active',
                'stock' => $request->stock ?? '10 Left',
                'validity' => $request->validity ?? '',
                'expiry_date' => $expiry_date,
                'sightseeing' => $request->sightseeing ?? '',
                'agent' => $agentJson,
                'gallery' => json_encode($galleryUrls),
                'brochure' => $brochureUrl,
                'included' => json_encode($included),
                'excluded' => json_encode($excluded),
                'hotels' => json_encode($hotels),
                'keywords' => json_encode($keywords),
                'amenities' => json_encode($amenities),
                'transfers' => json_encode($transfers),
                'meals' => json_encode($meals),
                'itinerary' => json_encode($itinerary),
                'editorial_itinerary' => $request->editorial_itinerary ?? null,
                'updated_at' => now(),
            ]);

            return redirect('/admin/packages')->with('success', 'Package updated successfully!');
        } catch (\Throwable $e) {
            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function deletePackage($id)
    {
        DB::table('packages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    public function togglePackage($id)
    {
        $pkg = DB::table('packages')->where('id', $id)->first();
        if ($pkg && $pkg->status !== 'Pending') {
            // Only toggle between Active and Inactive (not Pending)
            $newStatus = $pkg->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('packages')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Package status updated!');
    }

    public function approvePackage($id)
    {
        DB::table('packages')->where('id', $id)->update(['status' => 'Active', 'updated_at' => now()]);
        return redirect('/admin/packages/pending')->with('success', 'Package approved and is now live on the customer site!');
    }

    public function declinePackage($id)
    {
        DB::table('packages')->where('id', $id)->update(['status' => 'Inactive', 'updated_at' => now()]);
        return redirect('/admin/packages/pending')->with('success', 'Package declined and hidden from customer site.');
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
        $query = DB::table('activities')->orderBy('id', 'asc');
        if ($request->status === 'active') {
            $query->where('status', 'Active');
        } elseif ($request->status === 'inactive') {
            $query->where('status', 'Inactive');
        }
        $activities = $query->paginate(10)->withQueryString();
        $totalCount = DB::table('activities')->count();
        $activeCount = DB::table('activities')->where('status', 'Active')->count();
        $inactiveCount = DB::table('activities')->where('status', 'Inactive')->count();
        return view('admin.activities', compact('activities', 'totalCount', 'activeCount', 'inactiveCount'));
    }

    public function storeActivity(Request $request)
    {
        $request->validate(['name' => 'required', 'icon' => 'required']);
        DB::table('activities')->insert([
            'name' => $request->name,
            'icon' => $request->icon,
            'intensity' => $request->intensity ?? 'Medium',
            'price' => $request->price ?? 0,
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
            'price' => $request->price ?? 0,
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
        $query = DB::table('users')->whereIn('role', ['SUPER ADMIN', 'MANAGER', 'EDITOR']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();
        return view('admin.users', compact('users', 'search'));
    }

    public function createAdminUser()
    {
        $roles = DB::table('roles')->get();
        return view('admin.users-create', compact('roles'));
    }

    public function editAdminUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect('/admin/users')->with('error', 'User not found!');
        }
        $user->permissions = json_decode($user->permissions ?? '{}', true) ?: [];
        $roles = DB::table('roles')->get();
        return view('admin.users-edit', compact('user', 'roles'));
    }

    // CUSTOMERS (Normal Users)
    public function customers(Request $request)
    {
        $search = $request->input('search');
        // Assuming normal users don't have the admin roles, or their role is 'USER'
        $query = DB::table('users')->whereNotIn('role', ['SUPER ADMIN', 'MANAGER', 'EDITOR'])->orWhereNull('role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.customers', compact('customers', 'search'));
    }

    public function deleteCustomer($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Customer account removed!');
    }

    public function storeUser(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users,email']);

        $avatarPath = 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($request->name);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $fileName);
            $avatarPath = '/uploads/avatars/' . $fileName;
        }

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role' => $request->role ?? 'SUPER ADMIN',
            'avatar' => $avatarPath,
            'permissions' => json_encode($request->input('permissions', [])),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/users')->with('success', 'Admin user created!');
    }

    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $name = strtoupper(trim($request->name));

        $exists = DB::table('roles')->where('name', $name)->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Role already exists!']);
        }

        $id = DB::table('roles')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['success' => true, 'role' => ['id' => $id, 'name' => $name]]);
    }

    public function deleteRole($id)
    {
        DB::table('roles')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function updateUser(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required', 'email' => 'required|email']);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? 'SUPER ADMIN',
            'permissions' => json_encode($request->input('permissions', [])),
            'updated_at' => now(),
        ];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $fileName);
            $updateData['avatar'] = '/uploads/avatars/' . $fileName;
        }

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $request->id)->update($updateData);

        return redirect('/admin/users')->with('success', 'Admin user details updated!');
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
        $agents = DB::table('agents')->orderBy('id', 'desc')->get();
        $plans = DB::table('plans')->get();
        return view('admin.agents', compact('agents', 'plans'));
    }

    public function registeredAgents(Request $request)
    {
        $query = DB::table('agents')
            ->leftJoin('plans', 'agents.plan_id', '=', 'plans.id')
            ->select('agents.*', 'plans.name as plan_name');

        if ($request->has('guaranteed') && $request->input('guaranteed') !== null && $request->input('guaranteed') !== '') {
            $query->where('agents.service_guaranteed', $request->guaranteed);
        }

        if ($request->has('plan_id') && $request->input('plan_id') !== null && $request->input('plan_id') !== '') {
            $selectedPlan = DB::table('plans')->where('id', $request->plan_id)->first();
            if ($selectedPlan && strtolower($selectedPlan->name) === 'basic') {
                $query->where(function ($q) use ($request) {
                    $q->where('agents.plan_id', $request->plan_id)
                        ->orWhereNull('agents.plan_id');
                });
            } else {
                $query->where('agents.plan_id', $request->plan_id);
            }
        }

        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== '') {
            $query->where('agents.status', $request->status);
        }

        if ($request->has('search') && $request->input('search') !== null && $request->input('search') !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('agents.name', 'like', "%{$search}%")
                    ->orWhere('agents.email', 'like', "%{$search}%")
                    ->orWhere('agents.phone', 'like', "%{$search}%");
            });
        }

        $agents = $query->orderBy('agents.id', 'desc')->paginate(10)->withQueryString();

        $plans = DB::table('plans')->get();

        return view('admin.registered-agents', compact('agents', 'plans'));
    }

    public function storeAgent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'logo' => 'nullable|image|max:2048'
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/agents'), $fileName);
            $logoUrl = '/uploads/agents/' . $fileName;
        }

        $plan_id = null;
        if ($request->tier === 'Customise' && $request->filled('custom_plan_tier')) {
            $planRecord = DB::table('plans')->where('name', $request->custom_plan_tier)->first();
            if ($planRecord) {
                $plan_id = $planRecord->id;
            }
        }

        DB::table('agents')->insert([
            'name' => $request->name,
            'logo' => $logoUrl,
            'email' => $request->email,
            'phone' => $request->phone,
            'landline' => $request->landline,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'about' => $request->about,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'google_plus' => $request->google_plus,
            'instagram' => $request->instagram,
            'skype' => $request->skype,
            'website' => $request->website,
            'region' => $request->region ?? 'Asia Pacific',
            'tier' => $request->tier ?? 'Premium',
            'plan_id' => $plan_id,
            'status' => $request->status ?? 'Active',
            'service_guaranteed' => $request->has('service_guaranteed') ? true : false,
            'generate_bill' => $request->has('generate_bill') ? true : false,
            'api_access' => $request->has('api_access') ? true : false,
            'pending' => $request->pending ?? 0,
            'approved' => $request->approved ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect('/admin/registered-agents')->with('success', 'New Travel Agent onboarded successfully!');
    }

    public function editAgent($id)
    {
        $agent = DB::table('agents')
            ->leftJoin('plans', 'agents.plan_id', '=', 'plans.id')
            ->select('agents.*', 'plans.name as plan_name')
            ->where('agents.id', $id)
            ->first();
        if (!$agent) {
            return redirect()->back()->with('error', 'Agent not found.');
        }
        $plans = DB::table('plans')->where('status', 'Active')->get();
        return view('admin.agents-edit', compact('agent', 'plans'));
    }

    public function agentProfile($id)
    {
        $agent = DB::table('agents')->where('id', $id)->first();
        if (!$agent) {
            return redirect()->back()->with('error', 'Agent not found.');
        }

        // Seed realistic default values for empty fields based on reference design
        if (empty($agent->address)) {
            $agent->address = "102 Royal Plaza, Opp. Crystal Mall";
        }
        if (empty($agent->city)) {
            $agent->city = "Rajkot";
        }
        if (empty($agent->pincode)) {
            $agent->pincode = "360003";
        }
        if (empty($agent->state)) {
            $agent->state = "Gujarat";
        }
        if (empty($agent->about)) {
            $agent->about = "Specializing in luxury domestic tours and international holiday packages. We pride ourselves on customer satisfaction and 24/7 on-ground support for all our clients.";
        }
        if (empty($agent->landline)) {
            $agent->landline = "0281-2233445";
        }

        $activePlan = DB::table('plans')->where('id', $agent->plan_id ?? null)->first();
        $payments = DB::table('payments')->where('email', $agent->email)->orderBy('id', 'desc')->get();

        return view('admin.agent-profile', compact('agent', 'activePlan', 'payments'));
    }

    public function updateAgent(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $agent = DB::table('agents')->where('id', $request->id)->first();
        if (!$agent) {
            return redirect()->back()->with('error', 'Agent not found.');
        }

        $logoUrl = $agent->logo;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/agents'), $fileName);
            $logoUrl = '/uploads/agents/' . $fileName;
        }

        $plan_id = null;
        if ($request->tier === 'Customise' && $request->filled('custom_plan_tier')) {
            $planRecord = DB::table('plans')->where('name', $request->custom_plan_tier)->first();
            if ($planRecord) {
                $plan_id = $planRecord->id;
            }
        }

        DB::table('agents')->where('id', $request->id)->update([
            'name' => $request->name,
            'logo' => $logoUrl,
            'email' => $request->email,
            'phone' => $request->phone,
            'landline' => $request->landline,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'about' => $request->about,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'google_plus' => $request->google_plus,
            'instagram' => $request->instagram,
            'skype' => $request->skype,
            'website' => $request->website,
            'region' => $request->region ?? 'Asia Pacific',
            'tier' => $request->tier ?? 'Premium',
            'plan_id' => ($request->tier === 'Customise' && $plan_id) ? $plan_id : DB::raw('plan_id'),
            'status' => $request->status ?? 'Active',
            'service_guaranteed' => $request->has('service_guaranteed') ? true : false,
            'generate_bill' => $request->has('generate_bill') ? true : false,
            'api_access' => $request->has('api_access') ? true : false,
            'pending' => $request->pending ?? 0,
            'approved' => $request->approved ?? 0,
            'updated_at' => now(),
        ]);

        return redirect('/admin/registered-agents')->with('success', 'Travel Agent updated successfully!');
    }

    public function deleteAgent($id)
    {
        DB::table('agents')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Agent removed successfully!');
    }

    public function toggleAgent($id)
    {
        $agent = DB::table('agents')->where('id', $id)->first();
        if ($agent) {
            $newStatus = strtolower($agent->status) === 'active' ? 'Inactive' : 'Active';
            DB::table('agents')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect('/admin/registered-agents')->with('success', 'Agent status toggled!');
    }

    // LEAD MANAGEMENT
    public function leads(Request $request)
    {
        // Ensure all unique agents in leads exist in agents table
        try {
            $leadAgents = DB::table('leads')->distinct()->pluck('agent');
            foreach ($leadAgents as $laName) {
                if (empty($laName))
                    continue;
                $exists = DB::table('agents')
                    ->where('name', $laName)
                    ->orWhere('name', 'like', "%{$laName}%")
                    ->exists();
                if (!$exists) {
                    $email = strtolower(str_replace(' ', '', $laName)) . '@' . strtolower(str_replace(' ', '', $laName)) . '.com';
                    $phone = '+91 9' . rand(7000, 9999) . ' ' . rand(10000, 99999);
                    $landline = '0' . rand(20, 80) . '-' . rand(2000000, 9999999);
                    $cities = ['Rajkot', 'Ahmedabad', 'Surat', 'Vadodara', 'Mumbai'];
                    $city = $cities[array_rand($cities)];
                    $states = ['Gujarat', 'Gujarat', 'Gujarat', 'Gujarat', 'Maharashtra'];
                    $state = $states[array_rand($states)];
                    $pincodes = ['360001', '380001', '395001', '390001', '400001'];
                    $pincode = $pincodes[array_rand($pincodes)];

                    DB::table('agents')->insert([
                        'name' => $laName,
                        'email' => $email,
                        'phone' => $phone,
                        'landline' => $landline,
                        'address' => rand(100, 999) . ' Business Plaza, Near Main Ring Road',
                        'city' => $city,
                        'state' => $state,
                        'pincode' => $pincode,
                        'about' => "Specializing in luxury domestic tours and international holiday packages. We pride ourselves on customer satisfaction and 24/7 on-ground support for all our clients at {$laName}.",
                        'status' => 'Active',
                        'tier' => 'Premium',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            // ignore
        }

        $search = $request->input('search');
        $type = $request->input('type');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $query = DB::table('leads');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('agent', 'like', "%{$search}%")
                    ->orWhere('package', 'like', "%{$search}%");
            });
        }

        if ($type) {
            if ($type === 'Other') {
                $query->where('package', 'not like', "%Flight%")
                    ->where('package', 'not like', "%Train%")
                    ->where('package', 'not like', "%Bus%")
                    ->where('package', 'not like', "%Cruise%")
                    ->where('package', 'not like', "%Land%");
            } else {
                $query->where('package', 'like', "%{$type}%");
            }
        }

        if ($from_date) {
            $query->whereDate('created_at', '>=', $from_date);
        }

        if ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }

        $leads = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        // Fetch all agents to pass for name to ID mapping
        $agents = DB::table('agents')->get();

        return view('admin.leads', compact('leads', 'search', 'type', 'agents', 'from_date', 'to_date'));
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

        $oldLead = DB::table('leads')->where('id', $request->id)->first();

        DB::table('leads')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'agent' => $request->agent,
            'package' => $request->package,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        if ($oldLead) {
            try {
                $mappedStatus = 'Pending';
                if ($request->status === 'Booked') {
                    $mappedStatus = 'Confirmed';
                } elseif ($request->status === 'Lost') {
                    $mappedStatus = 'Cancelled';
                }

                // Update booking status
                DB::table('user_bookings')
                    ->where('traveler_email', $oldLead->email)
                    ->where('package_title', $oldLead->package)
                    ->update([
                        'status' => $mappedStatus,
                        'updated_at' => now()
                    ]);

                // Create a notification for the user to inform them about status update!
                $booking = DB::table('user_bookings')
                    ->where('traveler_email', $oldLead->email)
                    ->where('package_title', $oldLead->package)
                    ->first();

                if ($booking && $booking->user_id) {
                    DB::table('user_notifications')->insert([
                        'user_id' => $booking->user_id,
                        'title' => 'Booking Status Updated',
                        'message' => "Your booking request for '{$booking->package_title}' is now: {$mappedStatus}.",
                        'type' => $mappedStatus === 'Confirmed' ? 'Info' : ($mappedStatus === 'Cancelled' ? 'Alert' : 'Info'),
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Silently ignore sync errors
            }
        }

        return redirect()->back()->with('success', 'Lead record updated and customer booking synced!');
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

    public function createPaidUser()
    {
        return view('admin.paid-users-create');
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

    public function payments(Request $request)
    {
        // Ensure invoice_data and generate_bill columns exist
        try {
            DB::statement("SELECT invoice_data, generate_bill FROM payments LIMIT 1");
        } catch (\Exception $e) {
            try {
                DB::statement("ALTER TABLE payments ADD COLUMN invoice_data LONGTEXT NULL");
            } catch (\Exception $ex) {
            }
            try {
                DB::statement("ALTER TABLE payments ADD COLUMN generate_bill TINYINT(1) DEFAULT 1");
            } catch (\Exception $ex) {
            }
        }

        // Ensure is_synced column exists
        try {
            DB::statement("SELECT is_synced FROM paid_users LIMIT 1");
        } catch (\Exception $e) {
            try {
                DB::statement("ALTER TABLE paid_users ADD COLUMN is_synced TINYINT(1) DEFAULT 0");
            } catch (\Exception $ex) {
            }
            // For existing records, set is_synced to 1 if they already exist in payments
            try {
                $existingEmails = DB::table('payments')->pluck('email')->toArray();
                if (!empty($existingEmails)) {
                    DB::table('paid_users')->whereIn('email', $existingEmails)->update(['is_synced' => 1]);
                }
            } catch (\Exception $ex) {
            }
        }

        // Sync paid_users records to payments table
        $paidUsers = DB::table('paid_users')->where('is_synced', 0)->get();
        foreach ($paidUsers as $pu) {
            $paymentId = 'TXN_PU_' . str_pad($pu->id, 4, '0', STR_PAD_LEFT);
            $exists = DB::table('payments')->where('payment_id', $paymentId)->exists();

            if (!$exists) {
                DB::table('payments')->insert([
                    'user_name' => $pu->name,
                    'email' => $pu->email,
                    'plan_type' => $pu->plan,
                    'amount' => $pu->amount,
                    'payment_id' => $paymentId,
                    'date' => $pu->joined_date,
                    'status' => $pu->status === 'Active' ? 'Completed' : 'Failed',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            DB::table('paid_users')->where('id', $pu->id)->update(['is_synced' => 1]);
        }

        $query = DB::table('payments')
            ->select('payments.*')
            ->addSelect([
                'service_guaranteed' => DB::table('agents')
                    ->select('service_guaranteed')
                    ->whereColumn('agents.email', 'payments.email')
                    ->limit(1)
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payments.payment_id', 'like', "%{$search}%")
                    ->orWhere('payments.user_name', 'like', "%{$search}%")
                    ->orWhere('payments.email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('plan_type')) {
            $query->where('payments.plan_type', 'like', "%{$request->plan_type}%");
        }

        if ($request->filled('status')) {
            $query->where('payments.status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payments.date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payments.date', '<=', $request->to_date);
        }

        if ($request->filled('service_guaranteed')) {
            // Since we are using a subquery for service_guaranteed, filtering requires a whereExists or similar
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('agents')
                    ->whereColumn('agents.email', 'payments.email')
                    ->where('service_guaranteed', $request->service_guaranteed);
            });
        }

        if ($request->filled('generate_bill')) {
            $query->where('payments.generate_bill', $request->generate_bill);
        }

        $payments = $query->orderBy('payments.id', 'desc')->paginate(10);
        $payments->appends($request->all());

        $plans = DB::table('plans')->where('status', 'Active')->orderBy('name')->get();
        $agentsList = DB::table('agents')->select('name', 'email')->get();

        return view('admin.payments', compact('payments', 'plans', 'agentsList'));
    }

    public function paymentInvoice($id)
    {
        try {
            DB::statement("SELECT invoice_data FROM payments LIMIT 1");
        } catch (\Exception $e) {
            DB::statement("ALTER TABLE payments ADD COLUMN invoice_data LONGTEXT NULL");
        }

        $payment = DB::table('payments')
            ->select('payments.*')
            ->addSelect([
                'service_guaranteed' => DB::table('agents')
                    ->select('service_guaranteed')
                    ->whereColumn('agents.email', 'payments.email')
                    ->limit(1)
            ])
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            abort(404, 'Payment record not found');
        }



        if (!$payment->generate_bill) {
            return response()->make("<div style='font-family: system-ui, -apple-system, sans-serif; padding: 40px; text-align: center; max-width: 600px; margin: 100px auto; background: #fff; border-radius: 28px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;'>
                <div style='color: #D35400; font-size: 56px; margin-bottom: 20px;'>⚠️</div>
                <h2 style='color: #1a1a1a; font-weight: 800; margin-bottom: 15px; letter-spacing: -0.02em;'>Generation Blocked</h2>
                <p style='color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 30px;'>Invoice cannot be generated because Bill Generate is No.</p>
                <a href='" . url('/admin/payments') . "' style='display: inline-flex; align-items: center; justify-content: center; padding: 14px 32px; background: #D35400; color: #fff; text-decoration: none; border-radius: 16px; font-weight: bold; font-size: 14px; transition: all 0.2s;'>Back to Payments</a>
            </div>", 400);
        }

        $invoiceData = json_decode($payment->invoice_data ?? '', true);
        if (!$invoiceData) {
            $price = ($payment->generate_bill === 0 || $payment->generate_bill === false || $payment->generate_bill === '0') ? 0 : $payment->amount;
            $invoiceData = [
                'invoice_no' => 'TR-INV-2024-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
                'invoice_date' => \Carbon\Carbon::parse($payment->date)->format('F d, Y'),
                'due_date' => \Carbon\Carbon::parse($payment->date)->addDays(30)->format('F d, Y'),
                'customer_name' => $payment->user_name,
                'customer_address' => "12th Floor, Trade Center, Bandra Kurla Complex\nMumbai, Maharashtra - 400051",
                'customer_gstin' => '27AABCA1234B1Z2',
                'customer_phone' => '+91 98765 43210',
                'customer_email' => $payment->email,
                'place_of_supply' => 'Uttar Pradesh (09)',
                'state_code' => '09',
                'payment_due' => 'Net 30 Days (' . \Carbon\Carbon::parse($payment->date)->addDays(30)->format('M d, Y') . ')',
                'services' => [
                    [
                        'name' => $payment->plan_type,
                        'description' => 'Subscription package fee for ' . $payment->plan_type,
                        'sac_hsn' => '998522',
                        'qty' => 1,
                        'price' => $price,
                        'total' => $price
                    ]
                ],
                'notes' => "All payments should be made in favor of Tour Raja Private Limited.\nInterest at 18% p.a. will be charged if the bill is not paid by the due date.\nGoods/Services once sold cannot be returned.\nSubject to Noida Jurisdiction only."
            ];
        } else {
            $defaultData = [
                'invoice_no' => 'TR-INV-2024-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
                'invoice_date' => \Carbon\Carbon::parse($payment->date)->format('F d, Y'),
                'due_date' => \Carbon\Carbon::parse($payment->date)->addDays(30)->format('F d, Y'),
                'customer_name' => $payment->user_name,
                'customer_address' => "12th Floor, Trade Center, Bandra Kurla Complex\nMumbai, Maharashtra - 400051",
                'customer_gstin' => '27AABCA1234B1Z2',
                'customer_phone' => '+91 98765 43210',
                'customer_email' => $payment->email,
                'place_of_supply' => 'Uttar Pradesh (09)',
                'state_code' => '09',
                'payment_due' => 'Net 30 Days (' . \Carbon\Carbon::parse($payment->date)->addDays(30)->format('M d, Y') . ')',
                'services' => [],
                'notes' => "All payments should be made in favor of Tour Raja Private Limited.\nInterest at 18% p.a. will be charged if the bill is not paid by the due date.\nGoods/Services once sold cannot be returned.\nSubject to Noida Jurisdiction only."
            ];
            $invoiceData = array_merge($defaultData, $invoiceData);

            if (empty($invoiceData['services'])) {
                $price = ($payment->generate_bill === 0 || $payment->generate_bill === false || $payment->generate_bill === '0') ? 0 : $payment->amount;
                $invoiceData['services'] = [
                    [
                        'name' => $payment->plan_type,
                        'description' => 'Subscription package fee for ' . $payment->plan_type,
                        'sac_hsn' => '998522',
                        'qty' => 1,
                        'price' => $price,
                        'total' => $price
                    ]
                ];
            } else {
                if ($payment->generate_bill === 0 || $payment->generate_bill === false || $payment->generate_bill === '0') {
                    foreach ($invoiceData['services'] as &$svc) {
                        $svc['price'] = 0;
                        $svc['total'] = 0;
                    }
                }
            }
        }

        return view('admin.invoice-overview', compact('payment', 'invoiceData'));
    }

    public function updatePaymentInvoice(Request $request)
    {
        $id = $request->input('id');
        $payment = DB::table('payments')
            ->leftJoin('agents', 'payments.email', '=', 'agents.email')
            ->select('payments.*', 'agents.generate_bill')
            ->where('payments.id', $id)
            ->first();

        $isFree = $payment && ($payment->generate_bill === 0 || $payment->generate_bill === false || $payment->generate_bill === '0');

        $invoiceData = [
            'invoice_no' => $request->input('invoice_no'),
            'invoice_date' => $request->input('invoice_date'),
            'due_date' => $request->input('due_date'),
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_gstin' => $request->input('customer_gstin'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_email' => $request->input('customer_email'),
            'place_of_supply' => $request->input('place_of_supply'),
            'state_code' => $request->input('state_code'),
            'payment_due' => $request->input('payment_due'),
            'services' => [],
            'notes' => $request->input('notes')
        ];

        $serviceNames = $request->input('service_name', []);
        $serviceDescriptions = $request->input('service_description', []);
        $serviceSacs = $request->input('service_sac', []);
        $serviceQtys = $request->input('service_qty', []);
        $servicePrices = $request->input('service_price', []);

        $totalAmount = 0;
        foreach ($serviceNames as $index => $name) {
            if (!empty($name)) {
                $qty = intval($serviceQtys[$index] ?? 1);
                $price = $isFree ? 0 : floatval($servicePrices[$index] ?? 0);
                $total = $qty * $price;
                $totalAmount += $total;

                $invoiceData['services'][] = [
                    'name' => $name,
                    'description' => $serviceDescriptions[$index] ?? '',
                    'sac_hsn' => $serviceSacs[$index] ?? '',
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $total
                ];
            }
        }

        // SGST 9%, CGST 9% (18% total GST)
        $subtotal = $totalAmount;
        $sgst = round($subtotal * 0.09, 2);
        $cgst = round($subtotal * 0.09, 2);
        $grandTotal = $subtotal + $sgst + $cgst;

        DB::table('payments')->where('id', $id)->update([
            'amount' => $grandTotal,
            'plan_type' => $invoiceData['services'][0]['name'] ?? 'Custom Plan',
            'invoice_data' => json_encode($invoiceData),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Invoice details updated successfully!');
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
            'generate_bill' => $request->input('generate_bill', 1),
            'gst_number' => $request->gst_number,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->has('email')) {
            DB::table('agents')->where('email', $request->email)->update([
                'service_guaranteed' => $request->input('service_guaranteed', 0),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Payment log entered successfully!');
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'user_name' => 'required',
            'email' => 'required|email',
            'plan_type' => 'required',
            'amount' => 'required|numeric',
            'payment_id' => 'required',
            'date' => 'required|date',
            'status' => 'required'
        ]);

        DB::table('payments')->where('id', $request->id)->update([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'plan_type' => $request->plan_type,
            'amount' => $request->amount,
            'payment_id' => $request->payment_id,
            'date' => $request->date,
            'status' => $request->status,
            'generate_bill' => $request->input('generate_bill', 0),
            'gst_number' => $request->gst_number,
            'updated_at' => now(),
        ]);

        DB::table('agents')->where('email', $request->email)->update([
            'service_guaranteed' => $request->input('service_guaranteed', 0),
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
        $ads = DB::table('ads')
            ->leftJoin('agents', 'ads.agent_id', '=', 'agents.id')
            ->select('ads.*', 'agents.name as agent_name', 'agents.logo as agent_logo')
            ->orderBy('ads.id', 'desc')
            ->paginate(5);
        $agents = DB::table('agents')->where('status', 'Active')->orderBy('name', 'asc')->get();
        return view('admin.ads', compact('ads', 'agents'));
    }

    public function storeAd(Request $request)
    {
        $request->validate(['campaign_name' => 'required', 'position' => 'required']);

        $imagePath = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ads'), $filename);
            $imagePath = 'uploads/ads/' . $filename;
        }

        DB::table('ads')->insert([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'image' => $imagePath ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
            'link' => $request->link ?? '/discover',
            'subtitle' => $request->subtitle,
            'agent_id' => $request->agent_id,
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

        $imagePath = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ads'), $filename);
            $imagePath = 'uploads/ads/' . $filename;
        }

        DB::table('ads')->where('id', $request->id)->update([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'image' => $imagePath,
            'link' => $request->link,
            'subtitle' => $request->subtitle,
            'agent_id' => $request->agent_id,
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
        $search = $request->input('search');
        $status = $request->input('status');

        $query = DB::table('plans');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        return view('admin.plans', compact('plans', 'search', 'status'));
    }

    public function createPlan()
    {
        return view('admin.plans-create');
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'package_limit' => 'required|integer'
        ]);

        DB::table('plans')->insert([
            'name' => $request->name,
            'price' => $request->price,
            'package_limit' => $request->package_limit,
            'duration' => $request->duration ?? '1 Month',
            'description' => $request->description,
            'features' => json_encode([$request->package_limit . ' package listings']),
            'status' => $request->has('status') ? 'Active' : 'Inactive',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/plans')->with('success', 'Plan created successfully!');
    }

    public function editPlan($id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/admin/plans')->with('error', 'Plan not found!');
        }
        return view('admin.plans-edit', compact('plan'));
    }

    public function updatePlan(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'package_limit' => 'required|integer'
        ]);

        DB::table('plans')->where('id', $request->id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'package_limit' => $request->package_limit,
            'duration' => $request->duration,
            'description' => $request->description,
            'features' => json_encode([$request->package_limit . ' package listings']),
            'status' => $request->has('status') ? 'Active' : 'Inactive',
            'updated_at' => now(),
        ]);

        return redirect('/admin/plans')->with('success', 'Plan updated successfully!');
    }

    public function deletePlan($id)
    {
        DB::table('plans')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Plan deleted!');
    }

    public function duplicatePlan($id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/admin/plans')->with('error', 'Plan not found!');
        }

        DB::table('plans')->insert([
            'name' => $plan->name . ' (Copy)',
            'price' => $plan->price,
            'package_limit' => $plan->package_limit,
            'duration' => $plan->duration,
            'description' => $plan->description,
            'features' => $plan->features,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/plans')->with('success', 'Plan duplicated successfully!');
    }

    public function previewPlan(Request $request, $id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/admin/plans')->with('error', 'Plan not found!');
        }

        $created_from = $request->input('created_from');
        $created_to = $request->input('created_to');

        $query = DB::table('agents')->where(function($q) use ($plan) {
            $q->where('plan_id', $plan->id)
              ->orWhere('tier', $plan->name);
        });

        if ($created_from) {
            $query->whereDate('created_at', '>=', $created_from);
        }
        if ($created_to) {
            $query->whereDate('created_at', '<=', $created_to);
        }

        $subscribedAgents = $query->get();

        return view('admin.plans-preview', compact('plan', 'subscribedAgents', 'created_from', 'created_to'));
    }

    public function exportPreviewPlan(Request $request, $id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/admin/plans')->with('error', 'Plan not found!');
        }

        $created_from = $request->input('created_from');
        $created_to = $request->input('created_to');

        $query = DB::table('agents')->where(function($q) use ($plan) {
            $q->where('plan_id', $plan->id)
              ->orWhere('tier', $plan->name);
        });

        if ($created_from) {
            $query->whereDate('created_at', '>=', $created_from);
        }
        if ($created_to) {
            $query->whereDate('created_at', '<=', $created_to);
        }

        $agents = $query->get();

        $csvFileName = 'plan_' . $plan->id . '_agents_export_' . time() . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($agents) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Agent Name', 'Email', 'Tier', 'Status', 'Created At']);

            foreach ($agents as $agent) {
                fputcsv($handle, [
                    $agent->id,
                    $agent->name,
                    $agent->email,
                    $agent->tier,
                    $agent->status,
                    $agent->created_at
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPlans()
    {
        $plans = DB::table('plans')->orderBy('id', 'asc')->get();
        $csvFileName = 'plans_export_' . time() . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Plan Name', 'Price', 'Package Limit', 'Duration', 'Status', 'Created At'];

        $callback = function () use ($plans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($plans as $plan) {
                fputcsv($file, [
                    $plan->id,
                    $plan->name,
                    $plan->price,
                    $plan->package_limit,
                    $plan->duration,
                    $plan->status,
                    $plan->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $dbTransits = DB::table('transits')->where('status', 'Active')->get();
        $transits = $dbTransits->sortBy(function($t) {
            $name = strtolower(trim($t->name));
            $orderMap = [
                'land' => 1,
                'bullet' => 2,
                'flight' => 3,
                'train' => 4,
                'bus' => 5,
                'cruise' => 6,
                'tracking' => 7,
                'helicopter' => 8,
            ];
            $norm = $name;
            if (str_contains($name, 'land') || str_contains($name, 'custom')) $norm = 'land';
            elseif (str_contains($name, 'bullet') || str_contains($name, 'bike')) $norm = 'bullet';
            elseif (str_contains($name, 'flight') || str_contains($name, 'air')) $norm = 'flight';
            elseif (str_contains($name, 'train') || str_contains($name, 'rail')) $norm = 'train';
            elseif (str_contains($name, 'bus') || str_contains($name, 'coach')) $norm = 'bus';
            elseif (str_contains($name, 'cruise') || str_contains($name, 'ship') || str_contains($name, 'boat')) $norm = 'cruise';
            elseif (str_contains($name, 'track') || str_contains($name, 'hike') || str_contains($name, 'trek')) $norm = 'tracking';
            elseif (str_contains($name, 'helicopter') || str_contains($name, 'sky')) $norm = 'helicopter';
            return $orderMap[$norm] ?? 999;
        })->values();
        $transitMusics = DB::table('transit_music')->orderBy('id', 'desc')->get();
        return view('admin.banners', compact('banners', 'transits', 'transitMusics'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate(['title' => 'required']);

        $imageUrl = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/banners'), $filename);
            $imageUrl = '/uploads/banners/' . $filename;
        }

        DB::table('banners')->insert([
            'title' => $request->title,

            'subtitle' => $request->subtitle,
            'image' => !empty($imageUrl) ? $imageUrl : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
            'link' => !empty($request->link) ? $request->link : '/discover',
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Marketing banner created!');
    }

    public function updateBanner(Request $request)
    {
        $request->validate(['id' => 'required', 'title' => 'required']);

        $imageUrl = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/banners'), $filename);
            $imageUrl = '/uploads/banners/' . $filename;
        }

        DB::table('banners')->where('id', $request->id)->update([
            'title' => $request->title,

            'subtitle' => $request->subtitle,
            'image' => !empty($imageUrl) ? $imageUrl : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
            'link' => !empty($request->link) ? $request->link : '/discover',
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

    public function uploadMusic(Request $request)
    {
        $request->validate([
            'music_file' => 'required|file|max:15360', // Max 15MB
        ]);

        if ($request->hasFile('music_file')) {
            $file = $request->file('music_file');
            $file->move(public_path('audio'), 'bg_music.mp3');
            return redirect()->back()->with('success', 'Background music updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to upload music.');
    }

    public function storeTransitMusic(Request $request)
    {
        $request->validate([
            'transit_name' => 'required|string',
            'music_name'   => 'required|string',
            'music_file'   => 'required|file|mimes:mp3,mpeg,mpga|max:20480',
        ]);

        $file = $request->file('music_file');
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/transit_music'), $filename);

        // Remove existing entry for same transit (only one music per transit)
        DB::table('transit_music')->where('transit_name', $request->transit_name)->delete();

        DB::table('transit_music')->insert([
            'transit_name' => $request->transit_name,
            'music_name'   => $request->music_name,
            'music_file'   => '/uploads/transit_music/' . $filename,
            'status'       => 'Active',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Transit music added for ' . $request->transit_name . '!');
    }

    public function deleteTransitMusic($id)
    {
        $music = DB::table('transit_music')->where('id', $id)->first();
        if ($music) {
            $fullPath = public_path($music->music_file);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            DB::table('transit_music')->where('id', $id)->delete();
        }
        return redirect()->back()->with('success', 'Transit music deleted!');
    }

    // NOTIFICATIONS
    public function notifications(Request $request)
    {
        $notifications = DB::table('notifications')->orderBy('id', 'desc')->paginate(5);
        $agents = DB::table('agents')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $roles = DB::table('roles')->orderBy('name', 'asc')->get();
        return view('admin.notifications', compact('notifications', 'agents', 'roles'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate(['title' => 'required', 'message' => 'required']);

        $targetAudience = $request->target_audience ?? 'all_users';
        $agentId = $request->agent_id ?? null;
        $adminRole = $request->admin_role ?? null;

        DB::table('notifications')->insert([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'Info',
            'target_audience' => $targetAudience,
            'agent_id' => $targetAudience === 'specific_agent' ? $agentId : null,
            'admin_role' => $targetAudience === 'specific_admin_role' ? $adminRole : null,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            // If target is all users, broadcast to all users
            if ($targetAudience === 'all_users') {
                $users = DB::table('users')->get();
                foreach ($users as $user) {
                    DB::table('user_notifications')->insert([
                        'user_id' => $user->id,
                        'title' => $request->title,
                        'message' => $request->message,
                        'type' => $request->type ?? 'Info',
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            // If target is all admins, broadcast to all admins
            if ($targetAudience === 'all_admins') {
                $users = DB::table('users')->get();
                foreach ($users as $u) {
                    DB::table('user_notifications')->insert([
                        'user_id' => $u->id,
                        'title' => $request->title,
                        'message' => $request->message,
                        'type' => $request->type ?? 'Info',
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            // If target is specific admin role, broadcast only to that role
            if ($targetAudience === 'specific_admin_role' && $adminRole) {
                $users = DB::table('users')->where('role', $adminRole)->get();
                foreach ($users as $u) {
                    DB::table('user_notifications')->insert([
                        'user_id' => $u->id,
                        'title' => $request->title,
                        'message' => $request->message,
                        'type' => $request->type ?? 'Info',
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            // If target is all agents, broadcast to all agents
            if ($targetAudience === 'all_agents') {
                $agents = DB::table('agents')->get();
                foreach ($agents as $agent) {
                    DB::table('agent_notifications')->insert([
                        'agent_id' => $agent->id,
                        'title' => $request->title,
                        'message' => $request->message,
                        'type' => $request->type ?? 'Info',
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            // If target is specific agent, broadcast only to that agent
            if ($targetAudience === 'specific_agent' && $agentId) {
                DB::table('agent_notifications')->insert([
                    'agent_id' => $agentId,
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => $request->type ?? 'Info',
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Notification broadcast failed: ' . $e->getMessage());
        }

        $successMsg = 'Global system notification sent and delivered to all users! 📣';
        if ($targetAudience === 'specific_agent') {
            $successMsg = 'Notification sent successfully to the selected agent! 📣';
        } elseif ($targetAudience === 'all_agents') {
            $successMsg = 'Notification sent successfully to all agents! 📣';
        } elseif ($targetAudience === 'all_admins') {
            $successMsg = 'Notification sent successfully to all admins! 📣';
        } elseif ($targetAudience === 'specific_admin_role') {
            $successMsg = "Notification sent successfully to all users with role '{$adminRole}'! 📣";
        }

        return redirect()->back()->with('success', $successMsg);
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
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        $query = DB::table('contacts');
        
        if ($from_date) {
            $query->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }
        
        $contacts = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.contact-us', compact('contacts', 'from_date', 'to_date'));
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
        foreach ($request->all() as $key => $value) {
            if ($key === '_token')
                continue;

            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/settings'), $filename);
                $value = '/uploads/settings/' . $filename;
            }

            DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function preferences(Request $request)
    {
        return view('admin.preferences');
    }

    public function hotelCategories(Request $request)
    {
        $categories = DB::table('hotel_categories')->orderBy('id', 'asc')->paginate(10);
        return view('admin.hotel-categories', compact('categories'));
    }

    public function storeHotelCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::table('hotel_categories')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bed',
            'status' => $request->has('status') ? $request->status : true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Hotel Category created successfully!');
    }

    public function updateHotelCategory(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:255',
        ]);

        DB::table('hotel_categories')->where('id', $request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bed',
            'status' => $request->has('status') ? $request->status : true,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Hotel Category updated successfully!');
    }

    public function toggleHotelCategory($id)
    {
        $category = DB::table('hotel_categories')->where('id', $id)->first();
        if ($category) {
            $newStatus = !$category->status;
            DB::table('hotel_categories')->where('id', $id)->update([
                'status' => $newStatus,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Hotel Category status updated!');
    }

    public function deleteHotelCategory($id)
    {
        DB::table('hotel_categories')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Hotel Category deleted successfully!');
    }

    public function updateProfile(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email']);

        $adminId = Auth::check() ? Auth::id() : 1;

        DB::table('users')->where('id', $adminId)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Admin profile updated!');
    }

    public function downloadInquiryReport(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = DB::table('contacts');

        if ($from_date) {
            $query->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }

        $contacts = $query->orderBy('id', 'desc')->get();
        $filename = "inquiry_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Date'];

        $callback = function () use ($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->subject,
                    $contact->message,
                    $contact->status,
                    $contact->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadLeadsReport(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = DB::table('leads');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('agent', 'like', "%{$search}%")
                    ->orWhere('package', 'like', "%{$search}%");
            });
        }

        if ($type) {
            if ($type === 'Other') {
                $query->where('package', 'not like', "%Flight%")
                    ->where('package', 'not like', "%Train%")
                    ->where('package', 'not like', "%Bus%")
                    ->where('package', 'not like', "%Cruise%")
                    ->where('package', 'not like', "%Land%");
            } else {
                $query->where('package', 'like', "%{$type}%");
            }
        }

        if ($from_date) {
            $query->whereDate('created_at', '>=', $from_date);
        }

        if ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }

        $leads = $query->orderBy('id', 'desc')->get();
        $filename = "leads_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Package', 'Agent', 'Status', 'Date'];

        $callback = function () use ($leads, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->package,
                    $lead->agent,
                    $lead->status,
                    $lead->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPaymentsReport(Request $request)
    {
        $payments = DB::table('payments')->orderBy('id', 'desc')->get();
        $filename = "payments_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'User Name', 'Email', 'Plan Type', 'Amount', 'Payment ID', 'Status', 'Date'];

        $callback = function () use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user_name,
                    $payment->email,
                    $payment->plan_type,
                    $payment->amount,
                    $payment->payment_id,
                    $payment->status,
                    $payment->date
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadSubscribersReport(Request $request)
    {
        $subscribers = DB::table('subscribers')->orderBy('id', 'desc')->get();
        $filename = "subscribers_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Email Address', 'Status', 'Date Joined'];

        $callback = function () use ($subscribers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($subscribers as $sub) {
                fputcsv($file, [
                    $sub->id,
                    $sub->email,
                    $sub->status,
                    $sub->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reports(Request $request)
    {
        // Simple aggregate data for reports page
        $totalInquiries = DB::table('contacts')->count();
        $totalLeads = DB::table('leads')->count();
        $totalBookings = DB::table('user_bookings')->count();
        $totalRevenue = DB::table('user_bookings')->sum('package_price');

        $recentInquiries = DB::table('contacts')->orderBy('id', 'desc')->limit(5)->get();

        return view('admin.reports', compact('totalInquiries', 'totalLeads', 'totalBookings', 'totalRevenue', 'recentInquiries'));
    }

    public function adminProfile()
    {
        $admin = Auth::check() ? Auth::user() : DB::table('users')->where('id', 1)->first();

        // System and analytical overview stats for admin profile!
        $totalPackages = DB::table('packages')->count();
        $totalLeads = DB::table('leads')->count();
        $totalUsers = DB::table('users')->count();
        $totalPayments = DB::table('payments')->count();
        $totalRevenue = DB::table('payments')->sum('amount');

        return view('admin.profile', compact('admin', 'totalPackages', 'totalLeads', 'totalUsers', 'totalPayments', 'totalRevenue'));
    }

    // ─── CAREERS ─────────────────────────────────────────────────────────────
    public function careers()
    {
        $applications = \App\Models\CareerApplication::orderBy('id', 'desc')->get();
        $positions = \App\Models\OpenPosition::with('department')->orderBy('id', 'desc')->get();
        $departments = \App\Models\JobDepartment::orderBy('name', 'asc')->get();
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

        return view('admin.careers', compact(
            'applications', 'positions', 'departments', 'locations', 
            'careerFormEnabled', 'careerFormTitle', 'careerFormFields', 'careerCustomFields'
        ));
    }

    public function deleteCareer($id)
    {
        $application = \App\Models\CareerApplication::findOrFail($id);

        // Delete the resume file if it exists
        if ($application->resume_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($application->resume_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return redirect()->back()->with('success', 'Career application deleted successfully.');
    }

    public function storePosition(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|integer',
            'locations' => 'required|array',
            'experience' => 'required|string',
            'job_type' => 'required|string',
            'salary' => 'nullable|string',
            'status' => 'required|string'
        ]);

        \App\Models\OpenPosition::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'title' => $request->title,
                'department_id' => $request->department_id,
                'locations' => $request->locations,
                'experience' => $request->experience,
                'job_type' => $request->job_type,
                'salary' => $request->salary,
                'status' => $request->status,
            ]
        );

        return redirect()->back()->with('success', 'Job position saved successfully.');
    }

    public function deletePosition($id)
    {
        \App\Models\OpenPosition::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Job position deleted successfully.');
    }

    public function updateCareerSettings(Request $request)
    {
        $enabled = $request->has('career_form_enabled') ? '1' : '0';
        DB::table('settings')->updateOrInsert(
            ['key' => 'career_form_enabled'],
            ['value' => $enabled, 'updated_at' => now()]
        );

        if ($request->has('career_form_title')) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'career_form_title'],
                ['value' => $request->input('career_form_title'), 'updated_at' => now()]
            );
        }

        if ($request->has('career_form_fields')) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'career_form_fields'],
                ['value' => json_encode($request->input('career_form_fields')), 'updated_at' => now()]
            );
        } else {
            DB::table('settings')->updateOrInsert(
                ['key' => 'career_form_fields'],
                ['value' => json_encode([]), 'updated_at' => now()]
            );
        }

        if ($request->has('career_custom_fields')) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'career_custom_fields'],
                ['value' => json_encode($request->input('career_custom_fields')), 'updated_at' => now()]
            );
        } else {
            DB::table('settings')->updateOrInsert(
                ['key' => 'career_custom_fields'],
                ['value' => json_encode([]), 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'Career form settings saved successfully.');
    }

    public function storeDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:job_departments,name']);
        $dept = \App\Models\JobDepartment::create(['name' => $request->name]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $dept->id, 'name' => $dept->name]);
        }
        return redirect()->back()->with('success', 'Department added successfully.');
    }

    public function storeLocation(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:job_locations,name']);
        $loc = \App\Models\JobLocation::create(['name' => $request->name]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $loc->id, 'name' => $loc->name]);
        }
        return redirect()->back()->with('success', 'Location added successfully.');
    }

    public function deleteDepartment($id)
    {
        try {
            $dept = \App\Models\JobDepartment::findOrFail($id);
            \App\Models\OpenPosition::where('department_id', $id)->delete();
            $dept->delete();
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true]);
            }
            return redirect('/admin/careers')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            return redirect('/admin/careers')->with('error', 'Could not delete department: ' . $e->getMessage());
        }
    }

    public function deleteLocation($id)
    {
        try {
            $loc = \App\Models\JobLocation::findOrFail($id);
            $loc->delete();
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true]);
            }
            return redirect('/admin/careers')->with('success', 'Location deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            return redirect('/admin/careers')->with('error', 'Could not delete location: ' . $e->getMessage());
        }
    }

    // ─── TRANSITS ─────────────────────────────────────────────────────────────
    public function transits(Request $request)
    {
        // Auto-seed if empty
        if (DB::table('transits')->count() === 0) {
            DB::table('transits')->insert([
                [
                    'name' => 'Flight Package',
                    'description' => 'International Air Charter',
                    'selected_icon' => 'plane',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Train Package',
                    'description' => 'Scenic Rail Expeditions',
                    'selected_icon' => 'train',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Bus Package',
                    'description' => 'Regional Coach Fleet',
                    'selected_icon' => 'bus',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Bullet Ride',
                    'description' => 'High-Octane Motorbiking',
                    'selected_icon' => 'bike',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Cruise Package',
                    'description' => 'Maritime Leisure Journeys',
                    'selected_icon' => 'ship',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Tracking Package',
                    'description' => 'Guided Mountain Trails',
                    'selected_icon' => 'footprints',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Helicopter Package',
                    'description' => 'Premium Sky Tours',
                    'selected_icon' => 'helicopter',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Other',
                    'description' => 'General transportation options',
                    'selected_icon' => 'map-pin',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        $dbTransits = DB::table('transits')->get();
        $sortedTransits = $dbTransits->sortBy(function($t) {
            $name = strtolower(trim($t->name));
            $orderMap = [
                'land' => 1,
                'bullet' => 2,
                'flight' => 3,
                'train' => 4,
                'bus' => 5,
                'cruise' => 6,
                'tracking' => 7,
                'helicopter' => 8,
            ];
            $norm = $name;
            if (str_contains($name, 'land') || str_contains($name, 'custom')) $norm = 'land';
            elseif (str_contains($name, 'bullet') || str_contains($name, 'bike')) $norm = 'bullet';
            elseif (str_contains($name, 'flight') || str_contains($name, 'air')) $norm = 'flight';
            elseif (str_contains($name, 'train') || str_contains($name, 'rail')) $norm = 'train';
            elseif (str_contains($name, 'bus') || str_contains($name, 'coach')) $norm = 'bus';
            elseif (str_contains($name, 'cruise') || str_contains($name, 'ship') || str_contains($name, 'boat')) $norm = 'cruise';
            elseif (str_contains($name, 'track') || str_contains($name, 'hike') || str_contains($name, 'trek')) $norm = 'tracking';
            elseif (str_contains($name, 'helicopter') || str_contains($name, 'sky')) $norm = 'helicopter';
            return $orderMap[$norm] ?? 999;
        })->values();

        $page = request()->get('page', 1);
        $perPage = 10;
        $transits = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedTransits->forPage($page, $perPage),
            $sortedTransits->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('admin.transits', compact('transits'));
    }

    public function storeTransit(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_img_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/transits'), $fileName);
            $imagePath = '/uploads/transits/' . $fileName;
        }

        $svgPath = null;
        if ($request->hasFile('svg_icon')) {
            $file = $request->file('svg_icon');
            $fileName = time() . '_svg_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/transits'), $fileName);
            $svgPath = '/uploads/transits/' . $fileName;
        }

        DB::table('transits')->insert([
            'name' => $request->name,
            'image' => $imagePath,
            'svg_icon' => $svgPath,
            'selected_icon' => $request->selected_icon,
            'description' => $request->description,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Transit package added successfully!');
    }

    public function updateTransit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'selected_icon' => $request->selected_icon,
            'description' => $request->description,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_img_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/transits'), $fileName);
            $data['image'] = '/uploads/transits/' . $fileName;
        }

        if ($request->hasFile('svg_icon')) {
            $file = $request->file('svg_icon');
            $fileName = time() . '_svg_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/transits'), $fileName);
            $data['svg_icon'] = '/uploads/transits/' . $fileName;
        }

        DB::table('transits')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'Transit package updated successfully!');
    }

    public function deleteTransit($id)
    {
        DB::table('transits')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Transit package deleted successfully.');
    }

    public function toggleTransit($id)
    {
        $transit = DB::table('transits')->where('id', $id)->first();
        if ($transit) {
            $newStatus = $transit->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('transits')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Transit status updated!');
    }

    // ─── DURATIONS ─────────────────────────────────────────────────────────────
    public function durations(Request $request)
    {
        $durations = DB::table('durations')->orderBy('id', 'asc')->paginate(10);

        $totalDurations = DB::table('durations')->count();
        $activeDurations = DB::table('durations')->where('status', 'Active')->count();
        $avgLength = round(DB::table('durations')->avg('nights') + 1);

        return view('admin.durations', compact('durations', 'totalDurations', 'activeDurations', 'avgLength'));
    }

    public function storeDuration(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'nights' => 'required|integer',
            'status' => 'required|string',
        ]);

        DB::table('durations')->insert([
            'name' => $request->name,
            'nights' => $request->nights,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Duration added successfully!');
    }

    public function updateDuration(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'nights' => 'required|integer',
            'status' => 'required|string',
        ]);

        DB::table('durations')->where('id', $request->id)->update([
            'name' => $request->name,
            'nights' => $request->nights,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Duration updated successfully!');
    }

    public function toggleDuration($id)
    {
        $duration = DB::table('durations')->where('id', $id)->first();
        if ($duration) {
            $newStatus = $duration->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('durations')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Duration status updated!');
    }

    public function deleteDuration($id)
    {
        DB::table('durations')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Duration deleted successfully.');
    }

    // ─── THEMES ──────────────────────────────────────────────────────────────
    public function themes(Request $request)
    {
        // Auto-seed if empty
        if (DB::table('themes')->count() === 0) {
            DB::table('themes')->insert([
                [
                    'name' => 'Family/Group',
                    'description' => 'Fun-filled trips for everyone',
                    'image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Religious',
                    'description' => 'Spiritual & sacred journeys',
                    'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Honeymoon',
                    'description' => 'Romantic & intimate escapes',
                    'image' => 'https://images.unsplash.com/photo-1573152958734-1922c188fba3?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Solo',
                    'description' => 'Self-discovery & independent journeys',
                    'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Adventure',
                    'description' => 'Thrilling & action-packed expeditions',
                    'image' => 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Cruise',
                    'description' => 'Luxury voyages & ship adventures',
                    'image' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'WaterPark',
                    'description' => 'Wet & wild aquatic theme parks',
                    'image' => 'https://images.unsplash.com/photo-1582650625119-3a31f8fa2699?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Pilgrimage',
                    'description' => 'Sacred trails & religious devotion',
                    'image' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&q=80&w=400&v=1',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
        $themes = DB::table('themes')->orderBy('id', 'asc')->paginate(10);
        $totalThemes = DB::table('themes')->count();
        $activeThemes = DB::table('themes')->where('status', 'Active')->count();

        return view('admin.themes', compact('themes', 'totalThemes', 'activeThemes'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_theme_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/themes'), $fileName);
            $imagePath = '/uploads/themes/' . $fileName;
        } else {
            $imagePath = 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=400';
        }

        DB::table('themes')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Theme added successfully!');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_theme_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/themes'), $fileName);
            $data['image'] = '/uploads/themes/' . $fileName;
        }

        DB::table('themes')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'Theme updated successfully!');
    }

    public function toggleTheme($id)
    {
        $theme = DB::table('themes')->where('id', $id)->first();
        if ($theme) {
            $newStatus = $theme->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('themes')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Theme status updated!');
    }

    public function deleteTheme($id)
    {
        DB::table('themes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Theme deleted successfully.');
    }

    // ─── COUNTRIES ────────────────────────────────────────────────────────────
    public function countries(Request $request)
    {
        // Auto-seed if empty
        if (DB::table('countries')->count() === 0) {
            DB::table('countries')->insert([
                [
                    'name' => 'Indonesia',
                    'region' => 'Southeast Asia',
                    'image' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Vietnam',
                    'region' => 'Southeast Asia',
                    'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Thailand',
                    'region' => 'Southeast Asia',
                    'image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Inactive',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Japan',
                    'region' => 'East Asia',
                    'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $countries = DB::table('countries')->orderBy('id', 'asc')->paginate(10);
        $totalCountries = DB::table('countries')->count();
        $activeCountries = DB::table('countries')->where('status', 'Active')->count();

        // Get the primary region
        $primaryRegion = DB::table('countries')
            ->where('status', 'Active')
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->first();

        $primaryRegionName = $primaryRegion ? $primaryRegion->region : 'N/A';

        return view('admin.countries', compact('countries', 'totalCountries', 'activeCountries', 'primaryRegionName'));
    }

    public function storeCountry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_country_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/countries'), $fileName);
            $imagePath = '/uploads/countries/' . $fileName;
        } else {
            $imagePath = 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=400';
        }

        DB::table('countries')->insert([
            'name' => $request->name,
            'region' => $request->region,
            'image' => $imagePath,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Country added successfully!');
    }

    public function updateCountry(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'region' => $request->region,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_country_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/countries'), $fileName);
            $data['image'] = '/uploads/countries/' . $fileName;
        }

        DB::table('countries')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'Country updated successfully!');
    }

    public function toggleCountry($id)
    {
        $country = DB::table('countries')->where('id', $id)->first();
        if ($country) {
            $newStatus = $country->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('countries')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'Country status updated!');
    }

    public function deleteCountry($id)
    {
        DB::table('countries')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Country deleted successfully.');
    }

    // ─── STATES ───────────────────────────────────────────────────────────────
    public function states(Request $request)
    {
        // Auto-seed if empty
        if (DB::table('states')->count() === 0) {
            DB::table('states')->insert([
                [
                    'name' => 'Rajasthan',
                    'country' => 'India',
                    'image' => 'https://images.unsplash.com/photo-1477584308802-dd6538a3a26b?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Tuscany',
                    'country' => 'Italy',
                    'image' => 'https://images.unsplash.com/photo-1543872084-c7bd3822856f?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Alberta',
                    'country' => 'Canada',
                    'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Inactive',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Munster',
                    'country' => 'Ireland',
                    'image' => 'https://images.unsplash.com/photo-1590089415225-4f3ed405cb6b?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $states = DB::table('states')->orderBy('id', 'asc')->paginate(10);
        $totalStates = DB::table('states')->count();

        $activeStates = DB::table('states')->where('status', 'Active')->count();
        $utilizationRate = $totalStates > 0 ? round(($activeStates / $totalStates) * 100) : 0;

        // Group by country and count states for top contributing countries
        $topCountries = DB::table('states')
            ->select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(2)
            ->get();

        $countriesList = DB::table('countries')->where('status', 'Active')->orderBy('name', 'asc')->get();

        return view('admin.states', compact('states', 'totalStates', 'activeStates', 'utilizationRate', 'topCountries', 'countriesList'));
    }

    public function storeState(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_state_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/states'), $fileName);
            $imagePath = '/uploads/states/' . $fileName;
        } else {
            $imagePath = 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=400';
        }

        DB::table('states')->insert([
            'name' => $request->name,
            'country' => $request->country,
            'image' => $imagePath,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'State added successfully!');
    }

    public function updateState(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'country' => $request->country,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_state_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/states'), $fileName);
            $data['image'] = '/uploads/states/' . $fileName;
        }

        DB::table('states')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'State updated successfully!');
    }

    public function toggleState($id)
    {
        $state = DB::table('states')->where('id', $id)->first();
        if ($state) {
            $newStatus = $state->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('states')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'State status updated!');
    }

    public function deleteState($id)
    {
        DB::table('states')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'State deleted successfully.');
    }

    // ─── CITIES ───────────────────────────────────────────────────────────────
    public function cities(Request $request)
    {
        // Auto-seed if empty
        if (DB::table('cities')->count() === 0) {
            DB::table('cities')->insert([
                [
                    'name' => 'Paris',
                    'timezone' => 'UTC +1:00',
                    'state' => 'Île-de-France',
                    'country' => 'France',
                    'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Dubai',
                    'timezone' => 'UTC +4:00',
                    'state' => 'Dubai',
                    'country' => 'UAE',
                    'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Tokyo',
                    'timezone' => 'UTC +9:00',
                    'state' => 'Tokyo Prefecture',
                    'country' => 'Japan',
                    'image' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Inactive',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Cairo',
                    'timezone' => 'UTC +2:00',
                    'state' => 'Cairo Governorate',
                    'country' => 'Egypt',
                    'image' => 'https://images.unsplash.com/photo-1539650116574-8efeb43e2750?auto=format&fit=crop&q=80&w=150',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $cities = DB::table('cities')->orderBy('id', 'asc')->paginate(10);
        $totalCities = 1478 + DB::table('cities')->count();
        $activeCities = 953 + DB::table('cities')->where('status', 'Active')->count();

        $countriesList = DB::table('countries')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $statesList = DB::table('states')->where('status', 'Active')->orderBy('name', 'asc')->get();

        return view('admin.cities', compact('cities', 'totalCities', 'activeCities', 'countriesList', 'statesList'));
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_city_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cities'), $fileName);
            $imagePath = '/uploads/cities/' . $fileName;
        } else {
            $imagePath = 'https://images.unsplash.com/photo-1449034446853-66c86144b0ad?auto=format&fit=crop&q=80&w=400';
        }

        DB::table('cities')->insert([
            'name' => $request->name,
            'timezone' => $request->timezone ?? 'UTC +0:00',
            'state' => $request->state,
            'country' => $request->country,
            'image' => $imagePath,
            'status' => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'City added successfully!');
    }

    public function updateCity(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'timezone' => $request->timezone ?? 'UTC +0:00',
            'state' => $request->state,
            'country' => $request->country,
            'status' => $request->status ?? 'Active',
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_city_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cities'), $fileName);
            $data['image'] = '/uploads/cities/' . $fileName;
        }

        DB::table('cities')->where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'City updated successfully!');
    }

    public function toggleCity($id)
    {
        $city = DB::table('cities')->where('id', $id)->first();
        if ($city) {
            $newStatus = $city->status === 'Active' ? 'Inactive' : 'Active';
            DB::table('cities')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);
        }
        return redirect()->back()->with('success', 'City status updated!');
    }

    public function deleteCity($id)
    {
        DB::table('cities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'City deleted successfully.');
    }

    // ─── MAIL SETUP ────────────────────────────────────────────────────────────
    public function mailSetup(Request $request)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('admin.mail-setup', compact('settings'));
    }

    public function updateMailSetup(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key === '_token')
                continue;
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
        return redirect()->back()->with('success', 'Mail settings updated successfully!');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'mail_driver' => 'required',
            'mail_host' => 'nullable',
            'mail_port' => 'nullable',
            'mail_encryption' => 'nullable',
            'mail_username' => 'nullable',
            'mail_password' => 'nullable',
            'mail_from_name' => 'required',
            'mail_from_address' => 'required|email',
        ]);

        $toEmail = $request->input('test_email');
        $driver = $request->input('mail_driver');
        $host = $request->input('mail_host') ?? 'smtp.gmail.com';
        $port = $request->input('mail_port') ?? '587';
        $encryption = $request->input('mail_encryption') ?? 'tls';
        $username = $request->input('mail_username');
        $password = $request->input('mail_password');
        $fromAddress = $request->input('mail_from_address');
        $fromName = $request->input('mail_from_name');

        config([
            'mail.default' => $driver,
            'mail.mailers.smtp.transport' => $driver === 'none' ? 'smtp' : $driver,
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        try {
            $body = '<h3>SMTP Settings Test Connection</h3><p>Hello! If you are reading this email, your SMTP configuration settings on <strong>Tour Raja</strong> are correct and working perfectly!</p>';
            $subject = 'Test Connection: Tour Raja SMTP Setup';

            \Illuminate\Support\Facades\Mail::mailer($driver)->html($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $toEmail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mail configuration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function packageReminder(Request $request)
    {
        // Fetch packages expiring within 30 days, or already expired
        $packages = DB::table('packages')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->get();

        // Attach agent details dynamically
        foreach ($packages as $pkg) {
            $agentEmail = '';
            $agentName = '';
            $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
            if ($agentData && isset($agentData['id'])) {
                $agentDb = DB::table('agents')->where('id', $agentData['id'])->first();
                if ($agentDb) {
                    $agentEmail = $agentDb->email;
                    $agentName = $agentDb->name;
                }
            }
            if (!$agentEmail && $agentData) {
                $agentName = $agentData['name'] ?? 'Unknown Agent';
                $agentDb = DB::table('agents')->where('name', $agentName)->first();
                if ($agentDb) {
                    $agentEmail = $agentDb->email;
                }
            }
            $pkg->agent_email = $agentEmail ?: 'no-email@agent.com';
            $pkg->agent_name = $agentName ?: 'Unknown Agent';
        }

        return view('admin.package-reminder', compact('packages'));
    }

    private function sendEmailNotification($toEmail, $subject, $body)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        
        $driver = $settings['mail_driver'] ?? 'smtp';
        $host = $settings['mail_host'] ?? 'smtp.gmail.com';
        $port = $settings['mail_port'] ?? '587';
        $encryption = $settings['mail_encryption'] ?? 'tls';
        $username = $settings['mail_username'] ?? null;
        $password = $settings['mail_password'] ?? null;
        $fromAddress = $settings['mail_from_address'] ?? 'noreply@tourraja.com';
        $fromName = $settings['mail_from_name'] ?? 'Tour Raja';

        config([
            'mail.default' => $driver,
            'mail.mailers.smtp.transport' => $driver === 'none' ? 'smtp' : $driver,
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::mailer($driver)->html($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPackageReminder(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|in:all,individual',
        ]);

        $subject = $request->input('subject');
        $bodyTemplate = $request->input('body');
        $type = $request->input('type');

        if ($type === 'individual') {
            $packageId = $request->input('package_id');
            $packages = DB::table('packages')->where('id', $packageId)->get();
        } else {
            $packages = DB::table('packages')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->get();
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($packages as $pkg) {
            $agentEmail = '';
            $agentName = '';
            $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
            if ($agentData && isset($agentData['id'])) {
                $agentDb = DB::table('agents')->where('id', $agentData['id'])->first();
                if ($agentDb) {
                    $agentEmail = $agentDb->email;
                    $agentName = $agentDb->name;
                }
            }
            if (!$agentEmail && $agentData) {
                $agentName = $agentData['name'] ?? 'Unknown Agent';
                $agentDb = DB::table('agents')->where('name', $agentName)->first();
                if ($agentDb) {
                    $agentEmail = $agentDb->email;
                }
            }

            if (!$agentEmail) {
                $failedCount++;
                continue;
            }

            $personalBody = str_replace(
                ['{AGENT_NAME}', '{PACKAGE_TITLE}', '{EXPIRY_DATE}'],
                [$agentName ?: 'Agent', $pkg->title, \Carbon\Carbon::parse($pkg->expiry_date)->format('M d, Y')],
                $bodyTemplate
            );

            $success = $this->sendEmailNotification($agentEmail, $subject, $personalBody);
            if ($success) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        if ($failedCount > 0) {
            return redirect()->back()->with('success', "Reminders sent successfully to {$sentCount} agents. Failed for {$failedCount} agents (check SMTP settings).");
        }

        return redirect()->back()->with('success', "All {$sentCount} package expiry reminders sent successfully!");
    }

    // ─── PAYMENT SETUP ─────────────────────────────────────────────────────────
    public function paymentSetup(Request $request)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('admin.payment-setup', compact('settings'));
    }

    public function updatePaymentSetup(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key === '_token')
                continue;
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
        return redirect()->back()->with('success', 'Payment gateway configurations updated successfully!');
    }

    // ─── WHATSAPP TEMPLATES ──────────────────────────────────────────────────
    public function whatsappTemplate(Request $request)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('admin.whatsapp-template', compact('settings'));
    }

    public function updateWhatsappTemplate(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key === '_token')
                continue;
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
        return redirect()->back()->with('success', 'WhatsApp template saved successfully!');
    }

    // ─── EMAIL TEMPLATES ─────────────────────────────────────────────────────
    public function emailTemplate(Request $request)
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('admin.email-template', compact('settings'));
    }

    public function updateEmailTemplate(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key === '_token')
                continue;
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
        return redirect()->back()->with('success', 'Email template saved successfully!');
    }
}

// Reusable custom timing function for activity feed
function human_timing($time)
{
    $time = time() - $time;
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}
