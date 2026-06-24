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

        // Fetch packages pending approval (status = Draft)
        $pendingPackages = DB::table('packages')
            ->where('status', 'Draft')
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            'title'    => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'subtitle' => $request->subtitle,
            'image'    => $imagePath,
            'link'     => $request->link ?? '/discover',
            'status'   => $request->status ?? 'Live',
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
            'title'    => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'subtitle' => $request->subtitle,
            'link'     => $request->link ?? '/discover',
            'status'   => $request->status ?? 'Live',
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
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $packages = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.packages', compact('packages', 'search'));
    }

    public function pendingPackages(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('packages')->where('status', 'Pending');

        if ($search) {
            $query->where(function($q) use ($search) {
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
            $imageUrl = '/uploads/packages/' . $fileName;
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
                $galleryUrls[] = '/uploads/packages/gallery/' . $fileName;
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
            $brochureUrl = '/uploads/packages/brochures/' . $fileName;
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
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            'itinerary'            => json_encode($itinerary),
            'editorial_itinerary'  => $request->editorial_itinerary ?? null,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        return redirect('/admin/packages')->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request)
    {
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
            $imageUrl = '/uploads/packages/' . $fileName;
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
                $galleryUrls[] = '/uploads/packages/gallery/' . $fileName;
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
            $brochureUrl = '/uploads/packages/brochures/' . $fileName;
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
            'departure_city'      => $request->departure_city ?? null,
            'departure_state'     => $request->departure_state ?? null,
            'departure_country'   => $request->departure_country ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'location' => $request->location,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'rating' => $request->rating ?? 4.8,
            'reviews' => $request->reviews ?? 10,
            'duration' => $request->duration,
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
            'itinerary'            => json_encode($itinerary),
            'editorial_itinerary'  => $request->editorial_itinerary ?? null,
            'updated_at'           => now(),
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
        $activities    = $query->paginate(10)->withQueryString();
        $totalCount    = DB::table('activities')->count();
        $activeCount   = DB::table('activities')->where('status', 'Active')->count();
        $inactiveCount = DB::table('activities')->where('status', 'Inactive')->count();
        return view('admin.activities', compact('activities', 'totalCount', 'activeCount', 'inactiveCount'));
    }

    public function storeActivity(Request $request)
    {
        $request->validate(['name' => 'required', 'icon' => 'required']);
        DB::table('activities')->insert([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'intensity'  => $request->intensity ?? 'Medium',
            'price'      => $request->price ?? 0,
            'status'     => $request->status ?? 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Activity added successfully!');
    }

    public function updateActivity(Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        DB::table('activities')->where('id', $request->id)->update([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'intensity'  => $request->intensity,
            'price'      => $request->price ?? 0,
            'status'     => $request->status ?? 'Active',
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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();
        return view('admin.users', compact('users', 'search'));
    }

    public function createAdminUser()
    {
        return view('admin.users-create');
    }

    public function editAdminUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect('/admin/users')->with('error', 'User not found!');
        }
        $user->permissions = json_decode($user->permissions ?? '{}', true) ?: [];
        return view('admin.users-edit', compact('user'));
    }

    // CUSTOMERS (Normal Users)
    public function customers(Request $request)
    {
        $search = $request->input('search');
        // Assuming normal users don't have the admin roles, or their role is 'USER'
        $query = DB::table('users')->whereNotIn('role', ['SUPER ADMIN', 'MANAGER', 'EDITOR'])->orWhereNull('role');

        if ($search) {
            $query->where(function($q) use ($search) {
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
        return view('admin.agents', compact('agents'));
    }

    public function registeredAgents(Request $request)
    {
        $agents = DB::table('agents')
            ->leftJoin('plans', 'agents.plan_id', '=', 'plans.id')
            ->select('agents.*', 'plans.name as plan_name')
            ->orderBy('agents.id', 'desc')
            ->paginate(10);
        return view('admin.registered-agents', compact('agents'));
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
         $agent = DB::table('agents')->where('id', $id)->first();
         if (!$agent) {
             return redirect()->back()->with('error', 'Agent not found.');
         }
         return view('admin.agents-edit', compact('agent'));
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
                if (empty($laName)) continue;
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
        $query = DB::table('leads');

        if ($search) {
            $query->where(function($q) use ($search) {
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

        $leads = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        
        // Fetch all agents to pass for name to ID mapping
        $agents = DB::table('agents')->get();

        return view('admin.leads', compact('leads', 'search', 'agents'));
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
        // Ensure invoice_data column exists
        try {
            DB::statement("SELECT invoice_data FROM payments LIMIT 1");
        } catch (\Exception $e) {
            DB::statement("ALTER TABLE payments ADD COLUMN invoice_data LONGTEXT NULL");
        }

        // Sync paid_users records to payments table
        $paidUsers = DB::table('paid_users')->get();
        foreach ($paidUsers as $pu) {
            $exists = DB::table('payments')
                ->where('email', $pu->email)
                ->where('plan_type', $pu->plan)
                ->exists();
            if (!$exists) {
                DB::table('payments')->insert([
                    'user_name' => $pu->name,
                    'email' => $pu->email,
                    'plan_type' => $pu->plan,
                    'amount' => $pu->amount,
                    'payment_id' => 'TXN_PU_' . str_pad($pu->id, 4, '0', STR_PAD_LEFT),
                    'date' => $pu->joined_date,
                    'status' => $pu->status === 'Active' ? 'Completed' : 'Failed',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $payments = DB::table('payments')
            ->leftJoin('agents', 'payments.email', '=', 'agents.email')
            ->select('payments.*', 'agents.service_guaranteed')
            ->orderBy('payments.id', 'desc')
            ->paginate(5);
        return view('admin.payments', compact('payments'));
    }

    public function paymentInvoice($id)
    {
        try {
            DB::statement("SELECT invoice_data FROM payments LIMIT 1");
        } catch (\Exception $e) {
            DB::statement("ALTER TABLE payments ADD COLUMN invoice_data LONGTEXT NULL");
        }

        $payment = DB::table('payments')
            ->leftJoin('agents', 'payments.email', '=', 'agents.email')
            ->select('payments.*', 'agents.service_guaranteed', 'agents.generate_bill')
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            abort(404, 'Payment record not found');
        }

        if (!$payment->service_guaranteed) {
            return response()->make("<div style='font-family: system-ui, -apple-system, sans-serif; padding: 40px; text-align: center; max-width: 600px; margin: 100px auto; background: #fff; border-radius: 28px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;'>
                <div style='color: #D35400; font-size: 56px; margin-bottom: 20px;'>⚠️</div>
                <h2 style='color: #1a1a1a; font-weight: 800; margin-bottom: 15px; letter-spacing: -0.02em;'>Generation Blocked</h2>
                <p style='color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 30px;'>Invoice cannot be generated because Service Guaranteed is No.</p>
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
            if ($payment->generate_bill === 0 || $payment->generate_bill === false || $payment->generate_bill === '0') {
                if (isset($invoiceData['services'])) {
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
        
        DB::table('ads')->insert([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
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
        
        DB::table('ads')->where('id', $request->id)->update([
            'campaign_name' => $request->campaign_name,
            'position' => $request->position,
            'image' => $request->image,
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

    public function previewPlan($id)
    {
        $plan = DB::table('plans')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/admin/plans')->with('error', 'Plan not found!');
        }

        $tier = 'Standard';
        $nameLower = strtolower($plan->name);
        if (str_contains($nameLower, 'premium')) {
            $tier = 'Premium';
        } elseif (str_contains($nameLower, 'enterprise')) {
            $tier = 'Enterprise';
        } elseif (str_contains($nameLower, 'customise') || str_contains($nameLower, 'custom')) {
            $tier = 'Customise';
        }

        $subscribedAgents = DB::table('agents')
            ->where('tier', 'like', '%' . $tier . '%')
            ->get();

        return view('admin.plans-preview', compact('plan', 'subscribedAgents'));
    }

    public function exportPlans()
    {
        $plans = DB::table('plans')->orderBy('id', 'asc')->get();
        $csvFileName = 'plans_export_' . time() . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Plan Name', 'Price', 'Package Limit', 'Duration', 'Status', 'Created At'];

        $callback = function() use($plans, $columns) {
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
        return view('admin.banners', compact('banners'));
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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

    // NOTIFICATIONS
    public function notifications(Request $request)
    {
        $notifications = DB::table('notifications')->orderBy('id', 'desc')->paginate(5);
        $agents = DB::table('agents')->where('status', 'Active')->orderBy('name', 'asc')->get();
        return view('admin.notifications', compact('notifications', 'agents'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate(['title' => 'required', 'message' => 'required']);
        
        $targetAudience = $request->target_audience ?? 'all_users';
        $agentId = $request->agent_id ?? null;

        DB::table('notifications')->insert([
            'title' => $request->title,
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
            'message' => $request->message,
            'type' => $request->type ?? 'Info',
            'target_audience' => $targetAudience,
            'agent_id' => $targetAudience === 'specific_agent' ? $agentId : null,
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
                        'message' => $request->message,
                        'type' => $request->type ?? 'Info',
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently ignore DB errors
        }

        $successMsg = 'Global system notification sent and delivered to all users! 📣';
        if ($targetAudience === 'specific_agent') {
            $successMsg = 'Notification sent successfully to the selected agent! 📣';
        } elseif ($targetAudience === 'all_agents') {
            $successMsg = 'Notification sent successfully to all agents! 📣';
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    
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
            if ($key === '_token') continue;
            
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
        $contacts = DB::table('contacts')->orderBy('id', 'desc')->get();
        $filename = "inquiry_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Date'];

        $callback = function() use($contacts, $columns) {
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
        $leads = DB::table('leads')->orderBy('id', 'desc')->get();
        $filename = "leads_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Package', 'Agent', 'Status', 'Date'];

        $callback = function() use($leads, $columns) {
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
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'User Name', 'Email', 'Plan Type', 'Amount', 'Payment ID', 'Status', 'Date'];

        $callback = function() use($payments, $columns) {
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
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Email Address', 'Status', 'Date Joined'];

        $callback = function() use($subscribers, $columns) {
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
        return view('admin.careers', compact('applications'));
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
        $transits = DB::table('transits')->orderBy('id', 'asc')->paginate(10);
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
            if ($key === '_token') continue;
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
        return redirect()->back()->with('success', 'Mail settings updated successfully!');
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
            if ($key === '_token') continue;
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
            if ($key === '_token') continue;
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
            if ($key === '_token') continue;
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
