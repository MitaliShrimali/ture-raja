@props([
    'variant' => 'soft', // soft, solid, outline
    'color' => 'primary', // primary, success, warning, neutral
])

@php
    $baseClasses = 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest';
    
    $colors = [
        'primary' => [
            'soft' => 'bg-primary/10 text-primary',
            'solid' => 'bg-primary text-white',
            'outline' => 'border border-primary text-primary',
        ],
        'success' => [
            'soft' => 'bg-emerald-500/10 text-emerald-600',
            'solid' => 'bg-emerald-500 text-white',
            'outline' => 'border border-emerald-500 text-emerald-600',
        ],
        'warning' => [
            'soft' => 'bg-amber-500/10 text-amber-600',
            'solid' => 'bg-amber-500 text-white',
            'outline' => 'border border-amber-500 text-amber-600',
        ],
        'neutral' => [
            'soft' => 'bg-gray-100 text-gray-600',
            'solid' => 'bg-gray-800 text-white',
            'outline' => 'border border-gray-300 text-gray-600',
        ],
    ];

    $classes = $baseClasses . ' ' . $colors[$color][$variant];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
