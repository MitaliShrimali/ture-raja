@extends('layouts.app')

@section('content')
    <x-hero :banners="$heroBanners ?? collect()" />

    <!-- Section 1: Browse Popular Transits -->
    <section class="py-12 bg-white border-b border-border-soft/30 animate-fade-up">
        <div class="container-custom">
            <h2 class="text-xl font-black text-foreground mb-10 text-center tracking-tight font-heading">Browse Popular Transits</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 lg:gap-3">
                @php
                    $transits = [
                        ['label' => 'Flight Package', 'gif' => 'https://s13.gifyu.com/images/b7YmN.gif'],
                        ['label' => 'Train Package', 'gif' => 'https://s13.gifyu.com/images/b7Yms.gif'],
                        ['label' => 'Bus Package', 'gif' => 'https://s13.gifyu.com/images/b7YmK.gif'],
                        ['label' => 'Bullet Ride', 'gif' => 'https://s13.gifyu.com/images/b7YmH.gif'],
                        ['label' => 'Cruise Package', 'gif' => 'https://s13.gifyu.com/images/b7Ymx.gif'],
                        ['label' => 'Land Package', 'gif' => 'https://s13.gifyu.com/images/b7Ymw.gif'],
                        ['label' => 'Tracking', 'gif' => 'https://s13.gifyu.com/images/b7YmT.gif'],
                        ['label' => 'Helicopter Package', 'gif' => 'https://s13.gifyu.com/images/b7Ym3.gif'],
                    ];
                @endphp

                @foreach($transits as $item)
                    <div class="group flex flex-col items-center justify-between p-4 border border-blue-400/30 rounded-xl hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 cursor-pointer bg-white h-full min-h-[140px]">
                        <div class="w-16 h-16 flex items-center justify-center transition-transform duration-500 group-hover:scale-110">
                            <img src="{{ $item['gif'] }}" alt="{{ $item['label'] }}" class="w-full h-full object-contain pointer-events-none">
                        </div>
                        <h4 class="font-black text-foreground group-hover:text-primary transition-colors text-[10px] lg:text-[11px] uppercase tracking-tighter text-center leading-tight mt-2">
                            {{ $item['label'] }}
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

    <!-- Section 4: International Packages -->
    <section class="py-16 lg:py-20 bg-background">
        <div class="container-custom overflow-hidden animate-fade-up">
            <div class="flex items-center justify-between mb-10">
                <div class="space-y-1">
                    <span class="text-primary font-black text-xs uppercase tracking-[0.2em]">Global Adventures</span>
                    <h2 class="text-2xl md:text-3xl font-black text-foreground leading-none tracking-tight font-heading">
                        Top International Ready Packages
                    </h2>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <button class="w-12 h-12 rounded-full border border-border-soft flex items-center justify-center text-foreground hover:bg-primary hover:border-primary hover:text-white transition-all duration-300" id="prev-intl">
                        <i data-lucide="arrow-left" size="20"></i>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-border-soft flex items-center justify-center text-foreground hover:bg-primary hover:border-primary hover:text-white transition-all duration-300" id="next-intl">
                        <i data-lucide="arrow-right" size="20"></i>
                    </button>
                </div>
            </div>

            <div class="flex gap-6 overflow-x-auto hide-scrollbar snap-x-mandatory pb-4" id="intl-slider">
                @php
                    $intl = [
                        ['title' => 'Bangkok', 'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Dubai', 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Las Vegas', 'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Rome', 'image' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Bali', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Andaman', 'image' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Paris', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Maldives', 'image' => 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Singapore', 'image' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Tokyo', 'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'London', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600'],
                    ];
                @endphp

                @foreach($intl as $pkg)
                    @php $rating = number_format(4.5 + (rand(0, 5) / 10), 1); @endphp
                    <div class="snap-start">
                        <x-package-destination-card :title="$pkg['title']" :image="$pkg['image']" :rating="$rating" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 5: Domestic Packages -->
    <section class="py-16 lg:py-20 bg-background -mt-10 lg:-mt-16">
        <div class="container-custom overflow-hidden animate-fade-up">
            <div class="flex items-center justify-between mb-10 border-t border-border-soft pt-16">
                <div class="space-y-1">
                    <span class="text-primary font-black text-xs uppercase tracking-[0.2em]">Indian Wonders</span>
                    <h2 class="text-2xl md:text-3xl font-black text-foreground leading-none tracking-tight font-heading">
                        Top Domestic Ready Packages
                    </h2>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <button class="w-12 h-12 rounded-full border border-border-soft flex items-center justify-center text-foreground hover:bg-primary hover:border-primary hover:text-white transition-all duration-300" id="prev-dom">
                        <i data-lucide="arrow-left" size="20"></i>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-border-soft flex items-center justify-center text-foreground hover:bg-primary hover:border-primary hover:text-white transition-all duration-300" id="next-dom">
                        <i data-lucide="arrow-right" size="20"></i>
                    </button>
                </div>
            </div>

            <div class="flex gap-6 overflow-x-auto hide-scrollbar snap-x-mandatory pb-4" id="dom-slider">
                @php
                    $dom = [
                        ['title' => 'Goa', 'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Kerala', 'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Jaipur', 'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Manali', 'image' => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Rishikesh', 'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Srinagar', 'image' => 'https://images.unsplash.com/photo-1562979314-bee7453e911c?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Varanasi', 'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Mumbai', 'image' => 'https://images.unsplash.com/photo-1566552881560-0be862a7c445?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Hampi', 'image' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Mysore', 'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Ooty', 'image' => 'https://images.unsplash.com/photo-1590490359854-dfba19688d70?auto=format&fit=crop&q=80&w=600'],
                        ['title' => 'Darjeeling', 'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&q=80&w=600'],
                    ];
                @endphp

                @foreach($dom as $pkg)
                    @php $rating = number_format(4.5 + (rand(0, 5) / 10), 1); @endphp
                    <div class="snap-start">
                        <x-package-destination-card :title="$pkg['title']" :image="$pkg['image']" :rating="$rating" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 6: Browse by Travel Theme -->
    <section class="py-16 bg-white border-t border-border-soft/30 animate-fade-up">
        <div class="container-custom">
            <h2 class="text-2xl font-black text-foreground text-center mb-10 tracking-tight font-heading">Browse by Travel Theme</h2>

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

    <!-- Section 7: Promo Ads Banner -->
    <section class="py-16 bg-white border-t border-border-soft/30">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Promo Card 1 -->
                <div class="relative overflow-hidden rounded-[32px] lg:rounded-[48px] bg-gradient-to-br from-[#1A1A24] to-[#2D2D3A] p-8 lg:p-12 flex flex-col justify-center min-h-[300px] lg:min-h-[400px] group transition-all duration-500 hover:shadow-premium animate-fade-up">
                    <div class="absolute top-0 right-0 w-1/2 h-full opacity-20 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover">
                    </div>
                    <div class="relative z-10 space-y-4 lg:space-y-6 max-w-sm">
                        <span class="inline-block px-3 py-1 lg:px-4 lg:py-1.5 rounded-full bg-primary/20 text-primary text-[10px] lg:text-xs font-black uppercase tracking-widest">Limited Offer</span>
                        <h2 class="text-2xl lg:text-4xl font-black leading-tight font-heading" style="color: #ffffff !important;">
                            Summer Escape <br/> <span class="text-primary">25% OFF</span>
                        </h2>
                        <p class="text-white/60 text-sm lg:text-base font-medium">
                            Book your dream beach vacation now and save big on premium resorts.
                        </p>
                        <div class="pt-2">
                            <x-button variant="primary" size="md" icon-right="arrow-right">Claim Discount</x-button>
                        </div>
                    </div>
                </div>

                <!-- Promo Card 2 -->
                <div class="relative overflow-hidden rounded-[32px] lg:rounded-[48px] bg-gradient-to-br from-primary to-[#FF6B2C] p-8 lg:p-12 flex flex-col justify-center min-h-[300px] lg:min-h-[400px] group transition-all duration-500 hover:shadow-premium animate-fade-up" style="animation-delay: 200ms">
                    <div class="absolute top-0 right-0 w-1/2 h-full opacity-20 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none">
                        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover">
                    </div>
                    <div class="relative z-10 space-y-4 lg:space-y-6 max-w-sm">
                        <span class="inline-block px-3 py-1 lg:px-4 lg:py-1.5 rounded-full bg-white/20 text-white text-[10px] lg:text-xs font-black uppercase tracking-widest">New Arrival</span>
                        <h2 class="text-2xl lg:text-4xl font-black text-white leading-tight font-heading">
                            Mountain <br/> Expedition 2024
                        </h2>
                        <p class="text-white/80 text-sm lg:text-base font-medium">
                            Join our expert-led treks to the world's breathtaking peaks.
                        </p>
                        <div class="pt-2">
                            <x-button variant="dark" size="md" icon-right="compass">Explore Treks</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 8: Testimonials -->
    <section class="pt-16 lg:pt-24 bg-white overflow-hidden">
        <div class="container-custom">
            <div class="flex items-center justify-between mb-12 animate-fade-up">
                <div class="space-y-2">
                    <h2 class="text-4xl lg:text-6xl font-black text-foreground tracking-tight font-heading">What Our Clients Say!!!</h2>
                    <p class="text-text-muted text-lg font-medium">They Love TourRaja!</p>
                </div>
                <div class="flex items-center gap-4">
                    <button id="prev-testi" class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-foreground hover:bg-primary hover:text-white transition-all duration-300">
                        <i data-lucide="arrow-left" size="20"></i>
                    </button>
                    <button id="next-testi" class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-white shadow-glow hover:scale-110 transition-all duration-300">
                        <i data-lucide="arrow-right" size="20"></i>
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
                <div class="flex-1 min-h-[400px] lg:min-h-[500px] relative">
                    <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&q=80&w=1200" class="absolute inset-0 w-full h-full object-cover">
                    <!-- Glass Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-[#FFF9F0] to-transparent lg:w-32 h-full"></div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
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
