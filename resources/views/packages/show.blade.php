@php
    if (!function_exists('parseTextItinerary')) {
        function parseTextItinerary($text) {
            if (empty($text)) return [];
            $text = str_replace("\r", "", $text);
            $parts = preg_split('/•\s*/u', $text);
            $days = [];
            foreach ($parts as $part) {
                $part = trim($part);
                if (empty($part)) continue;
                $lines = explode("\n", $part);
                $titleLine = trim($lines[0]);
                $descLines = array_slice($lines, 1);
                $desc = implode("\n", $descLines);
                $desc = trim($desc);
                
                if (preg_match('/Day\s+\d+/i', $titleLine) || preg_match('/Day\s+/i', $titleLine)) {
                    $days[] = [
                        'title' => $titleLine,
                        'desc' => $desc
                    ];
                } else {
                    if (count($days) > 0) {
                        $days[count($days) - 1]['desc'] .= "\n\n• " . $part;
                    }
                }
            }
            
            foreach ($days as &$day) {
                $desc = e($day['desc']);
                $desc = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $desc);
                $desc = preg_replace('/_(.*?)_/', '<em>$1</em>', $desc);
                $desc = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" target="_blank" class="text-orange-600 hover:underline font-bold">$1</a>', $desc);
                $desc = nl2br($desc);
                $day['desc_html'] = $desc;
            }
            return $days;
        }
    }
    
    $parsedItinerary = [];
    if (!empty($package['editorial_itinerary'])) {
        $parsedItinerary = parseTextItinerary($package['editorial_itinerary']);
    }
    
    // Combine both sources of sightseeing details
    $sightseeingPills = [];
    if (!empty($package['sightseeing'])) {
        $sightseeingPills = array_filter(array_map('trim', explode(',', $package['sightseeing'])));
    }
    if (!empty($package['itinerary']) && is_array($package['itinerary'])) {
        foreach ($package['itinerary'] as $day) {
            if (!empty($day['title'])) {
                $sightseeingPills[] = $day['title'];
            }
        }
    }
    $sightseeingPills = array_unique($sightseeingPills);

    $defaultGallery = [
        $package["image"] ?? "https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200",
        "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200",
        "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200",
        "https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200"
    ];
    $gallerySlides = [];
    if (!empty($package["gallery"])) {
        if (is_string($package["gallery"])) {
            $decoded = json_decode($package["gallery"], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $gallerySlides = $decoded;
            } else {
                // Fallback: try splitting by comma
                $gallerySlides = array_map('trim', explode(',', $package["gallery"]));
            }
        } elseif (is_array($package["gallery"])) {
            $gallerySlides = $package["gallery"];
        }
    }
    
    // Filter out invalid or short URLs
    $gallerySlides = array_filter($gallerySlides, function($item) {
        return is_string($item) && strlen($item) > 5;
    });

    if (empty($gallerySlides)) {
        $gallerySlides = $defaultGallery;
    }
    $gallerySlides = array_map('asset', array_values($gallerySlides)); // apply asset() and reindex for valid JSON array

    $navSections = [
        ['id' => 'overview', 'title' => 'Overview']
    ];
    if(count($parsedItinerary) > 0) $navSections[] = ['id' => 'itinerary', 'title' => 'Itinerary'];
    if(count($sightseeingPills) > 0) $navSections[] = ['id' => 'sightseeing', 'title' => 'Sightseeing'];
    if(!empty($package['hotels'])) $navSections[] = ['id' => 'hotels', 'title' => 'Hotels'];
    if(!empty($package['terms'])) $navSections[] = ['id' => 'terms', 'title' => 'Tour Info'];
    $navSections[] = ['id' => 'faq', 'title' => 'FAQ'];
@endphp
@extends('layouts.app')

@section('title', $package['title'] . ' — TourRaja')

