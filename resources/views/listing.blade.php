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
    <div class="bg-primary pt-32 pb-20">
        <div class="container-custom text-white text-center">
            <h1 class="text-4xl md:text-5xl font-black mb-4 font-syne">Explore Destinations</h1>
            <p class="text-white/80 max-w-2xl mx-auto font-medium">
                Discover 100+ amazing tour packages across the globe with premium services and best prices.
            </p>
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

    <div class="container-custom py-16" x-data="{ viewStyle: localStorage.getItem('tourraja_view_style') || 'grid', mobileFiltersOpen: false }" x-init="$watch('viewStyle', value => { localStorage.setItem('tourraja_view_style', value); $nextTick(() => lucide.createIcons()) }); $watch('mobileFiltersOpen', value => { if (value) { document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); } })">

        <form id="filter-form" action="{{ url('/listing') }}" method="GET" class="flex flex-col lg:flex-row gap-12 w-full">
            <!-- Sidebar Wrapper (Responsive: Slide-over Drawer on Mobile, Sticky Column on Desktop) -->
            <div 
                :class="mobileFiltersOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="desktop-sidebar-reset fixed inset-y-0 left-0 w-[85vw] sm:w-[400px] lg:w-1/4 lg:static lg:h-auto bg-white lg:bg-transparent z-50 lg:z-0 shadow-2xl lg:shadow-none p-6 lg:p-0 transition-transform duration-300 overflow-y-auto shrink-0"
            >
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between lg:hidden border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-black">Filters</h3>
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
                
                <div class="mt-8">
                    <button type="submit" @click="mobileFiltersOpen = false" class="w-full bg-primary hover:bg-primary-hover text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-primary/20 lg:hidden">
                        Apply Filters
                    </button>
                    <button type="button" onclick="clearAllFilters()" class="w-full text-center mt-4 text-muted-text font-bold hover:text-primary transition-colors text-sm">
                        Clear All Filters
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
                <div class="bg-white rounded-[32px] p-5 md:p-6 shadow-soft flex flex-col md:flex-row items-center md:items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[9px] mb-1">Search Results</p>
                        <h3 class="text-lg md:text-2xl font-black">Showing <span id="results-count" class="text-primary">{{ $packages->count() }}</span> Packages</h3>
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
                                <option value="Recommended" {{ request('sort') == 'Recommended' ? 'selected' : '' }}>Recommended</option>
                                <option value="Price: Low to High" {{ request('sort') == 'Price: Low to High' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="Price: High to Low" {{ request('sort') == 'Price: High to Low' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="Top Rated" {{ request('sort') == 'Top Rated' ? 'selected' : '' }}>Top Rated</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="14"></i>
                        </div>
                        
                        <!-- View Toggle Buttons -->
                        <div class="flex bg-background p-1 rounded-2xl border border-gray-100 shrink-0">
                            <button @click="viewStyle = 'grid'" type="button" :class="viewStyle === 'grid' ? 'bg-white shadow-soft text-primary' : 'text-gray-400 hover:text-primary transition-colors'" class="p-2.5 rounded-xl transition-all duration-300">
                                <i data-lucide="layout-grid" size="18"></i>
                            </button>
                            <button @click="viewStyle = 'list'" type="button" :class="viewStyle === 'list' ? 'bg-white shadow-soft text-primary' : 'text-gray-400 hover:text-primary transition-colors'" class="p-2.5 rounded-xl transition-all duration-300">
                                <i data-lucide="list" size="18"></i>
                            </button>
                        </div>
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
            </div>
        </form>
    </div>
    @push('scripts')
    <script>
    function loadMorePackages() {
        const hiddenItems = document.querySelectorAll('.package-item.hidden');
        for (let i = 0; i < 6 && i < hiddenItems.length; i++) {
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
        form.addEventListener('submit', handleFilterUpdate);

        // Auto update on input/change events
        form.querySelectorAll('input[type="checkbox"], select').forEach(input => {
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

@endsection
