@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Platform Admin</p>
            <h1 class="text-5xl font-black text-foreground tracking-tight">System Settings</h1>
            <p class="text-muted-text font-medium">Configure core platform parameters and global defaults.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="save" size="20"></i> Save All Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $settingCards = [
                ['title' => 'General Settings', 'desc' => 'Platform name, logo, and core identity', 'icon' => 'globe', 'color' => 'bg-blue-50 text-blue-500'],
                ['title' => 'Preferences', 'desc' => 'Language, currency, and regional defaults', 'icon' => 'settings', 'color' => 'bg-purple-50 text-purple-500'],
                ['title' => 'Mail Setup', 'desc' => 'SMTP configuration and server limits', 'icon' => 'mail', 'color' => 'bg-orange-50 text-primary'],
                ['title' => 'Mail Template', 'desc' => 'Visual editor for automated system emails', 'icon' => 'layout', 'color' => 'bg-green-50 text-green-500'],
                ['title' => 'Home Page Banner', 'desc' => 'Hero section and marketing banners', 'icon' => 'layout', 'color' => 'bg-pink-50 text-pink-500'],
                ['title' => 'Payment Gateway', 'desc' => 'Stripe, PayPal, and Bank integrations', 'icon' => 'credit-card', 'color' => 'bg-yellow-50 text-yellow-600'],
                ['title' => 'Roles & Permissions', 'desc' => 'RBAC and team access management', 'icon' => 'users', 'color' => 'bg-indigo-50 text-indigo-500'],
                ['title' => 'Whatsapp Template', 'desc' => 'Automated traveler notifications', 'icon' => 'message-circle', 'color' => 'bg-green-50 text-green-600'],
                ['title' => 'API Health Card', 'desc' => 'Live system uptime and error monitoring', 'icon' => 'activity', 'color' => 'bg-red-50 text-red-500'],
            ];
        @endphp
        @foreach($settingCards as $card)
            <button class="group bg-white p-8 rounded-[40px] shadow-soft border border-border-soft hover:shadow-premium hover:border-primary/20 transition-all text-left flex flex-col justify-between h-64 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl {{ $card['color'] }} flex items-center justify-center transition-transform group-hover:scale-110">
                    <i data-lucide="{{ $card['icon'] }}" size="24"></i>
                </div>
                <div class="space-y-2 relative z-10">
                    <h4 class="text-xl font-black text-foreground">{{ $card['title'] }}</h4>
                    <p class="text-xs text-muted-text font-medium leading-relaxed">{{ $card['desc'] }}</p>
                </div>
                <div class="flex items-center gap-2 text-primary font-black uppercase text-[10px] tracking-widest pt-4 opacity-0 group-hover:opacity-100 transition-all">
                    Configure <i data-lucide="chevron-right" size="14"></i>
                </div>
                
                <!-- Background Pattern (Simplified for CSS) -->
                <div class="absolute -right-8 -bottom-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i data-lucide="{{ $card['icon'] }}" size="160"></i>
                </div>
            </button>
        @endforeach
    </div>
</div>
@endsection
