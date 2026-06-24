@extends('layouts.app')

@section('content')
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
            body .list-view-wrapper .package-content .flex.items-start.justify-between > div:last-child {
                display: none !important;
            }
            body .list-view-wrapper .package-image-container .absolute.top-4 { top: 6px !important; }
            body .list-view-wrapper .package-image-container .absolute.left-4 { left: 6px !important; }
            body .list-view-wrapper .package-image-container .absolute.right-4 { right: 6px !important; }
            body .list-view-wrapper .package-image-container span.px-4 { padding: 2px 6px !important; font-size: 7px !important; }
            body .list-view-wrapper .wishlist-btn { width: 26px !important; height: 26px !important; }
            body .list-view-wrapper .wishlist-btn svg { width: 12px !important; height: 12px !important; }
        }

        /* DESKTOP LIST VIEW RESTORATION */
        @media (min-width: 768px) {
            body .list-view-wrapper .package-card-inner {
                flex-direction: row !important;
                display: flex !important;
                height: 220px !important;
                padding: 0.5rem !important;
            }
            body .list-view-wrapper .package-image-container {
                width: 300px !important;
                height: 100% !important;
                margin: 0 !important;
                aspect-ratio: auto !important;
                flex-shrink: 0 !important;
            }
            body .list-view-wrapper .package-content {
                flex-direction: row !important;
                flex-grow: 1 !important;
                padding: 1rem 1rem 1rem 1.5rem !important;
                display: flex !important;
            }
            body .list-view-wrapper .package-content > * + * {
                margin-top: 0 !important;
            }
            body .list-view-wrapper .package-info {
                flex-grow: 1 !important;
                border-right: 1px dashed #e5e7eb !important;
                padding-right: 1.5rem !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
            }
            body .list-view-wrapper .package-action {
                width: 200px !important;
                flex-shrink: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
                padding-left: 1.5rem !important;
                border-top: none !important;
                margin-top: 0 !important;
                padding-top: 0 !important;
                gap: 1rem !important;
            }
            body .list-view-wrapper .package-action > div {
                align-items: center !important;
                text-align: center !important;
            }
        }
    </style>

    <!-- Small Hero Header -->
    <div class="pt-20 pb-6 bg-gray-50">

        <!-- Sticky Search Bar Wrapper with Placeholder height -->
        <div x-data="{ isSticky: false, topOffset: 0 }" 
             x-init="$nextTick(() => { topOffset = $el.getBoundingClientRect().top + window.pageYOffset - 80 })" 
             @scroll.window="isSticky = window.pageYOffset > topOffset"
             class="w-full"
             style="min-height: 72px;">
            
            <div :class="isSticky ? 'fixed top-20 left-0 right-0 z-50 py-2' : 'relative z-40'">
                <div class="container-custom text-center">
                    <div class="w-full max-w-5xl mx-auto">
                      <form id="top-search-form" action="{{ url('/discover') }}" method="GET">
                        <div class="p-3 md:p-3 flex flex-col md:flex-row items-center gap-3 w-full transition-all duration-300" style="background: #e85d26;" :class="isSticky ? 'rounded-xl shadow-2xl' : 'rounded-lg shadow-sm'">
                          
                          {{-- Destination Field --}}
                          <div class="flex items-center gap-3 flex-1 w-full rounded-md px-4 py-3" style="background: rgba(255,255,255,0.2);">
                            <i data-lucide="search" class="text-white" size="18"></i>
                            <input type="text" name="search" placeholder="Search Where You Go !!!"
                                   class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full p-0"
                                   value="{{ request('search') }}">
                          </div>

                          {{-- Agent/City Field --}}
                          <div class="flex items-center gap-3 flex-1 w-full rounded-md px-4 py-3" style="background: rgba(255,255,255,0.2);">
                            <i data-lucide="user" class="text-white" size="18"></i>
                            <input type="text" name="city" placeholder="Search Nearby Agent Location"
                                   class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full p-0"
                                   value="{{ is_array(request('city')) ? implode(', ', request('city')) : request('city') }}">
                          </div>

                          {{-- Check In Field --}}
                          <div class="flex items-center gap-3 w-full md:w-auto rounded-md px-4 py-3 shrink-0" style="background: rgba(255,255,255,0.2);">
                            <i data-lucide="calendar" class="text-white" size="18"></i>
                            <input type="text" name="check_in" placeholder="Check in" onfocus="(this.type='date')" onblur="(this.type='text')"
                                   class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full md:w-28 p-0"
                                   value="{{ request('check_in') }}">
                          </div>

                          {{-- Search Button --}}
                          <div class="w-full md:w-auto shrink-0">
                            <button type="submit"
                                    class="bg-white text-gray-900 font-bold text-sm hover:bg-gray-100 transition-colors w-full sm:w-auto px-10 py-3 rounded-md shadow-sm">
                              Search
                            </button>
                          </div>

                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    @media (min-width: 1024px) {
        .desktop-sidebar-reset {
            position: static !important;
            background: transparent !important;
            background-image: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            transform: none !important;
            z-index: 0 !important;
            width: 25% !important;
            height: auto !important;
            overflow: visible !important;
        }
    }
    </style>

    <div class="container-custom pt-8 pb-16" x-data="{ viewStyle: {{ isset($agent) ? "'grid'" : "localStorage.getItem('tourraja_view_style') || 'grid'" }}, mobileFiltersOpen: false, stateModalOpen: false, expandedStates: {} }" x-init="$watch('viewStyle', value => { localStorage.setItem('tourraja_view_style', value); $nextTick(() => lucide.createIcons()) }); $watch('mobileFiltersOpen', value => { if (value) { document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); } })">

        <form id="filter-form" action="{{ url('/listing') }}" method="GET" class="flex flex-col lg:flex-row gap-12 w-full">
            <!-- Sidebar Wrapper (Responsive: Slide-over Drawer on Mobile, Sticky Column on Desktop) -->
            <div 
                :class="mobileFiltersOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="desktop-sidebar-reset fixed inset-y-0 left-0 w-[85vw] sm:w-[400px] lg:w-1/4 lg:static lg:h-auto bg-white lg:bg-transparent z-50 lg:z-0 shadow-2xl lg:shadow-none p-6 lg:p-0 transition-transform duration-300 overflow-y-auto shrink-0"
            >
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between lg:hidden border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-sm font-black">Filters</h3>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="clearAllFilters()" class="text-xs font-black text-primary hover:text-primary-hover bg-primary/10 px-3 py-1.5 rounded-full transition-colors">
                            Clear All
                        </button>
                        <button type="button" @click="mobileFiltersOpen = false" class="text-gray-400 hover:text-primary transition-colors">
                            <i data-lucide="x" size="24"></i>
                        </button>
                    </div>
                </div>

                <x-filter-sidebar />

                @if(isset($sidebarAds) && $sidebarAds->count() > 0)
                <div class="mt-8 space-y-6">
                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="text-[10px] font-black text-muted-text uppercase tracking-widest mb-4">Sponsored Deals</h4>
                        <div class="space-y-4">
                            @foreach($sidebarAds as $ad)
                                <div class="bg-white rounded-3xl border border-gray-100 p-4 shadow-sm relative group hover:shadow-md transition-shadow">
                                    <div class="relative w-full h-32 rounded-2xl overflow-hidden mb-3">
                                        <img src="{{ $ad->image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=300' }}" 
                                             alt="{{ $ad->campaign_name }}" 
                                             class="w-full h-full object-cover">
                                        <span class="absolute top-2 left-2 bg-black/60 text-white font-extrabold uppercase text-[8px] tracking-widest px-1.5 py-0.5 rounded-md backdrop-blur-xs">AD</span>
                                    </div>
                                    <div class="space-y-1">
                                        @if($ad->subtitle)
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider mb-0.5 truncate">{{ $ad->subtitle }}</p>
                                        @endif
                                        <h5 class="font-extrabold text-foreground text-xs leading-snug line-clamp-2">
                                            {{ $ad->campaign_name }}
                                        </h5>
                                        <div class="flex items-center justify-between pt-2 mt-2 border-t border-gray-50">
                                            <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                               class="bg-[#4c75d4] hover:bg-[#3b62c2] text-white px-3.5 py-2 rounded-xl text-[9px] font-bold transition-all shadow-md shadow-[#4c75d4]/10">
                                                Check Now
                                            </a>
                                            @if($ad->agent_logo)
                                                <img src="{{ $ad->agent_logo }}" 
                                                     alt="{{ $ad->agent_name ?? 'Agent' }}" 
                                                     class="h-6 max-w-[80px] object-contain opacity-80">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="mt-8 lg:hidden">
                    <button type="submit" @click="mobileFiltersOpen = false" class="w-full bg-primary hover:bg-primary-hover text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-primary/20">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Dark Overlay for Mobile Drawer -->
            <div 
                x-show="mobileFiltersOpen" 
                x-transition:opacity
                @click="mobileFiltersOpen = false"
                class="lg:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
                style="display: none;"
            ></div>

            <!-- Main Content -->
            <div class="flex-1 space-y-8">
                @if(isset($agent))
                    <div class="bg-gradient-to-r from-orange-600 via-primary to-orange-500 rounded-[40px] p-8 md:p-10 shadow-premium border border-primary/20 text-white flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden animate-in fade-in slide-in-from-top-6 duration-500">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -ml-20 -mb-20"></div>
                        
                        <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8 relative z-10 text-center md:text-left">
                            <div class="w-24 h-24 rounded-[32px] bg-white p-1.5 shadow-xl shrink-0 flex items-center justify-center">
                                @php
                                    $agentInit = strtoupper(substr($agent->name ?? 'A', 0, 1));
                                    $agentLogoUrl = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agent->name ?? 'Agent');
                                @endphp
                                <img src="{{ $agentLogoUrl }}" alt="{{ $agent->name }}" class="w-full h-full object-cover rounded-[24px]">
                            </div>
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                                    <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10">
                                        {{ $agent->tier ?? 'Premium' }} Partner
                                    </span>
                                    <span class="px-3.5 py-1 bg-green-500/30 backdrop-blur-md rounded-full text-[10px] font-black text-green-200 uppercase tracking-widest border border-green-500/20 flex items-center gap-1">
                                        <i data-lucide="verified" size="12"></i> Verified Operator
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="font-family: 'Syne', sans-serif;">{{ $agent->name }}</h2>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-6 gap-y-2 text-white/80 text-xs font-medium">
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
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agent->phone ?? '') }}" target="_blank" class="w-full sm:w-auto px-6 py-4 bg-green-500 hover:bg-green-600 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-green-500/20 text-center flex items-center justify-center gap-2">
                                <i data-lucide="message-circle" size="16"></i> Chat on WhatsApp
                            </a>
                            <a href="mailto:{{ $agent->email }}" class="w-full sm:w-auto px-6 py-4 bg-white text-primary hover:bg-gray-50 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg text-center flex items-center justify-center gap-2">
                                <i data-lucide="mail" size="16"></i> Email Agent
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Mobile Search Input (Visible only on mobile/tablet) -->
                <div class="relative group lg:hidden w-full">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Search destination or package..." 
                        value="{{ request('search') }}"
                        class="w-full bg-white border border-gray-100 rounded-2xl py-4 pl-12 pr-4 shadow-soft focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all font-bold text-foreground placeholder:text-muted-text/40 text-sm"
                    >
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                </div>
                <!-- Top Bar -->
                <div class="bg-white rounded-lg py-3 px-5 shadow-soft flex flex-col md:flex-row items-center md:items-center justify-between gap-4">
                    <div class="text-center md:text-left flex items-center flex-wrap gap-3" style="font-size: 16px;">
                        <span class="text-gray-500 font-bold uppercase tracking-widest" style="font-size: 16px;">Search Results:</span>
                        <h3 class="font-black cursor-pointer hover:text-primary transition-colors" @click="stateModalOpen = true" style="font-size: 16px;">
                            Showing <span id="results-count" class="text-primary">{{ $packages->count() }}</span> Packages
                        </h3>
                        
                        <!-- Dropdown Pill -->
                        <button type="button" @click="stateModalOpen = true" class="flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-xs font-black text-gray-700 transition-all shadow-sm shrink-0">
                            <span>
                                @if(request()->filled('selected_cities'))
                                    Selected ({{ count(request('selected_cities')) }})
                                @else
                                    Select State
                                @endif
                            </span>
                            <i data-lucide="chevron-down" size="14" class="text-gray-400"></i>
                        </button>
                    </div>
                    
                    <div class="flex flex-row items-center justify-between gap-3 w-full md:w-auto border-t border-gray-50 pt-5 md:border-none md:pt-0">
                        <!-- Filters Trigger (Mobile only, static/non-floating) -->
                        <button type="button" @click="mobileFiltersOpen = true" class="lg:hidden flex items-center gap-2 bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2.5 rounded-2xl text-[12px] font-black transition-all shrink-0">
                            <i data-lucide="sliders-horizontal" size="14"></i>
                            Filters
                        </button>

                        <!-- Sort Dropdown: Constrained on mobile -->
                        <div class="relative mobile-sort-select-wrapper md:w-64 flex-1 md:flex-none">
                            <select name="sort" class="w-full bg-background border border-gray-100 rounded-2xl py-2.5 pl-4 pr-10 text-[12px] font-black focus:outline-none appearance-none cursor-pointer hover:border-primary/30 transition-all">
                                <option value="SHOW ALL" {{ !request('sort') || request('sort') == 'SHOW ALL' ? 'selected' : '' }}>SHOW ALL</option>
                                <option value="GUARANTEED SERVICE" {{ request('sort') == 'GUARANTEED SERVICE' || request('sort') == 'Recommended' ? 'selected' : '' }}>GUARANTEED SERVICE</option>
                                <option value="PRICE (LOW TO HIGH)" {{ request('sort') == 'PRICE (LOW TO HIGH)' || request('sort') == 'Price: Low to High' ? 'selected' : '' }}>PRICE (LOW TO HIGH)</option>
                                <option value="PRICE (HIGH TO LOW)" {{ request('sort') == 'PRICE (HIGH TO LOW)' || request('sort') == 'Price: High to Low' ? 'selected' : '' }}>PRICE (HIGH TO LOW)</option>
                                <option value="DURATION (LOW TO HIGH)" {{ request('sort') == 'DURATION (LOW TO HIGH)' ? 'selected' : '' }}>DURATION (LOW TO HIGH)</option>
                                <option value="DURATION (HIGH TO LOW)" {{ request('sort') == 'DURATION (HIGH TO LOW)' ? 'selected' : '' }}>DURATION (HIGH TO LOW)</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="14"></i>
                        </div>
                        
                        <!-- View Toggle Buttons -->
                        @if(!isset($agent))
                        <div class="flex bg-background p-1 rounded-2xl border border-gray-100 shrink-0">
                            <button @click="viewStyle = 'grid'" type="button" :class="viewStyle === 'grid' ? 'bg-white shadow-soft text-primary' : 'text-gray-400 hover:text-primary transition-colors'" class="p-2.5 rounded-xl transition-all duration-300">
                                <i data-lucide="layout-grid" size="18"></i>
                            </button>
                            <button @click="viewStyle = 'list'" type="button" :class="viewStyle === 'list' ? 'bg-white shadow-soft text-primary' : 'text-gray-400 hover:text-primary transition-colors'" class="p-2.5 rounded-xl transition-all duration-300">
                                <i data-lucide="list" size="18"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Grid -->
                <div id="packages-list-container" :class="viewStyle === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8' : 'flex flex-col gap-6'">
                    @forelse($packages as $index => $pkg)
                        <div class="package-item {{ $index >= 6 ? 'hidden' : '' }}" :class="viewStyle === 'list' ? 'list-view-wrapper' : ''">
                            <x-package-card :pkg="$pkg" />
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center space-y-4">
                            <h4 class="text-2xl font-bold">No packages found</h4>
                            <p class="text-gray-500">Try adjusting your filters or search term.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Load More Section -->
                <div id="load-more-section">
                    @if($packages->count() > 6)
                        <div class="text-center pt-10" id="loadMoreContainer">
                            <button type="button" onclick="loadMorePackages()" class="bg-white hover:bg-gray-50 text-foreground px-10 py-5 rounded-full font-bold shadow-soft transition-all border border-gray-100">
                                Load More Results
                            </button>
                        </div>
                    @endif
                </div>
            <!-- Modal placeholder (moved to root for relative viewport alignment) -->
        </form>
    </div>
    @push('scripts')
    <script>
    function loadMorePackages() {
        const hiddenItems = document.querySelectorAll('.package-item.hidden');
        for (let i = 0; i < 20 && i < hiddenItems.length; i++) {
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
                
                // Re-render Lucide icons for new cards!
                if (window.lucide) window.lucide.createIcons();

                // Update URL query params without reloading the page
                history.pushState(null, '', `${window.location.pathname}?${params.toString()}`);
            })
            .catch(err => {
                console.error('AJAX filtering error:', err);
                if (listContainer) listContainer.style.opacity = '1';
            });
        };

        // Intercept manual submit
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
        const form = document.getElementById('filter-form');
        if (!form) return;

        // Reset checkbox states
        form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

        // Reset range value to max
        const range = form.querySelector('input[type="range"]');
        if (range) {
            range.value = range.max;
            const label = document.getElementById('priceRangeValue');
            if (label) label.innerText = range.max;
        }

        // Clear all text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Trigger AJAX submit
        form.dispatchEvent(new Event('submit'));
    };
    </script>
    @endpush

    <!-- Select State Modal -->
    <div 
        x-show="stateModalOpen" 
        class="fixed inset-0 flex items-center justify-center p-4 md:p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none; z-index: 999999 !important;"
    >
        <div @click.away="stateModalOpen = false" class="bg-white rounded-[32px] md:rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-6 md:p-8 space-y-4 md:space-y-6 flex flex-col max-h-[90%] md:max-h-[85%]">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 shrink-0">
                <h3 class="text-lg md:text-xl font-black text-foreground">Select State</h3>
                <button type="button" @click="stateModalOpen = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <!-- Accordion Body -->
            <div class="flex-1 overflow-y-auto space-y-3 md:space-y-4 pr-2 py-2">
                @foreach($locationCatalog as $state => $cities)
                    @php
                        $stateTotal = array_sum($cities);
                        $stateSlug = \Illuminate\Support\Str::slug($state);
                    @endphp
                    <div class="border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/30">
                        <!-- State Header -->
                        <div 
                            @click="expandedStates['{{ $stateSlug }}'] = !expandedStates['{{ $stateSlug }}']"
                            class="flex items-center justify-between p-3.5 bg-gray-50/50 hover:bg-gray-50 cursor-pointer select-none transition-colors"
                        >
                            <span class="font-extrabold text-xs md:text-sm text-foreground">
                                {{ $state }} <span class="text-[10px] md:text-xs text-muted-text/60">({{ $stateTotal }})</span>
                            </span>
                            <i data-lucide="chevron-down" class="text-gray-400 transition-transform duration-300" :class="expandedStates['{{ $stateSlug }}'] ? 'rotate-180' : ''" size="14"></i>
                        </div>
                        
                        <!-- Cities list -->
                        <div 
                            x-show="expandedStates['{{ $stateSlug }}']"
                            class="p-3.5 bg-white border-t border-gray-50 space-y-2.5"
                            x-transition.opacity
                        >
                            @foreach($cities as $city => $count)
                                <label class="flex items-center gap-3 cursor-pointer group select-none pl-2">
                                    <input type="checkbox" name="selected_cities[]" form="filter-form" value="{{ strtolower($city) }}" {{ in_array(strtolower($city), request('selected_cities', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">
                                        {{ $city }} <span class="text-[10px] text-gray-400">({{ $count }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 shrink-0">
                <button 
                    type="button" 
                    @click="
                        document.querySelectorAll('input[name=\'selected_cities[]\']').forEach(cb => cb.checked = false);
                        stateModalOpen = false;
                        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
                    "
                    class="px-5 py-2.5 hover:bg-gray-50 rounded-xl text-xs font-black text-primary uppercase tracking-widest transition-all"
                >
                    Clear
                </button>
                <button 
                    type="submit" 
                    form="filter-form"
                    @click="stateModalOpen = false"
                    class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all"
                >
                    Apply
                </button>
            </div>
        </div>
    </div>

@endsection
