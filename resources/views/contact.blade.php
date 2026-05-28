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
                    <p class="text-gray-500 text-[11px] sm:text-sm md:text-[15px] font-medium leading-tight">We are here for you! How can we help?</p>
                </div>
                
                <form action="{{ url('/contact/submit') }}" method="POST">
                    @csrf
                    <div class="mb-3 sm:mb-5">
                        <input type="text" name="name" required placeholder="Enter your name" class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[12px] sm:text-[15px] font-medium placeholder-gray-400" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'" />
                    </div>
                    <div class="mb-3 sm:mb-5">
                        <input type="email" name="email" required placeholder="Enter your email" class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[12px] sm:text-[15px] font-medium placeholder-gray-400" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'" />
                    </div>
                    <div class="mb-4 sm:mb-6">
                        <textarea name="message" rows="4" required placeholder="Go ahead, we are listening.." class="w-full border-none rounded-md py-3 sm:py-4 px-3 sm:px-5 outline-none transition-all text-gray-700 text-[12px] sm:text-[15px] font-medium placeholder-gray-400 resize-none" style="background-color: #f0f4f5; box-shadow: inset 0 0 0 1px transparent;" onfocus="this.style.boxShadow='inset 0 0 0 2px #2b6d64'" onblur="this.style.boxShadow='inset 0 0 0 1px transparent'"></textarea>
                    </div>
                    <div class="text-left">
                        <button type="submit" class="text-white font-semibold text-[12px] sm:text-[15px] py-2 sm:py-3.5 px-6 sm:px-10 rounded transition-colors inline-block w-auto" style="background-color: #2b6d64;" onmouseover="this.style.backgroundColor='#1f524a'" onmouseout="this.style.backgroundColor='#2b6d64'">
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

        <!-- Payment Methods -->
        <div class="mt-16 flex flex-wrap items-center justify-center gap-10 opacity-90 transition-all">
            <span class="font-bold text-blue-800 text-xl italic tracking-tight">PayPal</span>
            <span class="font-bold text-indigo-600 text-xl tracking-tighter">stripe</span>
            <span class="font-bold text-gray-800 text-lg flex items-center gap-1"><i data-lucide="circle" size="18" class="text-orange-500"></i> Payoneer</span>
            <span class="font-black text-blue-900 text-2xl italic">VISA<span class="text-yellow-500 inline-block w-3 h-3 rounded-full bg-yellow-500 align-middle ml-1"></span></span>
            <span class="font-bold text-black text-lg flex items-center gap-1"><span class="bg-green-500 text-white rounded-md px-1 py-0.5"><i data-lucide="dollar-sign" size="14"></i></span> Cash App</span>
            <span class="font-bold text-black text-lg flex items-center gap-1"><i data-lucide="bitcoin" size="22" class="text-orange-500"></i> bitcoin</span>
            <span class="font-bold text-gray-900 text-lg uppercase tracking-tight">Discover</span>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="w-full py-16 relative overflow-hidden" style="background-color: #eef6ff;">
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
        
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left -->
            <div class="space-y-6 max-w-sm pt-8">
                <div class="inline-flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm text-[11px] font-bold text-gray-900">
                    <div class="flex -space-x-2">
                        <img src="https://i.pravatar.cc/150?img=32" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="avatar">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="avatar">
                        <img src="https://i.pravatar.cc/150?img=12" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="avatar">
                    </div>
                    Testimonials
                </div>
                <h2 class="font-extrabold text-black" style="font-size: 42px; line-height: 1.15; letter-spacing: -0.5px;">What our clients are<br>saying about us?</h2>
                <p class="text-gray-500 font-medium leading-relaxed" style="font-size: 14px;">
                    Discover how you can offset your adventure's carbon emissions and support the sustainable initiatives practiced by our operators worldwide.
                </p>
            </div>
            
            <!-- Right -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10 pt-4 pb-12">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=32" class="w-14 h-14 rounded-full object-cover" alt="Sara Mohamed">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Sara Mohamed</h4>
                                <p class="text-gray-500 font-medium" style="font-size: 11px;">Jakatar</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 gap-0.5">
                            <i data-lucide="star" size="10" class="fill-yellow-400"></i>
                            <i data-lucide="star" size="10" class="fill-yellow-400"></i>
                            <i data-lucide="star" size="10" class="fill-yellow-400"></i>
                            <i data-lucide="star" size="10" class="fill-yellow-400"></i>
                            <i data-lucide="star" size="10" class="fill-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed font-medium" style="font-size: 12px;">
                        I've been using the hotel booking system for several years now, and it's become my go-to platform for planning my trips. The interface is user-friendly, and I appreciate the detailed information and real-time availability of hotels.
                    </p>
                </div>
                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm space-y-6 sm:mt-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=11" class="w-14 h-14 rounded-full object-cover" alt="Atend John">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Atend John</h4>
                                <p class="text-gray-500 font-medium" style="font-size: 11px;">Califonia</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed font-medium" style="font-size: 12px;">
                        I had a last-minute business trip, and this system came to the rescue. I booked a hotel in no time and even got a great rate. The confirmation process was straightforward, and I had all the necessary information promptly.
                    </p>
                </div>
                
                <!-- Navigation Dots -->
                <div class="absolute right-0 flex gap-2" style="bottom: -20px;">
                    <button class="w-10 h-10 rounded-full text-gray-500 flex items-center justify-center transition-colors bg-[#e2e8f0] hover:bg-[#cbd5e1]">
                        <i data-lucide="arrow-left" size="16"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full text-gray-500 flex items-center justify-center transition-colors bg-[#e2e8f0] hover:bg-[#cbd5e1]">
                        <i data-lucide="arrow-right" size="16"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
