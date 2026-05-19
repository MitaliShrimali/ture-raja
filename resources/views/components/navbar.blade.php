<nav 
    :class="(isScrolled || !isHome) ? 'glass py-4' : 'bg-transparent py-8'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
>
    <div class="container-custom flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
            <div class="relative">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-glow group-hover:rotate-12 transition-transform duration-300">
                    T
                </div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-[#FF6B2C] rounded-full border-2 border-white"></div>
            </div>
            <span :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'" class="text-2xl font-bold tracking-tight font-heading transition-colors duration-300">
                Tour<span class="text-primary">Raja</span>
            </span>
        </a>

        <!-- Centered Menu -->
        <div :class="(isScrolled || !isHome) ? 'text-text-main' : 'text-white/90'" class="hidden lg:flex items-center gap-10 font-bold text-[15px] transition-colors duration-300">
            <a href="{{ url('/') }}" class="hover:text-primary transition-colors relative group">
                Home
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="{{ url('/discover') }}" class="hover:text-primary transition-colors relative group">
                Top Destinations
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="{{ url('/about') }}" class="hover:text-primary transition-colors relative group">
                About Us
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300"></span>
            </a>
        </div>

        <!-- Right Side Icons & Actions -->
        <div class="flex items-center gap-4">
            <!-- Navigation Actions -->
            <div class="hidden md:flex items-center gap-6 mr-4">
                <a href="{{ url('https://emperorsmartsolutions.com/Tour_Raja_Agent/') }}?tab=agent" :class="(isScrolled || !isHome) ? 'text-text-main' : 'text-white/90'" class="text-sm font-bold hover:text-primary transition-colors">
                    Agent Login
                </a>
                <a href="{{ url('/admin/login') }}" :class="(isScrolled || !isHome) ? 'text-text-main' : 'text-white/90'" class="text-sm font-bold hover:text-primary transition-colors">
                    Admin
                </a>
            </div>

            <!-- Wishlist -->
            <div class="relative hidden lg:block" x-data="{ open: false }" @click.away="open = false">
                <button 
                    @click="open = !open"
                    class="relative flex items-center justify-center w-11 h-11 bg-primary/10 hover:bg-primary backdrop-blur-md text-primary hover:text-white rounded-full transition-all shadow-premium group border border-primary/20"
                >
                    <i data-lucide="heart" size="20" class="transition-colors group-hover:fill-white group-hover:text-white"></i>
                    <span id="wishlist-count" class="absolute -top-1 -right-1 w-5 h-5 bg-primary text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
                </button>

                <!-- Wishlist Dropdown -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-premium border border-border-soft overflow-hidden"
                    style="display: none;"
                >
                    <div class="p-5 border-b border-border-soft">
                        <h4 class="text-sm font-black text-foreground uppercase tracking-widest">My Wishlist</h4>
                    </div>
                    <div id="wishlist-items" class="max-h-96 overflow-y-auto p-4 space-y-4">
                        <!-- Items will be injected here -->
                        <p class="text-center text-text-muted text-xs py-8 font-bold">Your wishlist is empty</p>
                    </div>
                    <div class="p-4 bg-gray-50">
                        <a href="{{ url('/listing') }}" class="block w-full py-3 bg-black text-white text-center text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary transition-all">Explore More</a>
                    </div>
                </div>
            </div>

            @if(Auth::check())
                <a href="{{ url('/profile') }}" class="hidden lg:flex items-center justify-center w-11 h-11 bg-primary text-white rounded-full transition-all shadow-premium hover:scale-105 duration-300">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="font-bold text-xs uppercase">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    @endif
                </a>
            @else
                <a href="{{ url('/login') }}" class="hidden lg:flex items-center justify-center w-11 h-11 bg-white/10 hover:bg-primary backdrop-blur-md text-white rounded-full transition-all shadow-premium group">
                    <i data-lucide="user" size="20" :class="(isScrolled || !isHome) ? 'text-foreground group-hover:text-white' : 'text-white'"></i>
                </a>
            @endif
            
            <!-- Mobile Wishlist Icon -->
            <button 
                @click="isMobileMenuOpen = true"
                class="lg:hidden relative flex items-center justify-center w-10 h-10 bg-primary/10 text-primary rounded-full border border-primary/20"
            >
                <i data-lucide="heart" size="18" class="fill-primary"></i>
            </button>

            <!-- Mobile Toggle -->
            <button 
                @click="isMobileMenuOpen = true"
                :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'"
                class="lg:hidden p-2.5 ml-1"
            >
                <i data-lucide="menu" size="28"></i>
            </button>
        </div>
    </div>
</nav>
