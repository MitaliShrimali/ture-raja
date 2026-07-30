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
    'currency' => null,
    'badge' => null,
    'slug' => null,
    'hideAgent' => false,
])

@php
    $agentObj = null;
    $pkgArr   = [];
    if ($pkg) {
        $pkgArr   = (array) $pkg;
        $title    = $pkgArr['title']    ?? $title;
        $image    = $pkgArr['image']    ?? $image;
        $duration = $pkgArr['duration'] ?? $duration;
        $currency = $pkgArr['currency'] ?? $currency;
        $groupSize= $pkgArr['groupSize']?? $groupSize;
        $rating   = $pkgArr['rating']   ?? $rating;
        $reviews  = $pkgArr['reviews']  ?? $reviews;
        $price    = $pkgArr['price']    ?? $price;
        $oldPrice = $pkgArr['oldPrice'] ?? $oldPrice;
        $badge    = $pkgArr['badge']    ?? $badge;
        $slug     = $pkgArr['slug']     ?? \Illuminate\Support\Str::slug($title ?? '') ?: $slug;
        $agentObj = $pkgArr['agent']    ?? null;
    }

    // ── Format duration → 3N/4D ─────────────────────────────────────────
    $formattedDuration = $duration ?? '';
    if ($duration) {
        $days   = 0;
        $nights = 0;
        if (preg_match('/(\d+)\s*[dD]ays?/i', $duration, $m)) $days   = (int)$m[1];
        if (preg_match('/(\d+)\s*[nN]ights?/i', $duration, $m)) $nights = (int)$m[1];
        if ($days > 0 && $nights > 0) {
            $formattedDuration = $nights . 'N/' . $days . 'D';
        } elseif ($days > 0) {
            $formattedDuration = $days . 'D';
        } elseif ($nights > 0) {
            $formattedDuration = $nights . 'N';
        }
    }

    // ── Tour type & category ─────────────────────────────────────────────
    $tourTypeVal  = $pkgArr['tour_type'] ?? '';
    $categoryVal  = $pkgArr['category']  ?? '';
    $themeVal     = $pkgArr['theme']     ?? '';

    // Icon per transit type
    $transportIcon = 'plane';
    $typeLower = strtolower($tourTypeVal);
    if (str_contains($typeLower, 'bus'))       $transportIcon = 'bus';
    elseif (str_contains($typeLower, 'train')) $transportIcon = 'train';
    elseif (str_contains($typeLower, 'cruise') || str_contains($typeLower, 'boat')) $transportIcon = 'ship';
    elseif (str_contains($typeLower, 'helicopter')) $transportIcon = 'plane-takeoff';
    elseif (str_contains($typeLower, 'trek') || str_contains($typeLower, 'track'))  $transportIcon = 'footprints';

    // ── Agent ─────────────────────────────────────────────────────────────
    if (is_string($agentObj)) {
        $decoded = json_decode($agentObj, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $agentObj  = $decoded;
            $agentName = $agentObj['name'] ?? 'Travel Agent';
        } else {
            $agentName = $agentObj;
        }
    } else {
        if (is_object($agentObj)) $agentObj = (array) $agentObj;
        $agentName = $agentObj['name'] ?? 'Travel Agent';
    }

    $rawLogo = is_array($agentObj) ? ($agentObj['logo'] ?? null) : null;
    if ($rawLogo && !str_starts_with($rawLogo, 'http') && !str_starts_with($rawLogo, '//')) {
        $rawLogo = asset(ltrim($rawLogo, '/'));
    }
    $agentLogo = $rawLogo ?? null; // null = show initials avatar

    // Agent location
    $agentCity    = is_array($agentObj) ? ($agentObj['city']    ?? '') : '';
    $agentState   = is_array($agentObj) ? ($agentObj['state']   ?? '') : '';
    $agentCountry = is_array($agentObj) ? ($agentObj['country'] ?? '') : '';
    $agentLocation = trim(implode(', ', array_filter([$agentCity, $agentState])));
    if (!$agentLocation && $agentCountry) $agentLocation = $agentCountry;

    // Guaranteed Service
    $isGuaranteed = !empty($agentObj['service_guaranteed']);
    if (!$isGuaranteed && !empty($agentName)) {
        try {
            $dbAgent = \Illuminate\Support\Facades\DB::table('agents')
                ->where('name', 'LIKE', trim($agentName))
                ->first();
            if ($dbAgent) {
                $isGuaranteed = !empty($dbAgent->service_guaranteed);
            }
        } catch (\Exception $e) {
            // Ignore DB errors
        }
    }

    // Initials for avatar fallback
    $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', trim($agentName)), 0, 2))));

    $detailUrl = $slug ? url('packages/' . $slug) : '#';
