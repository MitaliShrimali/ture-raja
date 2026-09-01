@php
    $itinerary = $pkg->itinerary ? (json_decode($pkg->itinerary, true) ?: []) : [];
    $sightseeingPills = [];
    if (!empty($pkg->sightseeing)) {
        $sightseeingData = is_string($pkg->sightseeing) ? json_decode($pkg->sightseeing, true) : $pkg->sightseeing;
        if (is_array($sightseeingData)) {
            if (isset($sightseeingData[0]) && is_array($sightseeingData[0]) && isset($sightseeingData[0]['location'])) {
                foreach ($sightseeingData as $item) {
                    $loc = trim($item['location'] ?? '');
                    $act = trim($item['activity'] ?? '');
                    if ($loc || $act) {
                        $text = '';
                        if ($loc && $act) {
                            $text = $loc . ' - ' . $act;
                        } else {
                            $text = $loc ?: $act;
                        }
                        $sightseeingPills[] = $text;
                    }
                }
            } else {
                foreach ($sightseeingData as $item) {
                    if (is_string($item) && trim($item)) {
                        $sightseeingPills[] = trim($item);
                    }
                }
            }
        } else {
            $sightseeingPills = array_filter(array_map('trim', explode(',', $pkg->sightseeing)));
        }
    }
    if (!empty($itinerary) && is_array($itinerary)) {
        foreach ($itinerary as $day) {
            $loc = trim($day['title'] ?? '');
            $act = trim($day['desc'] ?? '');
            if ($loc || $act) {
                if ($loc && $act) {
                    $sightseeingPills[] = $loc . ' - ' . $act;
                } else {
                    $sightseeingPills[] = $loc ?: $act;
                }
            }
        }
    }
    $sightseeingPills = array_unique($sightseeingPills);
@endphp
@extends('layouts.admin')

@section('admin_title', 'Package Details')
@section('content')
@php
    $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
    $agentName = $agentData['name'] ?? 'Unknown Agent';
    $dbAgent = \DB::table('agents')->where('name', $agentName)->first();
    $hotels = json_decode($pkg->hotels, true) ?: [];
    $transfers = json_decode($pkg->transfers, true) ?: [];
    if ($dbAgent && !empty($dbAgent->agency_name)) {
        $agentName = $dbAgent->agency_name;
    }
    $agentLogo = $agentData['logo'] ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);
    $agentPhone = $agentData['phone'] ?? '';

    $gallery = $pkg->gallery ? (json_decode($pkg->gallery, true) ?: []) : [];
    $included = $pkg->included ? (json_decode($pkg->included, true) ?: []) : [];
    $excluded = $pkg->excluded ? (json_decode($pkg->excluded, true) ?: []) : [];
    $itinerary = $pkg->itinerary ? (json_decode($pkg->itinerary, true) ?: []) : [];

    $statusColor = match($pkg->status) {
        'Active'   => 'bg-green-50 text-green-600 border-green-200',
        'Inactive' => 'bg-gray-50 text-gray-500 border-gray-200',
        'Pending'  => 'bg-orange-50 text-orange-600 border-orange-200',
        default    => 'bg-gray-50 text-gray-500 border-gray-200',
    };
@endphp

