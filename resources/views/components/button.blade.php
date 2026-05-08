@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconRight' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold transition-all duration-300 rounded-full focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-gradient-to-r from-primary to-[#FF6B2C] text-white shadow-glow hover:shadow-primary/40 hover:-translate-y-1',
        'secondary' => 'bg-white text-foreground border border-border-soft shadow-soft hover:bg-gray-50 hover:shadow-premium hover:-translate-y-1',
        'dark' => 'bg-foreground text-white shadow-premium hover:bg-black hover:-translate-y-1',
        'outline' => 'bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-white hover:-translate-y-1',
        'ghost' => 'bg-transparent text-text-main hover:bg-black/5',
    ];

    $sizes = [
        'sm' => 'px-6 py-2.5 text-xs tracking-wider uppercase',
        'md' => 'px-8 py-4 text-sm tracking-wide',
        'lg' => 'px-10 py-5 text-base tracking-wide',
    ];

    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="mr-2" size="18"></i>
        @endif
        {{ $slot }}
        @if($iconRight)
            <i data-lucide="{{ $iconRight }}" class="ml-2" size="18"></i>
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="mr-2" size="18"></i>
        @endif
        {{ $slot }}
        @if($iconRight)
            <i data-lucide="{{ $iconRight }}" class="ml-2" size="18"></i>
        @endif
    </button>
@endif