@endphp

<div onclick="window.open('{{ $detailUrl }}', '_blank')" {{ $attributes->merge(['class' => 'cursor-pointer group bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-1 flex flex-col border border-border-soft/50 package-card-inner']) }}>

    {{-- ── Image ─────────────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden m-2 rounded-xl package-image-container" style="aspect-ratio:1.25/1;">
        <img
            src="{{ asset($image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800') }}"
            alt="{{ $title }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >

        {{-- Badge top-left --}}
        @if($badge)
            <div class="absolute top-3 left-3">
                <span class="px-4 py-1.5 rounded-full bg-white text-foreground text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ $badge }}
                </span>
            </div>
        @endif

        {{-- Wishlist top-right --}}
        <button
            onclick="toggleWishlist(event, { slug: '{{ $slug }}', title: '{{ addslashes($title) }}', image: '{{ $image }}', price: '{{ $price }}', currency: '{{ $currency ?? '₹' }}' })"
            data-wishlist-slug="{{ $slug }}"
            class="wishlist-btn absolute top-3 right-3 w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all duration-300 border border-white/30"
        >
            <i data-lucide="heart" size="16"></i>
        </button>
    </div>

    {{-- ── Content ──────────────────────────────────────────────────── --}}
    <div class="px-5 pt-4 pb-5 flex flex-col flex-grow gap-3 package-content">

        {{-- Row 1: Title + Duration --}}
        <div class="flex items-center justify-between gap-3">
            <h3 class="font-black text-foreground leading-tight truncate font-heading flex-grow" style="font-size:18px;" title="{{ $title }}">
                {{ $title }}
            </h3>
            @if($formattedDuration)
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-gray-50 border border-gray-200 shrink-0 shadow-sm text-[11px] font-bold text-gray-700">
                    <i data-lucide="calendar-days" size="12" class="text-gray-500"></i>
                    <span>{{ $formattedDuration }}</span>
                </div>
            @endif
        </div>

        {{-- Row 2: Tour Type + Theme --}}
        <div class="flex items-center gap-2 flex-wrap">
            @if($tourTypeVal)
                <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 border border-orange-100 text-[11px] font-bold text-orange-600">
                    <i data-lucide="{{ $transportIcon }}" size="12"></i>
                    {{ $tourTypeVal }}
                </span>
            @endif

            @if($themeVal)
                <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-violet-50 border border-violet-100 text-[11px] font-bold text-violet-600">
                    <i data-lucide="users" size="12"></i>
                    {{ $themeVal }}
                </span>
            @endif
        </div>

        {{-- Row 4: Agent avatar + Name + Location --}}
        @if(!$hideAgent)
        <div class="flex items-center gap-2.5 min-w-0">
            {{-- Agent Avatar --}}
            <div class="relative shrink-0">
                <div class="w-9 h-9 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-100 shadow-sm"
                     style="background: linear-gradient(135deg, #6d28d9, #4f46e5);">
                    @if($agentLogo)
                        <img src="{{ $agentLogo }}" alt="{{ $agentName }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-[11px] font-black text-white">{{ $initials }}</span>
                    @endif
                </div>
                @if($isGuaranteed)
                    <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center w-5 h-5 shadow-sm z-10 text-white">
                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                    </div>
                @endif
            </div>
            {{-- Agent Name + Location --}}
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-800 truncate">{{ $agentName }}</p>
                @if($agentLocation)
                    <p class="text-[10px] text-gray-500 font-medium flex items-center gap-0.5 mt-0.5">
                        <i data-lucide="map-pin" size="10" class="shrink-0 text-gray-400"></i>
                        <span class="truncate">{{ $agentLocation }}</span>
                    </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Row 5: Price + Search Now --}}
        <div class="pt-3 border-t border-border-soft/50 flex items-end justify-between mt-auto package-action">
            <div class="flex flex-col">
                <span class="text-2xl font-black text-foreground tracking-tight font-heading">{{ $currency ?? '₹' }}{{ number_format($price) }}</span>
                @if($oldPrice)
                    <span class="text-[11px] text-gray-400 font-semibold line-through">{{ $currency ?? '₹' }}{{ number_format($oldPrice) }}</span>
                @endif
            </div>

            <a href="{{ $detailUrl }}" target="_blank" onclick="event.stopPropagation()"
               class="px-5 py-2.5 rounded-full bg-primary text-white text-xs font-black shadow-glow hover:bg-primary/90 transition-all duration-300 inline-block whitespace-nowrap shrink-0 text-center">
                Search Now
            </a>
        </div>
    </div>
</div>


