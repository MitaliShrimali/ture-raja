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
        class="fixed inset-y-0 right-0 w-full max-w-sm bg-background shadow-premium flex flex-col p-8"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-12">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    T
                </div>
                <span class="text-2xl font-bold tracking-tight font-heading text-foreground">
                    Tour<span class="text-primary">Raja</span>
                </span>
            </a>
            <button 
                @click="isMobileMenuOpen = false"
                class="p-2 text-text-muted hover:text-foreground transition-colors"
            >
                <i data-lucide="x" size="32"></i>
            </button>
        </div>

        <!-- Links -->
        <div class="flex flex-col gap-6 text-2xl font-bold font-heading text-foreground mb-12">
            <a href="{{ url('/') }}" class="hover:text-primary transition-colors flex items-center justify-between group">
                Home
                <i data-lucide="chevron-right" size="20" class="text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
            </a>
            <a href="{{ url('/discover') }}" class="hover:text-primary transition-colors flex items-center justify-between group">
                Top Destinations
                <i data-lucide="chevron-right" size="20" class="text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
            </a>
            <a href="{{ url('/about') }}" class="hover:text-primary transition-colors flex items-center justify-between group">
                About Us
                <i data-lucide="chevron-right" size="20" class="text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
            </a>
        </div>

        <hr class="border-border-soft mb-8">

        <!-- Actions -->
        <div class="space-y-4">
            <a href="{{ url('/login') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white shadow-soft font-bold text-foreground hover:bg-gray-50 transition-colors">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                    <i data-lucide="user" size="24"></i>
                </div>
                Sign In
            </a>
            <a href="{{ url('/login') }}?tab=agent" class="flex items-center gap-4 p-4 rounded-2xl bg-white shadow-soft font-bold text-foreground hover:bg-gray-50 transition-colors">
                <div class="w-12 h-12 bg-foreground/5 rounded-full flex items-center justify-center text-foreground">
                    <i data-lucide="briefcase" size="24"></i>
                </div>
                Agent Portal
            </a>
            <a href="{{ url('/admin/login') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-foreground text-white shadow-premium font-bold hover:bg-black transition-all">
                <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white">
                    <i data-lucide="shield" size="24"></i>
                </div>
                Admin Login
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-auto pt-8 flex items-center justify-center gap-6 text-text-muted">
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="facebook"></i></a>
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="instagram"></i></a>
            <a href="#" class="hover:text-primary transition-colors"><i data-lucide="twitter"></i></a>
        </div>
    </div>
</div>
