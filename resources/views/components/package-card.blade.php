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
    'hideAgent' => false,
])

@php
    $agentObj = null;
    if ($pkg) {
        $pkgArr = (array) $pkg;
        $title    = $pkgArr['title']    ?? $title;
        $image    = $pkgArr['image']    ?? $image;
        $duration = $pkgArr['duration'] ?? $duration;
        
        // Format duration to show Nights first if present
        if ($duration) {
            $days = 0;
            $nights = 0;
            if (preg_match('/(\d+)\s*[dD]ays?/i', $duration, $m)) {
                $days = (int)$m[1];
            }
            if (preg_match('/(\d+)\s*[nN]ights?/i', $duration, $m)) {
                $nights = (int)$m[1];
            }
            if ($days > 0 && $nights > 0) {
                $duration = $nights . ($nights == 1 ? ' Night' : ' Nights') . ' / ' . $days . ($days == 1 ? ' Day' : ' Days');
            }
        }

        $groupSize= $pkgArr['groupSize']?? $groupSize;
        $rating   = $pkgArr['rating']   ?? $rating;
        $reviews  = $pkgArr['reviews']  ?? $reviews;
        $price    = $pkgArr['price']    ?? $price;
        $oldPrice = $pkgArr['oldPrice'] ?? $oldPrice;
        $badge    = $pkgArr['badge']    ?? $badge;
        $slug     = $pkgArr['slug']     ?? \Illuminate\Support\Str::slug($title ?? '') ?: $slug;
        $agentObj = $pkgArr['agent']    ?? null;
    }
    
    if (is_string($agentObj)) {
        $decoded = json_decode($agentObj, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $agentObj = $decoded;
            $agentName = $agentObj['name'] ?? 'Travel Agent Name';
        } else {
            $agentName = $agentObj;
        }
    } else {
        if (is_object($agentObj)) {
            $agentObj = (array) $agentObj;
        }
        $agentName = $agentObj['name'] ?? 'Travel Agent Name';
    }

    $agentLogo = (is_array($agentObj) ? ($agentObj['logo'] ?? null) : null) ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode(str_replace(' ', '', $agentName));

    $detailUrl = $slug ? url('packages/' . $slug) : '#';
@endphp

<div onclick="window.location.href='{{ $detailUrl }}'" {{ $attributes->merge(['class' => 'cursor-pointer group bg-white rounded-lg overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-2 flex flex-col border border-border-soft/50 package-card-inner']) }}>
    <!-- Image Container -->
    <div class="relative aspect-[1.2/1] overflow-hidden m-2 rounded-md package-image-container" style="aspect-ratio: 1.2/1;">
        <img 
            src="{{ $image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800' }}" 
            alt="{{ $title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            style="width: 100%; height: 100%; object-fit: cover;"
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
            <h3 class="font-black text-foreground leading-tight line-clamp-2 font-heading min-h-[3rem] flex-grow" style="font-size: 20px;">
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

        @if($hideAgent)
            @php
                $categoryVal = $pkgArr['category'] ?? 'Tour';
                $tourTypeVal = $pkgArr['tour_type'] ?? 'Land Package';
                
                $transportIcon = 'plane';
                $typeLower = strtolower($tourTypeVal);
                if (str_contains($typeLower, 'bus')) {
                    $transportIcon = 'bus';
                } elseif (str_contains($typeLower, 'train')) {
                    $transportIcon = 'train';
                } elseif (str_contains($typeLower, 'cruise') || str_contains($typeLower, 'boat')) {
                    $transportIcon = 'ship';
                } elseif (str_contains($typeLower, 'helicopter')) {
                    $transportIcon = 'plane-takeoff';
                } elseif (str_contains($typeLower, 'trek') || str_contains($typeLower, 'track')) {
                    $transportIcon = 'footprints';
                }
            @endphp
            <div class="flex flex-wrap gap-2 mt-1">
                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-[11px] font-extrabold capitalize border border-blue-100">
                    <i data-lucide="globe" size="12"></i>
                    {{ $categoryVal }}
                </span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 text-orange-600 text-[11px] font-extrabold capitalize border border-orange-100">
                    <i data-lucide="{{ $transportIcon }}" size="12"></i>
                    {{ $tourTypeVal }}
                </span>
            </div>
        @else
            <div class="flex items-center justify-between mt-1">
                <div class="flex items-center gap-2">
                    <i data-lucide="shield-check" size="14" class="text-green-500"></i>
                    <span class="text-xs font-bold text-gray-700">{{ $agentName }}</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200 shrink-0">
                    <img src="{{ $agentLogo }}" alt="{{ $agentName }} Logo" class="w-full h-full object-cover">
                </div>
            </div>
        @endif

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

            <a href="{{ $detailUrl }}" onclick="event.stopPropagation()" class="px-6 py-2.5 rounded-full bg-primary text-white text-xs font-black shadow-glow hover:bg-primary/90 hover:shadow-primary/40 transition-all duration-300 inline-block whitespace-nowrap shrink-0 text-center">
                Search Now
            </a>
        </div>
    </div>
</div>
