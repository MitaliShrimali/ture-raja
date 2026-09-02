@props(['white' => false, 'localWhite' => false])

@php
    $agencyLogo = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'agency_logo')->value('value');
    $agencyLogoWhite = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'agency_logo_white')->value('value');
@endphp

@if($localWhite == 'true')
    <img src="{{ asset('images/tourraja_white.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@elseif($white)
    @if(!empty($agencyLogoWhite))
        <img src="{{ asset($agencyLogoWhite) }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
    @else
        <img src="{{ asset('images/logo/tourraja_orange_white.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
    @endif
@else
    @if(!empty($agencyLogo))
        <img src="{{ asset($agencyLogo) }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
    @else
        <img src="{{ asset('images/logo/tourraja_orange_black.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
    @endif
@endif
