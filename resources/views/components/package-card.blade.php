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
        $pkgArr = (array) $pkg;
        $title    = $pkgArr['title']    ?? $title;
        $image    = $pkgArr['image']    ?? $image;
        $duration = $pkgArr['duration'] ?? $duration;
        $groupSize= $pkgArr['groupSize']?? $groupSize;
        $rating   = $pkgArr['rating']   ?? $rating;
        $reviews  = $pkgArr['reviews']  ?? $reviews;
        $price    = $pkgArr['price']    ?? $price;
        $oldPrice = $pkgArr['oldPrice'] ?? $oldPrice;
        $badge    = $pkgArr['badge']    ?? $badge;
        $slug     = $pkgArr['slug']     ?? \Illuminate\Support\Str::slug($title ?? '') ?: $slug;
    }
    $detailUrl = $slug ? url('packages/' . $slug) : '#';
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white rounded-[24px] overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-2 flex flex-col border border-border-soft/50 package-card-inner']) }}>
    <!-- Image Container -->
    <div class="relative aspect-[1.2/1] overflow-hidden m-2 rounded-[20px] package-image-container">
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
        <button 
            onclick="toggleWishlist(event, { slug: '{{ $slug }}', title: '{{ addslashes($title) }}', image: '{{ $image }}', price: '{{ $price }}' })"
            data-wishlist-slug="{{ $slug }}"
            class="wishlist-btn absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all duration-300 border border-white/30"
        >
            <i data-lucide="heart" size="16"></i>
        </button>
    </div>

    <!-- Content -->
    <div class="px-6 pt-5 pb-6 flex flex-col flex-grow space-y-4 package-content">

        <div class="flex items-start justify-between gap-4">
            <h3 class="text-xl font-black text-foreground leading-tight line-clamp-2 font-heading min-h-[3rem] flex-grow">
                {{ $title }}
            </h3>
            
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 shrink-0 mt-1 shadow-sm">
                <i data-lucide="star" class="fill-orange-400 text-orange-400" size="14"></i>
                <span class="text-xs font-black text-foreground">{{ $rating }}</span>
            </div>
        </div>
        
        <div class="flex items-center gap-4 text-[11px] font-bold text-text-muted">
            <div class="flex items-center gap-1.5">
                <i data-lucide="clock" size="14" class="opacity-50"></i>
                {{ $duration }}
            </div>
            <div class="flex items-center gap-1.5">
                <i data-lucide="users" size="14" class="opacity-50"></i>
                {{ $groupSize }}
            </div>
        </div>

        <div class="pt-4 border-t border-border-soft/50 flex items-end justify-between mt-auto package-action">
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
