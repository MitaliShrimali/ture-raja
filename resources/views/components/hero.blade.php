<section class="relative min-h-screen flex items-center justify-center py-24 lg:py-32 overflow-hidden">
    <!-- Background Slider -->
    <div class="absolute inset-0 z-0 overflow-hidden bg-[#1A1A24]">
        <div class="hero-slider-container" id="heroSlider" style="display: flex; width: 100%; height: 100%; transition: transform 1.2s cubic-bezier(0.65, 0, 0.35, 1); will-change: transform;">@php
                $slides = [
                    'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80',
                ];
            @endphp @foreach($slides as $index => $url)<div class="hero-slide" style="background-image: url('{{ $url }}'); width: 100%; height: 100%; flex: 0 0 100%; background-size: cover; background-position: center;"></div>@endforeach</div>
        
        <!-- Original Cinematic Overlay Gradient -->
         
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/40 to-background z-10 pointer-events-none"></div>
    </div>

    <!-- Slider Navigation (Corner) -->
    <div class="absolute bottom-12 right-8 lg:right-16 z-40 flex items-center gap-4 animate-fade-in [animation-delay:1000ms]">
        <div class="hero-nav-dots flex items-center">
            <button onclick="prevHeroSlide()" class="text-white/70 hover:text-white transition-colors mr-2">
                <i data-lucide="arrow-left" size="20"></i>
            </button>
            
            @foreach($slides as $index => $url)
                <div class="hero-dot {{ $index === 0 ? 'active' : '' }}" data-dot="{{ $index }}" onclick="goToHeroSlide({{ $index }})" style="cursor: pointer; width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.3); margin: 0 4px;"></div>
            @endforeach

            <button onclick="nextHeroSlide()" class="text-white/70 hover:text-white transition-colors ml-2">
                <i data-lucide="arrow-right" size="20"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="container-custom relative z-30 text-center text-white pointer-events-none">
        <div class="max-w-5xl mx-auto flex flex-col items-center gap-8 md:gap-10 pointer-events-auto">
            <!-- Top Badge -->
            <div class="animate-fade-in [animation-delay:200ms]">
                <span class="inline-block px-5 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] md:text-xs font-black tracking-[0.2em] uppercase">
                    Discover the world with us
                </span>
            </div>

            <!-- Main Heading -->
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-black leading-[1.1] tracking-tight font-heading animate-fade-in [animation-delay:400ms]" style="color: #ffffff !important;">
                    Discover Exclusive <br class="hidden sm:block">
                    <span class="text-primary">Travel Packages</span> <br class="hidden sm:block">
                    from Local Agents Near You!
                </h1>

                <!-- Subheading -->
                <p class="text-base md:text-lg lg:text-xl text-white/90 max-w-2xl mx-auto font-medium leading-relaxed animate-fade-in [animation-delay:600ms]">
                    Experience curated luxury journeys and hidden gems with our premium global travel marketplace.
                </p>
            </div>

            <!-- Floating Search Bar -->
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

    <script>
        let currentSlide = 0;
        const slider = document.getElementById('heroSlider');
        const dots = document.querySelectorAll('.hero-dot');
        const totalSlides = dots.length;
        let slideInterval;

        function showSlide(index) {
            if (!slider) return;
            slider.style.transform = `translateX(-${index * 100}%)`;
            
            dots.forEach((d, i) => {
                d.classList.toggle('active', i === index);
            });
            currentSlide = index;
        }

        function nextHeroSlide() {
            let next = (currentSlide + 1) % totalSlides;
            showSlide(next);
            resetInterval();
        }

        function prevHeroSlide() {
            let prev = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(prev);
            resetInterval();
        }

        function goToHeroSlide(index) {
            showSlide(index);
            resetInterval();
        }

        function startInterval() {
            slideInterval = setInterval(nextHeroSlide, 5000);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        document.addEventListener('DOMContentLoaded', startInterval);
    </script>
</section>
