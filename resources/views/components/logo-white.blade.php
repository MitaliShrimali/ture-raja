@php
    $agencyLogoWhite = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'agency_logo_white')->value('value');
@endphp

@if(!empty($agencyLogoWhite))
    <img src="{{ asset($agencyLogoWhite) }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@else
    <img src="{{ asset('images/logo/tourraja_orange_white.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@endif
