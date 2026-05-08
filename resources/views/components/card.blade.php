@props([
    'image' => null,
    'padding' => 'p-8',
])

<div {{ $attributes->merge(['class' => 'card-premium group overflow-hidden flex flex-col']) }}>
    @if($image)
        <div class="relative aspect-[4/3] overflow-hidden">
            <img 
                src="{{ $image }}" 
                {{ $attributes->get('img-alt') ? 'alt=' . $attributes->get('img-alt') : '' }}
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            >
            @if(isset($imageOverlay))
                {{ $imageOverlay }}
            @endif
        </div>
    @endif

    <div class="{{ $padding }} flex-grow flex flex-col">
        {{ $slot }}
    </div>
</div>
