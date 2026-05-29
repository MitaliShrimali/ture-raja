@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative w-full flex items-center justify-center bg-gray-900" style="height: 550px;">
        <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/1.png" alt="Hero Image" class="absolute inset-0 w-full h-full object-cover">
        <!-- Blur fade to white at bottom -->
        <div class="absolute bottom-0 w-full h-32 bg-gradient-to-t from-white to-transparent z-0"></div>
        <div class="relative z-10 text-left w-full max-w-7xl px-6">
            <h1 class="text-5xl md:text-6xl font-extrabold uppercase tracking-wide" style="color: #ffffff !important; text-shadow: 0 4px 6px rgba(0,0,0,0.6);">ABOUT US</h1>
        </div>
    </div>

    <!-- About Info Section -->
    <div class="max-w-7xl mx-auto px-6 pt-8 lg:pt-10 pb-8 lg:pb-12 relative z-20">
        <div class="flex flex-col md:flex-row items-center gap-10 lg:gap-16">
            <!-- Left: Logo (Takes natural width, no unnecessary large boxes) -->
            <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 flex justify-center md:justify-start">
                <div class="w-full max-w-[240px] lg:max-w-[280px]">
                    <x-logo class="w-full h-auto" />
                </div>
            </div>
            
            <!-- Right: Text Content (Flexibly fills the rest of the space) -->
            <div class="w-full flex-2">
                <h4 class="text-gray-500 font-bold text-[12px] uppercase mb-2 tracking-wider">WELCOME TO OUR SITE!</h4>
                <h2 class="text-2xl lg:text-[40px] font-black text-black mb-4 leading-[1.15] tracking-tight">We are the bridge to gap between travel agents and customers.</h2>
                <div class="text-gray-800 font-medium text-[15px] leading-relaxed space-y-3">
                    <p>Welcome to <span class="font-bold text-black">tourraja.com</span> – your trusted platform connecting travel agents with customers through seamless brochure uploads and easy access to contact details.</p>
                    <p>At Tourraja, we specialize in offering travel agents a user-friendly space to showcase their brochures, upload their latest travel offerings, and manage essential contact details. Our platform is designed to make it easier for both travel agents and customers to find what they need, when they need it.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Book With Us Section -->
    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-10 items-center">
            
            <!-- Left: Text & Stats -->
            <div class="w-full lg:w-1/2 lg:pr-8">
                <!-- Bulletproof Pill with Inline style for Orange Background -->
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-bold text-[13px] mb-8 shadow-sm" style="background-color: #df8b3a !important; color: #111827 !important;">
                    <span class="text-base leading-none">🌍</span> Why book at TourRaja ?
                </div>
                
                <h2 class="text-4xl md:text-[46px] font-black text-gray-900 mb-5 leading-[1.15] tracking-tight">Embracing Adventure<br>Since 2003</h2>
                <p class="text-gray-700 font-medium text-[15px] leading-relaxed mb-12 max-w-[90%]">
                    Choose one style or create a package, fill your passports with adventures together.
                </p>
                
                <!-- Robust Row layout for Stats -->
                <div class="flex flex-row flex-wrap items-start justify-between gap-y-6 gap-x-4 sm:gap-x-6">
                    <div class="min-w-[100px]">
                        <div class="text-[32px] font-black text-gray-900 mb-1 leading-none tracking-tighter">45+</div>
                        <div class="text-gray-500 font-medium text-[13px] leading-snug">Global<br>Branches</div>
                    </div>
                    <div class="min-w-[100px]">
                        <div class="text-[32px] font-black text-gray-900 mb-1 leading-none tracking-tighter">29K</div>
                        <div class="text-gray-500 font-medium text-[13px] leading-snug">Destinations<br>Collaboration</div>
                    </div>
                    <div class="min-w-[100px]">
                        <div class="text-[32px] font-black text-gray-900 mb-1 leading-none tracking-tighter">20+</div>
                        <div class="text-gray-500 font-medium text-[13px] leading-snug">Years<br>Experience</div>
                    </div>
                    <div class="min-w-[100px]">
                        <div class="text-[32px] font-black text-gray-900 mb-1 leading-none tracking-tighter">168K</div>
                        <div class="text-gray-500 font-medium text-[13px] leading-snug">Happy<br>Customers</div>
                    </div>
                </div>
            </div>
            
            <!-- Right: Feature Cards (Staggered Grid) -->
            <div class="w-full lg:w-1/2">
                <div class="grid grid-cols-2 gap-5 lg:pl-10">
                    <!-- Column 1 (Offset down) -->
                    <div class="space-y-5 lg:mt-14">
                        <!-- Card 1: Trusted Reviews (Mint) -->
                        <div class="p-7 rounded-[22px] flex flex-col items-center text-center shadow-sm" style="background-color: #ebfbf9 !important;">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/60 mb-3">
                                <i data-lucide="luggage" class="text-gray-800 w-5 h-5"></i>
                            </div>
                            <h4 class="font-extrabold text-gray-900 text-[15px] mb-2">Trusted Reviews</h4>
                            <p class="text-gray-500 font-medium text-[12.5px] leading-relaxed">4.8 stars from 160,000+<br>Trustpilot reviews.</p>
                        </div>
                        
                        <!-- Card 2: Security Assurance (Beige/Grey) -->
                        <div class="p-7 rounded-[22px] flex flex-col items-center text-center shadow-sm" style="background-color: #f3f2ef !important;">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/60 mb-3">
                                <i data-lucide="fingerprint" class="text-gray-800 w-5 h-5"></i>
                            </div>
                            <h4 class="font-extrabold text-gray-900 text-[15px] mb-2">Security Assurance</h4>
                            <p class="text-gray-500 font-medium text-[12.5px] leading-relaxed">Data security through<br>encryption</p>
                        </div>
                    </div>
                    
                    <!-- Column 2 (Normal) -->
                    <div class="space-y-5">
                        <!-- Card 3: Experiences (Peach) -->
                        <div class="p-7 rounded-[22px] flex flex-col items-center text-center shadow-sm" style="background-color: #fcf0ec !important;">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/60 mb-3">
                                <i data-lucide="map-pin" class="text-gray-800 w-5 h-5"></i>
                            </div>
                            <h4 class="font-extrabold text-gray-900 text-[15px] mb-2">300,000+ Experiences</h4>
                            <p class="text-gray-500 font-medium text-[12.5px] leading-relaxed">Make memories around<br>the world.</p>
                        </div>
                        
                        <!-- Card 4: Reserve now (Blue) -->
                        <div class="p-7 rounded-[22px] flex flex-col items-center text-center shadow-sm" style="background-color: #eef5fd !important;">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/60 mb-3">
                                <i data-lucide="ticket" class="text-gray-800 w-5 h-5"></i>
                            </div>
                            <h4 class="font-extrabold text-gray-900 text-[15px] mb-2">Reserve now, Pay later</h4>
                            <p class="text-gray-500 font-medium text-[12.5px] leading-relaxed">Book your spot first, pay<br>later.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Travel With TourRaja Section -->
    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-16">
        <div class="text-center mb-16">
            <h2 class="text-[32px] md:text-[40px] font-extrabold text-black mb-3 leading-tight tracking-tight">Why Travel With TourRaja?</h2>
            <p class="text-gray-500 font-medium text-[15px]">The best booking platform you can trust</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Security Assurance -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #F8EFE4 !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="shield-check" class="text-yellow-500 w-8 h-8"></i>
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Security Assurance</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Demonstrates commitment to user data security</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 2: Best Price Deals -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #E8E9EC !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="headset" class="text-orange-500 w-8 h-8"></i>
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Best Price Deals</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Compare and choose the most affordable package</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 3: Direct Agent Contacts -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #F8EFE4 !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="handshake" class="text-blue-500 w-8 h-8"></i>
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Direct Agent Contacts</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">No middleman, connect instantly</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>

            <!-- Card 4: Find Nearby Travel Agent -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm" style="background-color: #E8E9EC !important;">
                <div class="w-16 h-16 rounded-[18px] bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="map" class="text-teal-400 w-8 h-8"></i>
                </div>
                <h4 class="font-extrabold text-black text-[16px] mb-3">Find Nearby Travel Agent</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8 px-2">Find the perfect trip without confusion with your local agent</p>
                <a href="#" class="mt-auto text-black font-medium text-[13px] flex items-center gap-1.5 transition-opacity hover:opacity-70">Learn More <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
            </div>
        </div>
    </div>

    <!-- What Our Clients Say Section -->
    <section class="py-12 lg:py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-12 gap-6">
                <div class="space-y-2">
                    <h2 class="text-3xl md:text-4xl lg:text-6xl font-black text-black tracking-tight">What Our Clients Say!!!</h2>
                    <p class="text-gray-500 text-base md:text-lg font-medium">They Love TourRaja!</p>
                </div>
                <div class="flex items-center gap-4 self-start md:self-auto">
                    <button id="about-prev-testi" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gray-100 flex items-center justify-center text-black hover:bg-[#E8460A] hover:text-white transition-all duration-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button id="about-next-testi" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-[#E8460A] flex items-center justify-center text-white hover:scale-110 transition-all duration-300 shadow-md">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <div class="relative">
                <div class="flex gap-6 overflow-x-hidden py-8" id="about-testi-slider">
                    @php
                        $aboutTestimonials = [
                            ['name' => 'Sara Mohamed',  'loc' => 'Jakarta',    'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly and I appreciate the real-time availability.",    'img' => 'https://i.pravatar.cc/150?u=sara',  'rating' => 5],
                            ['name' => 'Atend John',    'loc' => 'California', 'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly and I appreciate the real-time availability.",    'img' => 'https://i.pravatar.cc/150?u=john',  'rating' => 5],
                            ['name' => 'Michael Chen',  'loc' => 'Singapore',  'text' => "Excellent service and direct agent contact saved me 30% on my last Bali trip. Highly recommend TourRaja to anyone looking for reliable and affordable travel packages.",                               'img' => 'https://i.pravatar.cc/150?u=mike',  'rating' => 5],
                            ['name' => 'Sara Mohamed',  'loc' => 'Jakarta',    'text' => "I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly and I appreciate the real-time availability.",    'img' => 'https://i.pravatar.cc/150?u=sara2', 'rating' => 3],
                            ['name' => 'Emily Watson',  'loc' => 'London',     'text' => "Booking through TourRaja was seamless. The customer support team responded within minutes and helped me customise my itinerary perfectly. Will definitely use them again!",                            'img' => 'https://i.pravatar.cc/150?u=emily', 'rating' => 5],
                            ['name' => 'Raj Patel',     'loc' => 'Mumbai',     'text' => "The best travel platform I have ever used. TourRaja's deals are unbeatable and the booking process is incredibly smooth. My whole family had an amazing experience.",                                  'img' => 'https://i.pravatar.cc/150?u=raj',   'rating' => 5],
                        ];
                    @endphp

                    @foreach($aboutTestimonials as $testi)
                        <div class="flex-shrink-0 w-full md:w-[450px]">
                            <div class="p-6 md:p-10 rounded-[40px] border border-gray-100 bg-white shadow-sm hover:shadow-md transition-all duration-500 h-full flex flex-col space-y-4 md:space-y-6">
                                <h4 class="text-lg md:text-xl font-black text-black">The best booking system</h4>
                                <p class="text-gray-400 text-xs md:text-sm leading-relaxed font-medium italic">"{{ $testi['text'] }}"</p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-100 mt-auto gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $testi['img'] }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover ring-2 ring-orange-100">
                                        <div class="flex flex-col">
                                            <span class="font-black text-black text-sm md:text-base">{{ $testi['name'] }}</span>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold">{{ $testi['loc'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        @for($i = 0; $i < 5; $i++)
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

        <script>
            (function() {
                var slider = document.getElementById('about-testi-slider');
                var prevBtn = document.getElementById('about-prev-testi');
                var nextBtn = document.getElementById('about-next-testi');
                var cardWidth = 450 + 24;
                nextBtn.addEventListener('click', function() {
                    slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
                });
                prevBtn.addEventListener('click', function() {
                    slider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
                });
            })();
        </script>
    </section>


    <!-- Newsletter Section -->
    <section class="py-12 lg:py-16 bg-[#F8F7F4]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-[#FFF9F0] rounded-[48px] overflow-hidden flex flex-col lg:flex-row">
                <!-- Left: Content -->
                <div class="flex-1 p-8 lg:p-20 flex flex-col justify-center space-y-8">
                    <div class="space-y-6 max-w-lg">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-[#E8460A] text-white text-[10px] font-black uppercase tracking-widest shadow-sm">
                            Join our newsletter
                        </span>
                        <h2 class="text-3xl lg:text-5xl font-black text-black leading-[1.1] tracking-tight">
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
                            <input type="email" name="email" placeholder="Your Email" required class="w-full h-16 pl-8 pr-40 rounded-full bg-white border-none shadow-md text-black font-bold focus:ring-2 focus:ring-orange-200 placeholder:text-gray-400">
                            <button type="submit" class="absolute right-2 top-2 h-12 px-8 rounded-full bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-[#E8460A] transition-all duration-300">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-gray-400 text-xs font-bold opacity-60">No ads. No trails. No commitments</p>
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

    <!-- Searched History Section -->
    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-16">
        <style>
            .scrollbar-none::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-none {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .history-card {
                width: calc(50% - 8px);
                min-width: calc(50% - 8px);
                flex-shrink: 0;
            }
        </style>
        
        <div class="flex flex-row justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl lg:text-[36px] font-black text-black tracking-tight flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-[#E8460A] flex items-center justify-center text-[15px] select-none shadow-sm">🔥</span>
                    Your Searched History
                </h2>
                <p class="text-gray-400 font-medium text-[13px] ml-11">We got you where you left this...</p>
            </div>
            <!-- Navigation -->
            <div class="flex items-center gap-2">
                <button onclick="const c=document.getElementById('history-carousel'); c.scrollBy({left: -(c.offsetWidth/2+8), behavior:'smooth'})" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-gray-200 transition-colors shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </button>
                <button onclick="const c=document.getElementById('history-carousel'); c.scrollBy({left: c.offsetWidth/2+8, behavior:'smooth'})" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-gray-200 transition-colors shadow-sm">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div id="history-carousel" class="flex gap-4 overflow-x-auto pb-4 scrollbar-none snap-x snap-mandatory">
            <!-- Card 1 -->
            <div class="history-card snap-start bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex h-[210px] overflow-hidden relative">
                <!-- Image block (42%) -->
                <div class="w-[33%] relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1508849789987-4e5333c12b78?auto=format&fit=crop&q=80&w=600" alt="New York" class="w-full h-full object-cover">
                    <!-- Favorite heart button -->
                    <button class="absolute top-4 left-4 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-red-500 transition-colors z-20">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Info block (58%) overlapping the image slightly with rounded-l-[24px] -->
                <div class="w-[67%] bg-white p-5 flex flex-col justify-between relative z-10 -ml-6 rounded-l-[24px] shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50/50">
                    <!-- Yellow discount ribbon hanging from top edge of this info container -->
                    <div class="absolute top-0 right-4 w-9 h-11 bg-[#FFD500] text-black flex items-center justify-center font-black text-[11px] shadow-sm z-20" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%); padding-bottom: 6px;">
                        -25%
                    </div>

                    <div>
                        <!-- Date top row -->
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-semibold mb-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>09 Jun 2024</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>16 Jun 2024</span>
                        </div>
                        
                        <!-- Route -->
                        <h4 class="font-extrabold text-black text-[18px] md:text-[20px] tracking-tight mb-3 flex items-center gap-2">
                            Denmark <span class="text-gray-900 font-normal text-[16px] md:text-[18px]">⇄</span> New York
                        </h4>
                        
                        <!-- Class/Price details -->
                        <div class="flex items-center gap-6">
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                            <div class="h-8 w-[1px] bg-gray-100"></div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer info and CTA -->
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                        <span class="text-[12px] text-gray-400 font-semibold">18 Seats left</span>
                        <button class="bg-[#E8460A] hover:bg-orange-600 text-white text-[12px] font-extrabold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="history-card snap-start bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex h-[210px] overflow-hidden relative">
                <!-- Image block (42%) -->
                <div class="w-[33%] relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=600" alt="Switzerland" class="w-full h-full object-cover">
                    <!-- Favorite heart button -->
                    <button class="absolute top-4 left-4 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-red-500 transition-colors z-20">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Info block (58%) overlapping the image slightly with rounded-l-[24px] -->
                <div class="w-[67%] bg-white p-5 flex flex-col justify-between relative z-10 -ml-6 rounded-l-[24px] shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50/50">
                    <div>
                        <!-- Date top row -->
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-semibold mb-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>09 Jun 2024</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>16 Jun 2024</span>
                        </div>
                        
                        <!-- Route -->
                        <h4 class="font-extrabold text-black text-[18px] md:text-[20px] tracking-tight mb-3 flex items-center gap-2">
                            Denmark <span class="text-gray-900 font-normal text-[16px] md:text-[18px]">⇄</span> New York
                        </h4>
                        
                        <!-- Class/Price details -->
                        <div class="flex items-center gap-6">
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                            <div class="h-8 w-[1px] bg-gray-100"></div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer info and CTA -->
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                        <span class="text-[12px] text-gray-400 font-semibold">18 Seats left</span>
                        <button class="bg-[#E8460A] hover:bg-orange-600 text-white text-[12px] font-extrabold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="history-card snap-start bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex h-[210px] overflow-hidden relative">
                <!-- Image block (42%) -->
                <div class="w-[33%] relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=600" alt="Dubai" class="w-full h-full object-cover">
                    <!-- Favorite heart button -->
                    <button class="absolute top-4 left-4 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-red-500 transition-colors z-20">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Info block (58%) overlapping the image slightly with rounded-l-[24px] -->
                <div class="w-[67%] bg-white p-5 flex flex-col justify-between relative z-10 -ml-6 rounded-l-[24px] shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50/50">
                    <div>
                        <!-- Date top row -->
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-semibold mb-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>09 Jun 2024</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>16 Jun 2024</span>
                        </div>
                        
                        <!-- Route -->
                        <h4 class="font-extrabold text-black text-[18px] md:text-[20px] tracking-tight mb-3 flex items-center gap-2">
                            Denmark <span class="text-gray-900 font-normal text-[16px] md:text-[18px]">⇄</span> New York
                        </h4>
                        
                        <!-- Class/Price details -->
                        <div class="flex items-center gap-6">
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                            <div class="h-8 w-[1px] bg-gray-100"></div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$288.15</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer info and CTA -->
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                        <span class="text-[12px] text-gray-400 font-semibold">18 Seats left</span>
                        <button class="bg-[#E8460A] hover:bg-orange-600 text-white text-[12px] font-extrabold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4 (Paris) -->
            <div class="history-card snap-start bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex h-[210px] overflow-hidden relative">
                <!-- Image block (42%) -->
                <div class="w-[33%] relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=600" alt="Paris" class="w-full h-full object-cover">
                    <!-- Favorite heart button -->
                    <button class="absolute top-4 left-4 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-red-500 transition-colors z-20">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Info block (58%) overlapping the image slightly with rounded-l-[24px] -->
                <div class="w-[67%] bg-white p-5 flex flex-col justify-between relative z-10 -ml-6 rounded-l-[24px] shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50/50">
                    <!-- Yellow discount ribbon -->
                    <div class="absolute top-0 right-4 w-9 h-11 bg-[#FFD500] text-black flex items-center justify-center font-black text-[11px] shadow-sm z-20" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%); padding-bottom: 6px;">
                        -15%
                    </div>

                    <div>
                        <!-- Date top row -->
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-semibold mb-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>12 Jul 2024</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>19 Jul 2024</span>
                        </div>
                        
                        <!-- Route -->
                        <h4 class="font-extrabold text-black text-[18px] md:text-[20px] tracking-tight mb-3 flex items-center gap-2">
                            Denmark <span class="text-gray-900 font-normal text-[16px] md:text-[18px]">⇄</span> Paris
                        </h4>
                        
                        <!-- Class/Price details -->
                        <div class="flex items-center gap-6">
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$340.50</span>
                            </div>
                            <div class="h-8 w-[1px] bg-gray-100"></div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$340.50</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer info and CTA -->
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                        <span class="text-[12px] text-gray-400 font-semibold">12 Seats left</span>
                        <button class="bg-[#E8460A] hover:bg-orange-600 text-white text-[12px] font-extrabold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 5 (London) -->
            <div class="history-card snap-start bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex h-[210px] overflow-hidden relative">
                <!-- Image block (42%) -->
                <div class="w-[33%] relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1513635269975-5969336cd44e?auto=format&fit=crop&q=80&w=600" alt="London" class="w-full h-full object-cover">
                    <!-- Favorite heart button -->
                    <button class="absolute top-4 left-4 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-red-500 transition-colors z-20">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Info block (58%) overlapping the image slightly with rounded-l-[24px] -->
                <div class="w-[67%] bg-white p-5 flex flex-col justify-between relative z-10 -ml-6 rounded-l-[24px] shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50/50">
                    <div>
                        <!-- Date top row -->
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-semibold mb-2.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>05 Aug 2024</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-300"></i>
                            <span>12 Aug 2024</span>
                        </div>
                        
                        <!-- Route -->
                        <h4 class="font-extrabold text-black text-[18px] md:text-[20px] tracking-tight mb-3 flex items-center gap-2">
                            Denmark <span class="text-gray-900 font-normal text-[16px] md:text-[18px]">⇄</span> London
                        </h4>
                        
                        <!-- Class/Price details -->
                        <div class="flex items-center gap-6">
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$295.00</span>
                            </div>
                            <div class="h-8 w-[1px] bg-gray-100"></div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold mb-0.5 uppercase tracking-wide">Business</span>
                                <span class="font-black text-black text-[17px]">$295.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer info and CTA -->
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                        <span class="text-[12px] text-gray-400 font-semibold">24 Seats left</span>
                        <button class="bg-[#E8460A] hover:bg-orange-600 text-white text-[12px] font-extrabold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