@section('content')
<style>
    .detail-overview-text { word-break: normal; overflow-wrap: break-word; }
    .itinerary-line { min-height: 40px; }
    .view-gallery-btn {
        background-color: rgba(0, 0, 0, 0.8) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        transition: all 0.2s ease-in-out !important;
    }
    .view-gallery-btn:hover {
        background-color: #000000 !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
    }
    .package-detail-title {
        font-size: 30px !important;
        font-family: 'Outfit', sans-serif !important;
        line-height: 1.25 !important;
    }
    .section-heading {
        font-size: 30px !important;
        font-family: 'Outfit', sans-serif !important;
        line-height: 1.3 !important;
        font-weight: 900 !important;
        position: relative;
        padding-bottom: 8px;
        display: inline-block;
    }
    .section-heading::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #e85d26;
        border-radius: 2px;
    }
    .standard-body-text {
        font-size: 16px !important;
        line-height: 1.65 !important;
        color: #4b5563 !important; /* text-gray-600 */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    html {
        scroll-behavior: smooth;
    }
    #overview, #itinerary, #sightseeing, #hotels, #terms, #faq {
        scroll-margin-top: 120px;
    }
    
    .package-gallery-details-grid {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    .package-gallery-images-col {
        grid-column: span 1 / span 1 !important;
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        height: 240px !important;
        gap: 0.5rem !important;
    }
    .package-gallery-details-col {
        grid-column: span 1 / span 1 !important;
    }
    @media (min-width: 1024px) {
        .package-gallery-details-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
        .package-gallery-images-col {
            grid-column: span 3 / span 3 !important;
            height: 320px !important;
        }
        .package-gallery-details-col {
            grid-column: span 1 / span 1 !important;
        }
    }
    
    .package-meta-details-container {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 1rem !important;
        margin-bottom: 1.5rem !important;
    }
    @media (min-width: 1024px) {
        .package-meta-details-container {
            display: block !important;
        }
        .package-meta-details-container > div + div {
            margin-top: 1.5rem !important;
        }
    }

    /* IMMEDIATE NAVBAR ALIGNMENT */
    main {
        padding-top: 80px !important;
    }
    @media (min-width: 1024px) {
        main {
            padding-top: 90px !important;
        }
    }
</style>

<div class="bg-[#F8F9FA] min-h-screen pt-4 lg:pt-6" x-data='{ showBookingModal: false, slides: @json($gallerySlides), sections: @json($navSections) }'>
    {{-- Breadcrumb --}}
    <div>
        <div class="container-custom py-2">
            <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors">Tours</a>
                <span>/</span>
                <span class="text-gray-800">{{ $package['title'] }}</span>
            </nav>
        </div>
    </div>

    <div class="container-custom py-8">

        {{-- TWO-COLUMN LAYOUT --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- ── LEFT MAIN CONTENT (2/3 width) ───────────────────── --}}
            <div class="w-full lg:w-2/3 space-y-6">

                {{-- Page Title Area --}}
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                            ✓ Free Cancellation
                        </span>
                        @if(!empty($package['badge']))
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-bold">
                            {{ $package['badge'] }}
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-600 text-xs font-bold capitalize">
                            {{ $package['category'] }}
                        </span>
                    </div>
                    <h1 class="font-black text-gray-900 mb-3 package-detail-title">
                        {{ $package['title'] }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i < floor($package['rating']) ? '#f97316' : '#e5e7eb' }}" xmlns="http://www.w3.org/2000/svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                            <span class="font-bold text-gray-800 text-sm ml-1">{{ $package['rating'] }}</span>
                            <span class="text-gray-500 text-sm">({{ $package['reviews'] }} reviews)</span>
                        </div>
                        <span class="flex items-center gap-1 text-gray-500 text-sm">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            India
                        </span>
                    </div>
                </div>

                {{-- Image Grid Gallery (Contained) --}}
                @php
                    $defaultGallery = [
                        $package["image"] ?? "https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200",
                        "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200",
                        "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200",
                        "https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200"
                    ];
                    $gallerySlides = [];
                    if (!empty($package["gallery"])) {
                        if (is_string($package["gallery"])) {
                            $decoded = json_decode($package["gallery"], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $gallerySlides = $decoded;
                            } else {
                                $gallerySlides = array_map('trim', explode(',', $package["gallery"]));
                            }
                        } elseif (is_array($package["gallery"])) {
                            $gallerySlides = $package["gallery"];
                        }
                    }
                    
                    $gallerySlides = array_filter($gallerySlides, function($item) {
                        return is_string($item) && strlen($item) > 5;
                    });

                    if (empty($gallerySlides)) {
                        $gallerySlides = $defaultGallery;
                    }
                    $gallerySlides = array_values($gallerySlides);
                @endphp
                <div class="mb-6">
                    
                    @php
                        $durationStr = $package['duration'];
                        $days = 0;
                        $nights = 0;
                        if (preg_match('/(\d+)\s*d/i', $durationStr, $m)) {
                            $days = $m[1];
                        }
                        if (preg_match('/(\d+)\s*n/i', $durationStr, $m)) {
                            $nights = $m[1];
                        }
                        
                        if ($days > 0 && $nights > 0) {
                            $formattedDuration = $nights . ($nights == 1 ? ' NIGHT' : ' NIGHTS') . ' - ' . $days . ($days == 1 ? ' DAY' : ' DAYS');
                        } else {
                            $formattedDuration = strtoupper($durationStr);
                        }
                    @endphp

                    {{-- Gallery & Details Container --}}
                    <div class="package-gallery-details-grid">
                        {{-- Left Column: Images Grid (Takes 3/4 space on desktop) --}}
                        <div class="package-gallery-images-col relative w-full overflow-hidden">
                            {{-- Large Left Image --}}
                            <div class="col-span-2 h-full w-full relative cursor-pointer hover:opacity-95 transition overflow-hidden rounded-2xl" style="grid-column: span 2 / span 2;" @click="$dispatch('open-gallery', { slides: slides, title: '{{ addslashes($package['title']) }}' })">
                                <img :src="slides[0]" class="w-full h-full object-cover rounded-2xl" alt="Package Main Image">
                            </div>
                            
                            {{-- Right Stacked Images --}}
                            <div class="flex flex-col gap-2 h-full w-full justify-between">
                                <div class="relative h-[calc(50%-4px)] w-full overflow-hidden cursor-pointer hover:opacity-95 transition rounded-2xl" @click="$dispatch('open-gallery', { slides: slides, title: '{{ addslashes($package['title']) }}' })">
                                    <img :src="slides[1] || slides[0]" class="w-full h-full object-cover rounded-2xl">
                                </div>
                                <div class="relative h-[calc(50%-4px)] w-full overflow-hidden cursor-pointer hover:opacity-95 transition rounded-2xl" @click="$dispatch('open-gallery', { slides: slides, title: '{{ addslashes($package['title']) }}' })">
                                    <img :src="slides[2] || slides[1] || slides[0]" class="w-full h-full object-cover rounded-2xl">
                                    {{-- View Gallery Overlay --}}
                                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-white rounded-2xl">
                                        <span class="text-xs font-black uppercase tracking-widest leading-none">View</span>
                                        <span class="text-[9px] font-bold uppercase tracking-wider mt-1 opacity-80">Gallery</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Package Details (Duration, Group Size, Type, Inquiry Now) --}}
                        <div class="package-gallery-details-col flex flex-col justify-between py-1.5 space-y-6">
                            @php
                                $hasChef = false;
                                $hasManager = false;
                                $searchLists = [];
                                if (isset($package['included'])) {
                                    $searchLists[] = is_string($package['included']) ? json_decode($package['included'], true) : $package['included'];
                                }
                                if (isset($package['amenities'])) {
                                    $searchLists[] = is_string($package['amenities']) ? json_decode($package['amenities'], true) : $package['amenities'];
                                }
                                foreach ($searchLists as $list) {
                                    if (is_array($list)) {
                                        foreach ($list as $item) {
                                            if (str_contains(strtolower($item), 'chef')) $hasChef = true;
                                            if (str_contains(strtolower($item), 'manager')) $hasManager = true;
                                        }
                                    }
                                }
                            @endphp

                            <div class="package-meta-details-container">
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest" style="font-size: 11px; margin-bottom: 0.25rem; letter-spacing: 0.1em;">DURATION</p>
                                    <p class="font-extrabold text-gray-800 text-base" style="font-size: 16px; font-family: 'Outfit', sans-serif;">{{ $formattedDuration }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest" style="font-size: 11px; margin-bottom: 0.25rem; letter-spacing: 0.1em;">GROUP SIZE</p>
                                    <p class="font-extrabold text-gray-800 text-base uppercase" style="font-size: 16px; font-family: 'Outfit', sans-serif;">{{ $package['groupSize'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest" style="font-size: 11px; margin-bottom: 0.25rem; letter-spacing: 0.1em;">TYPE</p>
                                    <p class="font-extrabold text-gray-800 text-base uppercase" style="font-size: 16px; font-family: 'Outfit', sans-serif;">{{ $package['category'] }}</p>
                                </div>
                            </div>

                            @if($hasChef || $hasManager)
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 space-y-3">
                                <p class="text-[11px] font-black text-gray-500 uppercase tracking-widest" style="font-size: 11px; letter-spacing: 0.1em;">SERVICES PROVIDED</p>
                                <div class="flex flex-wrap gap-2">
                                    @if($hasChef)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                            <i data-lucide="utensils" class="w-4 h-4"></i> Private Chef Included
                                        </span>
                                    @endif
                                    @if($hasManager)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                                            <i data-lucide="user" class="w-4 h-4"></i> Tour Manager Included
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div>
                                <a href="#contact-form" onclick="event.preventDefault(); document.getElementById('contact-form').scrollIntoView({behavior:'smooth', block:'start'}); return false;" style="background-color: #e85d26; display: flex; align-items: center; justify-content: center;" class="block w-full py-3.5 text-white text-center font-black text-xs uppercase tracking-widest rounded-md hover:opacity-90 transition-opacity shadow-sm">
                                    Inquiry Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Navigation Tabs --}}
                <div class="relative flex items-center mb-6 group bg-[#F8F9FA]">
                    <!-- Left Arrow -->
                    <button type="button" class="absolute left-0 z-10 w-12 h-full flex items-center justify-start bg-gradient-to-r from-[#F8F9FA] via-[#F8F9FA] to-transparent text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex" onclick="document.getElementById('section-nav').scrollBy({left: -250, behavior: 'smooth'})">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>

                    <div id="section-nav" class="flex overflow-x-auto gap-3 py-1 px-1 md:px-8 hide-scrollbar scroll-smooth w-full">
                        <a href="#overview" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="map" class="w-4 h-4"></i> Overview
                        </a>
                        @if(count($parsedItinerary) > 0)
                        <a href="#itinerary" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="list" class="w-4 h-4"></i> Itinerary
                        </a>
                        @endif
                        @if(count($sightseeingPills) > 0)
                        <a href="#sightseeing" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="binoculars" class="w-4 h-4"></i> Sightseeing
                        </a>
                        @endif
                        <button type="button" @click="$dispatch('open-gallery', { slides: slides, title: '{{ addslashes($package['title']) }}' })" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="image" class="w-4 h-4"></i> Gallery
                        </button>
                        @if(!empty($package['hotels']))
                        <a href="#hotels" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="building" class="w-4 h-4"></i> Hotels
                        </a>
                        @endif
                        @if(!empty($package['terms']))
                        <a href="#terms" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Tour Info
                        </a>
                        @endif
                        <a href="#faq" class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                            <i data-lucide="help-circle" class="w-4 h-4"></i> FAQ
                        </a>
                    </div>

                    <!-- Right Arrow -->
                    <button type="button" class="absolute right-0 z-10 w-12 h-full flex items-center justify-end bg-gradient-to-l from-[#F8F9FA] via-[#F8F9FA] to-transparent text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex" onclick="document.getElementById('section-nav').scrollBy({left: 250, behavior: 'smooth'})">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>

                {{-- Document / Brochure Download Section --}}
                <div class="bg-white rounded-lg border border-gray-150 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm mb-6">
                    <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-md bg-orange-50 flex items-center justify-center shrink-0" style="color: #e85d26;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm sm:text-base leading-snug">Travel Guide &amp; Itinerary Brochure</h4>
                            <p class="text-[11px] sm:text-xs text-gray-400 font-semibold font-medium mt-0.5">Download the complete tour handbook in PDF format</p>
                        </div>
                    </div>
                    @if(!empty($package['brochure']))
                        <a href="{{ asset($package['brochure']) }}" target="_blank" style="background-color: #e85d26;" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3.5 text-white text-center flex items-center justify-center gap-2 hover:opacity-90 text-[10px] sm:text-[11px] font-black uppercase tracking-widest rounded-md transition-all shadow-sm hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            <span>Download PDF</span>
                        </a>
                    @else
                        <button disabled class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3.5 bg-gray-100 text-gray-400 text-[10px] sm:text-[11px] font-black uppercase tracking-widest rounded-md text-center cursor-not-allowed">
                            Not Available
                        </button>
                    @endif
                </div>



                {{-- Includes Tags & Properties --}}
                @php
                    $tags = [];
                    if (!empty($package['category'])) $tags[] = ucfirst($package['category']);
                    if (!empty($package['tour_type'])) $tags[] = $package['tour_type'];
                    if (!empty($package['theme'])) $tags[] = $package['theme'];
                    if (!empty($package['holiday_type'])) $tags[] = $package['holiday_type'];
                    if (!empty($package['city'])) $tags[] = 'City: ' . $package['city'];
                    if (!empty($package['activities'])) {
                        foreach ((array)$package['activities'] as $act) {
                            $tags[] = $act;
                        }
                    }
                    if (empty($tags)) {
                        $tags = ['Land Package']; // Fallback
                    }
                @endphp
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <span class="flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold text-gray-700 shadow-sm">
                            <svg width="13" height="13" fill="none" stroke="#f97316" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>

                {{-- Tour Overview & Editorial --}}
                <div id="overview" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
                    <h2 class="font-black text-gray-900 mb-4 section-heading">Tour Overview</h2>
                    <p class="standard-body-text detail-overview-text">{{ $package['overview'] }}</p>

                    

                    <h3 class="font-black text-gray-900 mt-6 mb-3 section-heading">Tour Highlights</h3>
                    <ul class="space-y-2">
                        @foreach($package['highlights'] as $hl)
                        <li class="flex items-start gap-2 standard-body-text">
                            <svg class="shrink-0 mt-1" width="14" height="14" fill="none" stroke="#f97316" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>{{ $hl }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Pricing & Dates --}}
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
                      <h2 class="font-black text-gray-900 section-heading" style="margin-bottom: 1.5rem;">Pricing</h2>
                      <div class="flex flex-col gap-2 mb-4">
                          <div class="flex items-end gap-2">
                              <div class="text-3xl font-black text-[#e85d26]">{{ $package['currency'] ?? '₹' }}{{ number_format((float)$package['price'], 2) }}</div>
                              <div class="text-sm font-semibold text-gray-500 mb-1">/ Per Person</div>
                          </div>
                          @if(!empty($package['oldPrice']))
                          <div class="text-lg font-bold text-gray-600 line-through">{{ $package['currency'] ?? '₹' }}{{ number_format((float)$package['oldPrice'], 2) }}</div>
                          
                          @php
                              $saveAmount = (float)$package['oldPrice'] - (float)$package['price'];
                          @endphp
                          @if($saveAmount > 0)
                          <div class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#e85d26] bg-[#e85d26]/10 text-[#e85d26] font-bold text-sm w-max">
                              <i data-lucide="tag" class="w-4 h-4"></i>
                              Save {{ $package['currency'] ?? '₹' }}{{ number_format($saveAmount, 2) }}
                          </div>
                          @endif
                          @endif
                      </div>
                  </div>

                {{-- Inclusions & Exclusions --}}
                @if(count($package['included'] ?? []) > 0 || count($package['excluded'] ?? []) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if(count($package['included'] ?? []) > 0)
                    <div class="bg-green-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-5">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                <i data-lucide="check-circle-2" size="16" class="text-green-600"></i>
                            </div>
                            <h4 class="text-base font-black text-green-900">What's Included</h4>
                        </div>
                        <ul class="space-y-3">
                            @foreach($package['included'] as $item)
                            <li class="flex items-start gap-2 text-sm text-green-800 font-medium leading-relaxed">
                                <i data-lucide="check" size="14" class="text-green-600 mt-0.5 shrink-0"></i>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(count($package['excluded'] ?? []) > 0)
                    <div class="bg-red-50 rounded-2xl p-6 border border-red-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-5">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                <i data-lucide="x-circle" size="16" class="text-red-600"></i>
                            </div>
                            <h4 class="text-base font-black text-red-900">What's Excluded</h4>
                        </div>
                        <ul class="space-y-3">
                            @foreach($package['excluded'] as $item)
                            <li class="flex items-start gap-2 text-sm text-red-800 font-medium leading-relaxed">
                                <i data-lucide="x" size="14" class="text-red-600 mt-0.5 shrink-0"></i>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Additional Package Details --}}
                @if(!empty($package['validity']) || !empty($package['sightseeing']) || !empty($package['hotels']) || !empty($package['amenities']) || !empty($package['meals']) || !empty($package['transfers']))
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 mb-8">
                    <h2 class="font-black text-gray-900 section-heading mb-6 text-xl">Additional Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        @if(!empty($package['validity']))
                        <div>
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="calendar" class="w-4 h-4 text-orange-500"></i> Package Validity</h4>
                            <p class="standard-body-text">{{ $package['validity'] }}</p>
                        </div>
                        @endif

                        @if(!empty($package['sightseeing']))
                        <div>
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="map" class="w-4 h-4 text-orange-500"></i> Sightseeing Details</h4>
                            <p class="standard-body-text">{{ $package['sightseeing'] }}</p>
                        </div>
                        @endif

                        @if(!empty($package['hotels']))
                        <div id="hotels" class="col-span-1 md:col-span-2">
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="building" class="w-4 h-4 text-orange-500"></i> Hotels</h4>
                            <div class="overflow-hidden rounded-xl border border-gray-200">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                                            <th class="p-3 font-bold border-r border-gray-200">Hotel Name</th>
                                            <th class="p-3 font-bold">Room Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($package['hotels'] as $hotel)
                                        <tr class="border-b border-gray-200 last:border-0 hover:bg-gray-50">
                                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">{{ $hotel['name'] ?? ($hotel['hotel'] ?? '-') }}</td>
                                            <td class="p-3 text-sm text-gray-800">{{ $hotel['room'] ?? ($hotel['category'] ?? '-') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @php
                            $filteredAmenities = [];
                            if (!empty($package['amenities'])) {
                                $filteredAmenities = array_filter($package['amenities'], function($am) {
                                    $lower = strtolower($am);
                                    return !str_contains($lower, 'chef') && !str_contains($lower, 'manager');
                                });
                            }
                        @endphp
                        @if(count($filteredAmenities) > 0)
                        <div>
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="check-circle" class="w-4 h-4 text-orange-500"></i> Essential Amenities</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($filteredAmenities as $am)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 text-xs font-bold border border-gray-200">
                                    {{ $am }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!empty($package['meals']))
                        <div>
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="utensils" class="w-4 h-4 text-orange-500"></i> Meals Included</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($package['meals'] as $meal)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 text-orange-700 text-xs font-bold border border-orange-200">
                                    {{ $meal }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!empty($package['transfers']))
                        <div>
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-3"><i data-lucide="car" class="w-4 h-4 text-orange-500"></i> Transfers</h4>
                            <ul class="space-y-2">
                                @foreach($package['transfers'] as $trans)
                                @php
                                    $transferText = is_array($trans) ? ($trans['name'] ?? $trans['title'] ?? 'Transfer') : $trans;
                                @endphp
                                <li class="standard-body-text flex items-start gap-2">
                                    <i data-lucide="check" class="w-4 h-4 text-orange-500 mt-1 shrink-0"></i>
                                    <span>{{ $transferText }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

                {{-- Itinerary Timeline --}}
                @if(count($parsedItinerary) > 0)
                <div id="itinerary" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-8 section-heading text-xl">Itinerary</h2>
                    <div class="relative pl-2">
                        @foreach($parsedItinerary as $idx => $day)
                        <div class="relative flex gap-6 pb-8 last:pb-2">
                            @if(!$loop->last)
                                <div class="absolute left-[11px] top-6 bottom-0" style="border-left: 2px dashed #e85d26 !important;"></div>
                            @endif
                            <div class="relative z-10 shrink-0">
                                @if($loop->first || $loop->last)
                                    <div class="w-6 h-6 rounded-full shadow-sm flex items-center justify-center text-[10px] font-bold text-white" style="background-color: #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.2);">{{ $idx + 1 }}</div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-[10px] font-bold" style="border: 4px solid #e85d26 !important; color: #e85d26; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.12);">{{ $idx + 1 }}</div>
                                @endif
                            </div>
                            <div class="-mt-1 flex-1">
                                <h4 class="font-black text-gray-800" style="font-family: 'Outfit', sans-serif; font-size: 16px;">{{ $day['title'] }}</h4>
                                @if(!empty($day['desc']))
                                    <p class="standard-body-text mt-2 max-w-3xl">{!! $day['desc_html'] !!}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Sightseeing Details --}}
                @if(count($sightseeingPills) > 0)
                <div id="sightseeing" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sightseeingPills as $place)
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F6F8FA] border border-gray-200 text-gray-700 text-sm font-semibold rounded-full shadow-sm hover:bg-gray-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e85d26]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $place }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Terms & Conditions --}}
                @if(!empty($package['terms']))
                <div id="terms" class="bg-[#F8F9FA] rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <!-- <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm shrink-0"> -->
                            <!-- <i data-lucide="file-text" size="20" class="text-gray-600"></i> -->
                        <!-- </div> -->
                        <h2 class="font-black text-gray-900 section-heading !mb-0 text-xl pb-2">Terms & Conditions</h2>
                    </div>
                    <div class="text-sm text-gray-600 leading-relaxed mt-4">
                        {!! nl2br(e($package['terms'])) !!}
                    </div>
                </div>
                @endif

                {{-- FAQ Section --}}
                <div id="faq" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8" x-data="{ activeFaq: 0 }">
                    <h2 class="font-black text-gray-900 mb-6 section-heading">FAQ</h2>
                    <div class="space-y-3">
                        @php
                            $faqs = [
                                [
                                    'q' => 'Can I get the refund?',
                                    'a' => 'For a full refund, cancel at least 24 hours in advance of the start date of the experience. Discover and book ' . $package['title'] . ' with ease.'
                                ],
                                [
                                    'q' => 'Can I change the travel date?',
                                    'a' => 'Yes, you can request date changes up to 72 hours before departure, subject to availability and the specific operator policy.'
                                ],
                                [
                                    'q' => 'When and where does the tour end?',
                                    'a' => 'The tour typically ends back at the original departure point or airport drop-off, as detailed in the Itinerary.'
                                ],
                                [
                                    'q' => 'Do you arrange airport transfers?',
                                    'a' => 'Yes, airport pickups and drop-offs are fully included as standard in this premium tour package.'
                                ]
                            ];
                        @endphp
                        
                        @foreach($faqs as $i => $faq)
                        <div class="border border-gray-100 rounded-md overflow-hidden transition-all duration-300" :class="activeFaq === {{ $i }} ? 'bg-[#F8F9FA]/50 border-gray-200' : 'bg-white hover:bg-gray-50/50'">
                            <button 
                                type="button" 
                                class="w-full flex items-center justify-between p-5 text-left transition-all duration-200 outline-none"
                                @click="activeFaq = (activeFaq === {{ $i }} ? null : {{ $i }})"
                            >
                                <span class="font-black text-gray-800 leading-tight" style="font-size: 16px;">{{ $faq['q'] }}</span>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300"
                                     :style="activeFaq === {{ $i }} ? 'background-color: #e85d26 !important; color: #ffffff !important;' : 'background-color: rgba(26,26,36,0.04) !important; color: #6b6b7a !important;'">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="activeFaq === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                            </button>
                            
                            <div 
                                x-show="activeFaq === {{ $i }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-5 pb-5 pt-0 standard-body-text border-t border-gray-100/50"
                                style="padding-top: 0.75rem;"
                            >
                                {{ $faq['a'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>{{-- end left col --}}

            {{-- ── RIGHT SIDEBAR (1/3 width) ──────────────────────── --}}
            <div class="w-full lg:w-1/3 shrink-0 space-y-5">

                {{-- Agent Card --}}
                @php
                    $agentDataRaw = $package['agent'] ?? null;
                    if (is_string($agentDataRaw)) {
                        $agentDataRaw = json_decode($agentDataRaw, true);
                    }
                    
                    // Base agent details from package if available
                    $agentId = $agentDataRaw['id'] ?? null;
                    $agentName = $agentDataRaw['name'] ?? null;
                    
                    $dbAgent = null;
                    if ($agentId) {
                        $dbAgent = \DB::table('agents')->where('id', $agentId)->first();
                    } elseif ($agentName) {
                        $dbAgent = \DB::table('agents')->where('name', $agentName)->orWhere('agency_name', $agentName)->first();
                    }

                    // Fill from DB, then from package JSON, then fallback
                    $agentName = $dbAgent->agency_name ?? $dbAgent->name ?? $agentDataRaw['name'] ?? 'Miths Holidays';
                    $agentId = $dbAgent->id ?? $agentDataRaw['id'] ?? 64;
                    $agentPhone = $dbAgent->phone ?? $agentDataRaw['phone'] ?? '+91 7383682183';
                    $agentEmail = $dbAgent->email ?? $agentDataRaw['email'] ?? 'mithstours@gmail.com';
                    $agentRegion = $dbAgent->address ?? $agentDataRaw['region'] ?? '101 GF Nr Trikon Bagh Rajkot - Gujarat';
                    
                    $rawLogo = $dbAgent->logo ?? $agentDataRaw['logo'] ?? null;
                    if ($rawLogo && !str_starts_with($rawLogo, 'http') && !str_starts_with($rawLogo, '//')) {
                        $rawLogo = asset(ltrim($rawLogo, '/'));
                    }
                    $agentLogo = $rawLogo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);

                    $serviceGuaranteed = $dbAgent->service_guaranteed ?? false;

                    $agentRedirectUrl = url('/listing/holiday-list?agent_id=' . $agentId);
                @endphp
                <div class="bg-white rounded-lg border border-gray-100 shadow-md p-6 space-y-4">
                    <h3 class="text-base font-black text-gray-900 flex items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="inline-block w-[6px] h-5 rounded" style="background-color: #e85d26;"></span>
                        Agent Information
                    </h3>
                    
                    {{-- Square logo container --}}
                    <div class="w-full bg-[#FAF0EC] rounded-lg p-6 flex items-center justify-center relative overflow-hidden" style="height: 180px;">
                        <img src="{{ asset($agentLogo) }}" alt="{{ $agentName }}" class="max-w-full max-h-full object-contain">
                    </div>

                    <div>
                        <p class="font-black text-gray-900 text-lg flex items-center gap-1.5" style="font-family: 'Outfit', sans-serif;">
                            {{ $agentName }}
                            @if($serviceGuaranteed)
                                <i data-lucide="check-circle" class="text-blue-500" size="20" title="Trusted Agent"></i>
                            @endif
                        </p>
                    </div>

                    <div class="space-y-3 text-sm text-gray-700" style="margin-top: 1rem; margin-bottom: 1rem;">
                        <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/></svg>
                            <span class="font-medium text-gray-800" style="font-size: 16px !important;">{{ $agentPhone }}</span>
                        </div>
                        <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span class="font-medium text-gray-800" style="font-size: 16px !important;">{{ $agentEmail }}</span>
                        </div>
                        <div class="flex items-start gap-2" style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <svg class="shrink-0 text-blue-500 mt-0.5" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="font-medium text-gray-800 leading-tight" style="font-size: 16px !important;">{{ $agentRegion }}</span>
                        </div>
                        <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span class="font-medium text-gray-800" style="font-size: 16px !important;">www.{{ strtolower(str_replace(' ', '', $agentName)) }}.com</span>
                        </div>
                        <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <span class="text-blue-500 font-bold text-base select-none" style="color: #3b82f6 !important; font-size: 20px; width: 20px; text-align: center;">G</span>
                            <span class="text-gray-800 font-medium" style="font-size: 16px !important;">Google Reviews <span class="text-yellow-500">★</span> 4.5</span>
                        </div>
                    </div>

                    {{-- Social Icons --}}
                    <div class="flex items-center gap-3 pt-1" style="display: flex; gap: 0.75rem; margin-top: 0.5rem; margin-bottom: 0.5rem;">
                        <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center text-white" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%); display: flex !important; width: 36px; height: 36px; border-radius: 9999px;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051C.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center text-white bg-[#1877F2]" style="display: flex !important; width: 36px; height: 36px; border-radius: 9999px; background-color: #1877F2 !important;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>

                    {{-- Action buttons --}}
                    <div class="grid grid-cols-2 gap-2 pt-1" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; margin-top: 1rem; margin-bottom: 1rem;">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agentPhone) }}" style="background-color: #25D366 !important; display: flex !important; align-items: center; justify-content: center; height: 40px; color: #ffffff !important;" class="text-white text-center text-xs font-black rounded-md hover:opacity-90 transition-opacity">Whatsapp</a>
                        <a href="mailto:{{ $agentEmail }}" style="background-color: #3B82F6 !important; display: flex !important; align-items: center; justify-content: center; height: 40px; color: #ffffff !important;" class="text-white text-center text-xs font-black rounded-md hover:opacity-90 transition-opacity">Email</a>
                        <a href="{{ $agentRedirectUrl }}" style="background-color: #e85d26 !important; display: flex !important; align-items: center; justify-content: center; height: 40px; color: #ffffff !important;" class="text-white text-center text-[10px] font-black rounded-md hover:opacity-90 transition-opacity uppercase px-1">View Our Packages</a>
                        <a href="{{ $agentRedirectUrl }}" style="background-color: #000000 !important; display: flex !important; align-items: center; justify-content: center; height: 40px; color: #ffffff !important;" class="text-white text-center text-[10px] font-black rounded-md hover:opacity-90 transition-opacity uppercase px-1">View My Profile</a>
                    </div>

                    {{-- Our Services --}}
                    <div class="border-t border-gray-150 pt-4 mt-2">
                        <h4 class="font-black text-gray-900 mb-3" style="font-family: 'Outfit', sans-serif; font-size: 18px !important;">Our Services</h4>
                        <div class="text-gray-700" style="display: flex; flex-direction: column; gap: 0.4rem;">
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">Flight Booking</span>
                            </div>
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 3v18M15 3v18M4 11h16M4 15h16"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">Train Booking</span>
                            </div>
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">Passport</span>
                            </div>
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">Visa</span>
                            </div>
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">International Courier</span>
                            </div>
                            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="shrink-0 text-blue-500" style="color: #3b82f6 !important;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                <span class="font-medium text-gray-800" style="font-size: 16px !important;">Domestic Tour</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Get in Touch --}}
                <div class="bg-white rounded-lg border border-gray-100 shadow-md p-6 space-y-5" id="contact-form">
                    <div style="margin-bottom: 0.5rem;">
                        <h3 class="font-black text-gray-900 section-heading" style="font-family: 'Outfit', sans-serif; font-size: 22px;">Get in touch</h3>
                        <p class="text-gray-500 mt-4" style="font-size: 14px;">We are here for you, how can we help?</p>
                    </div>

                    @if(session('success'))
                        <div class="p-3 bg-green-50 border border-green-100 rounded-xl text-green-700 font-bold text-xs flex items-center gap-2">
                            <i data-lucide="check-circle" size="16"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 font-bold text-xs flex items-center gap-2">
                            <i data-lucide="alert-circle" size="16"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-3">
                        @csrf
                        @php
                            $agentIdForForm = $agentData->id ?? null;
                            $agentNameForForm = $agentData->name ?? 'Tour Raja';
                        @endphp
                        <input type="hidden" name="agent_id" value="{{ $agentIdForForm }}">
                        <input type="hidden" name="subject" value="Inquiry for {{ $package['title'] }}">
                        <input type="hidden" name="agent_name" value="{{ $agentNameForForm }}">
                        <input type="hidden" name="package_name" value="{{ $package['title'] }}">
                        <input type="text" name="name" required placeholder="Enter your name"
                            class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <input type="email" name="email" required placeholder="Enter your email"
                            class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <input type="tel" name="phone" required placeholder="Enter your phone number"
                            class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <textarea name="message" required rows="3" placeholder="Go ahead, we are listening..."
                            class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400 resize-none"></textarea>
                        <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black text-sm rounded-md transition-colors duration-200 shadow-glow">
                            Submit Inquiry
                        </button>
                    </form>
                </div>

            </div>{{-- end right sidebar --}}
        </div>{{-- end grid --}}
    </div>

    <!-- Customer Reviews Section (Marquee Testimonials) -->
    <section class="py-16 lg:py-24 bg-white overflow-hidden border-t border-gray-100">
        <div class="container-custom">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-12 gap-6">
                <div class="space-y-2">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-foreground tracking-tight font-heading" style="font-family: 'Outfit', sans-serif;">What Our Clients Say!!!</h2>
                    <p class="text-text-muted text-base md:text-lg font-medium">They Love TourRaja!</p>
                </div>
                <div class="flex items-center gap-4 self-start md:self-auto">
                    <button id="prev-testi" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gray-100 flex items-center justify-center text-foreground hover:bg-primary hover:text-white transition-all duration-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button id="next-testi" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-primary flex items-center justify-center text-white shadow-glow hover:scale-110 transition-all duration-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <div class="relative group">
                <div class="flex gap-6 overflow-hidden py-8 testimonial-track" id="testi-slider">
                    @php
                        $testimonials = [
                            ['name' => 'Sara Mohamed', 'loc' => 'Jakarta', 'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips.", 'img' => 'https://i.pravatar.cc/150?u=sara', 'rating' => 5],
                            ['name' => 'Atend John', 'loc' => 'California', 'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips.", 'img' => 'https://i.pravatar.cc/150?u=john', 'rating' => 5],
                            ['name' => 'Sara Mohamed', 'loc' => 'Jakarta', 'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips.", 'img' => 'https://i.pravatar.cc/150?u=sara2', 'rating' => 3],
                            ['name' => 'Michael Chen', 'loc' => 'Singapore', 'text' => "Excellent service and direct agent contact saved me 30% on my last Bali trip.", 'img' => 'https://i.pravatar.cc/150?u=mike', 'rating' => 5],
                        ];
                        // Clone for seamless loop
                        $allTestimonials = array_merge($testimonials, $testimonials);
                    @endphp

                    @foreach($allTestimonials as $testi)
                        <div class="flex-shrink-0 w-full md:w-[450px] testimonial-card">
                            <div class="p-6 md:p-10 rounded-lg border border-gray-200 bg-white shadow-soft hover:shadow-premium transition-all duration-500 h-full flex flex-col space-y-4 md:space-y-6">
                                <h4 class="text-lg md:text-xl font-black text-foreground font-heading" style="font-family: 'Outfit', sans-serif;">The best booking system</h4>
                                <p class="text-text-muted text-xs md:text-sm leading-relaxed font-medium italic">"{{ $testi['text'] }}"</p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-100 mt-auto gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset($testi['img']) }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-primary/10">
                                        <div class="flex flex-col">
                                            <span class="font-black text-foreground text-sm md:text-base">{{ $testi['name'] }}</span>
                                            <span class="text-[10px] md:text-xs text-text-muted font-bold">{{ $testi['loc'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        @for($i=0; $i<5; $i++)
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i < $testi['rating'] ? '#f97316' : '#e5e7eb' }}" xmlns="http://www.w3.org/2000/svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <style>
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-450px * 4 - 24px * 4)); }
            }
            .testimonial-track {
                display: flex;
                width: max-content;
                animation: marquee 40s linear infinite;
            }
            .testimonial-track:hover {
                animation-play-state: paused;
            }
            @media (max-width: 767px) {
                .testimonial-card {
                    width: calc(100vw - 80px);
                }
                @keyframes marquee-mobile {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
                .testimonial-track {
                    animation: marquee-mobile 15s linear infinite;
                }
            }
        </style>
    </section>
    
    {{-- Booking Modal (Alpine JS wrapper needed at root of page) --}}
    <div x-show="showBookingModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showBookingModal = false"></div>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-2xl p-6 lg:p-8 animate-fade-in-up">
            <button @click="showBookingModal = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                <i data-lucide="x" size="18"></i>
            </button>
            <h2 class="text-2xl font-black mb-1">Book {{ $package['title'] }}</h2>
            <p class="text-gray-500 text-sm mb-6">Complete the form below to request a booking.</p>
            
            <form action="{{ route('package.book') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package['id'] ?? 0 }}">
                <input type="hidden" name="package_title" value="{{ $package['title'] ?? '' }}">
                <input type="hidden" name="package_image" value="{{ $package['image'] ?? '' }}">
                <input type="hidden" name="package_price" value="{{ $package['price'] ?? 0 }}">
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Traveler Name</label>
                    <input type="text" name="traveler_name" required class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Email</label>
                        <input type="email" name="traveler_email" required class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Phone</label>
                        <input type="text" name="traveler_phone" required class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Travel Date</label>
                        <input type="date" name="travel_date" required class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Guests</label>
                        <input type="number" name="guests" min="1" value="2" required class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Special Requests</label>
                    <textarea name="special_request" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"></textarea>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-hover text-white font-black rounded-md transition-all shadow-lg shadow-primary/30">
                        Confirm Booking Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
