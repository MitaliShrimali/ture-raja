@props([
    'pkg' => null,
    'title' => null,
    'image' => null,
    'destination' => null,
    'duration' => null,
    'groupSize' => '4-6 guest',
    'rating' => '0',
    'reviews' => '0',
    'price' => 0,
    'oldPrice' => null,
    'badge' => null,
    'slug' => null,
])

@php
    if ($pkg) {
        $title    = $title    ?? ($pkg->title    ?? $pkg['title']    ?? '');
        $image    = $image    ?? ($pkg->image    ?? $pkg['image']    ?? '');
        $duration = $duration ?? ($pkg->duration ?? $pkg['duration'] ?? '');
        $rating   = $rating   ?? ($pkg->rating   ?? $pkg['rating']   ?? '0');
        $reviews  = $reviews  ?? ($pkg->reviews  ?? $pkg['reviews']  ?? '0');
        $price    = $price    ?? ($pkg->price    ?? $pkg['price']    ?? 0);
        $badge    = $badge    ?? ($pkg->badge    ?? $pkg['badge']    ?? null);
        $slug     = $slug     ?? ($pkg->slug     ?? $pkg['slug']     ?? null);
    }
    $detailUrl = $slug ? url('packages/' . $slug) : '#';
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white rounded-[32px] overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-2 flex flex-col border border-border-soft/50']) }}>
    <!-- Image Container -->
    <div class="relative aspect-[1.2/1] overflow-hidden m-2 rounded-[24px]">
        <img 
            src="{{ $image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800' }}" 
            alt="{{ $title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        
        <!-- Badge top-left -->
        <div class="absolute top-4 left-4">
            @if($badge)
                <span class="px-4 py-1.5 rounded-full bg-white text-foreground text-[10px] font-black uppercase tracking-widest shadow-soft">
                    {{ $badge }}
                </span>
            @endif
        </div>

        <!-- Wishlist Button top-right -->
        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all duration-300 border border-white/30">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>

        <!-- ⭐ Rating Badge — bottom-right corner (professional style) -->
        <div class="absolute bottom-3 right-3 z-10">
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/95 backdrop-blur-sm shadow-lg border border-white/50">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#fb923c" stroke="#fb923c" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="text-xs font-black text-foreground">{{ $rating }}</span>
                <span class="text-[10px] text-text-muted font-bold">({{ $reviews }})</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 pt-5 pb-6 flex flex-col flex-grow space-y-4">
        <div class="space-y-2">
            <h3 class="text-xl font-black text-foreground leading-tight line-clamp-2 min-h-[3rem] font-heading">
                {{ $title }}
            </h3>
            
            <div class="flex items-center gap-4 text-[11px] font-bold text-text-muted">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-50"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $duration }}
                </div>
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-50"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $groupSize }}
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-border-soft/50 flex items-end justify-between mt-auto">
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="text-2xl font-black text-foreground tracking-tight font-heading">₹{{ number_format($price) }}</span>
                    <span class="text-[10px] text-text-muted font-bold mt-1">/ person</span>
                </div>
                @if($oldPrice)
                    <span class="text-[11px] text-primary font-black line-through">₹{{ number_format($oldPrice) }}</span>
                @endif
            </div>

            <a href="{{ $detailUrl }}" class="px-6 py-2.5 rounded-full bg-primary text-white text-xs font-black shadow-glow hover:bg-primary/90 hover:shadow-primary/40 transition-all duration-300 inline-block">
                Book Now
            </a>
        </div>
    </div>
</div>
