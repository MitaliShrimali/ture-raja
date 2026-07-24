@extends('layouts.app')

@section('content')
    <x-hero :banners="$heroBanners ?? collect()" :home-ad="$homeAd ?? null" />

    {{-- Popular Transits is now inside the hero component --}}

    <!-- Section 4: International Packages — small card grid (Image 1 style) -->
    <section class="py-10 bg-white relative overflow-hidden">
        <!-- Floating Decorations -->
        <img src="{{ asset('images/weather.gif') }}" alt="Weather" class="absolute top-2 -left-4 md:left-4 w-16 h-16 md:w-24 md:h-24 z-10 pointer-events-none animate-[pulse_3s_ease-in-out_infinite]" style="opacity: 0.9;" />
        <img src="{{ asset('images/hot-air-balloon.gif') }}" alt="Balloon" class="absolute top-4 -right-4 md:right-8 w-14 h-14 md:w-20 md:h-20 z-10 pointer-events-none animate-[bounce_4s_ease-in-out_infinite]" style="opacity: 0.9;" />
        
        <div class="container-custom relative z-20">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-3 pl-12 pr-12 md:pl-20 md:pr-24">
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
            <div class="flex flex-nowrap gap-5 pt-2 pb-4 overflow-x-auto hide-scrollbar">
                @php
                    if(isset($homeInternational) && $homeInternational->count() > 0) {
                        $intl = $homeInternational->map(function($pkg) {
                            return [
                                'title' => $pkg->title,
                                'image' => $pkg->image,
                            ];
                        })->toArray();
                    } else {
                        $intl = [
                            ['title' => 'Bangkok',   'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Dubai',     'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Las Vegas', 'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Rome',      'image' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Bali',      'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Andaman',   'image' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&q=80&w=400'],
                        ];
                    }
                @endphp
                @foreach($intl as $pkg)
                <a href="{{ url('/discover?destination='.urlencode($pkg['title'])) }}"
                   class="relative rounded-lg overflow-hidden block group flex-1" style="height:115px; min-width: 130px; flex-shrink: 0;">
                    <img src="{{ asset($pkg['image']) }}" alt="{{ $pkg['title'] }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                    <span style="position:absolute; bottom:8px; left:10px; color:#fff; font-size:18px; font-weight:700; text-shadow:0 1px 5px rgba(0,0,0,0.7); line-height:1.2;">{{ $pkg['title'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 5: Domestic Packages — small card grid (Image 1 style) -->
    <section class="py-10 bg-white border-t border-gray-100 relative overflow-hidden">
        <!-- Floating Decorations -->
        <img src="{{ asset('images/small-banner-decoration-3.png') }}" alt="Decoration 3" class="absolute top-2 -left-4 md:left-4 w-12 h-12 md:w-20 md:h-20 z-10 pointer-events-none animate-[pulse_3s_ease-in-out_infinite]" style="opacity: 0.9;" />
        <img src="{{ asset('images/small-banner-decoration-4.png') }}" alt="Decoration 4" class="absolute top-4 -right-4 md:right-8 w-12 h-12 md:w-20 md:h-20 z-10 pointer-events-none animate-[bounce_4s_ease-in-out_infinite]" style="opacity: 0.9;" />
        
        <div class="container-custom relative z-20">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-3 pl-12 pr-12 md:pl-20 md:pr-24">
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
            <div class="flex flex-nowrap gap-5 pt-2 pb-4 overflow-x-auto hide-scrollbar">
                @php
                    if(isset($homeDomestic) && $homeDomestic->count() > 0) {
                        $dom = $homeDomestic->map(function($pkg) {
                            return [
                                'title' => $pkg->title,
                                'image' => $pkg->image,
                            ];
                        })->toArray();
                    } else {
                        $dom = [
                            ['title' => 'Goa',     'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Kerala',  'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Jaipur',  'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Kutch',   'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Mumbai',  'image' => 'https://images.unsplash.com/photo-1566552881560-0be862a7c445?auto=format&fit=crop&q=80&w=400'],
                            ['title' => 'Srinagar','image' => 'https://images.unsplash.com/photo-1562979314-bee7453e911c?auto=format&fit=crop&q=80&w=400'],
                        ];
                    }
                @endphp
                @foreach($dom as $pkg)
                <a href="{{ url('/discover?destination='.urlencode($pkg['title'])) }}"
                   class="relative rounded-lg overflow-hidden block group flex-1" style="height:115px; min-width: 130px; flex-shrink: 0;">
                    <img src="{{ asset($pkg['image']) }}" alt="{{ $pkg['title'] }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                    <span style="position:absolute; bottom:8px; left:10px; color:#fff; font-size:18px; font-weight:700; text-shadow:0 1px 5px rgba(0,0,0,0.7); line-height:1.2;">{{ $pkg['title'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    @if(isset($domesticAds) && $domesticAds->count() > 0)
    <!-- Section: Ads under Domestic Packages -->
    <section class="py-10 bg-gray-50/40 border-t border-b border-gray-100 relative group">
        <div class="container-custom relative flex items-center justify-between">
            <!-- Prev Button -->
            <button type="button" class="w-10 h-10 bg-white rounded-full shadow-md border border-gray-100 flex-shrink-0 items-center justify-center text-gray-600 hover:text-primary transition-colors hidden md:flex mr-4 md:mr-6" onclick="document.getElementById('ads-slider').scrollBy({left: -document.getElementById('ads-slider').clientWidth, behavior: 'smooth'})">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>

            <!-- Scrollable Ads Row -->
            <style>
                .ad-card-custom { flex-shrink: 0; width: 280px; }
                @media (min-width: 768px) { .ad-card-custom { width: calc(50% - 12px); } }
                @media (min-width: 1024px) { .ad-card-custom { width: calc(25% - 18px); } }
            </style>
            <div id="ads-slider" class="flex-1 min-w-0 flex flex-nowrap gap-6 overflow-x-auto hide-scrollbar pb-2 snap-x snap-mandatory scroll-smooth {{ $domesticAds->count() < 4 ? 'lg:justify-center' : '' }}">
                @foreach($domesticAds as $ad)
                <div style="background: #ffffff; border: 1px solid #f0f0f0; border-radius: 24px; box-shadow: 0 8px 24px -8px rgba(0,0,0,0.06); padding: 12px; display: flex; gap: 12px; box-sizing: border-box; transition: shadow 0.3s;" class="group hover:shadow-md relative snap-start ad-card-custom">
                    <!-- Left: Ad Image -->
                    <div style="width: 100px; height: 100px; border-radius: 16px; overflow: hidden; flex-shrink: 0; position: relative;">
                        <img src="{{ asset($ad->image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=300') }}" 
                             alt="{{ $ad->campaign_name }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <!-- AD Label -->
                        <span style="position: absolute; top: 6px; left: 6px; background: rgba(0,0,0,0.6); color: #ffffff; font-weight: 800; text-transform: uppercase; font-size: 8px; letter-spacing: 0.5px; padding: 2px 4px; border-radius: 4px; z-index: 10; font-family: sans-serif;">AD</span>
                    </div>

                    <!-- Right: Ad Details -->
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-w: 0;">
                        <div>
                            @if($ad->subtitle)
                                <p style="font-size: 9px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 2px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $ad->subtitle }}</p>
                            @else
                                <p style="font-size: 9px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 2px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Stop Searching, Start</p>
                            @endif
                            <h4 style="font-weight: 800; color: #1a1a1a; font-size: 13px; line-height: 1.3; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; padding-right: 4px;">
                                {{ $ad->campaign_name }}
                            </h4>
                            <p style="font-size: 10px; font-weight: 500; color: #9ca3af; margin: 2px 0 0 0;">Get Your Best Deal Now!</p>
                        </div>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; gap: 4px;">
                            <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                               style="background-color: #4c75d4; color: #ffffff; padding: 8px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-block; white-space: nowrap; text-align: center; min-width: 85px; flex-shrink: 0; transition: background-color 0.2s;"
                               onmouseover="this.style.backgroundColor='#3b62c2'"
                               onmouseout="this.style.backgroundColor='#4c75d4'">
                                Check Now
                            </a>
                            @if($ad->agent_logo)
                                <img src="{{ asset($ad->agent_logo) }}" 
                                     alt="{{ $ad->agent_name ?? 'Agent' }}" 
                                     style="height: 32px; max-width: 50px; object-fit: contain; opacity: 0.9; transition: opacity 0.2s;"
                                     onmouseover="this.style.opacity='1'"
                                     onmouseout="this.style.opacity='0.9'">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Next Button -->
            <button type="button" class="w-10 h-10 bg-white rounded-full shadow-md border border-gray-100 flex-shrink-0 items-center justify-center text-gray-600 hover:text-primary transition-colors hidden md:flex ml-4 md:ml-6" onclick="document.getElementById('ads-slider').scrollBy({left: document.getElementById('ads-slider').clientWidth, behavior: 'smooth'})">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </section>
    @endif

    <!-- Section 7: Offer Stickers -->
    <section class="pt-5 md:pt-10 pb-2 md:pb-4 relative" style="background-color: #FFF4CE;">
        <div class="container-custom">
            <div class="px-4 py-8 md:px-8 md:py-10 relative flex items-center justify-between w-full" x-data="{
                scrollAmount: 0,
                scrollSpeed: 1,
                animationFrame: null,
                startAutoScroll() {
                    let slider = document.getElementById('promo-slider');
                    if (!slider) return;
                    let scroll = () => {
                        this.scrollAmount += this.scrollSpeed;
                        if (this.scrollAmount >= slider.scrollWidth / 2) {
                            this.scrollAmount = 0;
                        }
                        slider.scrollLeft = this.scrollAmount;
                        this.animationFrame = requestAnimationFrame(scroll);
                    };
                    this.animationFrame = requestAnimationFrame(scroll);
                },
                stopAutoScroll() {
                    cancelAnimationFrame(this.animationFrame);
                    let slider = document.getElementById('promo-slider');
                    if (slider) this.scrollAmount = slider.scrollLeft;
                }
            }" x-init="startAutoScroll()" @mouseenter="stopAutoScroll()" @mouseleave="startAutoScroll()" @touchstart="stopAutoScroll()" @touchend="startAutoScroll()">
                
                <!-- SVG Airplane Path Left -->
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

                <!-- SVG Airplane Path Right -->
                <div class="absolute -top-12 -right-8 md:-top-16 md:-right-16 hidden md:block w-64 h-32 pointer-events-none z-20" style="transform: scaleX(-1);">
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

                <!-- Cards Track -->
                <style>
                    .sticker-card-custom { flex-shrink: 0; width: 260px; }
                    @media (min-width: 768px) { .sticker-card-custom { width: calc(50% - 12px); } }
                    @media (min-width: 1024px) { .sticker-card-custom { width: calc(25% - 18px); } }
                </style>
                <div id="promo-slider" class="flex-1 min-w-0 flex items-center justify-start overflow-x-hidden hide-scrollbar z-10 relative pb-4">
                            @if(isset($offerStickers) && $offerStickers->count() > 0)
                                {{-- Dynamic stickers from admin --}}
                                @php
                                    $stickerArray = $offerStickers->all();
                                    $seamlessStickers = array_merge($stickerArray, $stickerArray);
                                @endphp
                                @foreach($seamlessStickers as $sticker)
                                    <a href="{{ url($sticker->link ?? '/discover') }}"
                                       class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6">

                                        {{-- Image fills the fixed container, cover-cropped --}}
                                        @if($sticker->image)
                                            <img src="{{ asset($sticker->image) }}"
                                                 class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                                 alt="{{ $sticker->title }}">
                                        @endif

                                        <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                            <div class="max-w-[200px] mt-2">
                                                @if($sticker->subtitle)
                                                    <span class="text-[11px] font-bold mb-1 block" style="{{ !empty($sticker->bg_color) ? 'color: ' . $sticker->bg_color . ';' : 'color: #1f2937;' }}">{{ $sticker->subtitle }}</span>
                                                @endif
                                                <h3 class="text-[18px] md:text-[20px] font-black leading-tight" style="{{ !empty($sticker->bg_color) ? 'color: ' . $sticker->bg_color . ';' : 'color: #111827;' }}">{{ $sticker->title }}</h3>
                                            </div>
                                            <div class="mt-auto">
                                                <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #E85D26;">View More &rarr;</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                {{-- Static fallback cards (shown when no stickers added from admin yet) --}}
                                @for($i = 0; $i < 2; $i++)
                                <!-- Card 1 -->
                                <a href="{{ url('/discover') }}" class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6">
                                    <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/img1offer.jpg" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Travel">
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
                                <a href="{{ url('/discover') }}" class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6" style="background-color: #FCE08F;">
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
                                <a href="{{ url('/discover') }}" class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6">
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

                                <!-- Card 4 -->
                                <a href="{{ url('/discover') }}" class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6">
                                    <img src="https://images.unsplash.com/photo-1540206351-d6465b3ac5c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Beach Vacations">
                                    <div class="absolute inset-0 bg-black/30"></div>
                                    <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                        <div class="flex flex-col items-start space-y-[3px]">
                                            <span class="text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #3b82f6;">Special Discount</span>
                                            <span class="text-white text-[16px] md:text-[18px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #3b82f6;">20% Off Family</span>
                                            <span class="text-white text-[16px] md:text-[18px] font-bold px-2 py-0.5 rounded shadow-sm" style="background-color: #3b82f6;">Vacations</span>
                                        </div>
                                        <div class="mt-auto">
                                            <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #3b82f6;">View More &rarr;</span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card 5 -->
                                <a href="{{ url('/discover') }}" class="sticker-card-custom h-48 md:h-52 rounded-[24px] overflow-hidden relative group block hover:-translate-y-1 transition-transform duration-300 shadow-sm mr-4 md:mr-6" style="background-color: #FFC0CB;">
                                    <img src="https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="absolute right-0 bottom-0 w-1/2 h-full object-cover object-center opacity-90 transition-transform duration-500 group-hover:scale-105" style="mask-image: linear-gradient(to right, transparent, black 40%); -webkit-mask-image: linear-gradient(to right, transparent, black 40%);" alt="Honeymoon">
                                    <div class="absolute inset-0 p-6 flex flex-col justify-between z-10">
                                        <div class="max-w-[150px] mt-2">
                                            <span class="text-[11px] font-bold text-gray-800 mb-1 block">Couples Only</span>
                                            <h3 class="text-gray-900 text-[18px] md:text-[20px] font-black leading-tight">Romantic<br>Getaways</h3>
                                        </div>
                                        <div class="mt-auto">
                                            <span class="inline-block text-white text-[11px] font-bold px-4 py-1.5 rounded-full hover:opacity-90 transition-opacity" style="background-color: #db2777;">View More &rarr;</span>
                                        </div>
                                    </div>
                                </a>
                                @endfor
                        @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Why Travel With TourRaja -->
    <div class="max-w-7xl mx-auto px-6 py-4 md:py-8 lg:py-10">
        <x-section-title subtitle="The TourRaja Advantage" align="center">
            Why tourraja !!!
            <x-slot:description>The best booking platform you can trust</x-slot:description>
        </x-section-title>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Security Assurance -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #F8EFE4 !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <img src="{{ asset('svgs/security.svg.svg') }}" alt="Security Assurance" class="w-8 h-8 object-contain">
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Security Assurance</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Demonstrates commitment to user data security</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 2: Best Price Deals -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #E8E9EC !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <img src="{{ asset('svgs/support.svg.svg') }}" alt="Best Price Deals" class="w-8 h-8 object-contain">
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Best Price Deals</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Compare and choose the most affordable package</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 3: Direct Agent Contacts -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #F8EFE4 !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <img src="{{ asset('svgs/policy.svg.svg') }}" alt="Direct Agent Contacts" class="w-8 h-8 object-contain">
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Direct Agent Contacts</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">No middleman, connect instantly</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 4: Find Nearby Travel Agent -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #E8E9EC !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <img src="{{ asset('svgs/repu.svg.svg') }}" alt="Find Nearby Travel Agent" class="w-8 h-8 object-contain">
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Find Nearby Travel Agent</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Find the perfect trip without confusion with your local agent</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>
        </div>
    </div>

    <!-- Section 6: Browse by Travel Theme -->
    <section class="py-6 md:py-10 bg-white border-t border-border-soft/30 animate-fade-up">
        <div class="container-custom">
            <h2 class="font-black text-foreground text-center mb-10 tracking-tight font-heading" style="font-size: 28px;">Browse by Travel Theme</h2>

            <div class="relative w-full group px-2 md:px-8">
                <!-- Left Arrow -->
                <button type="button" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex opacity-0 group-hover:opacity-100" onclick="document.getElementById('theme-slider').scrollBy({left: -250, behavior: 'smooth'})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>

                <div id="theme-slider" class="flex overflow-x-auto gap-4 lg:gap-8 items-start w-full hide-scrollbar scroll-smooth pb-4 px-2">
                    @if(isset($themes) && !$themes->isEmpty())
                        @foreach($themes as $theme)
                            <a href="{{ url('/discover?theme=' . urlencode($theme->name)) }}" class="group flex flex-col items-center text-center space-y-2 cursor-pointer shrink-0 w-24 md:w-28 lg:w-32 animate-hover">
                                <div class="w-full aspect-square rounded-lg overflow-hidden shadow-soft transition-all duration-500 group-hover:scale-105 group-hover:shadow-premium">
                                    <img src="{{ asset($theme->image) }}" alt="{{ $theme->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>
                                <h4 class="font-semibold text-foreground group-hover:text-[#e85d26] transition-colors text-xs md:text-sm tracking-tight whitespace-nowrap">
                                    {{ $theme->name }}
                                </h4>
                            </a>
                        @endforeach
                    @else
                        @php
                            $fallbackThemes = [
                                ['label' => 'Family/Group', 'image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Religious', 'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Honeymoon', 'image' => 'https://images.unsplash.com/photo-1573152958734-1922c188fba3?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Solo', 'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Adventure', 'image' => 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Cruise', 'image' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'WaterPark', 'image' => 'https://images.unsplash.com/photo-1582650625119-3a31f8fa2699?auto=format&fit=crop&q=80&w=400&v=1'],
                                ['label' => 'Pilgrimage', 'image' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&q=80&w=400&v=1'],
                            ];
                        @endphp
                        @foreach($fallbackThemes as $theme)
                            <a href="{{ url('/discover?theme=' . urlencode($theme['label'])) }}" class="group flex flex-col items-center text-center space-y-2 cursor-pointer shrink-0 w-24 md:w-28 lg:w-32">
                                <div class="w-full aspect-square rounded-lg overflow-hidden shadow-soft transition-all duration-500 group-hover:scale-105 group-hover:shadow-premium">
                                    <img src="{{ asset($theme['image']) }}" alt="{{ $theme['label'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>
                                <h4 class="font-semibold text-foreground group-hover:text-[#e85d26] transition-colors text-xs md:text-sm tracking-tight whitespace-nowrap">
                                    {{ $theme['label'] }}
                                </h4>
                            </a>
                        @endforeach
                    @endif
                </div>

                <!-- Right Arrow -->
                <button type="button" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-[#e85d26] transition-colors hidden md:flex opacity-0 group-hover:opacity-100" onclick="document.getElementById('theme-slider').scrollBy({left: 250, behavior: 'smooth'})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Section 2: Featured Travel Packages -->
    <section class="py-6 md:py-10 lg:py-12 bg-background">
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
                        <div id="cat-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl overflow-hidden shadow-premium min-w-[160px]" style="z-index: 40;">
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
                        <div id="dur-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl overflow-hidden shadow-premium min-w-[170px]" style="z-index: 40;">
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
                        <div id="rat-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl overflow-hidden shadow-premium min-w-[160px]" style="z-index: 40;">
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
                        <div id="pri-menu" class="hidden absolute right-0 top-12 bg-white border border-border-soft rounded-2xl overflow-hidden shadow-premium min-w-[180px]" style="z-index: 40;">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8" id="packages-grid" style="position: relative; z-index: 1;">
                @php
                    // Fetch all agents to check their locations for the home page cards
                    try {
                        $agentsList = \Illuminate\Support\Facades\DB::table('agents')
                            ->select('id', 'name', 'city', 'state', 'logo')
                            ->get();
                        $agentsById = $agentsList->keyBy('id')->toArray();
                        $agentsByName = $agentsList->keyBy(function($item) {
                            return strtolower(trim($item->name));
                        })->toArray();
                    } catch (\Exception $e) {
                        $agentsById = [];
                        $agentsByName = [];
                    }

                    $dbPkgs = isset($packages) && $packages instanceof \Illuminate\Support\Collection ? $packages : collect();
                    $mappedDbPkgs = $dbPkgs->map(function($pkg) use ($agentsById, $agentsByName) {
                        if (is_object($pkg)) {
                            $title = $pkg->title ?? '';
                            $price = $pkg->price ?? 0;
                            $oldPrice = $pkg->old_price ?? null;
                            $rating = $pkg->rating ?? '4.8';
                            $reviews = $pkg->reviews ?? '10';
                            $duration = $pkg->duration ?? '3 Days';
                            $groupSize = $pkg->group_size ?? '4-6 guest';
                            $image = $pkg->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800';
                            $category = $pkg->category ?? 'Tropical';
                            $badge = $pkg->badge ?? null;
                            $slugVal = $pkg->slug ?? null;
                            $idVal = $pkg->id ?? null;
                            $agentData = $pkg->agent ?? null;
                            $tourType = $pkg->tour_type ?? '';
                            $theme = $pkg->theme ?? '';
                        } else {
                            $title = $pkg['title'] ?? '';
                            $price = $pkg['price'] ?? 0;
                            $oldPrice = $pkg['old_price'] ?? ($pkg['oldPrice'] ?? null);
                            $rating = $pkg['rating'] ?? '4.8';
                            $reviews = $pkg['reviews'] ?? '10';
                            $duration = $pkg['duration'] ?? '3 Days';
                            $groupSize = $pkg['group_size'] ?? ($pkg['groupSize'] ?? '4-6 guest');
                            $image = $pkg['image'] ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800';
                            $category = $pkg['category'] ?? 'Tropical';
                            $badge = $pkg['badge'] ?? null;
                            $slugVal = $pkg['slug'] ?? null;
                            $idVal = $pkg['id'] ?? null;
                            $agentData = $pkg['agent'] ?? null;
                            $tourType = $pkg['tour_type'] ?? '';
                            $theme = $pkg['theme'] ?? '';
                        }

                        $durationDays = 3;
                        if ($duration) {
                            preg_match('/\d+/', $duration, $matches);
                            if (!empty($matches)) {
                                $durationDays = (int) $matches[0];
                            }
                        }
                        if (!$slugVal && $title) {
                            $slugVal = \Illuminate\Support\Str::slug($title);
                        }

                        // Resolve Agent
                        $agentId = null;
                        $agentName = null;
                        if (is_string($agentData)) {
                            $decoded = json_decode($agentData, true);
                            if (is_array($decoded)) {
                                $agentId = $decoded['id'] ?? null;
                                $agentName = $decoded['name'] ?? null;
                                $agentData = $decoded;
                            } else {
                                $agentName = $agentData;
                                $agentData = ['name' => $agentData];
                            }
                        } elseif (is_object($agentData)) {
                            $agentId = $agentData->id ?? null;
                            $agentName = $agentData->name ?? null;
                            $agentData = (array)$agentData;
                        } elseif (is_array($agentData)) {
                            $agentId = $agentData['id'] ?? null;
                            $agentName = $agentData['name'] ?? null;
                        }

                        $dbAgent = null;
                        if ($agentId && isset($agentsById[$agentId])) {
                            $dbAgent = (array)$agentsById[$agentId];
                        } elseif ($agentName) {
                            $key = strtolower(trim($agentName));
                            if (isset($agentsByName[$key])) {
                                $dbAgent = (array)$agentsByName[$key];
                            }
                        }

                        if ($dbAgent) {
                            $agentData['city'] = $dbAgent['city'] ?? '';
                            $agentData['state'] = $dbAgent['state'] ?? '';
                            if (empty($agentData['logo']) && !empty($dbAgent['logo'])) {
                                $agentData['logo'] = $dbAgent['logo'];
                            }
                        }

                        return [
                            'slug' => $slugVal ?: 'package-' . ($idVal ?? rand(1000, 9999)),
                            'title' => $title,
                            'image' => $image,
                            'duration' => $duration,
                            'duration_days' => $durationDays,
                            'groupSize' => $groupSize,
                            'rating' => $rating,
                            'reviews' => $reviews,
                            'price' => $price,
                            'oldPrice' => $oldPrice,
                            'badge' => $badge,
                            'category' => strtolower($category),
                            'tour_type' => $tourType,
                            'theme' => $theme,
                            'agent' => $agentData,
                        ];
                    })->toArray();

                    $staticPkgs = [
                        ['slug'=>'monaco-luxury-tour','title'=>'Monaco Luxury Tour Package','image'=>'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=600','duration'=>'2 days 3 nights','duration_days'=>2,'groupSize'=>'4-6 guest','rating'=>'4.96','reviews'=>'672','price'=>44825,'oldPrice'=>59825,'badge'=>'Top Rated','category'=>'international', 'tour_type'=>'Cruise Package', 'theme'=>'Honeymoon', 'agent'=>'Azure Horizons'],
                        ['slug'=>'vietnam-tour-package','title'=>'Vietnam Tour Package','image'=>'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=600','duration'=>'3 days 3 nights','duration_days'=>3,'groupSize'=>'2-3 guest','rating'=>'4.91','reviews'=>'670','price'=>17320,'oldPrice'=>25320,'badge'=>'Best Sale','category'=>'international', 'tour_type'=>'Flight Package', 'theme'=>'Adventure', 'agent'=>'Nomad Ventures'],
                        ['slug'=>'char-dham-yatra','title'=>'Char Dham Yatra Package','image'=>'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=600','duration'=>'7 days 6 nights','duration_days'=>7,'groupSize'=>'4-6 guest','rating'=>'4.86','reviews'=>'656','price'=>15463,'oldPrice'=>19000,'badge'=>'25% Off','category'=>'religious', 'tour_type'=>'Bus Package', 'theme'=>'Religious', 'agent'=>'Miths Holidays'],
                        ['slug'=>'goa-beach-package','title'=>'Goa Beach Holiday Package','image'=>'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600','duration'=>'2 days 3 nights','duration_days'=>2,'groupSize'=>'4-6 guest','rating'=>'4.74','reviews'=>'631','price'=>14755,'oldPrice'=>19825,'badge'=>'Top Rated','category'=>'domestic', 'tour_type'=>'Flight Package', 'theme'=>'Honeymoon', 'agent'=>'Miths Holidays'],
                        ['slug'=>'spiti-valley-adventure','title'=>'Spiti Valley Package','image'=>'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=600','duration'=>'3 days 3 nights','duration_days'=>3,'groupSize'=>'4-6 guest','rating'=>'4.51','reviews'=>'617','price'=>24840,'oldPrice'=>31825,'badge'=>'Best Sale','category'=>'adventure', 'tour_type'=>'Train Package', 'theme'=>'Adventure', 'agent'=>'Nomad Ventures'],
                        ['slug'=>'swiss-paris-delight','title'=>'Swiss Paris Delight','image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600','duration'=>'7 days 6 nights','duration_days'=>7,'groupSize'=>'4-6 guest','rating'=>'4.29','reviews'=>'608','price'=>51247,'oldPrice'=>null,'badge'=>'25% Off','category'=>'international', 'tour_type'=>'Flight Package', 'theme'=>'Family/Group', 'agent'=>'Globe Trotters'],
                        ['slug'=>'kerala-backwaters','title'=>'Kerala Backwaters Escape','image'=>'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=600','duration'=>'4 days 3 nights','duration_days'=>4,'groupSize'=>'2-4 guest','rating'=>'4.65','reviews'=>'420','price'=>12500,'oldPrice'=>15000,'badge'=>'Popular','category'=>'domestic', 'tour_type'=>'Train Package', 'theme'=>'Honeymoon', 'agent'=>'Miths Holidays'],
                        ['slug'=>'dubai-desert-safari','title'=>'Dubai Desert Safari & Burj','image'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=600','duration'=>'4 days 3 nights','duration_days'=>4,'groupSize'=>'2-6 guest','rating'=>'4.8','reviews'=>'890','price'=>29999,'oldPrice'=>35000,'badge'=>'Trending','category'=>'international', 'tour_type'=>'Flight Package', 'theme'=>'Family/Group', 'agent'=>'Atlas Global Travels'],
                        ['slug'=>'bali-luxury-villa','title'=>'Bali Luxury Villa Escape','image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=600','duration'=>'5 days 4 nights','duration_days'=>5,'groupSize'=>'2 guest','rating'=>'4.9','reviews'=>'543','price'=>35000,'oldPrice'=>42000,'badge'=>'Honeymoon','category'=>'international', 'tour_type'=>'Flight Package', 'theme'=>'Honeymoon', 'agent'=>'Miths Holidays'],
                    ];

                    // Map locations for static packages as well
                    $staticPkgs = array_map(function($pkg) use ($agentsById, $agentsByName) {
                        $agentName = $pkg['agent'] ?? null;
                        $agentData = ['name' => $agentName];
                        if ($agentName) {
                            $key = strtolower(trim($agentName));
                            if (isset($agentsByName[$key])) {
                                $dbAgent = (array)$agentsByName[$key];
                                $agentData['city'] = $dbAgent['city'] ?? '';
                                $agentData['state'] = $dbAgent['state'] ?? '';
                                $agentData['logo'] = $dbAgent['logo'] ?? '';
                            }
                        }
                        $pkg['agent'] = $agentData;
                        return $pkg;
                    }, $staticPkgs);

                    $dbSlugs = array_column($mappedDbPkgs, 'slug');
                    $filteredStaticPkgs = array_filter($staticPkgs, function($static) use ($dbSlugs) {
                        return !in_array($static['slug'], $dbSlugs);
                    });

                    $packages = array_merge($mappedDbPkgs, $filteredStaticPkgs);
                @endphp

                @foreach($packages as $pkg)
                    <div class="animate-fade-up pkg-card {{ $loop->index >= 8 ? 'hidden' : '' }}"
                         style="animation-delay: {{ $loop->index * 100 }}ms"
                         data-category="{{ $pkg['category'] }}"
                         data-duration="{{ $pkg['duration_days'] }}"
                         data-rating="{{ $pkg['rating'] }}"
                         data-price="{{ $pkg['price'] }}">
                        <x-package-card :pkg="$pkg" />
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="mt-8 md:mt-20 flex justify-center animate-fade-up" id="load-more-container">
                <a href="{{ url('/discover') }}" class="bg-black text-white px-10 py-4 rounded-full font-black text-sm uppercase tracking-widest hover:bg-primary transition-all duration-300 shadow-premium inline-block text-center">
                    Load More Packages
                </a>
            </div>
        </div>
    </section>


    <!-- Section 7.5: Top Categories Packages -->
    <section class="py-6 md:py-10 lg:pt-8 lg:pb-12 bg-white">
        <div class="container-custom">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-10 gap-3">
                <div class="space-y-1">
                    <h2 class="text-2xl md:text-[40px] font-black text-foreground tracking-tight leading-tight">Top Categories Packages</h2>
                    <p class="text-gray-500 text-sm md:text-base">Favorite destinations based on customer reviews</p>
                </div>
                <a href="{{ url('/discover') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-gray-200 text-sm font-bold hover:bg-gray-300 transition-colors self-start md:self-auto mt-2 md:mt-0">
                    View More 
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Card 1: Mountain -->
                <a href="{{ url('/discover?categories[]=Mountain') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Safari') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Desert') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Flower') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Beach') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Temples') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover?categories[]=Yacht') }}" class="bg-white rounded-[24px] p-3 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow group block">
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
                <a href="{{ url('/discover') }}" class="rounded-[24px] p-6 flex flex-col justify-between group block hover:shadow-md transition-shadow" style="background-color: #E85D26;">
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
    <section class="pt-6 md:pt-10 lg:pt-8 bg-white overflow-hidden">
        <div class="container-custom">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 md:mb-4 animate-fade-up gap-6">
                <div class="space-y-2">
                    <h2 class="text-3xl md:text-4xl lg:text-6xl font-black text-foreground tracking-tight font-heading">What Our Clients Say!!!</h2>
                    <p class="text-text-muted text-base md:text-lg font-medium">They Love TourRaja!</p>
                </div>

            </div>

            <div class="relative group">
                <div class="flex gap-6 overflow-hidden pt-2 pb-8 testimonial-track" id="testi-slider">
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
                                        <img src="{{ asset($testi['img']) }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-primary/10">
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
                        <h2 class="font-black text-foreground leading-[1.1] tracking-tight font-heading" style="font-size: 38px;">
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

    @if(isset($footerAds) && $footerAds->count() > 0)
    <!-- Section: Ads under Newsletter -->
    <section class="pb-12 lg:pb-20 bg-white">
        <div class="container-custom space-y-6">
            @foreach($footerAds as $ad)
                <div class="w-full flex justify-center">
                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank" class="block w-full h-[80px] md:h-[100px] lg:h-[120px] bg-gray-100 rounded-[20px] shadow-sm hover:shadow-md transition-shadow overflow-hidden group flex items-center justify-center relative">
                        <img src="{{ asset($ad->image) }}" alt="{{ $ad->campaign_name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-black/60 text-white font-extrabold uppercase text-[9px] tracking-widest px-2 py-0.5 rounded-md backdrop-blur-xs">AD</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Welcome Popup Modal --}}
    <div id="welcome-popup" onclick="closeWelcomePopup()" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-500">
        <div id="welcome-popup-content" class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full relative transform scale-95 transition-transform duration-500" onclick="event.stopPropagation()">
            
            <!-- Content Container -->
            <div class="px-8 pt-12 pb-10 text-center relative z-10 flex flex-col items-center">
                
                <!-- Hexagon Alert Icon -->
                <div class="relative mb-6 flex justify-center items-center">
                    <!-- Subtle outer glow -->
                    <div class="absolute inset-0 blur-2xl opacity-20 rounded-full w-24 h-24 m-auto" style="background-color: #e85d26;"></div>
                    
                    <!-- Sparkles/Stars -->
                    <svg class="absolute -top-4 -left-6 w-3 h-3" style="color: #fbd7c9;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
                    <svg class="absolute top-2 -right-4 w-4 h-4" style="color: #fbd7c9;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
                    <svg class="absolute bottom-0 -left-2 w-2 h-2" style="color: #fbd7c9;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
                    <svg class="absolute bottom-4 -right-2 w-2 h-2" style="color: #fbd7c9;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2 9 9 2-9 2-2 9-2-9-9-2 9-2z"/></svg>
                    
                    <!-- 3D Hexagon SVG -->
                    <svg class="w-20 h-20 relative z-10 drop-shadow-xl" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Hexagon Base -->
                        <path d="M50 5L90 28V72L50 95L10 72V28L50 5Z" fill="#F47738"/>
                        <!-- 3D Inner Top Highlight -->
                        <path d="M50 5L90 28V72L50 95L10 72V28L50 5Z" fill="url(#hex-gradient)"/>
                        <path d="M50 8L85 29.5V69.5L50 90L15 69.5V29.5L50 8Z" fill="#E85D26"/>
                        <!-- Inner Exclamation Mark -->
                        <rect x="46" y="28" width="8" height="24" rx="4" fill="white"/>
                        <circle cx="50" cy="62" r="4.5" fill="white"/>
                        
                        <defs>
                            <linearGradient id="hex-gradient" x1="50" y1="5" x2="50" y2="95" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#FF9E66"/>
                                <stop offset="100%" stop-color="#D04D1B"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                
                <!-- Heading -->
                <h3 class="text-[28px] font-extrabold text-gray-900 mb-3 tracking-tight">Before You Book</h3>
                
                <!-- Text -->
                <p class="text-gray-600 text-[15px] leading-relaxed mb-8 max-w-sm px-2 font-medium">
                    It's important to check reviews, compare packages, and confirm the quality of services before booking.
                </p>
                
                <!-- Button -->
                <button onclick="closeWelcomePopup()" class="w-full sm:w-auto text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all duration-300 hover:-translate-y-0.5 flex items-center justify-center gap-2 group relative overflow-hidden z-20" style="background-color: #e85d26;">
                    Got it!
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>

            <!-- Decorative Wavy Background Elements -->
            <!-- Back Wave (Light Orange) -->
            <svg class="absolute bottom-0 left-0 w-full pointer-events-none transform translate-y-1" viewBox="0 0 1440 320" preserveAspectRatio="none" style="height: 200px; color: #fdba74;">
                <path fill="currentColor" fill-opacity="1" d="M0,192L48,176C96,160,192,128,288,138.7C384,149,480,203,576,213.3C672,224,768,192,864,170.7C960,149,1056,139,1152,149.3C1248,160,1344,192,1392,208L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <!-- Front Wave (Little Dark Orange) -->
            <svg class="absolute bottom-0 left-0 w-full pointer-events-none" viewBox="0 0 1440 320" preserveAspectRatio="none" style="height: 60px; color: #fb923c;">
                <path fill="currentColor" fill-opacity="1" d="M0,96L60,117.3C120,139,240,181,360,192C480,203,600,181,720,149.3C840,117,960,75,1080,80C1200,85,1320,139,1380,165.3L1440,192L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
              @push('scripts')
    <script>
        (function() {
            let welcomePopupTimeout;

            function initWelcome() {
                if (sessionStorage.getItem('welcomePopupShown') === 'true') return;
                
                const popup = document.getElementById('welcome-popup');
                const content = document.getElementById('welcome-popup-content');
                if (!popup) return;
                
                // Show smoothly after a short delay
                setTimeout(() => {
                    popup.classList.remove('opacity-0', 'pointer-events-none');
                    popup.classList.add('opacity-100');
                    if (content) {
                        content.classList.remove('scale-95');
                        content.classList.add('scale-100');
                    }
                    
                    // Auto-hide after 3 seconds
                    welcomePopupTimeout = setTimeout(() => {
                        window.closeWelcomePopup();
                    }, 3000);
                }, 500);
            }

            window.closeWelcomePopup = function() {
                const popup = document.getElementById('welcome-popup');
                const content = document.getElementById('welcome-popup-content');
                if (!popup) return;
                
                // Hide smoothly
                popup.classList.remove('opacity-100');
                popup.classList.add('opacity-0', 'pointer-events-none');
                if (content) {
                    content.classList.remove('scale-100');
                    content.classList.add('scale-95');
                }
                
                sessionStorage.setItem('welcomePopupShown', 'true');
                
                if (welcomePopupTimeout) {
                    clearTimeout(welcomePopupTimeout);
                }

                // Smooth transition to show Chef modal popup
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('open-chef-modal'));
                }, 400);
            };

            function initSlidersAndFilters() {
                // ── Sliders ──────────────────────────────────────────────
                const setupSlider = (sliderId, prevBtnId, nextBtnId, fallbackScroll = 340, autoSwipe = true) => {
                    const slider = document.getElementById(sliderId);
                    const prevBtn = document.getElementById(prevBtnId);
                    const nextBtn = document.getElementById(nextBtnId);

                    if (!slider) return;
                    
                    const getScrollAmount = () => {
                        const firstChild = slider.firstElementChild;
                        if (firstChild) {
                            const gap = parseFloat(window.getComputedStyle(slider).gap) || 0;
                            return firstChild.offsetWidth + gap;
                        }
                        return fallbackScroll;
                    };

                    const moveNext = () => {
                        const maxScroll = slider.scrollWidth - slider.clientWidth;
                        if (maxScroll <= 5) return; // No scrolling needed
                        
                        if (slider.scrollLeft >= maxScroll - 10) {
                            slider.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
                        }
                    };

                    const movePrev = () => {
                        if (slider.scrollLeft <= 10) {
                            const maxScroll = slider.scrollWidth - slider.clientWidth;
                            slider.scrollTo({ left: maxScroll, behavior: 'smooth' });
                        } else {
                            slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
                        }
                    };

                    if (nextBtn) nextBtn.addEventListener('click', moveNext);
                    if (prevBtn) prevBtn.addEventListener('click', movePrev);

                    if (autoSwipe) {
                        let interval = setInterval(moveNext, 2500);
                        const resetInterval = () => {
                            clearInterval(interval);
                            interval = setInterval(moveNext, 2500);
                        };
                        slider.addEventListener('mouseenter', () => clearInterval(interval));
                        slider.addEventListener('mouseleave', resetInterval);
                        slider.addEventListener('touchstart', () => clearInterval(interval), {passive: true});
                        slider.addEventListener('touchend', resetInterval, {passive: true});
                        slider.addEventListener('scroll', () => {
                            clearTimeout(slider.scrollTimeout);
                            clearInterval(interval);
                            slider.scrollTimeout = setTimeout(resetInterval, 2500);
                        }, {passive: true});
                    }
                };
                
                setupSlider('intl-slider', 'prev-intl', 'next-intl', 374, true);
                setupSlider('dom-slider', 'prev-dom', 'next-dom', 374, true);
                setupSlider('promo-slider', 'prev-promo', 'next-promo', 360, false);

                // ── Close dropdowns when clicking outside ─────────────────
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.filter-dropdown')) {
                        document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
                    }
                });
            }

            // ── Filter State ─────────────────────────────────────────────
            const activeFilters = { category: 'all', duration: 'all', rating: 'all', price: 'all' };

            window.toggleDropdown = function(menuId) {
                const menu = document.getElementById(menuId);
                if (!menu) return;
                const allMenus = document.querySelectorAll('[id$="-menu"]');
                allMenus.forEach(m => { if (m.id !== menuId) m.classList.add('hidden'); });
                menu.classList.toggle('hidden');
            };

            window.applyFilter = function(type, value, btnId, label) {
                activeFilters[type] = value;
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.childNodes[0].textContent = label + ' ';
                    const isActive = value !== 'all';
                    btn.classList.toggle('bg-primary', isActive);
                    btn.classList.toggle('text-white', isActive);
                    btn.classList.toggle('border-primary', isActive);
                    btn.classList.toggle('hover:bg-primary', isActive);
                    btn.classList.toggle('hover:text-white', isActive);
                    btn.classList.toggle('bg-white', !isActive);
                    btn.classList.toggle('text-foreground', !isActive);
                    btn.classList.toggle('border-border-soft', !isActive);
                    btn.classList.toggle('hover:bg-gray-50', !isActive);
                }
                // hide this dropdown
                document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
                filterCards();
            };

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

            window.resetFilters = function() {
                ['category','duration','rating','price'].forEach(t => {
                    activeFilters[t] = 'all';
                });
                const defaults = { 'cat-btn':'Categories','dur-btn':'Duration','rat-btn':'Review / Rating','pri-btn':'Price range' };
                Object.entries(defaults).forEach(([id, label]) => {
                    const btn = document.getElementById(id);
                    if (btn) {
                        btn.childNodes[0].textContent = label + ' ';
                        btn.className = "px-5 py-2.5 rounded-full bg-white border border-border-soft text-foreground text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-all";
                    }
                });
                filterCards();
            };

            window.loadMoreFeatured = function() {
                const hiddenCards = document.querySelectorAll('.pkg-card.hidden');
                for (let i = 0; i < 3 && i < hiddenCards.length; i++) {
                    hiddenCards[i].classList.remove('hidden');
                    hiddenCards[i].classList.add('animate-fade-up');
                }
                if (document.querySelectorAll('.pkg-card.hidden').length === 0) {
                    document.getElementById('load-more-container').style.display = 'none';
                }
            };

            // Run setups safely
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initWelcome();
                    initSlidersAndFilters();
                });
            } else {
                initWelcome();
                initSlidersAndFilters();
            }
        })();
    </script>
    @endpush
@endsection