<div class="space-y-8 pb-12">
    {{-- Header + Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="p-2 rounded-xl bg-white border border-border-soft text-muted-text hover:text-primary transition-all">
                    <i data-lucide="arrow-left" size="18"></i>
                </a>
                <div>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest">Package Review</p>
                    <h2 class="font-black text-foreground tracking-tight">{{ $pkg->title }}</h2>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full border text-xs font-black uppercase tracking-wider {{ $statusColor }}">
                {{ $pkg->status }}
            </span>
            @if($pkg->status === 'Pending')
            <a href="{{ url('/admin/packages/approve/' . $pkg->id) }}"
               onclick="return confirm('Approve this package? It will go live on the customer site.')"
               class="flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-green-500/20">
                <i data-lucide="check-circle-2" size="18"></i> Approve Package
            </a>
            <a href="{{ url('/admin/packages/decline/' . $pkg->id) }}"
               onclick="return confirm('Decline this package?')"
               class="flex items-center gap-2 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-red-500/20">
                <i data-lucide="x-circle" size="18"></i> Decline
            </a>
            @else
            <a href="{{ url('/admin/packages/edit/' . $pkg->id) }}" class="flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20">
                <i data-lucide="edit-3" size="18"></i> Edit Package
            </a>
            <a href="{{ url('/admin/packages/toggle/' . $pkg->id) }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-border-soft text-muted-text hover:text-foreground rounded-2xl font-black text-sm transition-all">
                <i data-lucide="toggle-left" size="18"></i> Toggle Status
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- Left: Main Info --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Hero Image --}}
            <div class="bg-white rounded-[32px] overflow-hidden border border-border-soft shadow-soft">
                <div class="relative h-72 overflow-hidden">
                    <img src="{{ asset($pkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200') }}" 
                         alt="{{ $pkg->title }}" 
                         class="w-full h-full object-cover">
                    @if($pkg->badge)
                    <span class="absolute top-4 left-4 bg-primary text-white text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                        {{ $pkg->badge }}
                    </span>
                    @endif
                </div>

                {{-- Gallery --}}
                @if(count($gallery) > 0)
                <div class="p-6 border-t border-border-soft">
                    <p class="text-xs font-black text-muted-text uppercase tracking-widest mb-4">Gallery</p>
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($gallery as $img)
                        <div class="w-24 h-20 rounded-2xl overflow-hidden shrink-0 border border-gray-100">
                            <img src="{{ asset($img) }}" alt="Gallery" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Package Details --}}
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft space-y-6">
                <h3 class="text-lg font-black text-foreground">Package Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Duration</p>
                        <p class="text-sm font-bold text-foreground flex items-center gap-1">
                            <i data-lucide="clock" size="14" class="text-primary"></i>
                            {{ $pkg->duration ?? '—' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Departure City</p>
                        <p class="text-sm font-bold text-foreground flex items-center gap-1">
                            <i data-lucide="map-pin" size="14" class="text-primary"></i>
                            {{ !empty($pkg->departure_city) ? $pkg->departure_city : ($pkg->location ?? '—') }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Destination Type</p>
                        <p class="text-sm font-bold text-foreground">{{ ucfirst($pkg->category ?? '—') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Transit Type</p>
                        <p class="text-sm font-bold text-foreground flex items-center gap-1">
                            <i data-lucide="car" size="14" class="text-primary"></i>
                            {{ $pkg->group_size ?? '—' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Departure State</p>
                        <p class="text-sm font-bold text-foreground flex items-center gap-1">
                            {{ $pkg->departure_state ?? '—' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Departure Country</p>
                        <p class="text-sm font-bold text-foreground flex items-center gap-1">
                            {{ $pkg->departure_country ?? '—' }}
                        </p>
                    </div>
                </div>

                @php
                    $categoriesList = json_decode($pkg->categories_list, true) ?: [];
                    $keywords = !empty($pkg->keywords) ? explode(',', $pkg->keywords) : [];
                @endphp
                
                @if(count($categoriesList) > 0 || !empty($pkg->badge))
                <div class="grid grid-cols-1 gap-4 mt-6 pt-6 border-t border-border-soft">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-2">Categories & Tag</p>
                        <div class="flex flex-wrap gap-2">
                            @if(!empty($pkg->badge))
                                <span class="text-xs font-bold bg-primary text-white px-2 py-1 rounded-md">{{ $pkg->badge }}</span>
                            @endif
                            @foreach($categoriesList as $cat)
                                <span class="text-xs font-medium bg-orange-100 text-orange-800 px-2 py-1 rounded-md">{{ $cat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Inclusions & Exclusions --}}
            @if(count($included) > 0 || count($excluded) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if(count($included) > 0)
                <div class="bg-green-50 rounded-[32px] p-8 border border-green-100 shadow-soft">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            <i data-lucide="check-circle-2" size="16" class="text-green-600"></i>
                        </div>
                        <h4 class="text-sm font-black text-green-900">What's Included</h4>
                    </div>
                    <ul class="space-y-3">
                        @foreach($included as $item)
                        <li class="flex items-start gap-2 text-sm text-green-800 font-medium">
                            <i data-lucide="check" size="14" class="text-green-600 mt-0.5 shrink-0"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(count($excluded) > 0)
                <div class="bg-red-50 rounded-[32px] p-8 border border-red-100 shadow-soft">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            <i data-lucide="x-circle" size="16" class="text-red-600"></i>
                        </div>
                        <h4 class="text-sm font-black text-red-900">What's Excluded</h4>
                    </div>
                    <ul class="space-y-3">
                        @foreach($excluded as $item)
                        <li class="flex items-start gap-2 text-sm text-red-800 font-medium">
                            <i data-lucide="x" size="14" class="text-red-600 mt-0.5 shrink-0"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif

            
            {{-- Hotels & Transfers Detailed Sections --}}
            @if(!empty($hotels))
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                    <i class="fas fa-hotel text-primary mr-2" style="color: #e85d26;"></i> Hotels Included
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($hotels as $hotel)
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-bed text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-foreground">{{ $hotel['name'] ?? 'Hotel Name' }}</h4>
                            <p class="text-xs text-muted-text mt-1">{{ isset($hotel['room']) ? $hotel['room'] : (isset($hotel['category']) ? $hotel['category'] : 'Standard Room') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($transfers))
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                    <i class="fas fa-car text-primary mr-2" style="color: #e85d26;"></i> Transfers Included
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($transfers as $transfer)
                      <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                          <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: rgba(232, 93, 38, 0.1); color: #e85d26;">
                              <i class="fas fa-car text-lg"></i>
                          </div>
                          <div>
                              @php
                                  $transferText = is_array($transfer) ? ($transfer['name'] ?? $transfer['title'] ?? 'Transfer') : $transfer;
                              @endphp
                              <h4 class="font-bold text-sm text-foreground">{{ $transferText }}</h4>
                              <p class="text-xs text-muted-text mt-1">Transport included</p>
                          </div>
                      </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- About Tours --}}
            @if(!empty($pkg->about_tours))
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <h3 class="text-lg font-black text-foreground">About Tours</h3>
                </div>
                <div class="prose max-w-none text-sm text-gray-600 leading-relaxed mt-2">
                    {!! $pkg->about_tours !!}
                </div>
            </div>
            @endif

            {{-- Terms & Conditions --}}
            @if(!empty($pkg->terms))
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <!-- <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm">
                        <i data-lucide="file-text" size="16" class="text-gray-600"></i>
                    </div> -->
                    <h3 class="text-lg font-black text-foreground">Terms & Conditions</h3>
                </div>
                <div class="text-sm text-gray-600 leading-relaxed mt-2">
                    {!! nl2br(e($pkg->terms)) !!}
                </div>
            </div>
            @endif

            {{-- Itinerary Timeline --}}
            @if(!empty($pkg->editorial_itinerary))
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Itinerary</h3>
                <div class="prose max-w-none text-sm text-gray-600 leading-relaxed">
                    {!! $pkg->editorial_itinerary !!}
                </div>
            </div>
            @endif

            {{-- Sightseeing Details --}}
            @if(count($sightseeingPills) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($sightseeingPills as $place)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm font-semibold rounded-full shadow-sm hover:bg-orange-100 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> {{ $place }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right: Pricing + Agent --}}
        <div class="space-y-6">
            {{-- Pricing Card --}}
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft space-y-4">
                <h3 class="text-lg font-black text-foreground">Pricing</h3>
                <div class="space-y-2">
                    <p class="text-3xl font-black text-primary">{{ $pkg->currency ?? '₹' }}{{ number_format($pkg->price, 2) }}</p>
                    @if($pkg->old_price)
                    <p class="text-sm text-muted-text line-through font-medium">{{ $pkg->currency ?? '₹' }}{{ number_format($pkg->old_price, 2) }}</p>
                    <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-50 text-xs font-black text-primary border border-orange-100">
                        <i data-lucide="tag" size="12"></i>
                        Save {{ $pkg->currency ?? '₹' }}{{ number_format($pkg->old_price - $pkg->price, 2) }}
                    </div>
                    @endif
                </div>

                {{-- Action Buttons for Pending packages --}}
                @if($pkg->status === 'Pending')
                <div class="pt-4 border-t border-border-soft space-y-3">
                    <a href="{{ url('/admin/packages/approve/' . $pkg->id) }}"
                       onclick="return confirm('Approve this package? It will go live on the customer site.')"
                       class="w-full flex items-center justify-center gap-2 py-3.5 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-green-500/20">
                        <i data-lucide="check-circle-2" size="18"></i> Approve & Publish
                    </a>
                    <a href="{{ url('/admin/packages/decline/' . $pkg->id) }}"
                       onclick="return confirm('Decline this package?')"
                       class="w-full flex items-center justify-center gap-2 py-3.5 border-2 border-red-200 text-red-500 hover:bg-red-50 rounded-2xl font-black text-sm transition-all">
                        <i data-lucide="x-circle" size="18"></i> Decline Package
                    </a>
                </div>
                @endif
            </div>

            {{-- Agent Info --}}
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft space-y-4">
                <h3 class="text-sm font-black text-muted-text uppercase tracking-widest">Submitted By</h3>
                <div class="flex items-center gap-4">
                    <img src="{{ asset($agentLogo) }}" alt="{{ $agentName }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100 bg-gray-50">
                    <div>
                        <p class="text-sm font-black text-foreground">{{ $agentName }}</p>
                        @if($agentPhone)
                        <p class="text-xs text-muted-text font-medium">{{ $agentPhone }}</p>
                        @endif
                    </div>
                </div>
                @if($agentPhone)
                @php
                    $adminWhatsappTemplate = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'whatsapp_message_template')->value('value');
                    if ($adminWhatsappTemplate) {
                        $custName = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : '';
                        $agtName = $agentName ?? '';
                        $adminWhatsappTemplate = str_replace('{{CustomerName}}', $custName, $adminWhatsappTemplate);
                        $adminWhatsappTemplate = str_replace('{{AgentName}}', $agtName, $adminWhatsappTemplate);
                        $adminWhatsappTemplate = str_replace('{{BookingID}}', '', $adminWhatsappTemplate);
                        $adminWhatsappTemplate = str_replace('{{TourDate}}', '', $adminWhatsappTemplate);
                    }
                    $cleanPhone = preg_replace('/[^0-9]/', '', $agentPhone);
                    $whatsappUrl = $adminWhatsappTemplate 
                        ? "https://api.whatsapp.com/send?phone={$cleanPhone}&text=" . rawurlencode($adminWhatsappTemplate) . "&type=phone_number&app_absent=0"
                        : "https://api.whatsapp.com/send?phone={$cleanPhone}&type=phone_number&app_absent=0";
                @endphp
                <a href="{{ $whatsappUrl }}" target="_blank"
                   class="flex items-center gap-2 text-xs font-bold text-green-600 hover:text-green-700 transition-colors">
                    <i data-lucide="message-circle" size="14"></i> Contact via WhatsApp
                </a>
                @endif
            </div>

            {{-- Brochure --}}
            @if($pkg->brochure)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft">
                <h3 class="text-sm font-black text-muted-text uppercase tracking-widest mb-4">Brochure</h3>
                <a href="{{ $pkg->brochure }}" target="_blank" class="flex items-center gap-2 text-sm font-bold text-primary hover:underline">
                    <i data-lucide="file-text" size="16"></i> Download Brochure (PDF)
                </a>
            </div>
            @endif

            {{-- Meta --}}
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft space-y-4">
                <h3 class="text-sm font-black text-muted-text uppercase tracking-widest">Package Info</h3>
                @if(!empty($pkg->theme))
                    <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                        <span class="text-muted-text">Theme</span>
                        <span class="font-bold text-foreground">{{ $pkg->theme }}</span>
                    </div>
                @endif
                @if(!empty($pkg->holiday_type))
                    <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                        <span class="text-muted-text">Holiday Type</span>
                        <span class="font-bold text-foreground">{{ $pkg->holiday_type }}</span>
                    </div>
                @endif

                    @if(!empty($pkg->validity))
                    <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                        <span class="text-muted-text">Validity</span>
                        <span class="font-bold text-foreground">{{ $pkg->validity }}</span>
                    </div>
                    @endif
                    @if(!empty($pkg->sightseeing))
                    <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                        <span class="text-muted-text">Sightseeing</span>
                        <span class="font-bold text-foreground">{{ $pkg->sightseeing }}</span>
                    </div>
                    @endif
                    @php
                        $meals = json_decode($pkg->meals, true) ?: [];
                    @endphp
                    @if(!empty($meals))
                    <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                        <span class="text-muted-text">Meals</span>
                        <span class="font-bold text-foreground">{{ implode(', ', $meals) }}</span>
                    </div>
                    @endif
                    
                    
                <div class="flex justify-between text-xs font-bold pt-2">
                        <span class="text-muted-text">Package ID</span>
                        <span class="text-foreground">#{{ str_pad($pkg->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-muted-text">Created</span>
                        <span class="text-foreground">{{ $pkg->created_at ? \Carbon\Carbon::parse($pkg->created_at)->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-muted-text">Expiry Date</span>
                        <span class="text-foreground">{{ $pkg->expiry_date ? \Carbon\Carbon::parse($pkg->expiry_date)->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-muted-text">Status</span>
                        <span class="font-black {{ $pkg->status === 'Active' ? 'text-green-600' : ($pkg->status === 'Pending' ? 'text-orange-600' : 'text-gray-500') }}">{{ $pkg->status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
