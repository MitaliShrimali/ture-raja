<!-- Chef & Tour Manager Modal Component -->
<div 
    x-show="showChefModal" 
    style="display: none;" 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <!-- Backdrop -->
    <div 
        class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
        @click="showChefModal = false"
    ></div>

    <!-- Modal Content (Wide Card Layout) -->
    <div 
        x-show="showChefModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-5xl max-h-[90vh] overflow-hidden bg-white rounded-[32px] shadow-2xl flex flex-col md:flex-row z-10 border border-white/20"
        @click.stop
    >
        <!-- Close Button (Outside the modal container in the top-right corner) -->
        <button 
            @click="showChefModal = false" 
            class="absolute -top-12 right-0 md:-right-12 z-50 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 shadow-lg transition-all hover:scale-110 cursor-pointer"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <!-- Left Side: Text Details (62% width on desktop) -->
        <div class="w-full md:w-[62%] p-5 md:p-10 flex flex-col justify-between bg-white rounded-t-[32px] md:rounded-t-none md:rounded-l-[32px] relative">
            <div class="space-y-4">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase border" style="background-color: #FFF4CE !important; color: #E85D26 !important; border-color: #FFE7A3 !important;">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#E85D26] animate-pulse" style="background-color: #E85D26 !important;"></span>
                    New on TourRaja
                </div>

                <!-- Main Heading -->
                <div class="space-y-2">
                    <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 leading-tight">
                        Travel in Style with <br class="hidden md:inline" />
                        <span style="color: #E85D26 !important;">Private Chef</span> & <br class="hidden md:inline" />
                        <span style="color: #E85D26 !important;">Tour Manager</span>
                    </h2>
                    <p class="text-gray-500 font-medium text-xs md:text-sm leading-relaxed">
                        Make your journey more comfortable, personalized and unforgettable.
                    </p>
                    <div class="w-20 h-0.5 rounded-full mt-1.5" style="background-color: #E85D26 !important;"></div>
                </div>

<style>
    .chef-hover-item {
        transition: all 0.2s ease-in-out !important;
        border: 1px solid transparent !important;
    }
    .chef-hover-item:hover {
        background-color: #FFF4CE !important;
        border-color: #FFE7A3 !important;
    }
    .chef-hover-item:hover h4 {
        color: #E85D26 !important;
    }
    .manager-hover-item {
        transition: all 0.2s ease-in-out !important;
        border: 1px solid transparent !important;
    }
    .manager-hover-item:hover {
        background-color: #FFE4E6 !important;
        border-color: #FECDD3 !important;
    }
    .manager-hover-item:hover h4 {
        color: #E11D48 !important;
    }
