@props([
    'title',
    'image',
    'count' => '40+ Packages',
])

<a href="{{ url('packages/' . \Illuminate\Support\Str::slug($title)) }}" {{ $attributes->merge(['class' => 'relative flex-shrink-0 w-[280px] md:w-[350px] aspect-[3/4] group overflow-hidden rounded-[40px] shadow-premium cursor-pointer block']) }}>
    <div class="relative w-full h-full overflow-hidden">
        <img 
            src="{{ $image ?: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=800' }}" 
            alt="{{ $title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
    </div>
    
    <!-- Dark Overlay Gradient -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-500"></div>

    <!-- Content Overlay -->
    <div class="absolute inset-0 p-8 flex flex-col justify-end">
        <div class="space-y-2 transform transition-transform duration-500 group-hover:-translate-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-widest">
                Explore
            </span>
            <h3 class="text-2xl md:text-3xl font-black leading-tight font-heading" style="color: #ffffff !important;">
                {{ $title }}
            </h3>
            <div class="flex items-center gap-2 text-white/70 text-sm font-bold">
                <i data-lucide="map-pin" size="14" class="text-primary"></i>
                {{ $count }}
            </div>
        </div>

        <!-- Hidden Detail on Hover -->
        <div class="mt-6 pt-6 border-t border-white/20 opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">
            <div class="flex items-center justify-between">
                <span class="text-white font-bold text-sm tracking-wide">View Destinations</span>
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary shadow-glow">
                    <i data-lucide="arrow-right" size="18"></i>
                </div>
            </div>
        </div>
    </div>
</a>
