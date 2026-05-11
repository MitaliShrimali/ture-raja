@extends('layouts.admin')

@section('content')
<div class="h-[60vh] flex flex-col items-center justify-center space-y-6 text-center">
    <div class="w-20 h-20 bg-primary/5 rounded-[24px] flex items-center justify-center text-primary">
        <i data-lucide="hammer" size="40"></i>
    </div>
    <div class="space-y-2">
        <h2 class="font-black text-foreground tracking-tight">{{ $title }}</h2>
        <p class="text-muted-text font-bold uppercase text-[10px] tracking-widest">
            This section is currently being provisioned
        </p>
    </div>
    <div class="p-6 bg-white rounded-3xl shadow-soft border border-border-soft max-w-md">
        <p class="text-sm font-medium text-muted-text leading-relaxed">
            The <span class="text-foreground font-bold">{{ $title }}</span> module is part of the TourRaja v2.4.0 expansion. Our engineers are finalizing the data models for this section.
        </p>
    </div>
</div>
@endsection
