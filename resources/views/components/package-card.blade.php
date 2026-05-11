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
])

@php
    // If a pkg object is passed, extract properties from it
    if ($pkg) {
        $title = $title ?? ($pkg->title ?? $pkg['title'] ?? '');
        $image = $image ?? ($pkg->image ?? $pkg['image'] ?? '');
        $duration = $duration ?? ($pkg->duration ?? $pkg['duration'] ?? '');
        $rating = $rating ?? ($pkg->rating ?? $pkg['rating'] ?? '0');
        $reviews = $reviews ?? ($pkg->reviews ?? $pkg['reviews'] ?? '0');
        $price = $price ?? ($pkg->price ?? $pkg['price'] ?? 0);
        $badge = $badge ?? ($pkg->badge ?? $pkg['badge'] ?? null);
    }
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white rounded-[32px] overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-2 flex flex-col border border-border-soft/50']) }}>
    <!-- Image Container -->
    <div class="relative aspect-[1.2/1] overflow-hidden m-2 rounded-[24px]">
        <img 
            src="{{ $image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800' }}" 
            alt="{{ $title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        
        <!-- Top Floating Badges -->
        <div class="absolute top-4 left-4">
            @if($badge)
                <span class="px-4 py-1.5 rounded-full bg-white text-foreground text-[10px] font-black uppercase tracking-widest shadow-soft">
                    {{ $badge }}
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all duration-300 border border-white/30">
            <i data-lucide="heart" size="16"></i>
        </button>

        <!-- Rating Badge (Floating at bottom of image) -->
        <div class="absolute -bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 z-10">
            <div class="flex items-center gap-1.5 px-4 py-2 rounded-full bg-white shadow-premium border border-border-soft whitespace-nowrap">
                <i data-lucide="star" size="14" class="fill-orange-400 text-orange-400"></i>
                <span class="text-xs font-black text-foreground">{{ $rating }}</span>
                <span class="text-[10px] text-text-muted font-bold">({{ $reviews }} reviews)</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 pt-8 pb-6 flex flex-col flex-grow space-y-5">
        <div class="space-y-3">
            <h3 class="text-xl font-black text-foreground leading-tight line-clamp-2 min-h-[3rem] font-heading">
                {{ $title }}
            </h3>
            
            <div class="flex items-center gap-4 text-[11px] font-bold text-text-muted">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="clock" size="14" class="text-text-muted opacity-50"></i>
                    {{ $duration }}
                </div>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="users" size="14" class="text-text-muted opacity-50"></i>
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

            <button class="px-6 py-2.5 rounded-full bg-primary text-white text-xs font-black shadow-glow hover:shadow-primary/40 transition-all duration-300">
                Book Now
            </button>
        </div>
    </div>
</div>
