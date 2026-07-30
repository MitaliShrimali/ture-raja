@extends('layouts.app')

@section('content')
    @php
        // Fetch transit music for the active tour_type filter
        $activeTourTypes = array_filter((array) request('tour_type'));
        $firstTourType = count($activeTourTypes) > 0 ? reset($activeTourTypes) : null;
        
        $transitMusic = null;
        if ($firstTourType) {
            try {
                $transitMusic = DB::table('transit_music')
                    ->where('transit_name', $firstTourType)
                    ->where('status', 'Active')
                    ->first();
            } catch (\Exception $e) {
                $transitMusic = null;
            }
        }
    @endphp

    {{-- Transit Music Player — same floating icon style as home page hero --}}
    <div id="transit-music-container">
        @if($transitMusic)
            <audio id="transitBgMusic" src="{{ asset($transitMusic->music_file) }}" loop></audio>

            {{-- Floating circular button: bottom-right, raised to avoid overlap with scroll-up arrow --}}
            <div class="fixed select-none" style="z-index: 9999; bottom: 90px; right: 20px;">

                <button type="button" onclick="toggleTransitSound()" id="transitSoundToggle"
                    class="w-8 h-8 rounded-full bg-[#e85d26] hover:bg-orange-600 flex items-center justify-center text-white transition-all shadow-md focus:outline-none border-[1.5px] border-white"
                    style="box-shadow: 0 0 10px rgba(232, 93, 38, 0.4);"
                    title="{{ $transitMusic->music_name }} — {{ $firstTourType }}">
                    {{-- Music On Icon --}}
                    <svg id="transitMusicOnIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 text-white">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                    {{-- Music Off Icon (hidden by default) --}}
                    <svg id="transitMusicOffIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 text-white hidden">
                        <line x1="2" y1="2" x2="22" y2="22"></line>
                        <path d="M9 13V5l12-2v9"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <style>
        /* MOBILE LIST VIEW FIXES */
        @media (max-width: 767px) {
            body .list-view-wrapper .package-card-inner {
                flex-direction: row !important;
                display: flex !important;
                height: auto !important;
                min-height: 150px !important;
                padding: 8px !important;
                align-items: center !important;
                gap: 0 !important;
                background: white !important;
            }

            body .list-view-wrapper .package-image-container {
                width: 120px !important;
                height: 120px !important;
                flex-shrink: 0 !important;
                aspect-ratio: 1/1 !important;
                margin: 0 !important;
                border-radius: 12px !important;
                overflow: hidden !important;
            }

            body .list-view-wrapper .package-content {
                padding: 10px 10px 10px 12px !important;
                flex-grow: 1 !important;
                flex-direction: column !important;
                justify-content: center !important;
                display: flex !important;
                height: auto !important;
                gap: 4px !important;
            }

            body .list-view-wrapper .package-content h3 {
                font-size: 0.85rem !important;
                line-height: 1.2 !important;
                min-height: 0 !important;
                margin-bottom: 2px !important;
                display: -webkit-box !important;
                -webkit-line-clamp: 2 !important;
                -webkit-box-orient: vertical !important;
                overflow: hidden !important;
            }

            body .list-view-wrapper .package-action {
                border-top: none !important;
                padding-top: 0 !important;
                margin-top: 4px !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                display: flex !important;
            }

            body .list-view-wrapper .package-action span.text-2xl {
                font-size: 1.1rem !important;
            }

            body .list-view-wrapper .package-action a {
                padding: 4px 12px !important;
                font-size: 10px !important;
                width: auto !important;
            }

            body .list-view-wrapper .package-info,
            body .list-view-wrapper .package-content .flex.items-start.justify-between>div:last-child {
                display: none !important;
            }

            body .list-view-wrapper .package-image-container .absolute.top-4 {
                top: 6px !important;
            }

            body .list-view-wrapper .package-image-container .absolute.left-4 {
                left: 6px !important;
            }

            body .list-view-wrapper .package-image-container .absolute.right-4 {
                right: 6px !important;
            }

            body .list-view-wrapper .package-image-container span.px-4 {
                padding: 2px 6px !important;
                font-size: 7px !important;
            }

            body .list-view-wrapper .wishlist-btn {
                width: 26px !important;
                height: 26px !important;
            }

            body .list-view-wrapper .wishlist-btn svg {
                width: 12px !important;
                height: 12px !important;
            }
        }

        /* DESKTOP LIST VIEW RESTORATION */
        @media (min-width: 768px) {
            body .list-view-wrapper .package-card-inner {
                flex-direction: row !important;
                display: flex !important;
                height: 180px !important;
                padding: 0.5rem !important;
                max-width: 100% !important;
            }

            body .list-view-wrapper .package-image-container {
                width: 250px !important;
                height: 100% !important;
                margin: 0 !important;
                aspect-ratio: auto !important;
                flex-shrink: 0 !important;
            }

            body .list-view-wrapper .package-content {
                display: grid !important;
                grid-template-columns: 2.1fr 1.2fr !important;
                grid-template-rows: auto auto auto !important;
                row-gap: 0.5rem !important;
                column-gap: 1.5rem !important;
                align-items: center !important;
                flex-grow: 1 !important;
                padding: 0.85rem 1.5rem !important;
            }

            /* Row 1: Title (left) + Rating (right) */
            body .list-view-wrapper .package-content>div:nth-child(1) {
                grid-column: 1 !important;
                grid-row: 1 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 1rem !important;
            }

            /* Row 2: Duration + Tour Type */
            body .list-view-wrapper .package-content>div:nth-child(2) {
                grid-column: 1 !important;
                grid-row: 2 !important;
                display: flex !important;
                gap: 0.5rem !important;
                margin-bottom: 0 !important;
                width: auto !important;
            }

            /* Row 3: Category + Theme */
            body .list-view-wrapper .package-content>div:nth-child(3) {
                grid-column: 1 !important;
                grid-row: 3 !important;
                display: flex !important;
                gap: 0.5rem !important;
                margin-top: 0 !important;
                width: auto !important;
            }

            /* Row 4: Agent Info (Top Right spanning row 1 & 2) */
            body .list-view-wrapper .package-content>div:nth-child(4) {
                grid-column: 2 !important;
                grid-row: 1 / span 2 !important;
                border-left: 1px dashed #e5e7eb !important;
                padding-left: 1.75rem !important;
                height: 100% !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Row 5: Action - Price (left) + Button (right) */
            body .list-view-wrapper .package-content>div:nth-child(5) {
                grid-column: 2 !important;
                grid-row: 3 !important;
                border-left: 1px dashed #e5e7eb !important;
                padding-left: 1.75rem !important;
                border-top: none !important;
                margin-top: 0 !important;
                padding-top: 0 !important;
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                height: 100% !important;
                gap: 0.75rem !important;
            }

            body .list-view-wrapper .package-content>div:nth-child(5) span.text-2xl {
                font-size: 1.15rem !important;
            }

            body .list-view-wrapper .package-content>*+* {
                margin-top: 0 !important;
            }
        }

        /* IMMEDIATE SEARCHBAR ALIGNMENT */
        main {
            padding-top: 80px !important;
        }

        @media (min-width: 1024px) {
            main {
                padding-top: 90px !important;
            }
        }
    </style>

    <!-- Header search bar removed -->

    <style>
        @media (min-width: 1024px) {

            /* Disable page-level scrolling on desktop */
            html,
            body {
                height: 100vh !important;
                overflow: hidden !important;
            }

            /* Container takes exact remaining height below the header */
            .container-custom.pt-8.pb-16 {
                height: calc(100vh - 90px) !important;
                overflow: hidden !important;
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            #filter-form {
                height: 100% !important;
                overflow: hidden !important;
                align-items: stretch !important;
            }

            /* Sticky / Fixed Sidebar on Left */
            .desktop-sidebar-reset {
                position: relative !important;
                top: 0 !important;
                height: 100% !important;
                overflow-y: auto !important;
                background: transparent !important;
                background-image: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                transform: none !important;
                z-index: 10 !important;
                width: 25% !important;
                flex-shrink: 0 !important;
                scrollbar-width: none;
                /* Hide scrollbar for sidebar */
            }

            .desktop-sidebar-reset::-webkit-scrollbar {
                display: none;
            }

            /* Scrollable Packages Grid on Right */
            #filter-form>.flex-1 {
                height: 100% !important;
                overflow-y: auto !important;
                padding-right: 0.75rem !important;
                padding-bottom: 4rem !important;
            }
        }
    </style>

    <div class="container-custom pt-8 pb-16"
        x-data="{ viewStyle: {{ isset($agent) ? "'grid'" : "localStorage.getItem('tourraja_view_style') || 'grid'" }}, mobileFiltersOpen: false, stateModalOpen: false, expandedStates: {} }"
        x-init="$watch('viewStyle', value => { localStorage.setItem('tourraja_view_style', value); $nextTick(() => lucide.createIcons()) }); $watch('mobileFiltersOpen', value => { if (value) { document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); } })">

        <form id="filter-form" action="{{ url('/listing') }}" method="GET"
            class="flex flex-col lg:flex-row gap-12 w-full items-start">
            <!-- Sidebar Wrapper (Responsive: Slide-over Drawer on Mobile, Sticky Column on Desktop) -->
            <div :class="mobileFiltersOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="desktop-sidebar-reset fixed inset-y-0 left-0 w-[85vw] sm:w-[400px] lg:w-1/4 lg:sticky lg:top-[90px] lg:max-h-[calc(100vh-100px)] bg-white lg:bg-transparent z-50 lg:z-0 shadow-2xl lg:shadow-none p-6 lg:p-0 transition-transform duration-300 overflow-y-auto shrink-0">
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between lg:hidden border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-sm font-black">Filters</h3>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="clearAllFilters()"
                            class="text-xs font-black text-primary hover:text-primary-hover bg-primary/10 px-3 py-1.5 rounded-full transition-colors">
                            Clear All
                        </button>
                        <button type="button" @click="mobileFiltersOpen = false"
                            class="text-gray-400 hover:text-primary transition-colors">
                            <i data-lucide="x" size="24"></i>
                        </button>
                    </div>
                </div>

                <x-filter-sidebar :filter-counts="$filterCounts" />



                <div class="mt-8 lg:hidden">
                    <button type="submit" @click="mobileFiltersOpen = false"
                        class="w-full bg-primary hover:bg-primary-hover text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-primary/20">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Dark Overlay for Mobile Drawer -->
            <div x-show="mobileFiltersOpen" x-transition:opacity @click="mobileFiltersOpen = false"
                class="lg:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40" style="display: none;"></div>

            <!-- Main Content -->
            <div class="flex-1 space-y-8">
                @if(isset($agent))
                    <div
                        class="bg-gradient-to-r from-orange-600 via-primary to-orange-500 rounded-[40px] p-8 md:p-10 shadow-premium border border-primary/20 text-white flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden animate-in fade-in slide-in-from-top-6 duration-500">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -ml-20 -mb-20"></div>

                        <div
                            class="flex flex-col md:flex-row items-center gap-6 md:gap-8 relative z-10 text-center md:text-left">
                            <div
                                class="w-24 h-24 rounded-[32px] bg-white p-1.5 shadow-xl shrink-0 flex items-center justify-center">
                                @php
                                    $agentInit = strtoupper(substr($agent->name ?? 'A', 0, 1));
                                    $agentLogoUrl = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agent->name ?? 'Agent');
                                @endphp
                                <img src="{{ asset($agentLogoUrl) }}" alt="{{ $agent->name }}"
                                    class="w-full h-full object-cover rounded-[24px]">
                            </div>
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                                    <span
                                        class="px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10">
                                        {{ $agent->tier ?? 'Premium' }} Partner
                                    </span>
                                    <span
                                        class="px-3.5 py-1 bg-green-500/30 backdrop-blur-md rounded-full text-[10px] font-black text-green-200 uppercase tracking-widest border border-green-500/20 flex items-center gap-1">
                                        <i data-lucide="verified" size="12"></i> Verified Operator
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black tracking-tight"
                                    style="font-family: 'Syne', sans-serif;">{{ $agent->name }}</h2>
                                <div
                                    class="flex flex-wrap items-center justify-center md:justify-start gap-x-6 gap-y-2 text-white/80 text-xs font-medium">
                                    @if($agent->phone)
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="phone" size="14"></i>
                                            <span>{{ $agent->phone }}</span>
                                        </div>
                                    @endif
                                    @if($agent->email)
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="mail" size="14"></i>
                                            <span>{{ $agent->email }}</span>
                                        </div>
                                    @endif
                                    @if($agent->region)
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="map-pin" size="14"></i>
                                            <span>{{ $agent->region }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 relative z-10 flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agent->phone ?? '') }}" target="_blank"
                                class="w-full sm:w-auto px-6 py-4 bg-green-500 hover:bg-green-600 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-green-500/20 text-center flex items-center justify-center gap-2">
                                <i data-lucide="message-circle" size="16"></i> Chat on WhatsApp
                            </a>
                            <a href="mailto:{{ $agent->email }}"
                                class="w-full sm:w-auto px-6 py-4 bg-white text-primary hover:bg-gray-50 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg text-center flex items-center justify-center gap-2">
                                <i data-lucide="mail" size="16"></i> Email Agent
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Mobile Search Input (Visible only on mobile/tablet) -->
                <div class="relative group lg:hidden w-full">
                    <input type="text" name="mobile_search" placeholder="Search destination or package..."
                        value="{{ request('search') ?: request('mobile_search') }}"
                        class="w-full bg-white border border-gray-100 rounded-2xl py-4 pl-12 pr-4 shadow-soft focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all font-bold text-foreground placeholder:text-muted-text/40 text-sm">
                    <i data-lucide="search"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors"
                        size="18"></i>
                </div>
                <!-- Top Bar -->
                <div
                    class="bg-white rounded-lg py-3 px-5 shadow-soft flex flex-col md:flex-row items-center md:items-center justify-between gap-4">
                    <div class="text-center md:text-left flex items-center flex-wrap gap-3">
                        <!-- Dropdown Pill replacing 'Packages' text -->
                        <button type="button" @click="stateModalOpen = true"
                            class="flex items-center justify-between min-w-[90px] px-4 py-2 bg-white border border-[#e85d26] rounded-md text-base font-black text-foreground transition-all shadow-sm shrink-0 hover:bg-orange-50">
                            <span>
                                (<span id="results-count">{{ $packages->count() }}</span>)
                            </span>
                            <i data-lucide="chevron-down" size="18" class="text-[#e85d26] ml-3"></i>
                        </button>
                    </div>

                    <div
                        class="flex flex-row items-center justify-between gap-3 w-full md:w-auto border-t border-gray-50 pt-5 md:border-none md:pt-0">
                        <!-- Filters Trigger (Mobile only, static/non-floating) -->
                        <button type="button" @click="mobileFiltersOpen = true"
                            class="lg:hidden flex items-center gap-2 bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2.5 rounded-2xl text-[12px] font-black transition-all shrink-0">
                            <i data-lucide="sliders-horizontal" size="14"></i>
                            Filters
                        </button>

                        <!-- Sort Dropdown: Constrained on mobile -->
                        <div class="relative mobile-sort-select-wrapper w-full md:w-48 flex-1 md:flex-none">
                            <select name="sort"
                                class="w-full bg-white border border-[#e85d26] rounded-md py-2 pl-4 pr-10 text-sm font-semibold focus:outline-none appearance-none cursor-pointer hover:bg-orange-50 transition-all text-[#e85d26]">
                                <option value="Sort by" {{ !request('sort') || request('sort') == 'Sort by' ? 'selected' : '' }}>Sort By</option>
                                <option value="Guaranteed Service" {{ strtolower(request('sort')) == 'guaranteed service' || request('sort') == 'Recommended' || request('sort') == 'GUARANTEED SERVICE' ? 'selected' : '' }}>Guaranteed Service</option>
                                <option value="Price (Low to High)" {{ strtolower(request('sort')) == 'price (low to high)' || request('sort') == 'PRICE (LOW TO HIGH)' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="Price (High to Low)" {{ strtolower(request('sort')) == 'price (high to low)' || request('sort') == 'PRICE (HIGH TO LOW)' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="Duration (Low to High)" {{ strtolower(request('sort')) == 'duration (low to high)' || request('sort') == 'DURATION (LOW TO HIGH)' ? 'selected' : '' }}>Duration (Low to High)</option>
                                <option value="Duration (High to Low)" {{ strtolower(request('sort')) == 'duration (high to low)' || request('sort') == 'DURATION (HIGH TO LOW)' ? 'selected' : '' }}>Duration (High to Low)</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#e85d26] pointer-events-none"
                                size="18"></i>
                        </div>

                        <!-- View Toggle Buttons -->
                        @if(!isset($agent))
                            <div class="flex items-center gap-1 shrink-0 ml-2">
                                <button @click="viewStyle = 'grid'" type="button"
                                    :class="viewStyle === 'grid' ? 'text-[#e85d26]' : 'text-gray-400 hover:text-[#e85d26] transition-colors'"
                                    class="p-1.5 transition-all duration-300">
                                    <i data-lucide="layout-grid" size="24"></i>
                                </button>
                                <button @click="viewStyle = 'list'" type="button"
                                    :class="viewStyle === 'list' ? 'text-[#e85d26]' : 'text-gray-400 hover:text-[#e85d26] transition-colors'"
                                    class="p-1.5 transition-all duration-300">
                                    <i data-lucide="list" size="24"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Grid -->
                <div id="packages-list-container"
                    :class="viewStyle === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8' : 'flex flex-col gap-6'">
                    @php $adIndex = 0; @endphp
                    @forelse($packages as $index => $pkg)
                        <div class="package-item {{ $index >= 25 ? 'hidden' : '' }}"
                            :class="viewStyle === 'list' ? 'list-view-wrapper' : ''">
                            <x-package-card :pkg="$pkg" />
                        </div>

                        {{-- 6-3-3 Pattern (6, 3, 3, 6, 3, 3...) for Ads --}}
                        @php
                            $showAd = false;
                            $localIndex = $index % 12;
                            if ($localIndex == 5 || $localIndex == 8 || $localIndex == 11) {
                                $showAd = true;
                            }
                        @endphp

                        @if($showAd && isset($sidebarAds) && $sidebarAds->count() > $adIndex)
                            @php
                                $inlineAd = $sidebarAds[$adIndex];
                                $adIndex++;
                            @endphp
                            @if(!empty($inlineAd->image))
                                <div class="col-span-full package-item {{ $index >= 25 ? 'hidden' : '' }}">
                                    <div
                                        class="w-full rounded-2xl overflow-hidden shadow-sm relative group transition-all flex justify-center items-center bg-transparent">
                                        <a href="{{ route('ad.click', $inlineAd->id) }}" target="_blank" class="block w-full relative">
                                            <img src="{{ asset($inlineAd->image) }}" alt="{{ $inlineAd->campaign_name }}"
                                                class="w-full h-auto object-contain rounded-2xl">
                                            <span
                                                class="absolute top-2 left-2 bg-black/60 text-white font-extrabold uppercase text-[10px] tracking-widest px-2 py-1 rounded-md backdrop-blur-xs">AD</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif

                    @empty
                        <div class="col-span-full py-20 text-center space-y-4">
                            <h4 class="text-2xl font-bold text-gray-800">No packages found
                                {{ request('search') ? "for '" . e(request('search')) . "'" : '' }}</h4>
                            <p class="text-gray-500">We couldn't find any packages matching your exact search.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Load More Section -->
                <div id="load-more-section">
                    @if($packages->count() > 25)
                        <div class="text-center pt-10" id="loadMoreContainer">
                            <button type="button" onclick="loadMorePackages()"
                                class="bg-white hover:bg-gray-50 text-foreground px-10 py-5 rounded-full font-bold shadow-soft transition-all border border-gray-100">
                                Load More Results
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Suggested Packages Section -->
                <div id="suggested-packages-section">
                    @if(isset($suggestedPackages) && $suggestedPackages->count() > 0)
                        <div class="mt-16 border-t border-gray-100 pt-12">
                            <div class="flex flex-col gap-2 mb-8">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-2xl md:text-3xl font-black text-foreground">Suggested Packages</h3>
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">Premium &
                                        Guaranteed</span>
                                </div>
                                <p class="text-gray-500 text-sm">We couldn't find many exact matches, so here are some highly
                                    recommended tours you might love.</p>
                            </div>

                            <div
                                :class="viewStyle === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8' : 'flex flex-col gap-6'">
                                @foreach($suggestedPackages as $pkg)
                                    <div :class="viewStyle === 'list' ? 'list-view-wrapper' : ''">
                                        <x-package-card :pkg="$pkg" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal placeholder (moved to root for relative viewport alignment) -->
        </form>
    </div>
    @push('scripts')
        <script>
            window.updateTransitSoundState = function(isPlaying) {
                const toggle = document.getElementById('transitSoundToggle');
                const onIcon = document.getElementById('transitMusicOnIcon');
                const offIcon = document.getElementById('transitMusicOffIcon');
                if (!toggle) return;
                if (isPlaying) {
                    toggle.style.backgroundColor = '#e85d26';
                    if (onIcon) onIcon.classList.remove('hidden');
                    if (offIcon) offIcon.classList.add('hidden');
                } else {
                    toggle.style.backgroundColor = '#4b5563';
                    if (onIcon) onIcon.classList.add('hidden');
                    if (offIcon) offIcon.classList.remove('hidden');
                }
            };

            window.toggleTransitSound = function() {
                const audio = document.getElementById('transitBgMusic');
                if (!audio) return;
                if (audio.paused) {
                    audio.play().then(() => {
                        window.updateTransitSoundState(true);
                        sessionStorage.setItem('transitMusicState', 'enabled');
                    }).catch(e => console.log('Audio play failed:', e));
                } else {
                    audio.pause();
                    window.updateTransitSoundState(false);
                    sessionStorage.setItem('transitMusicState', 'disabled');
                }
            };

            window.initTransitMusic = function() {
                const audio = document.getElementById('transitBgMusic');
                if (!audio) return;
                audio.volume = 0.5;

                const state = sessionStorage.getItem('transitMusicState');

                if (state === 'disabled') {
                    audio.pause();
                    window.updateTransitSoundState(false);
                } else if (state === 'enabled') {
                    audio.play().then(() => {
                        window.updateTransitSoundState(true);
                    }).catch(() => {
                        window.updateTransitSoundState(false);
                    });
                } else {
                    // First visit — try autoplay, fallback to first click
                    const startPlay = () => {
                        if (sessionStorage.getItem('transitMusicState') === 'disabled') return;
                        audio.play()
                            .then(() => {
                                window.updateTransitSoundState(true);
                                document.removeEventListener('click', startPlay);
                                document.removeEventListener('keydown', startPlay);
                                if (!sessionStorage.getItem('transitMusicState')) {
                                    sessionStorage.setItem('transitMusicState', 'played');
                                }
                            })
                            .catch(e => {
                                console.log('Autoplay blocked, waiting for interaction...', e);
                            });
                    };
                    startPlay();
                    document.addEventListener('click', startPlay);
                    document.addEventListener('keydown', startPlay);
                }
            };
            document.addEventListener('DOMContentLoaded', window.initTransitMusic);

            function loadMorePackages() {
                const hiddenItems = document.querySelectorAll('.package-item.hidden');
                for (let i = 0; i < 25 && i < hiddenItems.length; i++) {
                    hiddenItems[i].classList.remove('hidden');
                }
                if (document.querySelectorAll('.package-item.hidden').length === 0) {
                    const btn = document.getElementById('loadMoreContainer');
                    if (btn) btn.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('filter-form');
                if (!form) return;

                const handleFilterUpdate = (e) => {
                    if (e) e.preventDefault();

                    const listContainer = document.getElementById('packages-list-container');
                    if (listContainer) {
                        listContainer.style.opacity = '0.4';
                        listContainer.style.transition = 'opacity 0.2s';
                    }

                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);

                    fetch(`${form.action}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            // Swap count
                            const newCount = doc.getElementById('results-count');
                            const oldCount = document.getElementById('results-count');
                            if (newCount && oldCount) oldCount.innerHTML = newCount.innerHTML;

                            // Swap packages
                            const newPackages = doc.getElementById('packages-list-container');
                            if (newPackages && listContainer) {
                                listContainer.innerHTML = newPackages.innerHTML;
                                listContainer.style.opacity = '1';
                            }

                            // Swap load more
                            const newLoadMore = doc.getElementById('load-more-section');
                            const oldLoadMore = document.getElementById('load-more-section');
                            if (newLoadMore && oldLoadMore) oldLoadMore.innerHTML = newLoadMore.innerHTML;

                            // Swap suggested packages
                            const newSuggested = doc.getElementById('suggested-packages-section');
                            const oldSuggested = document.getElementById('suggested-packages-section');
                            if (newSuggested && oldSuggested) oldSuggested.innerHTML = newSuggested.innerHTML;

                            // Swap transit music dynamically if it changed
                            const newMusic = doc.getElementById('transit-music-container');
                            const oldMusic = document.getElementById('transit-music-container');
                            if (newMusic && oldMusic) {
                                const newAudio = newMusic.querySelector('audio');
                                const oldAudio = oldMusic.querySelector('audio');
                                const newSrc = newAudio ? newAudio.getAttribute('src') : null;
                                const oldSrc = oldAudio ? oldAudio.getAttribute('src') : null;

                                if (newSrc !== oldSrc) {
                                    // Make sure we stop the old audio before replacing
                                    if (oldAudio) {
                                        oldAudio.pause();
                                        oldAudio.removeAttribute('src');
                                        oldAudio.load();
                                    }
                                    oldMusic.innerHTML = newMusic.innerHTML;
                                    if (window.initTransitMusic) window.initTransitMusic();
                                }
                            }

                            // Re-render Lucide icons for new cards!
                            if (window.lucide) window.lucide.createIcons();

                            // Swap individual filter counts dynamically without losing input states
                            doc.querySelectorAll('[data-filter-count]').forEach(newEl => {
                                const key = newEl.getAttribute('data-filter-count');
                                const oldEl = document.querySelector(`[data-filter-count="${key}"]`);
                                if (oldEl) {
                                    oldEl.textContent = newEl.textContent;
                                }
                            });

                            // Update URL query params without reloading the page
                            history.pushState(null, '', `${window.location.pathname}?${params.toString()}`);
                        })
                        .catch(err => {
                            console.error('AJAX filtering error:', err);
                            if (listContainer) listContainer.style.opacity = '1';
                        });
                };

                // Poppinscept manual submit
                form.addEventListener('submit', (e) => {
                    clearTimeout(searchTimeout);
                    handleFilterUpdate(e);
                });

                // Auto update on input/change events
                form.querySelectorAll('input[type="checkbox"], input[type="range"], select').forEach(input => {
                    input.addEventListener('change', handleFilterUpdate);
                });

                // Debounced price range updates to prevent UI thread blocking/tearing during active touch dragging on mobile
                const rangeInput = form.querySelector('input[type="range"]');
                if (rangeInput) {
                    let rangeTimeout;
                    rangeInput.addEventListener('input', () => {
                        // Instantly update text value smoothly without re-rendering packages list during drag gesture
                        const label = document.getElementById('priceRangeValue');
                        if (label) label.innerText = rangeInput.value;

                        // Debounce the AJAX refiltering request to run 400ms after user pauses dragging
                        clearTimeout(rangeTimeout);
                        rangeTimeout = setTimeout(() => {
                            handleFilterUpdate();
                        }, 400);
                    });
                }

                // Dynamic typing search input with 400ms debounce
                let searchTimeout;
                form.querySelectorAll('input[type="text"]').forEach(input => {
                    input.addEventListener('input', () => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            handleFilterUpdate();
                        }, 400);
                    });
                });
            });

            window.clearAllFilters = () => {
                window.location.href = window.location.pathname;
            };
        </script>
    @endpush

    <!-- Select State Modal -->
    <div x-show="stateModalOpen"
        class="fixed inset-0 flex items-center justify-center p-4 md:p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none; z-index: 999999 !important;">
        <div @click.away="stateModalOpen = false" x-data="{ stateSearch: '' }"
            class="bg-white rounded-[32px] md:rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-6 md:p-8 space-y-3 md:space-y-4 flex flex-col max-h-[90%] md:max-h-[85%]">
            <!-- Header -->
            <div class="flex items-center justify-between shrink-0">
                <h3 class="text-lg md:text-xl font-black text-foreground">Select State</h3>
                <button type="button" @click="stateModalOpen = false"
                    class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative shrink-0 pb-2 border-b border-gray-100">
                <input type="text" x-model="stateSearch" placeholder="Search state..."
                    class="w-full bg-gray-50 border border-gray-100 rounded-xl py-2 pl-10 pr-4 text-sm font-semibold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
            </div>

            <!-- Accordion Body (Max height limits to ~6 items before scroll) -->
            <div class="overflow-y-auto space-y-1 pr-2 py-1" style="max-height: 200px;">
                @foreach($locationCatalog as $country => $states)
                    @php
                        $countryCities = [];
                        foreach ($states as $st => $cits) {
                            $countryCities = array_merge($countryCities, array_keys($cits));
                        }
                        $countrySearchStr = strtolower($country . ' ' . implode(' ', array_keys($states)) . ' ' . implode(' ', $countryCities));
                    @endphp
                    <div class="mb-2" x-show="'{{ $countrySearchStr }}'.includes(stateSearch.toLowerCase())">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1 px-1">{{ $country }}</h4>
                        <div class="space-y-1">
                            @foreach($states as $state => $cities)
                                @php
                                    $stateTotal = array_sum($cities);
                                    $stateSlug = \Illuminate\Support\Str::slug($country . '-' . $state);
                                    $stateSearchStr = strtolower($country . ' ' . $state . ' ' . implode(' ', array_keys($cities)));
                                @endphp
                                <div class="border border-gray-100 rounded-lg overflow-hidden bg-gray-50/30"
                                    x-show="'{{ $stateSearchStr }}'.includes(stateSearch.toLowerCase())">
                                    <!-- State Header -->
                                    <div @click="expandedStates['{{ $stateSlug }}'] = !expandedStates['{{ $stateSlug }}']"
                                        class="flex items-center justify-between px-2 py-1.5 bg-gray-50/50 hover:bg-gray-50 cursor-pointer select-none transition-colors">
                                        <span class="font-extrabold text-xs text-foreground">
                                            {{ $state }} <span class="text-[9px] text-muted-text/60">({{ $stateTotal }})</span>
                                        </span>
                                        <i data-lucide="chevron-down" class="text-gray-400 transition-transform duration-300"
                                            :class="expandedStates['{{ $stateSlug }}'] ? 'rotate-180' : ''" size="14"></i>
                                    </div>

                                    <!-- Cities list -->
                                    <div x-show="expandedStates['{{ $stateSlug }}']"
                                        class="px-3 py-2 bg-white border-t border-gray-50 space-y-1.5" x-transition.opacity>
                                        @foreach($cities as $city => $count)
                                            <label class="flex items-center gap-2.5 cursor-pointer group select-none pl-2">
                                                <input type="checkbox" name="selected_cities[]" form="filter-form"
                                                    value="{{ strtolower($city) }}" {{ in_array(strtolower($city), request('selected_cities', [])) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                                                <span
                                                    class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">
                                                    {{ $city }} <span class="text-[10px] text-gray-400">({{ $count }})</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 shrink-0">
                <button type="button" @click="
                                        document.querySelectorAll('input[name=\'selected_cities[]\']').forEach(cb => cb.checked = false);
                                        stateModalOpen = false;
                                        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
                                    "
                    class="px-5 py-2.5 hover:bg-gray-50 rounded-xl text-xs font-black text-primary uppercase tracking-widest transition-all">
                    Clear
                </button>
                <button type="submit" form="filter-form" @click="stateModalOpen = false"
                    class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
                    Apply
                </button>
            </div>
        </div>
    </div>

@endsection