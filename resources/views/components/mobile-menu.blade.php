<div 
    x-show="isMobileMenuOpen" 
    x-cloak
    class="fixed inset-0 z-[100] lg:hidden"
    role="dialog" 
    aria-modal="true"
>
    <!-- Backdrop -->
    <div 
        x-show="isMobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-foreground/60 backdrop-blur-sm"
        @click="isMobileMenuOpen = false"
    ></div>

    <!-- Drawer -->
    <div 
        x-show="isMobileMenuOpen"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full max-w-sm bg-background shadow-premium flex flex-col p-8 overflow-y-auto"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ url('/') }}" class="flex items-center">
                <x-logo class="h-8 sm:h-10 w-auto text-foreground" />
            </a>
            <button 
                @click="isMobileMenuOpen = false"
                class="p-2 text-text-muted hover:text-foreground transition-colors"
            >
                <i data-lucide="x" size="32"></i>
            </button>
        </div>

        <!-- Links -->
        <div class="flex flex-col gap-5 text-xl font-bold font-heading text-foreground mb-8">
            <a href="{{ url('/') }}" class="hover:text-primary transition-colors flex items-center justify-between group">
                Home
                <i data-lucide="chevron-right" size="20" class="text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
            </a>
            <a href="{{ url('/discover') }}" class="hover:text-primary transition-colors flex items-center justify-between group">
                Top Destinations
                <i data-lucide="chevron-right" size="20" class="text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
            </a>
            <div class="pt-4 mt-4 border-t border-border-soft flex items-center justify-between">
                <span class="text-sm font-black text-foreground uppercase tracking-widest">My Wishlist</span>
                <div class="relative">
                    <i data-lucide="heart" size="24" class="text-primary fill-primary"></i>
                    <span id="wishlist-count-mobile" class="absolute -top-2 -right-2 w-5 h-5 bg-primary text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-background">0</span>
                </div>
            </div>
            <div id="wishlist-items-mobile" class="max-h-60 overflow-y-auto space-y-4 py-2">
                <!-- Items injected here -->
                <p class="text-xs text-text-muted font-bold italic">No items yet</p>
            </div>
        </div>

        <hr class="border-border-soft mb-8">

        <!-- Actions -->
        <div class="space-y-4">
            @auth
                <a href="{{ url('/profile') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white shadow-soft font-bold text-foreground hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                        <i data-lucide="user" size="24"></i>
                    </div>
                    My Profile
                </a>
                <a href="{{ url('/logout') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white shadow-soft font-bold text-red-500 hover:bg-red-50 transition-colors">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                        <i data-lucide="log-out" size="24"></i>
                    </div>
                    Sign Out
                </a>
                @if(in_array(auth()->user()->role, ['SUPER ADMIN', 'MANAGER', 'EDITOR']))
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-foreground text-white shadow-premium font-bold hover:bg-black transition-all">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white">
                            <i data-lucide="shield" size="24"></i>
                        </div>
                        Admin Dashboard
                    </a>
                @endif
            @else
                <a href="{{ url('/login') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white shadow-soft font-bold text-foreground hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                        <i data-lucide="user" size="24"></i>
                    </div>
                    Sign In
                </a>
                <!-- Removed Agent Portal and Admin Login as requested -->
            @endauth
        </div>

        <!-- Footer -->
        <div class="mt-auto pt-8 flex items-center justify-center gap-6 text-text-muted">
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="facebook"></i></a>
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="instagram"></i></a>
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="twitter"></i></a>
        </div>
    </div>
</div>
