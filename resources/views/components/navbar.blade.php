<nav 
    :class="isScrolled ? 'glass py-4' : 'bg-transparent py-8'"
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
            <span :class="isScrolled ? 'text-foreground' : 'text-white'" class="text-2xl font-bold tracking-tight font-heading transition-colors duration-300">
                Tour<span class="text-primary">Raja</span>
            </span>
        </a>

        <!-- Centered Menu -->
        <div :class="isScrolled ? 'text-text-main' : 'text-white/90'" class="hidden lg:flex items-center gap-10 font-bold text-[15px] transition-colors duration-300">
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
                <a href="{{ url('/login') }}?tab=agent" :class="isScrolled ? 'text-text-main' : 'text-white/90'" class="text-sm font-bold hover:text-primary transition-colors">
                    Agent Login
                </a>
                <a href="{{ url('/admin/login') }}" :class="isScrolled ? 'text-text-main' : 'text-white/90'" class="text-sm font-bold hover:text-primary transition-colors">
                    Admin
                </a>
            </div>

            <button :class="isScrolled ? 'text-foreground' : 'text-white'" class="p-2.5 rounded-full hover:bg-black/5 transition-colors">
                <i data-lucide="search" size="22"></i>
            </button>
            <a href="{{ url('/login') }}" class="flex items-center justify-center w-11 h-11 bg-white/10 hover:bg-primary backdrop-blur-md text-white rounded-full transition-all shadow-premium group">
                <i data-lucide="user" size="20" :class="isScrolled ? 'text-foreground group-hover:text-white' : 'text-white'"></i>
            </a>
            
            <!-- Mobile Toggle -->
            <button 
                @click="isMobileMenuOpen = true"
                :class="isScrolled ? 'text-foreground' : 'text-white'"
                class="lg:hidden p-2.5 ml-2"
            >
                <i data-lucide="menu" size="28"></i>
            </button>
        </div>
    </div>
</nav>