</style>

                <!-- Features list -->
                <div class="space-y-3 pt-1">
                    <!-- Feature 1 -->
                    <a href="{{ url('/discover?private_chef=1') }}" class="flex items-start gap-3.5 p-2.5 rounded-2xl chef-hover-item cursor-pointer block group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-200" style="background-color: #FFF4CE !important; color: #E85D26 !important;">
                            <i data-lucide="utensils" class="w-5.5 h-5.5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm transition-colors">Private Chef</h4>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">Enjoy hygienic, delicious meals made just the way you like.</p>
                        </div>
                    </a>

                    <!-- Feature 2 -->
                    <a href="{{ url('/discover?tour_manager=1') }}" class="flex items-start gap-3.5 p-2.5 rounded-2xl manager-hover-item cursor-pointer block group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-200" style="background-color: #FFE4E6 !important; color: #E11D48 !important;">
                            <i data-lucide="map-pin" class="w-5.5 h-5.5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm transition-colors">Tour Manager</h4>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">Personal guidance, local expertise and stress-free travel.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Bottom Action Row -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #FFF4CE !important; color: #E85D26 !important;">
                        <i data-lucide="sparkles" class="w-4.5 h-4.5"></i>
                    </div>
                    <p class="text-[11px] font-bold text-gray-700 max-w-[210px] leading-tight">
                        Select Chef or Manager to add comfort & luxury to your next trip!
                    </p>
                </div>
                <a 
                    href="{{ url('/discover') }}" 
                    class="w-full sm:w-auto px-5 py-3 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all duration-200"
                    style="background-color: #E85D26 !important; color: #ffffff !important;"
                >
                    Explore Now
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Right Side: Interactive Card Visuals (38% width on desktop) -->
        <div class="w-full md:w-[38%] bg-[#F0F2F5] p-5 md:p-8 flex flex-col justify-between relative overflow-hidden min-h-[380px] md:min-h-auto rounded-b-[32px] md:rounded-b-none md:rounded-r-[32px]" style="background-image: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.3)), url('{{ asset('images/chef2.jpg') }}'); background-size: cover; background-position: center;">
            <!-- Overlapping Cards Area -->
            <div class="relative flex-grow flex items-center justify-center min-h-[300px] my-auto">
                
                <!-- Card 1: Private Chef (Top Left, Tilted Left) -->
                <a href="{{ url('/discover?private_chef=1') }}" class="absolute top-2 left-4 w-[210px] bg-white rounded-2xl p-3 shadow-xl border border-gray-100/50 transform -rotate-6 hover:rotate-0 hover:scale-105 transition-all duration-300 z-20 block cursor-pointer group">
                    <div class="absolute top-2 left-2 bg-[#22C55E] text-white text-[8px] font-black tracking-wider uppercase px-2 py-0.5 rounded flex items-center gap-1">
                        <i data-lucide="sun" class="w-2.5 h-2.5"></i> PRIVATE CHEF
                    </div>
                    <div class="mt-4 overflow-hidden rounded-xl bg-gray-50 h-[135px]">
                        <img 
                            src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&q=80&w=350" 
                            alt="Chef at Work" 
                            class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                </a>

                <!-- Card 2: Tour Manager (Bottom Right, Tilted Right) -->
                <a href="{{ url('/discover?tour_manager=1') }}" class="absolute bottom-2 right-4 w-[220px] bg-white rounded-2xl p-3 shadow-2xl border border-gray-100/50 transform rotate-6 hover:rotate-0 hover:scale-105 transition-all duration-300 z-10 block cursor-pointer group">
                    <div class="absolute top-2 left-2 bg-[#2563EB] text-white text-[8px] font-black tracking-wider uppercase px-2 py-0.5 rounded flex items-center gap-1">
                        <i data-lucide="user" class="w-2.5 h-2.5"></i> TOUR MANAGER
                    </div>
                    <div class="mt-4 overflow-hidden rounded-xl bg-gray-50 h-[135px]">
                        <img 
                            src="https://images.unsplash.com/photo-1569336415962-a4bd9f69cd83?auto=format&fit=crop&q=80&w=350" 
                            alt="Travel Tour Manager" 
                            class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-500"
                        />
                    </div>

                    <!-- Inner badge on Tour Manager Card -->
                    <div class="mt-2.5 bg-[#FCF8F2] border border-[#F5EAD4] rounded-xl p-1.5 flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-lg bg-[#E85D26]/10 flex items-center justify-center text-[#E85D26] flex-shrink-0" style="background-color: rgba(232, 93, 38, 0.1) !important; color: #E85D26 !important;">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[8px] font-extrabold text-gray-800 leading-tight">Trusted • Verified</p>
                        </div>
                    </div>
                </a>

            </div>

            <!-- Bottom Indicators/Badges -->
            <div class="mt-auto pt-4 border-t border-white/10 grid grid-cols-3 gap-1.5 text-center w-full bg-black/20 backdrop-blur-xs rounded-xl py-2 px-1">
                <div class="flex flex-col items-center">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-white mb-0.5"></i>
                    <span class="text-[8px] font-extrabold text-white leading-tight">Experienced</span>
                </div>
                <div class="flex flex-col items-center border-x border-white/10">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-white mb-0.5"></i>
                    <span class="text-[8px] font-extrabold text-white leading-tight">Verified</span>
                </div>
                <div class="flex flex-col items-center">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5 text-white mb-0.5"></i>
                    <span class="text-[8px] font-extrabold text-white leading-tight">24/7 Support</span>
                </div>
            </div>
        </div>
    </div>
</div>
