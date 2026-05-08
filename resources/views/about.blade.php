@extends('layouts.app')

@section('content')
    <!-- Hero -->
    <div class="relative pt-40 pb-32 overflow-hidden bg-foreground">
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="{{ asset('tourex/hero-bg.png') }}" alt="About Hero" class="w-full h-full object-cover">
        </div>
        <div class="container-custom relative z-10 text-center text-white">
            <h1 class="text-5xl md:text-7xl font-black mb-6 animate-in fade-in slide-in-from-bottom duration-700 font-syne">Our Story</h1>
            <p class="text-xl text-white/60 max-w-3xl mx-auto font-medium">
                Redefining travel experiences since 2010. We don't just plan trips; we craft memories that stay with you forever.
            </p>
        </div>
    </div>

    <!-- Content -->
    <section class="section-spacing bg-white">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-8">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm">Who We Are</span>
                    <h2 class="text-4xl md:text-5xl font-black leading-tight text-foreground font-syne">
                        We are a team of <span class="text-primary">passionate travelers</span> and storytellers.
                    </h2>
                    <p class="text-lg text-gray-500 leading-relaxed">
                        Tour Raja started with a simple idea: travel should be authentic, seamless, and unforgettable. Over the last decade, we have grown from a small group of enthusiasts to a global marketplace, connecting thousands of travelers with the best local agents and experiences.
                    </p>
                    <p class="text-lg text-gray-500 leading-relaxed">
                        Our mission is to empower local agents while providing travelers with the most transparent and premium booking experience possible.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-8 pt-6">
                        @php
                            $stats = [
                                ['label' => 'Happy Customers', 'value' => '50k+'],
                                ['label' => 'Destinations', 'value' => '200+'],
                                ['label' => 'Tour Guides', 'value' => '500+'],
                                ['label' => 'Awards Won', 'value' => '15+'],
                            ];
                        @endphp
                        @foreach($stats as $stat)
                            <div class="space-y-1">
                                <p class="text-4xl font-black text-primary font-syne">{{ $stat['value'] }}</p>
                                <p class="text-gray-400 font-bold uppercase tracking-wider text-xs">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="relative">
                    <div class="rounded-[40px] overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=800" alt="Team" class="w-full h-[600px] object-cover">
                    </div>
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="section-spacing bg-background">
        <div class="container-custom">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Our Values</span>
                <h2 class="text-4xl font-black font-syne">What Drives Us Every Day</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $values = [
                        ['icon' => 'globe', 'title' => 'Authenticity', 'desc' => 'We provide real experiences, not just tourist traps.'],
                        ['icon' => 'shield', 'title' => 'Trust & Safety', 'desc' => 'Your safety and security are our top priorities.'],
                        ['icon' => 'users', 'title' => 'Community', 'desc' => 'Supporting local economies and agents everywhere.'],
                        ['icon' => 'trophy', 'title' => 'Excellence', 'desc' => 'Setting the gold standard for travel services.'],
                    ];
                @endphp
                @foreach($values as $value)
                    <div class="bg-white p-10 rounded-[32px] shadow-soft hover:shadow-card transition-all group">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                            <i data-lucide="{{ $value['icon'] }}" size="32"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-4">{{ $value['title'] }}</h4>
                        <p class="text-gray-500 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
