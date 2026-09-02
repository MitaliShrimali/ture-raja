@php
    $agencyLogo = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'agency_logo')->value('value');
@endphp

@if(!empty($agencyLogo))
    <img src="{{ asset($agencyLogo) }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@else
    <img src="{{ asset('images/logo/tourraja_orange_white.svg') }}" {{ $attributes->merge(['class' => 'w-auto h-12']) }} alt="Tour Raja">
@endif
