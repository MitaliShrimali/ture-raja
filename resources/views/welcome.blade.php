@extends('layouts.app')

@section('content')
    <x-hero :banners="$heroBanners ?? collect()" />

    {{-- Popular Transits is now inside the hero component --}}

    <!-- Section 4: International Packages — small card grid (Image 1 style) -->
    <section class="py-10 bg-white">
        <div class="container-custom">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-3">
                <h2 class="font-bold text-[#1a1a1a] m-0" style="font-size: 34px;">
                    Top International Ready Packages
                </h2>
                <a href="{{ url('/discover?category=international') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-700 border border-gray-300 rounded-full px-4 py-1.5 hover:border-primary hover:text-primary transition-all self-start md:self-auto">
                    View More
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            {{-- 6 small cards --}}
            <div class="flex flex-nowrap gap-5 pt-2 pb-4">
                @php
                    $intl = [
                        ['title' => 'Bangkok',   'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Dubai',     'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Las Vegas', 'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Rome',      'image' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Bali',      'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Andaman',   'image' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&q=80&w=400'],
                    ];
                @endphp
                @foreach($intl as $pkg)
                <a href="{{ url('/discover?destination='.urlencode($pkg['title'])) }}"
                   class="relative rounded-xl overflow-hidden block group flex-1 min-w-0" style="height:115px;">
                    <img src="{{ $pkg['image'] }}" alt="{{ $pkg['title'] }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                    <span style="position:absolute; bottom:8px; left:10px; color:#fff; font-size:18px; font-weight:700; text-shadow:0 1px 5px rgba(0,0,0,0.7); line-height:1.2;">{{ $pkg['title'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 5: Domestic Packages — small card grid (Image 1 style) -->
    <section class="py-10 bg-white border-t border-gray-100">
        <div class="container-custom">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-3">
                <h2 class="font-bold text-[#1a1a1a] m-0" style="font-size: 34px;">
                    Top Domestic Ready Packages
                </h2>
                <a href="{{ url('/discover?category=domestic') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-700 border border-gray-300 rounded-full px-4 py-1.5 hover:border-primary hover:text-primary transition-all self-start md:self-auto">
                    View More
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            {{-- 6 small cards --}}
            <div class="flex flex-nowrap gap-5 pt-2 pb-4">
                @php
                    $dom = [
                        ['title' => 'Goa',     'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Kerala',  'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Jaipur',  'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Kutch',   'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Mumbai',  'image' => 'https://images.unsplash.com/photo-1566552881560-0be862a7c445?auto=format&fit=crop&q=80&w=400'],
                        ['title' => 'Srinagar','image' => 'https://images.unsplash.com/photo-1562979314-bee7453e911c?auto=format&fit=crop&q=80&w=400'],
                    ];
                @endphp
                @foreach($dom as $pkg)
                <a href="{{ url('/discover?destination='.urlencode($pkg['title'])) }}"
                   class="relative rounded-xl overflow-hidden block group flex-1 min-w-0" style="height:115px;">
                    <img src="{{ $pkg['image'] }}" alt="{{ $pkg['title'] }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                    <span style="position:absolute; bottom:8px; left:10px; color:#fff; font-size:18px; font-weight:700; text-shadow:0 1px 5px rgba(0,0,0,0.7); line-height:1.2;">{{ $pkg['title'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 3: Why Travel With TourRaja -->
    <section class="py-20 lg:py-24 bg-white">
        <div class="container-custom">
            <x-section-title subtitle="The TourRaja Advantage" align="center">
                Why Travel With TourRaja?
                <x-slot:description>The best booking platform you can trust</x-slot:description>
            </x-section-title>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $features = [
                        ['title' => 'Security Assurance', 'desc' => 'Demonstrates commitment to user data security', 'icon' => 'shield-check', 'color' => 'bg-[#FFF9F0]'],
                        ['title' => 'Best Price Deals', 'desc' => 'Compare and choose the most affordable package', 'icon' => 'badge-percent', 'color' => 'bg-[#F2F4F7]'],
                        ['title' => 'Direct Agent Contacts', 'desc' => 'No middleman, connect instantly', 'icon' => 'handshake', 'color' => 'bg-[#FFF9F0]'],
                        ['title' => 'Find Nearby Travel Agent', 'desc' => 'Find the perfect trip without confusion with your local agent', 'icon' => 'map-pinned', 'color' => 'bg-[#F2F4F7]'],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="{{ $feature['color'] }} p-10 rounded-[40px] text-center space-y-6 group hover-lift transition-all duration-500 animate-fade-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <div class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-soft group-hover:shadow-glow group-hover:scale-110 transition-all duration-500">
                            <i data-lucide="{{ $feature['icon'] }}" class="text-primary" size="32"></i>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-black text-foreground">{{ $feature['title'] }}</h3>
                            <p class="text-text-muted text-sm font-medium leading-relaxed">
                                {{ $feature['desc'] }}
                            </p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-2 text-foreground font-black text-xs uppercase tracking-widest hover:text-primary transition-colors">
                            Learn More <i data-lucide="arrow-right" size="14"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 6: Browse by Travel Theme -->
    <section class="py-16 bg-white border-t border-border-soft/30 animate-fade-up">
        <div class="container-custom">
            <h2 class="text-2xl md:text-[30px] font-black text-foreground text-center mb-10 tracking-tight font-heading">Browse by Travel Theme</h2>

            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-4 items-start">
                @php
                    $themes = [
                        ['label' => 'Family/Group', 'image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Religious', 'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Honeymoon', 'image' => 'https://images.unsplash.com/photo-1573152958734-1922c188fba3?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Solo', 'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Adventure', 'image' => 'https://images.unsplash.com/photo-1533240332313-0db36245e4a2?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Cruise', 'image' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'WaterPark', 'image' => 'https://images.unsplash.com/photo-1582650625119-3a31f8fa2699?auto=format&fit=crop&q=80&w=400&v=1'],
                        ['label' => 'Pilgrimage', 'image' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&q=80&w=400&v=1'],
                    ];
                @endphp

                @foreach($themes as $theme)
                    <div class="group flex flex-col items-center text-center space-y-3 cursor-pointer">
                        <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-2xl overflow-hidden shadow-soft transition-all duration-500 group-hover:scale-105 group-hover:shadow-premium">
                            <img src="{{ $theme['image'] }}" alt="{{ $theme['label'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <h4 class="font-bold text-foreground group-hover:text-primary transition-colors text-[11px] lg:text-xs tracking-tight">
                            {{ $theme['label'] }}
                        </h4>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 2: Featured Travel Packages -->
    <section class="py-16 lg:py-20 bg-background">
        <div class="container-custom">
            <!-- Header with Filters -->
            <div class="relative flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12" style="z-index: 40; position: relative;">
                <div class="space-y-2">
                    <h2 class="text-3xl md:text-5xl font-black text-foreground tracking-tight font-heading">
                        Featured Travel Packages
                    </h2>
                    <p class="text-text-muted font-medium text-lg">
                        Favourite destinations based on customer reviews
                    </p>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3" id="filter-bar">
                    <!-- Category Filter -->
                    <div class="relative filter-dropdown">
                        <button onclick="toggleDropdown('cat-menu')" class="px-5 py-2.5 rounded-full bg-white border border-border-soft text-foreground text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-all" id="cat-btn">
                            Categories
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div id="cat-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl shadow-premium min-w-[160px] py-2" style="z-index: 40;">
                            <button onclick="applyFilter('category','all','cat-btn','Categories')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">All</button>
                            <button onclick="applyFilter('category','domestic','cat-btn','Domestic')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">Domestic</button>
                            <button onclick="applyFilter('category','international','cat-btn','International')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">International</button>
                            <button onclick="applyFilter('category','religious','cat-btn','Religious')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">Religious</button>
                            <button onclick="applyFilter('category','adventure','cat-btn','Adventure')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">Adventure</button>
                        </div>
                    </div>

                    <!-- Duration Filter -->
                    <div class="relative filter-dropdown">
                        <button onclick="toggleDropdown('dur-menu')" class="px-5 py-2.5 rounded-full bg-white border border-border-soft text-foreground text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-all" id="dur-btn">
                            Duration
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div id="dur-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl shadow-premium min-w-[170px] py-2" style="z-index: 40;">
                            <button onclick="applyFilter('duration','all','dur-btn','Duration')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">All</button>
                            <button onclick="applyFilter('duration','1-3','dur-btn','1–3 Days')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">1–3 Days</button>
                            <button onclick="applyFilter('duration','4-6','dur-btn','4–6 Days')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">4–6 Days</button>
                            <button onclick="applyFilter('duration','7+','dur-btn','7+ Days')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">7+ Days</button>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="relative filter-dropdown">
                        <button onclick="toggleDropdown('rat-menu')" class="px-5 py-2.5 rounded-full bg-white border border-border-soft text-foreground text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-all" id="rat-btn">
                            Review / Rating
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div id="rat-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl shadow-premium min-w-[160px] py-2" style="z-index: 40;">
                            <button onclick="applyFilter('rating','all','rat-btn','Review / Rating')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">All</button>
                            <button onclick="applyFilter('rating','4.8','rat-btn','⭐ 4.8+')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">⭐ 4.8+</button>
                            <button onclick="applyFilter('rating','4.5','rat-btn','⭐ 4.5+')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">⭐ 4.5+</button>
                            <button onclick="applyFilter('rating','4.0','rat-btn','⭐ 4.0+')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">⭐ 4.0+</button>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="relative filter-dropdown">
                        <button onclick="toggleDropdown('pri-menu')" class="px-5 py-2.5 rounded-full bg-white border border-border-soft text-foreground text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-all" id="pri-btn">
                            Price range
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div id="pri-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl shadow-premium min-w-[180px] py-2" style="z-index: 40;">
                            <button onclick="applyFilter('price','all','pri-btn','Price range')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">All</button>
                            <button onclick="applyFilter('price','0-20000','pri-btn','Under ₹20,000')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">Under ₹20,000</button>
                            <button onclick="applyFilter('price','20000-40000','pri-btn','₹20K – ₹40K')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">₹20,000 – ₹40,000</button>
                            <button onclick="applyFilter('price','40000+','pri-btn','₹40,000+')" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-gray-50 transition-colors">₹40,000+</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <p id="no-results" class="hidden text-center text-text-muted font-bold py-20">No packages match your filters. <button onclick="resetFilters()" class="text-primary underline ml-1">Reset filters</button></p>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10" id="packages-grid" style="position: relative; z-index: 1;">
                @php
                    $packages = [
                        ['slug'=>'monaco-luxury-tour','title'=>'Monaco Luxury Tour Package','image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=600','duration'=>'2 days 3 nights','duration_days'=>2,'groupSize'=>'4-6 guest','rating'=>'4.96','reviews'=>'672','price'=>44825,'oldPrice'=>59825,'badge'=>'Top Rated','category'=>'international'],
                        ['slug'=>'vietnam-tour-package','title'=>'Vietnam Tour Package','image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=600','duration'=>'3 days 3 nights','duration_days'=>3,'groupSize'=>'2-3 guest','rating'=>'4.91','reviews'=>'670','price'=>17320,'oldPrice'=>25320,'badge'=>'Best Sale','category'=>'international'],
                        ['slug'=>'char-dham-yatra','title'=>'Char Dham Yatra Package','image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=600','duration'=>'7 days 6 nights','duration_days'=>7,'groupSize'=>'4-6 guest','rating'=>'4.86','reviews'=>'656','price'=>15463,'oldPrice'=>19000,'badge'=>'25% Off','category'=>'religious'],
                        ['slug'=>'goa-beach-package','title'=>'Goa Beach Holiday Package','image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600','duration'=>'2 days 3 nights','duration_days'=>2,'groupSize'=>'4-6 guest','rating'=>'4.74','reviews'=>'631','price'=>14755,'oldPrice'=>19825,'badge'=>'Top Rated','category'=>'domestic'],
                        ['slug'=>'spiti-valley-adventure','title'=>'Spiti Valley Package','image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=600','duration'=>'3 days 3 nights','duration_days'=>3,'groupSize'=>'4-6 guest','rating'=>'4.51','reviews'=>'617','price'=>24840,'oldPrice'=>31825,'badge'=>'Best Sale','category'=>'adventure'],
                        ['slug'=>'swiss-paris-delight','title'=>'Swiss Paris Delight','image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600','duration'=>'7 days 6 nights','duration_days'=>7,'groupSize'=>'4-6 guest','rating'=>'4.29','reviews'=>'608','price'=>51247,'oldPrice'=>null,'badge'=>'25% Off','category'=>'international'],
                        ['slug'=>'kerala-backwaters','title'=>'Kerala Backwaters Escape','image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=600','duration'=>'4 days 3 nights','duration_days'=>4,'groupSize'=>'2-4 guest','rating'=>'4.65','reviews'=>'420','price'=>12500,'oldPrice'=>15000,'badge'=>'Popular','category'=>'domestic'],
                        ['slug'=>'dubai-desert-safari','title'=>'Dubai Desert Safari & Burj','image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=600','duration'=>'4 days 3 nights','duration_days'=>4,'groupSize'=>'2-6 guest','rating'=>'4.8','reviews'=>'890','price'=>29999,'oldPrice'=>35000,'badge'=>'Trending','category'=>'international'],
                        ['slug'=>'bali-luxury-villa','title'=>'Bali Luxury Villa Escape','image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=600','duration'=>'5 days 4 nights','duration_days'=>5,'groupSize'=>'2 guest','rating'=>'4.9','reviews'=>'543','price'=>35000,'oldPrice'=>42000,'badge'=>'Honeymoon','category'=>'international'],
                    ];
                @endphp

                @foreach($packages as $pkg)
                    <div class="animate-fade-up pkg-card {{ $loop->index >= 6 ? 'hidden' : '' }}"
                         style="animation-delay: {{ $loop->index * 100 }}ms"
                         data-category="{{ $pkg['category'] }}"
                         data-duration="{{ $pkg['duration_days'] }}"
                         data-rating="{{ $pkg['rating'] }}"
                         data-price="{{ $pkg['price'] }}">
                        <x-package-card 
                            :title="$pkg['title']" 
                            :image="$pkg['image']" 
                            :duration="$pkg['duration']"
                            :groupSize="$pkg['groupSize']"
                            :rating="$pkg['rating']"
                            :reviews="$pkg['reviews']"
                            :price="$pkg['price']"
                            :oldPrice="$pkg['oldPrice']"
                            :badge="$pkg['badge']"
                            :slug="$pkg['slug']"
                        />
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="mt-20 flex justify-center animate-fade-up" id="load-more-container">
                <button onclick="loadMoreFeatured()" class="bg-black text-white px-10 py-4 rounded-full font-black text-sm uppercase tracking-widest hover:bg-primary transition-all duration-300 shadow-premium">
                    Load More Packages
                </button>
            </div>
        </div>
    </section>

    <!-- Section 7: Offer Stickers -->
    <section class="py-10 bg-white relative">
        <div class="container-custom">
            <div class="px-4 py-8 md:px-8 md:py-10 relative flex items-center justify-between shadow-sm" style="background-color: #F8F3DC; border-radius: 12px;">
                
                <!-- SVG Airplane Path (Moved inside the yellow box) -->
                <div class="absolute -top-12 -left-8 md:-top-16 md:-left-16 hidden md:block w-64 h-32 pointer-events-none z-20">
                    <svg width="250" height="150" viewBox="0 0 250 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 20 C 20 80, 50 120, 70 60 C 90 0, 120 -10, 140 40 Q 160 90, 200 60" stroke="#333" stroke-width="2" stroke-dasharray="4 4" fill="none" />
                        <path d="M10 20 A 4 4 0 1 1 10 19.9" stroke="#333" stroke-width="3" fill="none" />
                        <path d="M70 60 A 4 4 0 1 1 70 59.9" stroke="#333" stroke-width="3" fill="none" />
                        <!-- Airplane Icon -->
                        <g transform="translate(195, 45) rotate(25)">
                            <path d="M15 2 L18 8 L30 10 L18 12 L15 20 L12 12 L0 10 L12 8 Z" fill="#333"/>
                        </g>
                    </svg>
                </div>

                <!-- Prev Button -->
                <button class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/80 hover:bg-white flex-shrink-0 flex items-center justify-center text-gray-600 shadow-sm transition-all z-10 mr-2 md:mr-6">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                
                <!-- Cards Track -->
                <div class="flex-1 overflow-x-auto hide-scrollbar z-10 relative">
                    <div class="flex gap-4 md:gap-6 min-w-max items-center justify-center">
                        
                        <!-- Card 1 -->
                        <a href="#" class="w-72 md:w-80 h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                            <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/img1offer.jpg" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Travel">
                            <!-- <div class="absolute inset-0" style="background-color: rgba(32, 114, 245, 0.85);"></div> -->
                            <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                <div class="text-right">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white mb-2 ml-auto transform -rotate-45" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                    <h3 class="text-white text-[10px] font-bold leading-tight">We Make Every<br>Trips Special</h3>
                                </div>
                                <div class="mt-auto text-right">
                                    <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #E85D26;">View More &rarr;</span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="#" class="w-72 md:w-80 h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm" style="background-color: #FCE08F;">
                            <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/img2offer.png" class="absolute right-0 bottom-0 w-1/2 h-full object-cover object-center opacity-90 transition-transform duration-500 group-hover:scale-105" style="mask-image: linear-gradient(to right, transparent, black 40%); -webkit-mask-image: linear-gradient(to right, transparent, black 40%);" alt="Attractions">
                            <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                <div class="max-w-[150px] mt-2">
                                    <span class="text-[11px] font-bold text-gray-800 mb-1 block">Limited Offers</span>
                                    <h3 class="text-gray-900 text-[18px] md:text-[20px] font-black leading-tight">Buy 1, Get 1 Free<br>Attractions</h3>
                                </div>
                                <div class="mt-auto">
                                    <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #E85D26;">View More &rarr;</span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <a href="#" class="w-72 md:w-80 h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                            <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/images3offer.jpg" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Mountain Lake">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                <div class="flex flex-col items-start space-y-[3px]">
                                    <span class="text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #e7602e;">Limited Offers</span>
                                    <span class="text-white text-[16px] md:text-[18px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #e7602e;">Buy 1, Get 1 Free</span>
                                    <span class="text-white text-[16px] md:text-[18px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #e7602e;">Attractions</span>
                                </div>
                                <div class="mt-auto">
                                    <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #e7602e;">View More &rarr;</span>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- Next Button -->
                <button class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/80 hover:bg-white flex-shrink-0 flex items-center justify-center text-gray-600 shadow-sm transition-all z-10 ml-2 md:ml-6">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    <!-- Section 7.5: Top Categories Packages -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="container-custom">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-10 gap-3">
                <div class="space-y-1">
                    <h2 class="text-2xl md:text-[40px] font-black text-foreground tracking-tight leading-tight">Top Categories Packages</h2>
                    <p class="text-gray-500 text-sm md:text-base">Favorite destinations based on customer reviews</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center gap-2 px-5 py-2 rounded-full bg-gray-200 text-sm font-bold hover:bg-gray-300 transition-colors self-start md:self-auto">
                    View More 
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Card 1: Mountain -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=400&q=80" alt="Mountain" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Mountain</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 2: Safari -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=400&q=80" alt="Safari" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Safari</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 3: Desert -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?auto=format&fit=crop&w=400&q=80" alt="Desert" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Desert</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 4: Flower -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=400&q=80" alt="Flower" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Flower</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 5: Beach -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80" alt="Beach" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Beach</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 6: Temples -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1528181304800-259b08848526?auto=format&fit=crop&w=400&q=80" alt="Temples" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Temples</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 7: Yacht -->
                <a href="#" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
                    <div class="w-full h-36 rounded-2xl overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80" alt="Yacht" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="px-2 pb-2 flex items-end justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-[16px] mb-1">Yacht</h3>
                            <p class="text-gray-400 text-[11px]">356 Tours, 248 Activities</p>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-gray-200 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Card 8: Crafting CTA -->
                <a href="#" class="rounded-[24px] p-6 flex flex-col justify-between group block hover:shadow-md transition-shadow" style="background-color: #E85D26;">
                    <h3 class="text-white text-[22px] font-bold leading-tight pt-2">Crafting Your<br>Perfect Travel<br>Experience</h3>
                    
                    <div class="bg-[#111111] rounded-xl p-3 mt-6 flex items-center justify-between group-hover:bg-black transition-colors shadow-sm">
                        <div class="text-white">
                            <span class="block text-[10px] text-gray-400 font-medium">Browse</span>
                            <span class="block text-sm font-bold">All destinations</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-black">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- Section 8: Testimonials -->
    <section class="pt-16 lg:pt-24 bg-white overflow-hidden">
        <div class="container-custom">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-12 animate-fade-up gap-6">
                <div class="space-y-2">
                    <h2 class="text-3xl md:text-4xl lg:text-6xl font-black text-foreground tracking-tight font-heading">What Our Clients Say!!!</h2>
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
                            <div class="p-6 md:p-10 rounded-[40px] border border-border-soft bg-white shadow-soft hover:shadow-premium transition-all duration-500 h-full flex flex-col space-y-4 md:space-y-6">
                                <h4 class="text-lg md:text-xl font-black text-foreground font-heading">The best booking system</h4>
                                <p class="text-text-muted text-xs md:text-sm leading-relaxed font-medium italic">"{{ $testi['text'] }}"</p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-border-soft/50 mt-auto gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $testi['img'] }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-primary/10">
                                        <div class="flex flex-col">
                                            <span class="font-black text-foreground text-sm md:text-base">{{ $testi['name'] }}</span>
                                            <span class="text-[10px] md:text-xs text-text-muted font-bold">{{ $testi['loc'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        @for($i=0; $i<5; $i++)
                                            <i data-lucide="star" size="14" class="{{ $i < $testi['rating'] ? 'fill-orange-400 text-orange-400' : 'text-gray-200' }}"></i>
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
                    width: calc(100vw - 80px); /* Leave space for next card */
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

    <!-- Section 9: Newsletter -->
    <section class="pb-12 lg:pb-20 bg-white">
        <div class="container-custom">
            <div class="bg-[#FFF9F0] rounded-[48px] overflow-hidden flex flex-col lg:flex-row animate-fade-up">
                <!-- Left: Content -->
                <div class="flex-1 p-8 lg:p-20 flex flex-col justify-center space-y-8">
                    <div class="space-y-6 max-w-lg">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-glow">
                            Join our newsletter
                        </span>
                        <h2 class="text-3xl lg:text-5xl font-black text-foreground leading-[1.1] tracking-tight font-heading">
                            Subscribe to see secret deals prices drop the moment you sign up!
                        </h2>
                        
                        @if(session('success') && str_contains(session('success'), 'subscrib'))
                            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-sm flex items-center gap-3">
                                <i data-lucide="check-circle" size="20"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative max-w-md mt-10">
                            @csrf
                            <input type="email" name="email" placeholder="Your Email" required class="w-full h-16 pl-8 pr-40 rounded-full bg-white border-none shadow-soft text-foreground font-bold focus:ring-2 focus:ring-primary/20 placeholder:text-text-muted/50">
                            <button type="submit" class="absolute right-2 top-2 h-12 px-8 rounded-full bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-primary transition-all duration-300">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-text-muted text-xs font-bold opacity-60">No ads. No trails. No commitments</p>
                    </div>
                </div>

                <!-- Right: Image -->
                <div class="flex-1 min-h-[300px] md:min-h-[400px] lg:min-h-[500px] relative">
                    <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&q=80&w=1200" class="absolute inset-0 w-full h-full object-cover">
                    <!-- Glass Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-[#FFF9F0] to-transparent h-32 w-full md:h-full md:w-32"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Welcome Popup Modal --}}
    <div id="welcome-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-500">
        <div id="welcome-popup-content" class="bg-white rounded-3xl shadow-2xl p-8 max-w-lg w-full text-center flex flex-col items-center transform scale-95 transition-transform duration-500">
            <!-- Alert Icon -->
            <img src="data:image/png;base64,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" alt="Alert" class="w-24 h-24 mb-6 object-contain">
            
            <!-- Message -->
            <p class="text-gray-800 text-[15px] font-medium leading-relaxed mb-6 whitespace-nowrap">
                It's important to check reviews, compare packages, and<br>confirm the quality of services before booking.
            </p>

            <!-- Ok Button -->
            <button onclick="closeWelcomePopup()" 
                    style="background-color: #e85d26; color: #ffffff;"
                    class="px-10 py-2.5 rounded-lg font-bold hover:opacity-90 transition-opacity shadow-md text-sm">
                Ok
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        let welcomePopupTimeout;
        
        document.addEventListener('DOMContentLoaded', () => {
            // Check if already shown in this session
            if (!sessionStorage.getItem('welcomePopupShown')) {
                const popup = document.getElementById('welcome-popup');
                const content = document.getElementById('welcome-popup-content');
                
                // Show smoothly after a short delay
                setTimeout(() => {
                    popup.classList.remove('opacity-0', 'pointer-events-none');
                    popup.classList.add('opacity-100');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                    
                    // Auto-hide after 5 seconds
                    welcomePopupTimeout = setTimeout(() => {
                        closeWelcomePopup();
                    }, 5000);
                }, 500);
            }
        });

        function closeWelcomePopup() {
            const popup = document.getElementById('welcome-popup');
            const content = document.getElementById('welcome-popup-content');
            
            // Hide smoothly
            popup.classList.remove('opacity-100');
            popup.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            // Remember for session
            sessionStorage.setItem('welcomePopupShown', 'true');
            
            if (welcomePopupTimeout) {
                clearTimeout(welcomePopupTimeout);
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ── Sliders ──────────────────────────────────────────────
            const setupSlider = (sliderId, prevBtnId, nextBtnId, scrollAmount = 400, autoSwipe = true) => {
                const slider = document.getElementById(sliderId);
                const prevBtn = document.getElementById(prevBtnId);
                const nextBtn = document.getElementById(nextBtnId);
                if (!slider) return;
                
                const moveNext = () => {
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    }
                };

                if (nextBtn) nextBtn.addEventListener('click', moveNext);
                if (prevBtn) prevBtn.addEventListener('click', () => slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));

                if (autoSwipe) {
                    let interval = setInterval(moveNext, 1500);
                    const resetInterval = () => {
                        clearInterval(interval);
                        interval = setInterval(moveNext, 1500);
                    };
                    slider.addEventListener('mouseenter', () => clearInterval(interval));
                    slider.addEventListener('mouseleave', resetInterval);
                    slider.addEventListener('touchstart', () => clearInterval(interval), {passive: true});
                    slider.addEventListener('touchend', resetInterval, {passive: true});
                    slider.addEventListener('scroll', () => {
                        clearTimeout(slider.scrollTimeout);
                        clearInterval(interval);
                        slider.scrollTimeout = setTimeout(resetInterval, 1500);
                    }, {passive: true});
                }
            };
            // 350px width + 24px gap = 374
            setupSlider('intl-slider', 'prev-intl', 'next-intl', 374, true);
            setupSlider('dom-slider', 'prev-dom', 'next-dom', 374, true);

            // ── Close dropdowns when clicking outside ─────────────────
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.filter-dropdown')) {
                    document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
                }
            });
        });

        // ── Filter State ─────────────────────────────────────────────
        const activeFilters = { category: 'all', duration: 'all', rating: 'all', price: 'all' };

        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const allMenus = document.querySelectorAll('[id$="-menu"]');
            allMenus.forEach(m => { if (m.id !== menuId) m.classList.add('hidden'); });
            menu.classList.toggle('hidden');
        }

        function applyFilter(type, value, btnId, label) {
            activeFilters[type] = value;
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.childNodes[0].textContent = label + ' ';
                btn.classList.toggle('bg-primary', value !== 'all');
                btn.classList.toggle('text-white', value !== 'all');
                btn.classList.toggle('border-primary', value !== 'all');
                btn.classList.toggle('bg-white', value === 'all');
                btn.classList.toggle('text-foreground', value === 'all');
                btn.classList.toggle('border-border-soft', value === 'all');
            }
            // hide this dropdown
            document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
            filterCards();
        }

        function filterCards() {
            const cards = document.querySelectorAll('.pkg-card');
            let visible = 0;

            cards.forEach(card => {
                const cat      = card.dataset.category;
                const days     = parseInt(card.dataset.duration);
                const rating   = parseFloat(card.dataset.rating);
                const price    = parseInt(card.dataset.price);

                // Category
                const catOk = activeFilters.category === 'all' || cat === activeFilters.category;

                // Duration
                let durOk = true;
                if (activeFilters.duration === '1-3') durOk = days >= 1 && days <= 3;
                else if (activeFilters.duration === '4-6') durOk = days >= 4 && days <= 6;
                else if (activeFilters.duration === '7+') durOk = days >= 7;

                // Rating
                let ratOk = true;
                if (activeFilters.rating !== 'all') ratOk = rating >= parseFloat(activeFilters.rating);

                // Price
                let priOk = true;
                if (activeFilters.price === '0-20000') priOk = price < 20000;
                else if (activeFilters.price === '20000-40000') priOk = price >= 20000 && price <= 40000;
                else if (activeFilters.price === '40000+') priOk = price > 40000;

                const show = catOk && durOk && ratOk && priOk;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            const noResults = document.getElementById('no-results');
            if (noResults) noResults.classList.toggle('hidden', visible > 0);
        }

        function resetFilters() {
            ['category','duration','rating','price'].forEach(t => {
                activeFilters[t] = 'all';
            });
            const defaults = { 'cat-btn':'Categories','dur-btn':'Duration','rat-btn':'Review / Rating','pri-btn':'Price range' };
            Object.entries(defaults).forEach(([id, label]) => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.childNodes[0].textContent = label + ' ';
                    btn.className = btn.className
                        .replace('bg-primary','bg-white')
                        .replace('text-white','text-foreground')
                        .replace('border-primary','border-border-soft');
                }
            });
            filterCards();
        }
        function loadMoreFeatured() {
            const hiddenCards = document.querySelectorAll('.pkg-card.hidden');
            for (let i = 0; i < 3 && i < hiddenCards.length; i++) {
                hiddenCards[i].classList.remove('hidden');
                hiddenCards[i].classList.add('animate-fade-up');
            }
            if (document.querySelectorAll('.pkg-card.hidden').length === 0) {
                document.getElementById('load-more-container').style.display = 'none';
            }
        }
    </script>
    @endpush
@endsection

