@props(['white' => false])

@if($white)
    <img src="{{ asset('images/logo/tourraja_orange_white.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@else
    <img src="{{ asset('images/logo/tourraja_orange_black.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@endif

