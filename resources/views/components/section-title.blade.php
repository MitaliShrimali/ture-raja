@props([
    'subtitle' => null,
    'align' => 'center',
    'light' => false,
])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center mx-auto',
        'right' => 'text-right ml-auto',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'max-w-3xl space-y-4 ' . $alignmentClasses[$align]]) }}>
    @if($subtitle)
        <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-[10px] md:text-xs font-black uppercase tracking-widest animate-fade-in">
            {{ $subtitle }}
        </span>
    @endif
    
    <h2 class="{{ $light ? 'text-white' : 'text-foreground' }} text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight font-heading">
        {{ $slot }}
    </h2>
    
    @if(isset($description))
        <p class="{{ $light ? 'text-white/70' : 'text-text-muted' }} text-lg md:text-xl font-medium max-w-2xl {{ $align === 'center' ? 'mx-auto' : '' }}">
            {{ $description }}
        </p>
    @endif
</div>
