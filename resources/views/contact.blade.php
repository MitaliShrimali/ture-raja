@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative w-full flex items-center justify-center bg-gray-900" style="height: 550px;">
        <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/1.png" alt="Rome Vatican" class="absolute inset-0 w-full h-full object-cover">
        <!-- Blur fade to white at bottom -->
        <div class="absolute bottom-0 w-full h-32 bg-gradient-to-t from-white to-transparent z-0"></div>
        <div class="relative z-10 text-left w-full max-w-6xl px-6">
            <h1 class="text-5xl md:text-6xl font-extrabold uppercase tracking-wide" style="color: #ffffff !important; text-shadow: 0 4px 6px rgba(0,0,0,0.6);">CONTACT US</h1>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 relative z-20 overflow-hidden">
        <div class="flex flex-row gap-4 sm:gap-12 md:gap-16 items-start w-full">
            
            <!-- Left: Form -->
            <div class="flex-1 w-1/2">
                <div class="mb-4 sm:mb-8 text-left">
                    <h2 class="text-[#0f172a] font-bold mb-1 sm:mb-2 text-[22px] sm:text-3xl md:text-[34px] tracking-tight">Get in touch</h2>
                    <p class="text-gray-500 text-[11px] sm:text-sm md:text-[15px] font-medium leading-tight">We are here for you! How can we help?</p><br>
                </div>
                
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm font-medium">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-md text-sm font-medium">{{ session('error') }}</div>
                    @endif
                    <div class="mb-3 sm:mb-5">
                        <input type="text" name="name" required placeholder="Enter your name" class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[11px] sm:text-[15px] font-medium placeholder-gray-400" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'" />
                    </div>
                    <div class="mb-3 sm:mb-5">
                        <input type="email" name="email" required placeholder="Enter your email" class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[11px] sm:text-[15px] font-medium placeholder-gray-400" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'" />
                    </div>
                    <div class="mb-3 sm:mb-5">
                        <input type="tel" name="phone" required placeholder="Enter your phone number" class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[11px] sm:text-[15px] font-medium placeholder-gray-400" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'" />
                    </div>
                    <div class="mb-4 sm:mb-6">
                        <textarea name="message" rows="4" required placeholder="Go ahead, we are listening.." class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[11px] sm:text-[15px] font-medium placeholder-gray-400 resize-none" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'"></textarea>
                    </div>
                    <div class="text-left">
                        <button type="submit" class="text-white font-semibold text-[12px] sm:text-[15px] py-2 sm:py-3.5 px-6 sm:px-10 rounded-full transition-colors inline-block w-auto" style="background-color: #2b6d64;" onmouseover="this.style.backgroundColor='#1f524a'" onmouseout="this.style.backgroundColor='#2b6d64'">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Info -->
            <div class="flex-1 w-1/2 flex flex-col items-center">
                <!-- Illustration -->
                <div class="w-full mb-4 sm:mb-10 flex justify-center">
                    <img src="https://6a0bf3ee063c0d21459114f4.imgix.net/414506927f0d73bf9a720f34a71881c9fd4d0003.png" class="w-full h-auto max-w-[300px] object-contain" style="max-height: 220px;" alt="Contact Illustration">
                </div>
                
                <div class="space-y-3 sm:space-y-6 w-full max-w-[300px] mx-auto">
                    <div class="flex items-center gap-2 sm:gap-5">
                        <div class="w-6 h-6 sm:w-10 sm:h-10 rounded-full border-[1.5px] border-[#2b6d64] flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-3 h-3 sm:w-[18px] sm:h-[18px] text-[#2b6d64]"></i>
                        </div>
                        <span class="text-gray-600 font-semibold text-[10px] sm:text-[15px] break-words">205A Millennium City, Accra</span>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-5">
                        <div class="w-6 h-6 sm:w-10 sm:h-10 rounded-full border-[1.5px] border-[#2b6d64] flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-3 h-3 sm:w-[18px] sm:h-[18px] text-[#2b6d64]"></i>
                        </div>
                        <span class="text-gray-600 font-semibold text-[10px] sm:text-[15px]">233 - 8586 - 689</span>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-5">
                        <div class="w-6 h-6 sm:w-10 sm:h-10 rounded-full border-[1.5px] border-[#2b6d64] flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="w-3 h-3 sm:w-[18px] sm:h-[18px] text-[#2b6d64]"></i>
                        </div>
                        <span class="text-gray-600 font-semibold text-[10px] sm:text-[15px] break-words">phnes.travels@gmail.com</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Why Travel With Us? & Payment -->
    <div class="max-w-6xl mx-auto px-6 py-12 text-center">
        <h2 class="font-extrabold text-gray-900 mb-2" style="font-size: 38px; letter-spacing:-0.5px;">Why Travel With Us?</h2>
        <p class="text-gray-500 font-medium text-base mb-12">The best booking platform you can trust</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="px-6 py-10 flex flex-col items-center text-center space-y-4 rounded-3xl" style="background-color: #e6f9f6;">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500"><i data-lucide="shield-check" size="18" class="fill-yellow-500"></i></div>
                </div>
                <h4 class="font-extrabold text-gray-900 text-[17px]">Security Assurance</h4>
                <p class="text-gray-500 leading-relaxed font-medium" style="font-size: 13px;">Demonstrates commitment to user data security through encryption and secure payment practices</p>
                <a href="#" class="font-bold mt-2 flex items-center gap-1 transition-colors text-gray-900 hover:text-primary text-xs">Learn More <i data-lucide="arrow-right" size="12"></i></a>
            </div>
            <!-- Card 2 -->
            <div class="px-6 py-10 flex flex-col items-center text-center space-y-4 rounded-3xl" style="background-color: #fdf0f4;">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-500"><i data-lucide="user" size="18" class="fill-red-500"></i></div>
                </div>
                <h4 class="font-extrabold text-gray-900 text-[17px]">Security Assurance</h4>
                <p class="text-gray-500 leading-relaxed font-medium" style="font-size: 13px;">Demonstrates commitment to user data security through encryption and secure payment practices</p>
                <a href="#" class="font-bold mt-2 flex items-center gap-1 transition-colors text-gray-900 hover:text-primary text-xs">Learn More <i data-lucide="arrow-right" size="12"></i></a>
            </div>
            <!-- Card 3 -->
            <div class="px-6 py-10 flex flex-col items-center text-center space-y-4 rounded-3xl" style="background-color: #eef6ff;">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500"><i data-lucide="package" size="18" class="fill-blue-500"></i></div>
                </div>
                <h4 class="font-extrabold text-gray-900 text-[17px]">Security Assurance</h4>
                <p class="text-gray-500 leading-relaxed font-medium" style="font-size: 13px;">Demonstrates commitment to user data security through encryption and secure payment practices</p>
                <a href="#" class="font-bold mt-2 flex items-center gap-1 transition-colors text-gray-900 hover:text-primary text-xs">Learn More <i data-lucide="arrow-right" size="12"></i></a>
            </div>
            <!-- Card 4 -->
            <div class="px-6 py-10 flex flex-col items-center text-center space-y-4 rounded-3xl" style="background-color: #f6f3fc;">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500"><i data-lucide="file-text" size="18" class="fill-indigo-500"></i></div>
                </div>
                <h4 class="font-extrabold text-gray-900 text-[17px]">Security Assurance</h4>
                <p class="text-gray-500 leading-relaxed font-medium" style="font-size: 13px;">Demonstrates commitment to user data security through encryption and secure payment practices</p>
                <a href="#" class="font-bold mt-2 flex items-center gap-1 transition-colors text-gray-900 hover:text-primary text-xs">Learn More <i data-lucide="arrow-right" size="12"></i></a>
            </div>
        </div>


    </div>

    <!-- Testimonials -->
    <section class="py-16 lg:py-24 overflow-hidden border-t border-gray-100 relative" style="background-color: #eef6ff;">
        <!-- Plane illustration path -->
        <div class="absolute top-10 pointer-events-none hidden md:block" style="left: 15%; width: 250px; height: 120px; opacity: 0.8; z-index: 10;">
            <svg viewBox="0 0 250 120" fill="none" stroke="#333" stroke-dasharray="4 4" stroke-width="2">
                <path d="M10,100 Q80,-20 150,60 T240,10"/>
                <circle cx="10" cy="100" r="3" fill="#333" />
                <circle cx="150" cy="60" r="3" fill="#333" />
                <!-- Plane icon -->
                <g transform="translate(230, 0) rotate(20)">
                    <path d="M15 2 L18 8 L30 10 L18 12 L15 20 L12 12 L0 10 L12 8 Z" fill="#333" stroke="none"/>
                </g>
            </svg>
        </div>
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
                            <div class="p-6 md:p-10 rounded-lg border border-gray-200 bg-white shadow-soft hover:shadow-premium transition-all duration-500 h-full flex flex-col space-y-4 md:space-y-6">
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
@endsection
