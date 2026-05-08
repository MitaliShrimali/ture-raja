<section class="relative min-h-screen flex items-center justify-center py-24 lg:py-32">
    <!-- Background Image with Cinematic Zoom -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img
            src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
            alt="Cinematic Travel Background"
            class="w-full h-full object-cover animate-zoom-slow"
        />
        <!-- Darker Overlay Gradient for better contrast -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-background"></div>
    </div>

    <!-- Content -->
    <div class="container-custom relative z-10 text-center text-white">
        <div class="max-w-5xl mx-auto flex flex-col items-center gap-8 md:gap-10">
            <!-- Top Badge -->
            <div class="animate-fade-in [animation-delay:200ms]">
                <span class="inline-block px-5 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] md:text-xs font-black tracking-[0.2em] uppercase">
                    Discover the world with us
                </span>
            </div>

            <!-- Main Heading -->
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-black leading-[1.1] tracking-tight font-heading animate-fade-in [animation-delay:400ms]">
                    Discover Exclusive <br class="hidden sm:block">
                    <span class="text-primary">Travel Packages</span> <br class="hidden sm:block">
                    from Local Agents Near You!
                </h1>

                <!-- Subheading -->
                <p class="text-base md:text-lg lg:text-xl text-white/90 max-w-2xl mx-auto font-medium leading-relaxed animate-fade-in [animation-delay:600ms]">
                    Experience curated luxury journeys and hidden gems with our premium global travel marketplace.
                </p>
            </div>

            <!-- Floating Search Bar - Robust Responsiveness -->
            <div class="w-full max-w-4xl pt-4 animate-fade-in [animation-delay:800ms]">
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl lg:rounded-full p-2 lg:p-3 shadow-premium shadow-black/30 flex flex-col lg:flex-row items-center gap-2">
                    <!-- Destination -->
                    <div class="flex-1 w-full flex items-center gap-4 px-6 py-4 lg:py-1 border-b lg:border-b-0 lg:border-r border-gray-100">
                        <i data-lucide="map-pin" class="text-primary shrink-0" size="24"></i>
                        <div class="text-left flex-1">
                            <p class="text-[10px] font-bold text-foreground/40 uppercase tracking-widest">Destination</p>
                            <input 
                                type="text" 
                                placeholder="Where to go?" 
                                class="w-full bg-transparent text-foreground font-bold focus:outline-none placeholder:text-foreground/30 text-base"
                            >
                        </div>
                    </div>

                    <!-- Local City -->
                    <div class="flex-1 w-full flex items-center gap-4 px-6 py-4 lg:py-1">
                        <i data-lucide="navigation" class="text-primary shrink-0" size="24"></i>
                        <div class="text-left flex-1">
                            <p class="text-[10px] font-bold text-foreground/40 uppercase tracking-widest">From City</p>
                            <input 
                                type="text" 
                                placeholder="Search from local city" 
                                class="w-full bg-transparent text-foreground font-bold focus:outline-none placeholder:text-foreground/30 text-base"
                            >
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button class="w-full lg:w-auto px-10 py-5 bg-primary hover:bg-primary-hover text-white rounded-2xl lg:rounded-full font-black text-sm uppercase tracking-widest transition-all shadow-glow flex items-center justify-center gap-3 group shrink-0">
                        <i data-lucide="search" size="20" class="group-hover:scale-110 transition-transform"></i>
                        <span>Search</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
