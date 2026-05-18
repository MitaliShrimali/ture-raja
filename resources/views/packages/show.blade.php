@extends('layouts.app')

@section('title', $package['title'] . ' — TourRaja')

@section('content')
<style>
    .detail-overview-text { word-break: normal; overflow-wrap: break-word; }
    .itinerary-line { min-height: 40px; }
</style>

<div class="bg-[#F8F9FA] min-h-screen pt-32 lg:pt-40">


    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ url('/') }}" class="hover:text-orange-500 transition-colors">Tours</a>
                <span>/</span>
                <span class="text-gray-800">{{ $package['title'] }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

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
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 leading-tight mb-3" style="font-family: 'Outfit', sans-serif;">
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

        {{-- TWO-COLUMN LAYOUT --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- ── LEFT MAIN CONTENT (2/3 width) ───────────────────── --}}
            <div class="w-full lg:w-2/3 space-y-6">

                {{-- Interactive Image Slider --}}
                <div x-data="{ 
                    activeSlide: 0,
                    slides: [
                        '{{ $package['image'] }}',
                        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&q=80&w=1200'
                    ],
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                        this.scrollToActive();
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                        this.scrollToActive();
                    },
                    scrollToActive() {
                        const container = this.$refs.slider;
                        container.scrollTo({
                            left: container.getBoundingClientRect().width * this.activeSlide,
                            behavior: 'smooth'
                        });
                    },
                    init() {
                        setInterval(() => this.next(), 6000);
                    }
                }" class="relative group rounded-[32px] overflow-hidden shadow-2xl bg-gray-100">
                    
                    {{-- Scrollable Container --}}
                    <div 
                        x-ref="slider"
                        @scroll.debounce.100ms="activeSlide = Math.round($event.target.scrollLeft / $event.target.offsetWidth)"
                        class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar" 
                        style="height: 380px;"
                    >
                        <template x-for="(slide, index) in slides" :key="index">
                            <div class="w-full min-w-full flex-shrink-0 snap-center relative overflow-hidden">
                                <img :src="slide" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Package Image">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            </div>
                        </template>
                    </div>

                    {{-- Top Actions (Wishlist) --}}
                    <div class="absolute top-5 right-5 z-20">
                        <button 
                            class="w-11 h-11 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all duration-300 shadow-lg"
                            onclick="toggleWishlist(event, {slug: '{{ $package['slug'] }}', title: '{{ $package['title'] }}', image: '{{ $package['image'] }}', price: '{{ $package['price'] }}'})"
                        >
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>

                    {{-- Premium Combined Controls (Matching User Reference) --}}
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-6 z-20 bg-black/30 backdrop-blur-xl px-7 py-3.5 rounded-full border border-white/10 shadow-2xl">
                        <!-- Left Arrow (Thin style) -->
                        <button @click="prev()" class="text-white hover:text-primary transition-all duration-300 transform hover:scale-125">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        </button>

                        <!-- Dots/Pills -->
                        <div class="flex items-center gap-3">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button 
                                    @click="scrollToActive(activeSlide = index)"
                                    class="transition-all duration-500 rounded-full"
                                    :class="activeSlide === index ? 'w-10 h-3 bg-primary shadow-glow' : 'w-3 h-3 bg-white shadow-sm hover:bg-white/80'"
                                ></button>
                            </template>
                        </div>

                        <!-- Right Arrow (Thin style) -->
                        <button @click="next()" class="text-white hover:text-primary transition-all duration-300 transform hover:scale-125">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <style>
                        .hide-scrollbar::-webkit-scrollbar { display: none; }
                        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
                    </style>
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

                {{-- Includes Tags --}}
                <div class="flex flex-wrap gap-2">
                    @foreach(['Domestic','Land Package','Starting From Ahmedabad'] as $tag)
                        <span class="flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold text-gray-700 shadow-sm">
                            <svg width="13" height="13" fill="none" stroke="#f97316" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>

                {{-- Tour Overview --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-xl font-black text-gray-900 mb-4" style="font-family: 'Outfit', sans-serif;">Tour Overview</h2>
                    <p class="text-gray-600 leading-relaxed text-base detail-overview-text">{{ $package['overview'] }}</p>

                    <h3 class="text-base font-black text-gray-900 mt-6 mb-3">Tour Highlights</h3>
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
                    <h2 class="text-xl font-black text-gray-900 mb-5" style="font-family: 'Outfit', sans-serif;">What's Included</h2>
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
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-xl font-black text-gray-900 mb-6" style="font-family: 'Outfit', sans-serif;">Itinerary</h2>
                    <div class="space-y-0">
                        @foreach($package['itinerary'] as $idx => $day)
                        <div class="flex gap-4 {{ !$loop->last ? 'pb-6' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full bg-orange-500 flex items-center justify-center shrink-0 text-white text-xs font-black">
                                    {{ $idx + 1 }}
                                </div>
                                @if(!$loop->last)
                                    <div class="w-0.5 bg-orange-200 flex-1 mt-2"></div>
                                @endif
                            </div>
                            <div class="pt-1 {{ !$loop->last ? 'pb-2' : '' }}">
                                <p class="font-black text-gray-800 text-sm">Day {{ $idx + 1 }}: {{ $day['title'] }}</p>
                                <p class="text-gray-500 text-sm leading-relaxed mt-1">{{ $day['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>{{-- end left col --}}

            {{-- ── RIGHT SIDEBAR (1/3 width) ──────────────────────── --}}
            <div class="w-full lg:w-1/3 shrink-0 space-y-5">

                {{-- Price & Book Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Price Per Person</p>
                        <p class="text-3xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">₹{{ number_format($package['price']) }}</p>
                        @if($package['oldPrice'])
                        <p class="text-sm text-primary font-bold line-through mt-1">₹{{ number_format($package['oldPrice']) }}</p>
                        @endif
                    </div>
                    <a href="#contact-form" class="block w-full py-3.5 bg-primary hover:bg-primary/90 text-white text-center font-black text-sm uppercase tracking-widest rounded-xl transition-all duration-200 shadow-glow">
                        Book Now
                    </a>
                    <a href="#contact-form" class="block w-full py-3 border-2 border-gray-200 hover:border-orange-300 text-gray-700 text-center font-bold text-sm rounded-xl transition-all duration-200">
                        Enquire Now
                    </a>
                </div>

                {{-- Agent Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4">
                    <h3 class="text-base font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Agent Information</h3>
                    <div class="flex flex-col items-center text-center py-2 space-y-2">
                        <div class="w-16 h-16 rounded-full bg-primary/10 border-4 border-primary/20 flex items-center justify-center text-2xl font-black text-primary">M</div>
                        <div>
                            <p class="font-black text-gray-800 text-base">Miths Holidays</p>
                            <p class="text-gray-500 text-xs mt-0.5">101 Nr Trikon bagh Rajkot · Gujarat</p>
                        </div>
                        <div class="flex items-center gap-0.5">
                            @for($s = 0; $s < 5; $s++)
                                <svg width="13" height="13" fill="{{ $s < 4 ? '#f97316' : '#e5e7eb' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                            <span class="text-sm font-bold text-gray-700 ml-1">4.2</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-sm text-gray-600">
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span>www.mithstour.com</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/></svg>
                            <span>+91 00000 00000</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="shrink-0" width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>mithstravel@gmail.com</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="https://wa.me/910000000000" class="py-2.5 bg-green-500 hover:bg-green-600 text-white text-center text-xs font-black rounded-xl transition-colors">Whatsapp</a>
                        <a href="mailto:mithstravel@gmail.com" class="py-2.5 bg-primary hover:bg-primary/90 text-white text-center text-xs font-black rounded-xl transition-colors">Email</a>
                        <a href="{{ url('/') }}" class="py-2.5 border border-border-soft text-foreground text-center text-xs font-black rounded-xl hover:bg-gray-50 transition-colors">Other Packages</a>
                        <a href="#" class="py-2.5 bg-foreground hover:opacity-90 text-white text-center text-xs font-black rounded-xl transition-colors">See Profile</a>
                    </div>
                </div>

                {{-- Get in Touch --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 space-y-4" id="contact-form">
                    <div>
                        <h3 class="text-base font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Get in touch</h3>
                        <p class="text-sm text-gray-500 mt-1">We are here for you, how can we help?</p>
                    </div>
                    <form class="space-y-3">
                        <input type="text" placeholder="Enter your name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <input type="email" placeholder="Enter your email"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                        <textarea rows="3" placeholder="Got ahead, we are listening..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400 resize-none"></textarea>
                        <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black text-sm rounded-xl transition-colors duration-200 shadow-glow">
                            Submit
                        </button>
                    </form>
                </div>

            </div>{{-- end right sidebar --}}
        </div>{{-- end grid --}}
    </div>
</div>
@endsection
