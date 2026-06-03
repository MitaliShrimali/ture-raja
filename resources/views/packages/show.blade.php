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
        font-size: 38px !important;
        font-family: 'Outfit', sans-serif !important;
        line-height: 1.25 !important;
    }
    .section-heading {
        font-size: 32px !important;
        font-family: 'Outfit', sans-serif !important;
        line-height: 1.25 !important;
        font-weight: 900 !important;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="bg-[#F8F9FA] min-h-screen pt-24 lg:pt-32" x-data="{ showBookingModal: false }">
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
                        @if($package['badge'])
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
                <div x-data="{ 
                    showGallery: false, 
                    activeImage: 0,
                    slides: [
                        '{{ $package['image'] }}',
                        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&q=80&w=1200'
                    ],
                    openGallery(index) {
                        this.activeImage = index;
                        this.showGallery = true;
                        document.body.classList.add('overflow-hidden');
                        this.$nextTick(() => {
                            this.scrollToImage(index, 'auto');
                        });
                    },
                    closeGallery() {
                        this.showGallery = false;
                        document.body.classList.remove('overflow-hidden');
                    },
                    nextImage() {
                        const nextIndex = (this.activeImage + 1) % this.slides.length;
                        this.scrollToImage(nextIndex, 'smooth');
                    },
                    prevImage() {
                        const prevIndex = (this.activeImage - 1 + this.slides.length) % this.slides.length;
                        this.scrollToImage(prevIndex, 'smooth');
                    },
                    scrollToImage(index, behavior = 'smooth') {
                        this.activeImage = index;
                        const container = this.$refs.sliderContainer;
                        if (!container) return;
                        const slide = container.children[index];
                        if (slide) {
                            container.scrollTo({
                                left: slide.offsetLeft - (container.clientWidth - slide.clientWidth) / 2,
                                behavior: behavior
                            });
                        }
                    },
                    updateActiveFromScroll() {
                        const container = this.$refs.sliderContainer;
                        if (!container) return;
                        const scrollCenter = container.scrollLeft + container.clientWidth / 2;
                        let closestIndex = 0;
                        let minDistance = Infinity;
                        
                        Array.from(container.children).forEach((slide, index) => {
                            const slideCenter = slide.offsetLeft + slide.clientWidth / 2;
                            const distance = Math.abs(scrollCenter - slideCenter);
                            if (distance < minDistance) {
                                minDistance = distance;
                                closestIndex = index;
                            }
                        });
                        this.activeImage = closestIndex;
                    }
                }" class="mb-6">
                    
                    {{-- Gallery Layout --}}
                    <div class="grid grid-cols-3 gap-2 w-full overflow-hidden relative" style="height: 320px;">
                        {{-- Large Left Image --}}
                        <div class="col-span-2 h-full w-full relative cursor-pointer hover:opacity-95 transition overflow-hidden" @click="openGallery(0)">
                            <img :src="slides[0]" class="w-full h-full object-cover" alt="Package Main Image">
                        </div>
                        
                        {{-- Small Right Images (Top and Bottom) --}}
                        <div class="col-span-1 grid grid-rows-2 gap-2 h-full w-full">
                            <img :src="slides[1]" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" @click="openGallery(1)">
                            <img :src="slides[2]" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" @click="openGallery(2)">
                        </div>

                        {{-- View Gallery Button (Fixed on the grid container) --}}
                        <button @click.stop="openGallery(0)" class="absolute bottom-4 right-4 view-gallery-btn text-[12px] font-bold px-4 py-2.5 flex items-center gap-2 z-30 shadow-lg rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span>View Gallery</span>
                        </button>
                    </div>

                    {{-- Lightbox / Gallery Modal --}}
                    <div x-show="showGallery" x-cloak class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/95 backdrop-blur-sm py-6" @keydown.escape.window="closeGallery()" @keydown.arrow-left.window="prevImage()" @keydown.arrow-right.window="nextImage()" @click="closeGallery()">
                        {{-- Close Button --}}
                        <button @click.stop="closeGallery()" class="absolute top-6 right-6 text-white bg-black/60 hover:bg-black w-10 h-10 rounded-full flex items-center justify-center transition-all z-50 border border-white/10 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        
                        {{-- Prev Button --}}
                        <button @click.stop="prevImage()" class="absolute left-6 text-white bg-black/60 hover:bg-black w-12 h-12 rounded-full flex items-center justify-center transition-all z-50 border border-white/10 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        
                        {{-- Slider Container --}}
                        <div class="w-full max-w-6xl relative flex-grow flex items-center justify-center" @click.stop>
                            <div x-ref="sliderContainer" 
                                 @scroll.debounce.10ms="updateActiveFromScroll()"
                                 class="w-full flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth hide-scrollbar px-[12%] py-4">
                                <div class="w-[75vw] max-w-3xl flex-shrink-0 snap-center flex items-center justify-center h-[50vh] md:h-[55vh] transition-all duration-300"
                                     :class="activeImage === 0 ? 'scale-100 opacity-100' : 'scale-90 opacity-40'">
                                    <img :src="slides[0]" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none pointer-events-none">
                                </div>
                                <div class="w-[75vw] max-w-3xl flex-shrink-0 snap-center flex items-center justify-center h-[50vh] md:h-[55vh] transition-all duration-300"
                                     :class="activeImage === 1 ? 'scale-100 opacity-100' : 'scale-90 opacity-40'">
                                    <img :src="slides[1]" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none pointer-events-none">
                                </div>
                                <div class="w-[75vw] max-w-3xl flex-shrink-0 snap-center flex items-center justify-center h-[50vh] md:h-[55vh] transition-all duration-300"
                                     :class="activeImage === 2 ? 'scale-100 opacity-100' : 'scale-90 opacity-40'">
                                    <img :src="slides[2]" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none pointer-events-none">
                                </div>
                                <div class="w-[75vw] max-w-3xl flex-shrink-0 snap-center flex items-center justify-center h-[50vh] md:h-[55vh] transition-all duration-300"
                                     :class="activeImage === 3 ? 'scale-100 opacity-100' : 'scale-90 opacity-40'">
                                    <img :src="slides[3]" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none pointer-events-none">
                                </div>
                                <div class="w-[75vw] max-w-3xl flex-shrink-0 snap-center flex items-center justify-center h-[50vh] md:h-[55vh] transition-all duration-300"
                                     :class="activeImage === 4 ? 'scale-100 opacity-100' : 'scale-90 opacity-40'">
                                    <img :src="slides[4]" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none pointer-events-none">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Next Button --}}
                        <button @click.stop="nextImage()" class="absolute right-6 text-white bg-black/60 hover:bg-black w-12 h-12 rounded-full flex items-center justify-center transition-all z-50 border border-white/10 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        
                        {{-- Thumbnail Navigation Strip --}}
                        <div class="mt-4 flex items-center gap-2 bg-black/40 backdrop-blur-md p-2 rounded-2xl border border-white/10" @click.stop>
                            <button @click="scrollToImage(0)" class="w-14 h-10 md:w-16 md:h-12 rounded-lg overflow-hidden border-2 transition-all duration-250 shrink-0" :class="activeImage === 0 ? 'border-[#e85d26] scale-105 shadow-md opacity-100' : 'border-transparent opacity-40 hover:opacity-80'">
                                <img :src="slides[0]" class="w-full h-full object-cover select-none">
                            </button>
                            <button @click="scrollToImage(1)" class="w-14 h-10 md:w-16 md:h-12 rounded-lg overflow-hidden border-2 transition-all duration-250 shrink-0" :class="activeImage === 1 ? 'border-[#e85d26] scale-105 shadow-md opacity-100' : 'border-transparent opacity-40 hover:opacity-80'">
                                <img :src="slides[1]" class="w-full h-full object-cover select-none">
                            </button>
                            <button @click="scrollToImage(2)" class="w-14 h-10 md:w-16 md:h-12 rounded-lg overflow-hidden border-2 transition-all duration-250 shrink-0" :class="activeImage === 2 ? 'border-[#e85d26] scale-105 shadow-md opacity-100' : 'border-transparent opacity-40 hover:opacity-80'">
                                <img :src="slides[2]" class="w-full h-full object-cover select-none">
                            </button>
                            <button @click="scrollToImage(3)" class="w-14 h-10 md:w-16 md:h-12 rounded-lg overflow-hidden border-2 transition-all duration-250 shrink-0" :class="activeImage === 3 ? 'border-[#e85d26] scale-105 shadow-md opacity-100' : 'border-transparent opacity-40 hover:opacity-80'">
                                <img :src="slides[3]" class="w-full h-full object-cover select-none">
                            </button>
                            <button @click="scrollToImage(4)" class="w-14 h-10 md:w-16 md:h-12 rounded-lg overflow-hidden border-2 transition-all duration-250 shrink-0" :class="activeImage === 4 ? 'border-[#e85d26] scale-105 shadow-md opacity-100' : 'border-transparent opacity-40 hover:opacity-80'">
                                <img :src="slides[4]" class="w-full h-full object-cover select-none">
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick Info Strip --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 grid grid-cols-3 gap-2 sm:gap-4">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1.5 sm:gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</p>
                            <p class="font-bold text-gray-800 text-xs sm:text-sm">{{ $package['duration'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1.5 sm:gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Group Size</p>
                            <p class="font-bold text-gray-800 text-xs sm:text-sm">{{ $package['groupSize'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1.5 sm:gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Type</p>
                            <p class="font-bold text-gray-800 text-xs sm:text-sm capitalize">{{ $package['category'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Includes Tags & Properties --}}
                @php
                    $tags = [];
                    if (!empty($package['category'])) $tags[] = ucfirst($package['category']);
                    if (!empty($package['tour_type'])) $tags[] = $package['tour_type'];
                    if (!empty($package['theme'])) $tags[] = $package['theme'];
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

                {{-- Tour Overview --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-black text-gray-900 mb-4 section-heading">Tour Overview</h2>
                    <p class="text-gray-600 leading-relaxed text-base detail-overview-text">{{ $package['overview'] }}</p>

                    <h3 class="font-black text-gray-900 mt-6 mb-3 section-heading">Tour Highlights</h3>
                    <ul class="space-y-2">
                        @foreach($package['highlights'] as $hl)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="shrink-0 mt-0.5" width="16" height="16" fill="none" stroke="#f97316" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>{{ $hl }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- What's Included / Excluded --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-black text-gray-900 mb-5 section-heading">What's Included</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6">
                        @foreach($package['included'] as $item)
                        <div class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="shrink-0 mt-0.5" width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                        @foreach($package['excluded'] as $item)
                        <div class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="shrink-0 mt-0.5" width="16" height="16" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Itinerary --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h2 class="font-black text-gray-900 mb-8 section-heading">Itinerary</h2>
                    <div class="relative pl-2">
                        @foreach($package['itinerary'] as $idx => $day)
                        <div class="relative flex gap-6 pb-8 last:pb-2">
                            {{-- Timeline Line --}}
                            @if(!$loop->last)
                                <div class="absolute left-[11px] top-6 bottom-0" style="border-left: 2px dashed #e85d26 !important;"></div>
                            @endif
                            
                            {{-- Timeline Circle --}}
                            <div class="relative z-10 shrink-0">
                                @if($loop->first || $loop->last)
                                    {{-- Solid Circle --}}
                                    <div class="w-6 h-6 rounded-full shadow-sm" style="background-color: #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.2);"></div>
                                @else
                                    {{-- Hollow Circle --}}
                                    <div class="w-6 h-6 rounded-full bg-white shadow-sm" style="border: 4px solid #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.12);"></div>
                                @endif
                            </div>
                            
                            {{-- Content --}}
                            <div class="-mt-1 flex-1">
                                <h4 class="font-black text-gray-800 text-sm sm:text-base" style="font-family: 'Outfit', sans-serif;">Day {{ $idx + 1 }}: {{ $day['title'] }}</h4>
                                @if(!empty($day['desc']))
                                    <p class="text-gray-500 text-[13px] sm:text-sm leading-relaxed mt-2 max-w-3xl">{{ $day['desc'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- FAQ Section --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8" x-data="{ activeFaq: 0 }">
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
                        <div class="border border-gray-100 rounded-xl overflow-hidden transition-all duration-300" :class="activeFaq === {{ $i }} ? 'bg-[#F8F9FA]/50 border-gray-200' : 'bg-white hover:bg-gray-50/50'">
                            <button 
                                type="button" 
                                class="w-full flex items-center justify-between p-5 text-left transition-all duration-200 outline-none"
                                @click="activeFaq = (activeFaq === {{ $i }} ? null : {{ $i }})"
                            >
                                <span class="font-black text-gray-800 text-sm sm:text-base leading-tight">{{ $faq['q'] }}</span>
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
                                class="px-5 pb-5 pt-0 text-gray-600 text-sm sm:text-[15px] leading-relaxed border-t border-gray-100/50"
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
                    $agentName = $package['agent'] ?? 'Miths Holidays';
                    if (is_array($agentName)) {
                        $agentName = $agentName['name'] ?? 'Miths Holidays';
                    }

                    // Dynamic rich profiles for each agent name
                    $agentProfiles = [
                        'nomad ventures' => (object)[
                            'id' => 1,
                            'name' => 'Nomad Ventures',
                            'phone' => '+1 (555) 019-2831',
                            'email' => 'contact@nomad.com',
                            'region' => 'Asia Pacific Region',
                            'tier' => 'Premium'
                        ],
                        'azure horizons' => (object)[
                            'id' => 2,
                            'name' => 'Azure Horizons',
                            'phone' => '+44 (123) 456-7890',
                            'email' => 'info@azure.com',
                            'region' => 'London, United Kingdom',
                            'tier' => 'Standard'
                        ],
                        'globe trotters' => (object)[
                            'id' => 3,
                            'name' => 'Globe Trotters',
                            'phone' => '+1 (555) 012-3456',
                            'email' => 'contact@globetrotters.com',
                            'region' => 'New York, USA',
                            'tier' => 'Enterprise'
                        ],
                        'atlas global travels' => (object)[
                            'id' => 4,
                            'name' => 'Atlas Global Travels',
                            'phone' => '+91 99999 88888',
                            'email' => 'info@atlasglobal.com',
                            'region' => 'New Delhi, India',
                            'tier' => 'Premium'
                        ],
                        'miths holidays' => (object)[
                            'id' => 64,
                            'name' => 'Miths Holidays',
                            'phone' => '+91 7383682183',
                            'email' => 'mithstours@gmail.com',
                            'region' => '101 GF Nr Trikon Bagh Rajkot - Gujarat',
                            'tier' => 'Premium'
                        ]
                    ];

                    $agentKey = strtolower($agentName);
                    $agentData = $agentProfiles[$agentKey] ?? null;

                    // Fallback to database check
                    if (!$agentData) {
                        $dbAgent = \DB::table('agents')->where('name', $agentName)->first();
                        if ($dbAgent) {
                            $agentData = $dbAgent;
                        }
                    }

                    // Ultimate fallback
                    if (!$agentData) {
                        $agentData = (object)[
                            'id' => 64,
                            'name' => 'Miths Holidays',
                            'phone' => '+91 7383682183',
                            'email' => 'mithstours@gmail.com',
                            'region' => '101 GF Nr Trikon Bagh Rajkot - Gujarat',
                            'tier' => 'Premium'
                        ];
                    }

                    // Ensure agent ID exists
                    if (!isset($agentData->id)) {
                        $agentData->id = 64;
                    }
                    
                    $agentName = $agentData->name ?? 'Miths Holidays';
                    $agentPhone = $agentData->phone ?? '+91 7383682183';
                    $agentEmail = $agentData->email ?? 'mithstours@gmail.com';
                    $agentRegion = $agentData->region ?? '101 GF Nr Trikon Bagh Rajkot - Gujarat';
                    $agentInitial = strtoupper(substr($agentName, 0, 1));
                    $agentLogo = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);
                    
                    $agentRedirectUrl = url('/listing/holiday-list?agent_id=' . $agentData->id);
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4">
                    <h3 class="text-base font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Agent Information</h3>
                    <div class="flex flex-col items-center text-center py-2 space-y-2">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-primary/20 flex items-center justify-center shadow-md">
                            <img src="{{ $agentLogo }}" alt="{{ $agentName }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-black text-gray-800 text-base">{{ $agentName }}</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $agentRegion }}</p>
                        </div>
                        <div class="flex items-center gap-0.5">
                            @for($s = 0; $s < 5; $s++)
                                <svg width="13" height="13" fill="{{ $s < 4 ? '#f97316' : '#e5e7eb' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                            <span class="text-sm font-bold text-gray-700 ml-1">4.8</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-sm text-gray-600">
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span>www.{{ strtolower(str_replace(' ', '', $agentName)) }}.com</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/></svg>
                            <span>{{ $agentPhone }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>{{ $agentEmail }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agentPhone) }}" class="py-2.5 bg-green-500 hover:bg-green-600 text-white text-center text-xs font-black rounded-xl transition-colors">Whatsapp</a>
                        <a href="mailto:{{ $agentEmail }}" class="py-2.5 bg-primary hover:bg-primary/90 text-white text-center text-xs font-black rounded-xl transition-colors">Email</a>
                        <a href="{{ $agentRedirectUrl }}" class="py-2.5 border border-border-soft text-foreground text-center text-xs font-black rounded-xl hover:bg-gray-50 transition-colors">Other Packages</a>
                        <a href="{{ $agentRedirectUrl }}" class="py-2.5 bg-foreground hover:opacity-90 text-white text-center text-xs font-black rounded-xl transition-colors">See Profile</a>
                    </div>
                </div>

                {{-- Price & Book Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Price Per Person</p>
                        <p class="text-3xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">₹{{ number_format($package['price']) }}</p>
                        @if($package['oldPrice'])
                        <p class="text-sm text-primary font-bold line-through mt-1">₹{{ number_format($package['oldPrice']) }}</p>
                        @endif
                    </div>
                    <button type="button" @click="showBookingModal = true" class="block w-full py-3.5 bg-primary hover:bg-primary/90 text-white text-center font-black text-sm uppercase tracking-widest rounded-xl transition-all duration-200 shadow-glow">
                        Book Now
                    </button>
                    <a href="#contact-form" class="block w-full py-3 border-2 border-gray-200 hover:border-orange-300 text-gray-700 text-center font-bold text-sm rounded-xl transition-all duration-200">
                        Enquire Now
                    </a>
                </div>

                {{-- Get in Touch --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4" id="contact-form">
                    <div>
                        <h3 class="text-base font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Get in touch</h3>
                        <p class="text-sm text-gray-500 mt-1">We are here for you, how can we help?</p>
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
                        <input type="hidden" name="subject" value="Inquiry for {{ $package['title'] }}">
                        <input type="text" name="name" required placeholder="Enter your name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <input type="email" name="email" required placeholder="Enter your email"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <textarea name="message" required rows="3" placeholder="Go ahead, we are listening..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400 resize-none"></textarea>
                        <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black text-sm rounded-xl transition-colors duration-200 shadow-glow">
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
                            <div class="p-6 md:p-10 rounded-[40px] border border-gray-200 bg-white shadow-soft hover:shadow-premium transition-all duration-500 h-full flex flex-col space-y-4 md:space-y-6">
                                <h4 class="text-lg md:text-xl font-black text-foreground font-heading" style="font-family: 'Outfit', sans-serif;">The best booking system</h4>
                                <p class="text-text-muted text-xs md:text-sm leading-relaxed font-medium italic">"{{ $testi['text'] }}"</p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-100 mt-auto gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $testi['img'] }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-primary/10">
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
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-6 lg:p-8 animate-fade-in-up">
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
                    <input type="text" name="traveler_name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Email</label>
                        <input type="email" name="traveler_email" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Phone</label>
                        <input type="text" name="traveler_phone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Travel Date</label>
                        <input type="date" name="travel_date" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Guests</label>
                        <input type="number" name="guests" min="1" value="2" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Special Requests</label>
                    <textarea name="special_request" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"></textarea>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-hover text-white font-black rounded-xl transition-all shadow-lg shadow-primary/30">
                        Confirm Booking Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
