@php
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
        $gallerySlides = array_filter($gallerySlides, function ($item) {
            return is_string($item) && strlen($item) > 5;
        });

        if (empty($gallerySlides)) {
            $gallerySlides = $defaultGallery;
        }
        $gallerySlides = array_map('asset', array_values($gallerySlides)); // apply asset() and reindex for valid JSON array

        $navSections = [
            ['id' => 'overview', 'title' => 'Overview']
        ];
        if (!empty($package['editorial_itinerary']))
            $navSections[] = ['id' => 'itinerary', 'title' => 'Itinerary'];
        if (count($sightseeingPills) > 0)
            $navSections[] = ['id' => 'sightseeing', 'title' => 'Sightseeing'];
        if (!empty($package['hotels']))
            $navSections[] = ['id' => 'hotels', 'title' => 'Hotels'];
        if (!empty($package['about_tours']))
            $navSections[] = ['id' => 'about-tours', 'title' => 'About Tours'];
        if (!empty($package['terms']))
            $navSections[] = ['id' => 'terms', 'title' => 'Terms & Conditions'];
        $navSections[] = ['id' => 'faq', 'title' => 'FAQ'];
    @endphp
    @extends('layouts.app')

    @section('title', $package['title'] . ' — Tour Raja')

    @section('content')
        <style>
            .detail-overview-text {
                word-break: normal;
                overflow-wrap: break-word;
            }

            .itinerary-line {
                min-height: 40px;
            }

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
                font-family: 'Poppins', sans-serif !important;
                line-height: 1.25 !important;
            }

            .section-heading {
                font-size: 30px !important;
                font-family: 'Poppins', sans-serif !important;
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
                color: #4b5563 !important;
                /* text-gray-600 */
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

            #overview,
            #itinerary,
            #sightseeing,
            #hotels,
            #terms,
            #faq,
            #contact-form {
                scroll-margin-top: 120px;
            }

            .package-gallery-details-grid {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 1.5rem !important;
            }

            .package-gallery-images-col {
                grid-column: span 1 / span 1 !important;
                height: 350px !important;
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
                    height: 500px !important; /* Made taller vertically as requested */
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

                .package-meta-details-container>div+div {
                    margin-top: 1.5rem !important;
                }
            }

            /* IMMEDIATE NAVBAR ALIGNMENT */
            main {
                padding-top: 72px !important; /* Navbar height on mobile */
            }

            @media (min-width: 1024px) {
                main {
                    padding-top: 96px !important; /* Navbar height on desktop */
                }
            }
        </style>

        <div class="bg-[#F8F9FA] min-h-screen"
            x-data='{ showBookingModal: false, slides: @json($gallerySlides), sections: @json($navSections) }'>
            {{-- Breadcrumb --}}
            <div>
                <div class="container-custom pt-4 pb-2 sm:py-2 flex items-center justify-between gap-2">
                    <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500 overflow-hidden whitespace-nowrap">
                        <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors">Home</a>
                        <span>/</span>
                        <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors shrink-0">Tours</a>
                        <span>/</span>
                        <span class="text-gray-800 truncate">{{ $package['title'] }}</span>
                    </nav>

                    {{-- Share Section --}}
                    <div class="relative shrink-0" x-data="{ open: false, copied: false, shareData: { title: '{{ addslashes($package['title']) }}', text: 'Check out this awesome tour package: {{ addslashes($package['title']) }}', url: window.location.href } }">
                        <!-- Share Button -->
                        <button @click="if (navigator.share) { navigator.share(shareData).catch(err => console.log(err)) } else { open = !open }" 
                            class="inline-flex items-center justify-center gap-2 w-9 h-9 sm:w-auto sm:px-4 sm:py-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-full sm:rounded-xl text-xs font-black uppercase tracking-widest text-gray-700 hover:text-primary transition-all duration-300 shadow-sm focus:ring-2 focus:ring-primary/20">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Share Package</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                            style="display: none;"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 p-2 space-y-1">
                            
                            <!-- Copy Link Option -->
                            <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                                class="w-full flex items-center justify-between text-left px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-primary transition-all">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    <span x-text="copied ? 'Link Copied!' : 'Copy Link'"></span>
                                </span>
                                <span x-show="copied" class="text-[9px] font-black text-green-600 bg-green-50 px-1.5 py-0.5 rounded">DONE</span>
                            </button>

                            <!-- WhatsApp Option -->
                            <a :href="'https://api.whatsapp.com/send?text=' + encodeURIComponent(shareData.text + ' ' + shareData.url)" target="_blank"
                                class="w-full flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-primary transition-all">
                                <i class="fa-brands fa-whatsapp text-green-600 text-sm"></i> WhatsApp
                            </a>

                            <!-- Facebook Option -->
                            <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareData.url)" target="_blank"
                                class="w-full flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-primary transition-all">
                                <i class="fa-brands fa-facebook text-blue-600 text-sm"></i> Facebook
                            </a>

                            <!-- Twitter Option -->
                            <a :href="'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareData.text) + '&url=' + encodeURIComponent(shareData.url)" target="_blank"
                                class="w-full flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-primary transition-all">
                                <i class="fa-brands fa-x-twitter text-black text-sm"></i> Twitter / X
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-custom py-8">

                {{-- TWO-COLUMN LAYOUT --}}
                <div class="flex flex-col lg:flex-row gap-8 items-start">

                    {{-- ── LEFT MAIN CONTENT (2/3 width) ───────────────────── --}}
                    <div class="w-full lg:w-2/3 space-y-6">

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

                            $gallerySlides = array_filter($gallerySlides, function ($item) {
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

                            {{-- Gallery & Details Container — Full-width gallery, details below --}}
                            <div class="mb-2">

                                {{-- ── Full-Width Image Carousel ────────────────────── --}}
                                <div class="relative w-full overflow-hidden rounded-lg shadow-sm border border-gray-100 bg-black"
                                    style="height: 340px;"
                                    x-data="{
                                        currentSlide: 0,
                                        interval: null,
                                        startInterval() { this.interval = setInterval(() => { this.next() }, 4000) },
                                        stopInterval() { clearInterval(this.interval) },
                                        next() { this.currentSlide = (this.currentSlide === this.slides.length - 1) ? 0 : this.currentSlide + 1 },
                                        prev() { this.currentSlide = (this.currentSlide === 0) ? this.slides.length - 1 : this.currentSlide - 1 },
                                        init() { this.startInterval(); }
                                    }"
                                    @mouseenter="stopInterval()" @mouseleave="startInterval()">

                                    {{-- Slides --}}
                                    <div class="flex h-full w-full transition-transform duration-700 ease-in-out"
                                        :style="`transform: translateX(-${currentSlide * 100}%)`">
                                        <template x-for="(slide, index) in slides" :key="index">
                                            <div class="w-full h-full flex-shrink-0 cursor-pointer"
                                                @click="$dispatch('open-gallery', { slides: slides, title: '{{ addslashes($package['title']) }}' })">
                                                <img :src="slide" alt="Package Image" class="w-full h-full object-cover">
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Prev Arrow --}}
                                    <button @click.stop="prev()"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white/80 hover:bg-white text-gray-800 rounded-full flex items-center justify-center shadow transition-all border border-gray-200 hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>

                                    {{-- Next Arrow --}}
                                    <button @click.stop="next()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white/80 hover:bg-white text-gray-800 rounded-full flex items-center justify-center shadow transition-all border border-gray-200 hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>

                                    {{-- Dot indicators --}}
                                    <div class="absolute bottom-3 left-0 right-0 flex items-center justify-center z-10 gap-1.5">
                                        <template x-for="(slide, index) in slides" :key="index">
                                            <button @click.stop="currentSlide = index"
                                                class="h-1.5 rounded-full transition-all duration-300"
                                                :class="currentSlide === index ? 'w-6 bg-orange-500' : 'w-1.5 bg-white/70 hover:bg-white'">
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- ── Below-Gallery Details Row 1: Title | Rating+Duration | Price+CTA ── --}}
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4">

                                    {{-- Title --}}
                                    <div class="flex-1 min-w-0">
                                        <h1 class="font-black text-gray-900 leading-tight" style="font-size:22px; font-family:'Poppins',sans-serif;">
                                            {{ $package['title'] }}
                                        </h1>
                                    </div>

                                    {{-- Rating + Duration --}}
                                    <div class="flex flex-col items-start sm:items-center gap-1 shrink-0">
                                        <div class="flex items-center gap-1">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#e85d26" xmlns="http://www.w3.org/2000/svg">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                            </svg>
                                            <span class="font-bold text-gray-800 text-sm">{{ $package['rating'] }}</span>
                                            <span class="text-gray-500 text-xs">({{ $package['reviews'] }} Review)</span>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500">Duration : {{ $formattedDuration }}</span>
                                    </div>

                                    {{-- Price + Inquiry CTA --}}
                                    <div class="flex flex-col items-start sm:items-end gap-2 shrink-0">
                                        @php
                                            $displayPrice = $package['price'] ?? 0;
                                            $currency = $package['currency'] ?? '₹';
                                            $currencyMap = ['INR' => '₹', 'USD' => '$', 'AED' => 'AED', 'EUR' => '€', 'GBP' => '£',
                                                            '₹' => '₹', '$' => '$', '€' => '€', '£' => '£'];
                                            $currencySymbol = $currencyMap[$currency] ?? $currency;
                                        @endphp
                                        <div class="text-base font-black text-gray-900">
                                            Price : <span style="color:#e85d26;">{{ $currencySymbol }}{{ number_format($displayPrice) }}/-</span>
                                        </div>
                                        <a href="#contact-form"
                                            onclick="event.preventDefault(); const el = document.getElementById('contact-form'); const y = el.getBoundingClientRect().top + window.scrollY - 120; window.scrollTo({top: y, behavior: 'smooth'}); return false;"
                                            style="background-color:#e85d26;"
                                            class="inline-flex items-center justify-center px-6 py-2.5 text-white text-xs font-black uppercase tracking-widest rounded hover:opacity-90 transition-opacity shadow-sm whitespace-nowrap">
                                            Inquiry Now
                                        </a>
                                    </div>
                                </div>

                                {{-- ── Below-Gallery Row 2: Badge pills + Departure City ── --}}
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    {{-- Category badge --}}
                                    @if(!empty($package['category']))
                                        <span class="inline-flex items-center px-3 py-1.5 rounded border border-gray-300 text-gray-700 text-[11px] font-semibold bg-white">
                                            {{ ucfirst($package['category']) }}
                                        </span>
                                    @endif

                                    {{-- Transit / Tour Type --}}
                                    @if(!empty($package['tour_type']))
                                        <span class="inline-flex items-center px-3 py-1.5 rounded border border-gray-300 text-gray-700 text-[11px] font-semibold bg-white">
                                            {{ $package['tour_type'] }}
                                        </span>
                                    @endif

                                    {{-- Theme --}}
                                    @if(!empty($package['theme']))
                                        <span class="inline-flex items-center px-3 py-1.5 rounded border border-gray-300 text-gray-700 text-[11px] font-semibold bg-white">
                                            {{ $package['theme'] }}
                                        </span>
                                    @endif

                                    {{-- Holiday type --}}
                                    @if(!empty($package['holiday_type']))
                                        <span class="inline-flex items-center px-3 py-1.5 rounded border border-gray-300 text-gray-700 text-[11px] font-semibold bg-white">
                                            {{ ucfirst($package['holiday_type']) }}
                                        </span>
                                    @endif

                                    {{-- Badge --}}
                                    @if(!empty($package['badge']))
                                        <span class="inline-flex items-center px-3 py-1.5 rounded bg-orange-50 border border-orange-200 text-orange-600 text-[11px] font-bold">
                                            {{ $package['badge'] }}
                                        </span>
                                    @endif

                                    {{-- Departure city (Starting from Vadodara style) --}}
                                    @if(!empty($package['departure_city']))
                                        <span class="inline-flex items-center gap-1 text-[12px] font-semibold text-gray-600 ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            Starting from {{ $package['departure_city'] }}
                                            @if(!empty($package['departure_state']))
                                                , {{ $package['departure_state'] }}
                                            @endif
                                        </span>
                                    @elseif(!empty($package['city']))
                                        <span class="inline-flex items-center gap-1 text-[12px] font-semibold text-gray-600 ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            Starting from {{ $package['city'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Section Navigation Tabs --}}
                        <div id="sticky-section-nav-wrapper">
                            <div id="sticky-section-nav-placeholder" style="display: none;"></div>
                            <div id="sticky-section-nav" class="z-[90] flex items-center mb-6 group bg-[#F8F9FA] py-2 border-b border-gray-200 w-full transition-all duration-300">
                                
                                <script>
                                    (function() {
                                        let isSticky = false;
                                        
                                        function initStickyNav() {
                                            const nav = document.getElementById('sticky-section-nav');
                                            const placeholder = document.getElementById('sticky-section-nav-placeholder');
                                            const navbar = document.querySelector('nav.fixed.top-0');
                                            
                                            if (!nav || !placeholder || !navbar) return;

                                            const checkSticky = () => {
                                                const navHeight = navbar.offsetHeight || (window.innerWidth >= 1024 ? 96 : 80);
                                                
                                                // Calculate where the element WOULD be if it wasn't fixed
                                                const rect = isSticky ? placeholder.getBoundingClientRect() : nav.getBoundingClientRect();
                                                
                                                if (rect.top <= navHeight) {
                                                    if (!isSticky) {
                                                        // Become sticky
                                                        placeholder.style.height = nav.offsetHeight + 'px';
                                                        placeholder.style.display = 'block';
                                                        placeholder.style.marginBottom = window.getComputedStyle(nav).marginBottom;
                                                        
                                                        // Get exact width before fixing
                                                        const computedWidth = window.getComputedStyle(nav.parentElement).width;
                                                        
                                                        nav.style.position = 'fixed';
                                                        nav.style.top = navHeight + 'px';
                                                        nav.style.width = computedWidth;
                                                        nav.style.marginTop = '0';
                                                        nav.classList.add('shadow-md');
                                                        isSticky = true;
                                                    } else {
                                                        // Update width dynamically if window resized
                                                        nav.style.width = window.getComputedStyle(nav.parentElement).width;
                                                    }
                                                } else {
                                                    if (isSticky) {
                                                        // Stop being sticky
                                                        placeholder.style.display = 'none';
                                                        nav.style.position = '';
                                                        nav.style.top = '';
                                                        nav.style.width = '';
                                                        nav.classList.remove('shadow-md');
                                                        isSticky = false;
                                                    }
                                                }
                                            };

                                            window.addEventListener('scroll', checkSticky, { passive: true });
                                            window.addEventListener('resize', checkSticky, { passive: true });
                                            
                                            // Initial checks
                                            checkSticky();
                                            setTimeout(checkSticky, 100);
                                            setTimeout(checkSticky, 500);
                                        }

                                        if (document.readyState === 'loading') {
                                            document.addEventListener('DOMContentLoaded', initStickyNav);
                                        } else {
                                            initStickyNav();
                                        }
                                    })();
                                </script>
                            <!-- Left Arrow -->
                            <button type="button"
                                class="absolute left-0 z-10 w-12 h-full flex items-center justify-start bg-gradient-to-r from-[#F8F9FA] via-[#F8F9FA] to-transparent text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex"
                                onclick="document.getElementById('section-nav').scrollBy({left: -250, behavior: 'smooth'})">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>

                            <div id="section-nav"
                                class="flex overflow-x-auto gap-3 py-1 pl-4 pr-12 md:pl-14 md:pr-14 hide-scrollbar scroll-smooth w-full">
                                @if(!empty($package['brochure']))
                                    <a href="#document"
                                        class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                        <i data-lucide="file-text" class="w-4 h-4"></i> Document
                                    </a>
                                @else
                                    @if(!empty($package['overview']) && trim(strip_tags(str_replace('&nbsp;', '', $package['overview']))) !== '')
                                        <a href="#overview"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="map" class="w-4 h-4"></i> Overview
                                        </a>
                                    @endif
                                    @if(!empty($package['editorial_itinerary']))
                                        <a href="#itinerary"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="list" class="w-4 h-4"></i> Itinerary
                                        </a>
                                    @endif
                                    @if(count($sightseeingPills) > 0)
                                        <a href="#sightseeing"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="binoculars" class="w-4 h-4"></i> Sightseeing
                                        </a>
                                    @endif
                                    @if(count($package['included'] ?? []) > 0 || count($package['excluded'] ?? []) > 0)
                                        <a href="#inclusions"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="check-circle-2" class="w-4 h-4"></i> Inclusions/Exclusions
                                        </a>
                                    @endif
                                    @if(!empty($package['hotels']) && (is_array($package['hotels']) || trim(strip_tags(str_replace('&nbsp;', '', $package['hotels']))) !== ''))
                                        <a href="#hotels"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="building" class="w-4 h-4"></i> Hotels
                                        </a>
                                    @endif
                                    @if(!empty($package['amenities']) && (is_array($package['amenities']) || trim(strip_tags(str_replace('&nbsp;', '', $package['amenities']))) !== ''))
                                        <a href="#amenities"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="star" class="w-4 h-4"></i> Amenities
                                        </a>
                                    @endif

                                    @if(!empty($package['terms']) && trim(strip_tags(str_replace('&nbsp;', '', $package['terms']))) !== '')
                                        <a href="#terms"
                                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#e85d26] hover:text-[#e85d26] transition-colors focus:ring-2 focus:ring-[#e85d26]/50">
                                            <i data-lucide="file-text" class="w-4 h-4"></i> Tour Info
                                        </a>
                                    @endif
                                @endif
                            </div>

                            <!-- Right Arrow -->
                            <button type="button"
                                class="absolute right-0 z-10 w-12 h-full flex items-center justify-end bg-gradient-to-l from-[#F8F9FA] via-[#F8F9FA] to-transparent text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex"
                                onclick="document.getElementById('section-nav').scrollBy({left: 250, behavior: 'smooth'})">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                        </div> <!-- End sticky-section-nav-wrapper -->

                        @if(!empty($package['brochure']))
                            {{-- Document / Brochure PDF Preview Section --}}
                            <div id="document" class="bg-white rounded-lg border border-gray-150 p-6 shadow-sm mb-6" x-data="{ documentExpanded: false }">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-1.5 h-6 rounded-full" style="background-color: #e85d26;"></div>
                                    <h2 class="font-black text-gray-900 text-xl">Document</h2>
                                </div>

                                <div class="flex items-center gap-6 mb-4">
                                    <a href="{{ asset($package['brochure']) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 text-sm font-semibold hover:underline transition-colors">
                                        View PDF Document
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                </div>

                                <div class="mt-4 transition-all duration-500 ease-in-out relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50"
                                     :style="documentExpanded ? 'height: 1000px;' : 'height: 450px;'">
                                     
                                    <!-- Gradient Overlay when collapsed -->
                                    <div x-show="!documentExpanded" 
                                         class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-gray-50 to-transparent z-10 pointer-events-none">
                                    </div>

                                    <div class="relative w-full h-full">
                                    <!-- PDF Viewer -->
                                    <iframe src="{{ asset($package['brochure']) }}#page=1&view=FitH" width="100%" height="100%"
                                        class="w-full h-full border-0"></iframe>

                                    <!-- Top-Right Expand Icon -->
                                    <a href="{{ asset($package['brochure']) }}" target="_blank"
                                        class="absolute top-0 right-0 bg-black/80 hover:bg-black text-white p-2.5 rounded-bl-lg transition-colors z-10"
                                        title="Open in new tab">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                    </div> <!-- end of relative w-full -->
                                </div> <!-- end of documentExpanded wrapper -->

                                <div class="mt-4 flex justify-center relative z-10">
                                    <button type="button" @click="documentExpanded = !documentExpanded"
                                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg text-white text-sm font-bold tracking-wide transition-all hover:opacity-90 shadow-sm"
                                        style="background-color: #e85d26;">
                                        <span x-html="documentExpanded ? 'Show Less &uarr;' : 'Expand Full Preview &darr;'"></span>
                                    </button>
                                </div>
                            </div> <!-- end of #document -->
                        @else

                        @if(!empty($package['overview']) && trim(strip_tags(str_replace('&nbsp;', '', $package['overview']))) !== '')
                        {{-- Tour Overview & Editorial --}}
                        <div id="overview" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 mb-8">
                            <h2 class="font-black text-gray-900 mb-4 section-heading">Tour Overview</h2>
                            <p class="standard-body-text detail-overview-text">{{ $package['overview'] }}</p>

                            @if(!empty($package['highlights']) && count($package['highlights']) > 0)
                            <h3 class="font-black text-gray-900 mt-6 mb-3 section-heading">Tour Highlights</h3>
                            <ul class="space-y-2">
                                @foreach($package['highlights'] as $hl)
                                    <li class="flex items-start gap-2 standard-body-text">
                                        <svg class="shrink-0 mt-1" width="14" height="14" fill="none" stroke="#f97316"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <span>{{ $hl }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        @endif

                        {{-- Itinerary Timeline --}}
                        @if(!empty($package['editorial_itinerary']))
                            <div id="itinerary" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <h2 class="font-black text-gray-900 mb-8 section-heading text-xl">Itinerary</h2>
                                <div class="relative">
                                    <div id="itinerary-content" class="relative transition-all duration-500 overflow-hidden" style="max-height: 380px;">
                                        <div class="prose max-w-none standard-body-text">
                                            {!! $package['editorial_itinerary'] !!}
                                        </div>
                                        
                                        {{-- Blurred overlay inside the clipped container --}}
                                        <div id="itinerary-overlay" class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white via-white/90 to-transparent pointer-events-none"></div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-center relative z-10">
                                        <button type="button" id="itinerary-toggle-btn"
                                            onclick="toggleItinerary()"
                                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg text-white text-sm font-bold tracking-wide transition-all hover:opacity-90 shadow-sm"
                                            style="background-color: #e85d26;">
                                            <span>Expand Full Itinerary &darr;</span>
                                        </button>
                                    </div>
                                </div>
                                <script>
                                    function toggleItinerary() {
                                        const content = document.getElementById('itinerary-content');
                                        const overlay = document.getElementById('itinerary-overlay');
                                        const btnSpan = document.querySelector('#itinerary-toggle-btn span');
                                        
                                        if (content.style.maxHeight === '380px') {
                                            content.style.maxHeight = '5000px';
                                            overlay.style.display = 'none';
                                            btnSpan.innerHTML = 'Show Less &uarr;';
                                        } else {
                                            content.style.maxHeight = '380px';
                                            overlay.style.display = 'block';
                                            btnSpan.innerHTML = 'Expand Full Itinerary &darr;';
                                        }
                                    }
                                </script>
                            </div>
                        @endif
                        
                        {{-- Sightseeing Section --}}
                        @if(count($sightseeingPills) > 0)
                            <div id="sightseeing" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($sightseeingPills as $pill)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            <i data-lucide="binoculars" class="w-4 h-4 mr-2"></i>
                                            {{ $pill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Inclusions & Exclusions --}}
                        @if(count($package['included'] ?? []) > 0 || count($package['excluded'] ?? []) > 0)
                            <div id="inclusions" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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
                        
                        {{-- Hotels --}}
                        @if(!empty($package['hotels']) && (is_array($package['hotels']) || trim(strip_tags(str_replace('&nbsp;', '', $package['hotels']))) !== ''))
                            <div id="hotels" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <h2 class="font-black text-gray-900 mb-4 section-heading text-xl">Hotels</h2>
                                @if(is_array($package['hotels']))
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($package['hotels'] as $hotel)
                                            @if(is_array($hotel))
                                                <div class="flex items-start gap-4 border border-gray-100 p-4 rounded-xl bg-gray-50">
                                                    @if(!empty($hotel['image']))
                                                        <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] ?? 'Hotel' }}" class="w-20 h-20 object-cover rounded-lg shrink-0">
                                                    @endif
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-base">{{ $hotel['name'] ?? 'Hotel Name' }}</h4>
                                                        @if(!empty($hotel['room']))
                                                            <p class="text-sm text-gray-500 mt-1"><i data-lucide="bed" class="w-3 h-3 inline mr-1"></i>{{ $hotel['room'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-600 leading-relaxed">{{ $hotel }}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-sm text-gray-600 leading-relaxed">
                                        {!! nl2br(e($package['hotels'])) !!}
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        {{-- Amenities --}}
                        @if(!empty($package['amenities']) && (is_array($package['amenities']) || trim(strip_tags(str_replace('&nbsp;', '', $package['amenities']))) !== ''))
                            <div id="amenities" class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <h2 class="font-black text-gray-900 mb-4 section-heading text-xl">Amenities</h2>
                                @if(is_array($package['amenities']))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($package['amenities'] as $amenity)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-50 text-gray-800 border border-gray-200">
                                                <i data-lucide="check" class="w-4 h-4 mr-1.5 text-green-600"></i>
                                                {{ is_array($amenity) ? json_encode($amenity) : $amenity }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-sm text-gray-600 leading-relaxed">
                                        {!! nl2br(e($package['amenities'])) !!}
                                    </div>
                                @endif
                            </div>
                        @endif



                        {{-- About Tours --}}
                        @if(!empty($package['about_tours']) && trim(strip_tags(str_replace('&nbsp;', '', $package['about_tours']))) !== '')
                            <div id="about-tours" class="bg-[#F8F9FA] rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">About Tours</h2>
                                <div class="prose max-w-none standard-body-text text-sm text-gray-600 leading-relaxed">
                                    {!! $package['about_tours'] !!}
                                </div>
                            </div>
                        @endif

                        {{-- Terms & Conditions --}}
                        @if(!empty($package['terms']) && trim(strip_tags(str_replace('&nbsp;', '', $package['terms']))) !== '')
                            <div id="terms" class="bg-[#F8F9FA] rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <h2 class="font-black text-gray-900 section-heading !mb-0 text-xl pb-2">Terms & Conditions</h2>
                                </div>
                                <div class="text-sm text-gray-600 leading-relaxed mt-4">
                                    {!! nl2br(e($package['terms'])) !!}
                                </div>
                            </div>
                        @endif
                        @endif
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

                            $agentPackagesUrl = url('/listing/holiday-list?agent_id=' . $agentId . '&tab=packages');
                            $agentProfileUrl  = url('/listing/holiday-list?agent_id=' . $agentId . '&tab=profile');
                        @endphp
                        <!-- FontAwesome CDN for social media icons -->
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                        <style>
                            .pkg-social-btn {
                                width: 36px !important;
                                height: 36px !important;
                                border-radius: 50% !important;
                                display: flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                transition: all 0.3s ease !important;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
                            }
                            
                            .pkg-social-btn.fb { background: #eff6ff !important; color: #1877f2 !important; }
                            .pkg-social-btn.twitter { background: #ecfeff !important; color: #1da1f2 !important; }
                            .pkg-social-btn.linkedin { background: #eff6ff !important; color: #0077b5 !important; }
                            .pkg-social-btn.insta { background: #fff5f5 !important; color: #e1306c !important; }

                            .pkg-social-btn:hover {
                                color: #ffffff !important;
                                transform: translateY(-2px) !important;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                            }

                            .pkg-social-btn.fb:hover { background: #1877f2 !important; }
                            .pkg-social-btn.twitter:hover { background: #1da1f2 !important; }
                            .pkg-social-btn.linkedin:hover { background: #0077b5 !important; }
                            .pkg-social-btn.insta:hover { background: #e1306c !important; }
                        </style>
                        <div class="relative bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden group">
                            <!-- Header / Cover -->
                            <div class="absolute inset-x-0 top-0 w-full z-0 flex items-center justify-end p-4 pr-6 overflow-hidden text-left" style="height: 8rem; background-color: #e85d26 !important;">
                                <!-- Subtle Pattern Overlay -->
                                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                                
                                <!-- Text confined strictly to the right area -->
                                <div class="relative z-10 pl-4" style="width: 65%; max-width: 280px; margin-left: auto;">
                                    @if(!empty($dbAgent->about))
                                        <p class="text-white font-medium drop-shadow-sm" style="font-size: 11px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $dbAgent->about }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="relative z-10 pt-20 px-6 pb-6">
                                <!-- Avatar & Verification & Details -->
                                <div class="flex justify-between items-end mb-4">
                                    <div class="flex items-start gap-4">
                                        <div class="relative shrink-0">
                                            <div class="w-24 h-24 bg-white rounded-full shadow-xl p-1 flex items-center justify-center relative z-10 border border-gray-100">
                                                <img src="{{ asset($agentLogo) }}" alt="{{ $agentName }}" class="w-full h-full object-cover rounded-full">
                                            </div>
                                            @if($serviceGuaranteed)
                                            <div class="absolute right-0 bottom-0 bg-blue-500 text-white p-1 rounded-full border-2 border-white z-20 shadow-sm" title="Verified Partner">
                                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if(!empty($dbAgent->since))
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-[#e85d26] uppercase tracking-wider bg-orange-50/80 px-2 py-1.5 rounded border border-orange-100 h-max mt-12 relative z-20 shadow-sm">
                                            <i data-lucide="calendar" class="w-3 h-3"></i> Since {{ $dbAgent->since }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center gap-1.5 bg-gray-100/80 backdrop-blur-md px-3 py-1.5 rounded-full mb-2 border border-gray-200/50 shrink-0">
                                        <span class="text-yellow-500 text-sm leading-none">★</span>
                                        <span class="text-xs font-black text-gray-700">4.9</span>
                                    </div>
                                </div>

                                <!-- Name & Location Stacked -->
                                <div class="mb-6 space-y-2">
                                    <h3 class="font-black text-gray-900 text-2xl tracking-tight leading-none">{{ $agentName }}</h3>
                                    
                                    @if(!empty($agentRegion))
                                    <div class="text-xs font-semibold text-gray-500 flex items-start gap-1.5 leading-relaxed">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5"></i> <span>{{ $agentRegion }}</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Quick Contact -->
                                <div class="flex flex-col gap-2.5 mb-6">
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $agentPhone) }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all group/phone border border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover/phone:text-blue-500 transition-colors">
                                                <i data-lucide="phone-call" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Direct Line</div>
                                                <div class="text-sm font-bold text-gray-900">{{ $agentPhone }}</div>
                                            </div>
                                        </div>
                                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/phone:translate-x-1 transition-transform"></i>
                                    </a>

                                    @if(!empty($dbAgent->secondary_phone))
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $dbAgent->secondary_phone) }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all group/secphone border border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover/secphone:text-blue-500 transition-colors">
                                                <i data-lucide="phone-forwarded" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Secondary Mobile</div>
                                                <div class="text-sm font-bold text-gray-900">{{ $dbAgent->secondary_phone }}</div>
                                            </div>
                                        </div>
                                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/secphone:translate-x-1 transition-transform"></i>
                                    </a>
                                    @endif

                                    @if(!empty($dbAgent->landline))
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $dbAgent->landline) }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all group/landline border border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover/landline:text-indigo-500 transition-colors">
                                                <i data-lucide="phone" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Landline</div>
                                                <div class="text-sm font-bold text-gray-900">{{ $dbAgent->landline }}</div>
                                            </div>
                                        </div>
                                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/landline:translate-x-1 transition-transform"></i>
                                    </a>
                                    @endif
                                    
                                    <a href="mailto:{{ $agentEmail }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all group/mail border border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover/mail:text-orange-500 transition-colors">
                                                <i data-lucide="mail" class="w-4 h-4"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email Address</div>
                                                <div class="text-sm font-bold text-gray-900 truncate">{{ $agentEmail }}</div>
                                            </div>
                                        </div>
                                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/mail:translate-x-1 transition-transform"></i>
                                    </a>

                                    @if(!empty($dbAgent->website))
                                    <a href="{{ $dbAgent->website }}" target="_blank" class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all group/web border border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover/web:text-green-600 transition-colors">
                                                <i data-lucide="globe" class="w-4 h-4"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Website</div>
                                                <div class="text-sm font-bold text-gray-900 truncate">{{ preg_replace('(^https?://)', '', $dbAgent->website) }}</div>
                                            </div>
                                        </div>
                                        <i data-lucide="external-link" class="w-4 h-4 text-gray-300 group-hover/web:translate-x-1 transition-transform"></i>
                                    </a>
                                    @endif
                                </div>

                                <!-- Action Grid -->
                                <div class="grid grid-cols-2 gap-2.5 mb-6">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agentPhone) }}" target="_blank" class="flex items-center justify-center gap-1.5 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl text-xs font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                                    </a>
                                    <a href="mailto:{{ $agentEmail }}" class="flex items-center justify-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-xl text-xs font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                        <i data-lucide="mail" class="w-4 h-4"></i> Send Email
                                    </a>
                                    <a href="{{ $agentPackagesUrl }}" class="flex items-center justify-center gap-1.5 bg-[#e85d26] hover:bg-[#d0501f] text-white py-3 rounded-xl text-[11px] font-bold transition-all shadow-sm uppercase tracking-wider hover:shadow-md hover:-translate-y-0.5">
                                        <i data-lucide="package" class="w-4 h-4"></i> Packages
                                    </a>
                                    <a href="{{ $agentProfileUrl }}" class="flex items-center justify-center gap-1.5 bg-gray-900 hover:bg-black text-white py-3 rounded-xl text-[11px] font-bold transition-all shadow-sm uppercase tracking-wider hover:shadow-md hover:-translate-y-0.5">
                                        <i data-lucide="user" class="w-4 h-4"></i> Profile
                                    </a>
                                </div>

                                <!-- Social Integration Icons Only -->
                                @if(!empty($dbAgent->facebook) || !empty($dbAgent->twitter) || !empty($dbAgent->linkedin) || !empty($dbAgent->instagram))
                                <div class="flex items-center justify-center gap-4 mb-6 pt-4 border-t border-gray-50">
                                    @if(!empty($dbAgent->facebook))
                                        <a href="{{ $dbAgent->facebook }}" target="_blank" class="pkg-social-btn fb" title="Facebook">
                                            <i class="fab fa-facebook-f text-sm"></i>
                                        </a>
                                    @endif
                                    @if(!empty($dbAgent->twitter))
                                        <a href="{{ $dbAgent->twitter }}" target="_blank" class="pkg-social-btn twitter" title="Twitter (X)">
                                            <i class="fab fa-twitter text-sm"></i>
                                        </a>
                                    @endif
                                    @if(!empty($dbAgent->linkedin))
                                        <a href="{{ $dbAgent->linkedin }}" target="_blank" class="pkg-social-btn linkedin" title="LinkedIn">
                                            <i class="fab fa-linkedin-in text-sm"></i>
                                        </a>
                                    @endif
                                    @if(!empty($dbAgent->instagram))
                                        <a href="{{ $dbAgent->instagram }}" target="_blank" class="pkg-social-btn insta" title="Instagram">
                                            <i class="fab fa-instagram text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                                @endif

                                <!-- Expertise Tags -->
                                <div class="pt-5 border-t border-gray-100">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Services</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $agentServices = ($dbAgent && $dbAgent->services) ? json_decode($dbAgent->services, true) : [];
                                            if (empty($agentServices)) {
                                                $agentServices = [
                                                    ['name' => 'Flight Booking', 'icon' => 'fas fa-plane'],
                                                    ['name' => 'Visa', 'icon' => 'fas fa-passport'],
                                                    ['name' => 'International Tours', 'icon' => 'fas fa-globe'],
                                                    ['name' => 'Domestic Escapes', 'icon' => 'fas fa-map-marked-alt']
                                                ];
                                            }
                                        @endphp
                                        @foreach($agentServices as $service)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 border border-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-100 transition-colors">
                                                <i class="{{ $service['icon'] ?? 'fas fa-check' }} text-[#e85d26] text-xs"></i>
                                                {{ $service['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Get in Touch --}}
                        <div class="bg-white rounded-lg border border-gray-100 shadow-md p-6 space-y-5 scroll-mt-24" id="contact-form">
                            <div style="margin-bottom: 0.5rem;">
                                <h3 class="font-black text-gray-900 section-heading"
                                    style="font-family: 'Poppins', sans-serif; font-size: 22px;">Get in touch</h3>
                                <p class="text-gray-500 mt-4" style="font-size: 14px;">We are here for you, how can we help?</p>
                            </div>

                            @if(session('success'))
                                <div
                                    class="p-3 bg-green-50 border border-green-100 rounded-xl text-green-700 font-bold text-xs flex items-center gap-2">
                                    <i data-lucide="check-circle" size="16"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div
                                    class="p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 font-bold text-xs flex items-center gap-2">
                                    <i data-lucide="alert-circle" size="16"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-3">
                                @csrf
                                @php
                                    $agentIdForForm = $agentId ?? null;
                                    $agentNameForForm = $agentName ?? 'Tour Raja';
                                @endphp
                                <input type="hidden" name="agent_id" value="{{ $agentIdForForm }}">
                                <input type="hidden" name="subject" value="Inquiry for {{ $package['title'] }}">
                                <input type="hidden" name="agent_name" value="{{ $agentNameForForm }}">
                                <input type="hidden" name="package_name" value="{{ $package['title'] }}">
                                <input type="text" name="name" placeholder="Enter your name"
                                    class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                                <input type="email" name="email" placeholder="Enter your email"
                                    class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                                <div class="flex gap-2 items-center">
                                    <div class="relative w-28 shrink-0">
                                        <select class="phone-country-code w-full px-3 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                                            <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                            <option value="+1" data-len="10">🇺🇸 +1</option>
                                            <option value="+44" data-len="10">🇬🇧 +44</option>
                                            <option value="+62" data-len="11">🇮🇩 +62</option>
                                            <option value="+65" data-len="8">🇸🇬 +65</option>
                                            <option value="+971" data-len="9">🇦🇪 +971</option>
                                            <option value="+61" data-len="9">🇦🇺 +61</option>
                                            <option value="+66" data-len="9">🇹🇭 +66</option>
                                            <option value="+60" data-len="10">🇲🇾 +60</option>
                                        </select>
                                    </div>
                                    <div class="relative flex-grow">
                                        <input type="tel" required placeholder="Phone Number *"
                                            class="phone-number-val w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                                    </div>
                                </div>
                                <input type="hidden" class="phone-full-val" name="phone">
                                <textarea name="message" required rows="3" placeholder="Type your message.."
                                    class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400 resize-none"></textarea>
                                <button type="submit"
                                    class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black text-sm rounded-md transition-colors duration-200 shadow-glow">
                                    Submit Inquiry
                                </button>
                            </form>
                        </div>

                        {{-- Leave Feedback --}}
                        <div class="bg-white rounded-lg border border-gray-100 shadow-md p-6 space-y-5 mt-6" id="feedback-form">
                            <div style="margin-bottom: 0.5rem;">
                                <h3 class="font-black text-gray-900 section-heading"
                                    style="font-family: 'Poppins', sans-serif; font-size: 22px;">Leave Feedback</h3>
                                <p class="text-gray-500 mt-4" style="font-size: 14px;">How was your experience with {{ $agentNameForForm }}?</p>
                            </div>

                            @if(session('feedback_success'))
                                <div
                                    class="p-3 bg-green-50 border border-green-100 rounded-xl text-green-700 font-bold text-xs flex items-center gap-2">
                                    <i data-lucide="check-circle" size="16"></i>
                                    {{ session('feedback_success') }}
                                </div>
                            @endif

                            <form action="{{ route('package.feedback.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="agent_id" value="{{ $agentIdForForm }}">
                                <input type="hidden" name="package_id" value="{{ $package['id'] ?? '' }}">
                                
                                <input type="text" name="customer_name" required placeholder="Your name"
                                    class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                                    
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-700">Rating:</span>
                                    <select name="rating" required class="px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                                        <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                                        <option value="3">⭐⭐⭐ 3 Stars</option>
                                        <option value="2">⭐⭐ 2 Stars</option>
                                        <option value="1">⭐ 1 Star</option>
                                    </select>
                                </div>
                                
                                <textarea name="message" required rows="3" placeholder="Share your experience..."
                                    class="w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400 resize-none"></textarea>
                                
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 block mb-1">Upload Photo (Optional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                </div>
                                
                                <button type="submit"
                                    class="w-full py-3 bg-gray-900 hover:bg-black text-white font-black text-sm rounded-md transition-colors duration-200 shadow-sm mt-2">
                                    Submit Feedback
                                </button>
                            </form>
                        </div>

                    </div>{{-- end right sidebar --}}
                </div>{{-- end grid --}}
            </div>

            <!-- Customer Reviews Section (Marquee Testimonials) -->
            <section class="pt-6 pb-6 lg:pt-8 lg:pb-6 bg-white overflow-hidden border-t border-gray-100">
                <div class="container-custom">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-1 gap-6">
                        <div class="space-y-1">
                            <h2 class="text-[34px] leading-tight font-black text-foreground tracking-tight font-heading"
                                style="font-family: 'Poppins', sans-serif;">What Our Clients Say!!!</h2>
                            <p class="text-text-muted text-base font-medium">They Love Tour Raja!</p>
                        </div>
                    </div>

                    <div class="relative group">
                        <div class="flex gap-6 overflow-hidden pt-2 pb-8 testimonial-track" id="testi-slider">
                            @php
                                $testimonials = \DB::table('reviews')->where('status', 'Active')->orderBy('id', 'desc')->get()->toArray();
                                $allTestimonials = $testimonials; // Removed array_merge so it doesn't duplicate
                            @endphp

                            @foreach($allTestimonials as $testi)
                                <div class="flex-shrink-0 w-full md:w-[450px] testimonial-card">
                                    <div class="p-6 rounded-[32px] border border-gray-200 bg-white shadow-soft hover:shadow-premium transition-all duration-500 h-full flex flex-col space-y-3">
                                        <h4 class="text-lg md:text-xl font-black text-foreground font-heading">The best booking system</h4>
                                        <p class="text-text-muted text-xs md:text-sm leading-relaxed font-medium italic break-words line-clamp-4">"{{ $testi->text }}"</p>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-100 mt-auto gap-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ Str::startsWith($testi->image, 'http') ? $testi->image : asset($testi->image) }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-primary/10">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-foreground text-sm md:text-base">{{ $testi->name }}</span>
                                                    <span class="text-[10px] md:text-xs text-text-muted font-bold">{{ $testi->location }}</span>
                                                </div>
                                            </div>
                                            <div class="flex gap-0.5">
                                                @for($i=0; $i<5; $i++)
                                                    <i data-lucide="star" size="14" class="{{ $i < $testi->rating ? 'fill-orange-400 text-orange-400' : 'text-gray-200' }}"></i>
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
                        0% {
                            transform: translateX(0);
                        }

                        100% {
                            transform: translateX(calc(-450px * 4 - 24px * 4));
                        }
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
                            0% {
                                transform: translateX(0);
                            }

                            100% {
                                transform: translateX(-50%);
                            }
                        }

                        .testimonial-track {
                            animation: marquee-mobile 15s linear infinite;
                        }
                    }
                </style>
            </section>

            @if(!empty($agentPackages) && count($agentPackages) > 0)
            <!-- Agent Packages Suggestions Section -->
            <section class="pt-6 pb-12 lg:pt-8 lg:pb-16 bg-gray-50 border-t border-b border-gray-100">
                <div class="container-custom">
                    <div class="flex items-center justify-between mb-6 md:mb-8">
                        <div class="space-y-1">
                            <h2 class="text-[34px] leading-tight font-black text-foreground tracking-tight font-heading"
                                style="font-family: 'Poppins', sans-serif;">More Packages from {{ $agentName }}</h2>
                            <p class="text-text-muted text-xs font-semibold">Other handcrafted itineraries by this agent</p>
                        </div>
                    </div>

                    <div class="relative w-full">
                        <div id="agent-pkg-slider" class="flex overflow-x-auto gap-4 pb-4 hide-scrollbar scroll-smooth w-full snap-x snap-mandatory">
                            @foreach($agentPackages as $agentPkg)
                                <div style="width: 320px; flex-shrink: 0;" class="snap-start">
                                    <x-package-card :pkg="$agentPkg" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            @endif

            {{-- Booking Modal (Alpine JS wrapper needed at root of page) --}}
            <div x-show="showBookingModal" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showBookingModal = false"></div>
                <div class="relative w-full max-w-lg bg-white rounded-lg shadow-2xl p-6 lg:p-8 animate-fade-in-up max-h-[90vh] overflow-y-auto">
                    <button @click="showBookingModal = false"
                        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
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
                            <input type="text" name="traveler_name" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Email</label>
                                <input type="email" name="traveler_email" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Phone</label>
                                <div class="flex gap-2 items-center">
                                    <div class="relative w-28 shrink-0">
                                        <select class="phone-country-code w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-xs font-bold">
                                            <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                            <option value="+1" data-len="10">🇺🇸 +1</option>
                                            <option value="+44" data-len="10">🇬🇧 +44</option>
                                            <option value="+62" data-len="11">🇮🇩 +62</option>
                                            <option value="+65" data-len="8">🇸🇬 +65</option>
                                            <option value="+971" data-len="9">🇦🇪 +971</option>
                                            <option value="+61" data-len="9">🇦🇺 +61</option>
                                            <option value="+66" data-len="9">🇹🇭 +66</option>
                                            <option value="+60" data-len="10">🇲🇾 +60</option>
                                        </select>
                                    </div>
                                    <div class="relative flex-grow">
                                        <input type="tel" required placeholder="Phone *"
                                            class="phone-number-val w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    </div>
                                </div>
                                <input type="hidden" class="phone-full-val" name="traveler_phone">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Travel Date</label>
                                <input type="date" name="travel_date" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Guests</label>
                                <input type="number" name="guests" min="1" value="2" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Special Requests</label>
                            <textarea name="special_request" rows="2"
                                class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-4 bg-primary hover:bg-primary-hover text-white font-black rounded-md transition-all shadow-lg shadow-primary/30">
                                Confirm Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup drag-to-scroll for sliders
            const setupSlider = (sliderId) => {
                const slider = document.getElementById(sliderId);
                if (!slider) return;

                let isDown = false;
                let startX;
                let scrollLeft;
                let preventClickFlag = false;
                
                slider.style.cursor = 'grab';

                slider.addEventListener('mousedown', (e) => {
                    isDown = true;
                    preventClickFlag = false;
                    slider.style.cursor = 'grabbing';
                    slider.style.userSelect = 'none'; // Prevent text selection
                    startX = e.pageX - slider.offsetLeft;
                    scrollLeft = slider.scrollLeft;
                });

                const endDrag = () => {
                    isDown = false;
                    slider.style.cursor = 'grab';
                    slider.style.removeProperty('user-select');
                    Array.from(slider.children).forEach(c => c.style.pointerEvents = '');
                    
                    if (preventClickFlag) {
                        setTimeout(() => preventClickFlag = false, 100);
                    }
                };

                slider.addEventListener('mouseleave', endDrag);
                slider.addEventListener('mouseup', endDrag);
                
                // Failsafe for if mouse is released outside the element
                window.addEventListener('mouseup', () => {
                    if (isDown) endDrag();
                });

                slider.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - slider.offsetLeft;
                    const walk = (x - startX) * 1.5; // Scroll-fast
                    
                    // If we drag more than 5 pixels, prevent clicking
                    if (Math.abs(walk) > 5) {
                        preventClickFlag = true;
                        Array.from(slider.children).forEach(c => c.style.pointerEvents = 'none');
                    }
                    
                    slider.scrollLeft = scrollLeft - walk;
                });
                
                // Prevent clicks on children if we are dragging
                const preventClick = (e) => {
                    if (preventClickFlag) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                    }
                };
                slider.addEventListener('click', preventClick, true);
            };

            setupSlider('section-nav');
            setupSlider('agent-pkg-slider');
        });
    </script>
    @endpush