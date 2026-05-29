@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative w-full flex items-center justify-center bg-gray-900" style="height: 550px;">
        <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/1.png" alt="Hero Image" class="absolute inset-0 w-full h-full object-cover">
        <!-- Blur fade to white at bottom -->
        <div class="absolute bottom-0 w-full h-32 bg-gradient-to-t from-white to-transparent z-0"></div>
        <div class="relative z-10 text-left w-full max-w-6xl px-6">
            <h1 class="text-5xl md:text-6xl font-extrabold uppercase tracking-wide" style="color: #ffffff !important; text-shadow: 0 4px 6px rgba(0,0,0,0.6);">ABOUT US</h1>
        </div>
    </div>

    <!-- About Info Section -->
    <div class="max-w-6xl mx-auto px-6 py-16 lg:py-20 relative z-20 bg-white">
        <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-16">
            <!-- Left: Logo Box -->
            <div class="w-full md:w-5/12">
                <div class="border-[1.5px] border-gray-300 rounded-[2rem] p-12 sm:p-16 flex items-center justify-center w-full max-w-[420px] mx-auto md:mx-0 aspect-square">
                    <x-logo class="w-full h-auto" />
                </div>
            </div>
            
            <!-- Right: Text Content -->
            <div class="w-full md:w-7/12">
                <h4 class="text-gray-500 font-bold text-[12px] uppercase mb-4 tracking-wider">WELCOME TO OUR SITE!</h4>
                <h2 class="text-2xl lg:text-[44px] font-black text-black mb-6 leading-tight tracking-tight">We are the bridge to gap between travel agents and customers.</h2>
                <div class="text-gray-800 font-medium text-[15px] leading-[1.8] space-y-5">
                    <p>Welcome to <span class="font-bold text-black">tourraja.com</span> – your trusted platform connecting travel agents with customers through seamless brochure uploads and easy access to contact details.</p>
                    <p>At Tourraja, we specialize in offering travel agents a user-friendly space to showcase their brochures, upload their latest travel offerings, and manage essential contact details. Our platform is designed to make it easier for both travel agents and customers to find what they need, when they need it.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Book With Us Section -->
    <div class="max-w-6xl mx-auto px-6 py-16 mb-16 bg-white">
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
    <div class="max-w-6xl mx-auto px-6 py-16 mb-16 bg-white">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-[42px] font-black text-gray-900 mb-3 leading-tight tracking-tight">Why Travel With TourRaja?</h2>
            <p class="text-gray-500 font-medium text-[15px]">The best booking platform you can trust</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Security Assurance -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow" style="background-color: #f9f5f0 !important;">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="shield-check" class="text-[#df8b3a] w-7 h-7 fill-[#df8b3a]/10"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-[17px] mb-3">Security Assurance</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-6">Demonstrates commitment to user data security</p>
                <a href="#" class="mt-auto text-gray-900 font-bold text-[13px] hover:underline flex items-center gap-1">Learn More <span class="text-sm">→</span></a>
            </div>

            <!-- Card 2: Best Price Deals -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow" style="background-color: #edf0f2 !important;">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="headphones" class="text-[#e85a2a] w-7 h-7 fill-[#e85a2a]/10"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-[17px] mb-3">Best Price Deals</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-6">Compare and choose the most affordable package</p>
                <a href="#" class="mt-auto text-gray-900 font-bold text-[13px] hover:underline flex items-center gap-1">Learn More <span class="text-sm">→</span></a>
            </div>

            <!-- Card 3: Direct Agent Contacts -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow" style="background-color: #f9f5f0 !important;">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="handshake" class="text-[#df8b3a] w-7 h-7 fill-[#df8b3a]/10"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-[17px] mb-3">Direct Agent Contacts</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-6">No middleman, connect instantly</p>
                <a href="#" class="mt-auto text-gray-900 font-bold text-[13px] hover:underline flex items-center gap-1">Learn More <span class="text-sm">→</span></a>
            </div>

            <!-- Card 4: Find Nearby Travel Agent -->
            <div class="p-8 rounded-[24px] flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow" style="background-color: #e9ecef !important;">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm mb-6">
                    <i data-lucide="map" class="text-[#20b2aa] w-7 h-7 fill-[#20b2aa]/10"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-[17px] mb-3">Find Nearby Travel Agent</h4>
                <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-6">Find the perfect trip without confusion with your local agent</p>
                <a href="#" class="mt-auto text-gray-900 font-bold text-[13px] hover:underline flex items-center gap-1">Learn More <span class="text-sm">→</span></a>
            </div>
        </div>
    </div>

    <!-- What Our Clients Say Section -->
    <div class="max-w-6xl mx-auto px-6 py-16 mb-20 bg-white">
        <div class="flex flex-row justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-[42px] font-black text-gray-900 mb-2 leading-tight tracking-tight">What Our Clients Say!!!</h2>
                <p class="text-gray-500 font-medium text-[15px]">They Love TourRaja!</p>
            </div>
            
            <!-- Navigation Arrow Controls -->
            <div class="flex items-center gap-3">
                <button class="w-11 h-11 rounded-full bg-[#f3f4f6] flex items-center justify-center text-gray-700 hover:bg-gray-200 transition-colors shadow-sm">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <button class="w-11 h-11 rounded-full flex items-center justify-center text-white hover:opacity-90 transition-opacity shadow-sm" style="background-color: #df8b3a !important;">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Testimonial 1 -->
            <div class="border border-gray-200 p-8 rounded-[24px] bg-white flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div>
                    <h4 class="font-extrabold text-gray-900 text-[16px] mb-3">The best booking system</h4>
                    <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8">
                        I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly, and I appreciate the detailed information and real-time availability of hotels.
                    </p>
                </div>
                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" class="w-11 h-11 rounded-full object-cover">
                        <div>
                            <h5 class="font-extrabold text-gray-900 text-[14px] leading-tight">Sara Mohamed</h5>
                            <p class="text-gray-400 font-medium text-[11px] mt-0.5">Jakatar</p>
                        </div>
                    </div>
                    <div class="flex gap-0.5 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="border border-gray-200 p-8 rounded-[24px] bg-white flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div>
                    <h4 class="font-extrabold text-gray-900 text-[16px] mb-3">The best booking system</h4>
                    <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8">
                        I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly, and I appreciate the detailed information and real-time availability of hotels.
                    </p>
                </div>
                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=100" class="w-11 h-11 rounded-full object-cover">
                        <div>
                            <h5 class="font-extrabold text-gray-900 text-[14px] leading-tight">Atend John</h5>
                            <p class="text-gray-400 font-medium text-[11px] mt-0.5">Califonia</p>
                        </div>
                    </div>
                    <div class="flex gap-0.5 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="border border-gray-200 p-8 rounded-[24px] bg-white flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div>
                    <h4 class="font-extrabold text-gray-900 text-[16px] mb-3">The best booking system</h4>
                    <p class="text-gray-500 font-medium text-[13px] leading-relaxed mb-8">
                        I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly, and I appreciate the detailed information and real-time availability of hotels.
                    </p>
                </div>
                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" class="w-11 h-11 rounded-full object-cover">
                        <div>
                            <h5 class="font-extrabold text-gray-900 text-[14px] leading-tight">Sara Mohamed</h5>
                            <p class="text-gray-400 font-medium text-[11px] mt-0.5">Jakatar</p>
                        </div>
                    </div>
                    <div class="flex gap-0.5 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
