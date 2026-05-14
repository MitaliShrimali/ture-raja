@extends('layouts.app')

@section('title', $pkg['title'] . ' - Tour Raja')

@section('content')
    <div class="pt-32 lg:pt-40 pb-16 text-left">
        <div class="container-custom">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-8 font-medium">
                <a href="{{ url('/') }}" class="hover:text-primary cursor-pointer">Home</a>
                <i data-lucide="chevron-right" size="14"></i>
                <a href="{{ url('/listing') }}" class="hover:text-primary cursor-pointer">Destinations</a>
                <i data-lucide="chevron-right" size="14"></i>
                <span class="text-foreground font-bold">{{ $pkg['title'] }}</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Left Content -->
                <div class="flex-1 space-y-12">
                    <!-- Interactive Image Slider -->
                    <div x-data="{ 
                        activeSlide: 0,
                        slides: [
                            '{{ asset($pkg['image']) }}',
                            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200',
                            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200',
                            'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200',
                            'https://images.unsplash.com/photo-1472396961695-1ad7a82fe28b?auto=format&fit=crop&q=80&w=1200'
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
                                left: container.offsetWidth * this.activeSlide,
                                behavior: 'smooth'
                            });
                        },
                        init() {
                            setInterval(() => this.next(), 6000);
                        }
                    }" class="relative group rounded-[40px] overflow-hidden shadow-2xl bg-gray-100">
                        
                        {{-- Scrollable Container --}}
                        <div 
                            x-ref="slider"
                            @scroll.debounce.100ms="activeSlide = Math.round($event.target.scrollLeft / $event.target.offsetWidth)"
                            class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar" 
                            style="height: 400px;"
                        >
                            <template x-for="(slide, index) in slides" :key="index">
                                <div class="w-full flex-shrink-0 snap-center relative">
                                    <img :src="slide" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Package Image">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                </div>
                            </template>
                        </div>

                        {{-- Top Actions --}}
                        <div class="absolute top-6 right-6 flex gap-3 z-20">
                            <button 
                                class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all duration-300 shadow-lg"
                                onclick="toggleWishlist(event, {slug: '{{ $pkg['id'] }}', title: '{{ $pkg['title'] }}', image: '{{ asset($pkg['image']) }}', price: '{{ $pkg['price'] }}'})"
                            >
                                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                            <button class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all duration-300 shadow-lg">
                                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
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

                    <!-- Title & Info -->
                    <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft space-y-8 border border-gray-50">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-primary font-bold">
                                    <i data-lucide="map-pin" size="18"></i>
                                    <span class="tracking-widest uppercase text-sm">{{ $pkg['location'] }}</span>
                                </div>
                                <h1 class="text-4xl md:text-5xl font-black text-foreground leading-tight font-syne">{{ $pkg['title'] }}</h1>
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2 text-orange-500 font-bold">
                                        <i data-lucide="star" size="20" fill="currentColor"></i>
                                        <span>{{ $pkg['rating'] }} ({{ $pkg['reviews'] }} Reviews)</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-400 font-medium">
                                        <i data-lucide="clock" size="20"></i>
                                        <span>{{ $pkg['duration'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:text-right">
                                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest mb-1">Total Price</p>
                                <div class="flex items-baseline md:justify-end gap-1">
                                    <span class="text-5xl font-black text-primary">₹{{ $pkg['price'] }}</span>
                                    <span class="text-gray-400 font-medium">/person</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-100">
                            <h3 class="text-2xl font-bold mb-6">Tour Overview</h3>
                            <p class="text-gray-500 leading-relaxed text-lg">
                                Experience the ultimate luxury with our {{ $pkg['title'] }} package. This carefully curated journey takes you through the most stunning landscapes and cultural landmarks, ensuring every moment is filled with wonder. From premium accommodations to expert-guided tours, we've handled all the details so you can focus on creating memories that last a lifetime.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 pt-8">
                            <h3 class="text-2xl font-bold col-span-full mb-2 font-syne">What's Included</h3>
                            @php
                                $included = [
                                    "Luxury Accommodation",
                                    "Daily Gourmet Breakfast",
                                    "Expert Local Guides",
                                    "Private Transportation",
                                    "Entrance Fees to All Sites",
                                    "Welcome Dinner Party",
                                    "24/7 Concierge Service",
                                    "Photography Sessions"
                                ];
                            @endphp
                            @foreach($included as $item)
                                <div class="flex items-center gap-4 text-gray-600 font-medium bg-background/50 p-4 rounded-2xl border border-gray-100">
                                    <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center text-green-500">
                                        <i data-lucide="check" size="18" stroke-width="3"></i>
                                    </div>
                                    <span>{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft space-y-10 border border-gray-50">
                        <h3 class="text-3xl font-black font-syne">Tour Itinerary</h3>
                        <div class="space-y-12 relative before:absolute before:left-6 before:top-4 before:bottom-4 before:w-0.5 before:bg-gray-100">
                            @php
                                $itinerary = [
                                    ['day' => 'Day 01', 'title' => 'Arrival & Welcome Dinner', 'desc' => 'Arrive at the airport and transfer to your luxury villa. Enjoy a traditional welcome dinner with the team.'],
                                    ['day' => 'Day 02', 'title' => 'Cultural Heritage Tour', 'desc' => 'Visit the most famous landmarks and learn about the local history from our expert guides.'],
                                    ['day' => 'Day 03', 'title' => 'Nature & Adventure', 'desc' => 'Explore the natural wonders of the region with a mix of hiking and relaxation.'],
                                    ['day' => 'Day 04', 'title' => 'Free Day & Shopping', 'desc' => 'A day for you to explore at your own pace or relax by the pool. Evening shopping tour included.'],
                                    ['day' => 'Day 05', 'title' => 'Farewell & Departure', 'desc' => 'Enjoy a final breakfast before your private transfer back to the airport.'],
                                ];
                            @endphp
                            @foreach($itinerary as $i => $item)
                                <div class="relative pl-16 space-y-2 group">
                                    <div class="absolute left-0 top-1 w-12 h-12 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-primary font-bold shadow-soft group-hover:border-primary transition-colors z-10">
                                        {{ $i + 1 }}
                                    </div>
                                    <span class="text-primary font-bold uppercase tracking-widest text-sm">{{ $item['day'] }}</span>
                                    <h4 class="text-2xl font-black text-foreground">{{ $item['title'] }}</h4>
                                    <p class="text-gray-500 text-lg leading-relaxed">{{ $item['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:w-[400px] space-y-8">
                    <!-- Agent Card -->
                    <div class="bg-white rounded-[32px] p-8 shadow-soft border border-gray-50 sticky top-28 space-y-8">
                        @if(isset($pkg['agent']))
                            <div class="flex items-center gap-5 pb-6 border-b border-gray-100">
                                <img src="{{ $pkg['agent']['logo'] }}" alt="{{ $pkg['agent']['name'] }}" class="w-16 h-16 rounded-2xl shadow-lg" />
                                <div>
                                    <h4 class="text-xl font-bold">{{ $pkg['agent']['name'] }}</h4>
                                    <p class="text-gray-400 font-medium">Verified Agent</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <button class="w-full flex items-center justify-center gap-3 bg-foreground hover:bg-black text-white py-5 rounded-[20px] font-bold transition-all shadow-xl">
                                    <i data-lucide="phone" size="22"></i>
                                    <span>Call Now</span>
                                </button>
                                <button class="w-full flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white py-5 rounded-[20px] font-bold transition-all shadow-xl">
                                    <i data-lucide="message-circle" size="22"></i>
                                    <span>WhatsApp Message</span>
                                </button>
                            </div>
                        @endif

                        <div class="space-y-6 pt-6 border-t border-gray-100">
                            <h4 class="text-xl font-bold font-syne">Inquiry Form</h4>
                            <form class="space-y-4">
                                <input type="text" placeholder="Your Name" class="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                <input type="email" placeholder="Your Email" class="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                <textarea placeholder="Message" rows={4} class="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                                <button type="button" class="w-full bg-primary hover:bg-primary-hover text-white py-5 rounded-[20px] font-bold transition-all shadow-lg shadow-primary/30">
                                    Send Inquiry
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
