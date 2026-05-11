@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Subscription Plans</h2>
            <p class="text-muted-text font-medium">Define and configure agent-tier subscription levels and benefits.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Create New Tier
        </button>
    </div>

    <!-- Tier Comparison Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $plans = [
                [
                    'name' => 'Silver Standard',
                    'price' => '$49',
                    'period' => '/mo',
                    'color' => 'bg-gray-50 text-gray-500',
                    'features' => ['Up to 5 Tour Packages', 'Basic Lead Management', 'Email Support'],
                    'active_users' => 450
                ],
                [
                    'name' => 'Gold Plus',
                    'price' => '$99',
                    'period' => '/mo',
                    'color' => 'bg-yellow-50 text-yellow-600',
                    'features' => ['Unlimited Packages', 'Advanced CRM Tools', 'Priority Support', 'Custom Branding'],
                    'active_users' => 268,
                    'popular' => true
                ],
                [
                    'name' => 'Platinum Elite',
                    'price' => '$199',
                    'period' => '/mo',
                    'color' => 'bg-primary/5 text-primary',
                    'features' => ['Everything in Gold', 'Dedicated Manager', 'API Access', 'Global Reach Tools'],
                    'active_users' => 124
                ],
            ];
        @endphp
        @foreach($plans as $plan)
            <div class="bg-white p-10 rounded-[40px] shadow-premium border border-border-soft relative overflow-hidden group hover-lift transition-all">
                @if(isset($plan['popular']))
                    <div class="absolute top-8 -right-12 bg-primary text-white text-[10px] font-black uppercase tracking-widest px-12 py-1 rotate-45 shadow-lg">Popular</div>
                @endif
                <div class="space-y-8">
                    <div class="space-y-4">
                        <span class="px-4 py-1.5 rounded-full {{ $plan['color'] }} text-[10px] font-black uppercase tracking-widest">
                            {{ $plan['name'] }}
                        </span>
                        <div class="flex items-baseline gap-1">
                            <h2 class="font-black text-foreground">{{ $plan['price'] }}</h2>
                            <span class="text-muted-text font-bold text-sm">{{ $plan['period'] }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-border-soft pt-8">
                        @foreach($plan['features'] as $feature)
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                                    <i data-lucide="check" size="12"></i>
                                </div>
                                <span class="text-sm font-medium text-muted-text">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        <div class="flex items-center justify-between text-[10px] font-bold text-muted-text uppercase tracking-widest mb-2">
                            <span>Active Subscriptions</span>
                            <span>{{ $plan['active_users'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: {{ ($plan['active_users'] / 500) * 100 }}%"></div>
                        </div>
                    </div>

                    <button class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all {{ isset($plan['popular']) ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-foreground hover:bg-gray-200' }}">
                        Manage Plan
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
