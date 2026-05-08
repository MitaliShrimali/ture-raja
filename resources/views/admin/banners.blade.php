@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Management</span>
            <span class="opacity-40">/</span>
            <span class="text-primary">Home Banners</span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-5xl font-black text-foreground tracking-tight">Manage Banners</h1>
                <p class="text-muted-text font-medium max-w-2xl">
                    Curate the first impression for your travelers. High-impact banners drive 40% more conversions.
                </p>
            </div>
            <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                <i data-lucide="plus" size="20"></i> Add New Banner
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $bannerStats = [
                ['label' => 'Total Banners', 'value' => '12', 'sub' => '+2 this month'],
                ['label' => 'Active Now', 'value' => '08', 'active' => true],
                ['label' => 'Avg. CTR', 'value' => '4.2%', 'sub' => 'High'],
                ['label' => 'Upcoming', 'value' => '03', 'sub' => 'Scheduled', 'highlight' => true],
            ];
        @endphp
        @foreach($bannerStats as $stat)
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2 {{ isset($stat['highlight']) ? 'bg-orange-50/50' : '' }}">
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">{{ $stat['label'] }}</p>
                <div class="flex items-end gap-3">
                    <h4 class="text-4xl font-black text-foreground">{{ $stat['value'] }}</h4>
                    @if(isset($stat['active'])) <div class="w-2.5 h-2.5 bg-green-500 rounded-full mb-2 animate-pulse"></div> @endif
                    @if(isset($stat['sub'])) <span class="text-[10px] font-bold uppercase mb-1 {{ $stat['label'] === 'Avg. CTR' ? 'text-primary' : ($stat['label'] === 'Total Banners' ? 'text-blue-500' : 'text-muted-text') }}">{{ $stat['sub'] }}</span> @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Banner List -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-border-soft">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER IMAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DESCRIPTION & MARKETING</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET LINK</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $banners = [
                            ['id' => '01', 'title' => 'Get Up to 20% OFF* on South India Holiday Packages', 'desc' => 'Summer Explorer Series', 'link' => '/packages/south-india', 'status' => 'Active', 'image' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&q=80&w=400'],
                            ['id' => '02', 'title' => 'Experience the Majesty of the Himalayas', 'desc' => 'Adventure Peak Season', 'link' => '/packages/himalayas', 'status' => 'Active', 'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=400'],
                            ['id' => '03', 'title' => 'Exclusive Monsoon Retreats in Goa', 'desc' => 'Off-Season Deals', 'link' => '/campaign/goa-monsoon', 'status' => 'Inactive', 'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=400'],
                        ];
                    @endphp
                    @foreach($banners as $banner)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-8 px-10">
                                <div class="w-40 h-24 rounded-2xl overflow-hidden border border-border-soft bg-gray-100 group-hover:scale-105 transition-transform duration-500">
                                    <img src="{{ $banner['image'] }}" alt="Banner" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <div class="max-w-xs space-y-1">
                                    <p class="text-sm font-black text-foreground leading-tight">{{ $banner['title'] }}</p>
                                    <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest opacity-60">{{ $banner['desc'] }}</p>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full w-fit">
                                    <i data-lucide="external-link" size="12"></i>
                                    <span class="text-[10px] font-black tracking-tighter uppercase">{{ $banner['link'] }}</span>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <span class="px-3 py-1 rounded-full {{ $banner['status'] === 'Active' ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $banner['status'] }}
                                </span>
                            </td>
                            <td class="py-8 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-2xl transition-all"><i data-lucide="edit-3" size="18"></i></button>
                                    <button class="p-3 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all"><i data-lucide="trash-2" size="18"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live Preview Component Simulation -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 bg-white rounded-[40px] shadow-premium border border-border-soft p-10 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                        <i data-lucide="layout" size="22"></i>
                    </div>
                    <h3 class="text-2xl font-black text-foreground tracking-tight">Live Preview</h3>
                </div>
                <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-xl">
                    <button class="p-2 bg-white rounded-lg shadow-sm text-primary"><i data-lucide="monitor" size="18"></i></button>
                    <button class="p-2 text-muted-text hover:text-foreground transition-all"><i data-lucide="smartphone" size="18"></i></button>
                </div>
            </div>

            <div class="relative rounded-[32px] overflow-hidden aspect-[16/7] shadow-2xl">
                <img src="{{ $banners[0]['image'] }}" alt="Preview" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center px-16">
                    <div class="max-w-md space-y-6">
                        <span class="bg-primary px-4 py-1.5 rounded-full text-[10px] font-black text-white uppercase tracking-widest">Live Preview</span>
                        <h2 class="text-4xl font-black text-white leading-tight">Discover the Soul of South India with Exclusive Tours</h2>
                        <p class="text-white/80 font-medium">Tailor-made itineraries for the discerning explorer.</p>
                        <button class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-primary transition-all">
                            Explore Packages
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-primary p-10 rounded-[40px] shadow-2xl text-white relative overflow-hidden flex flex-col justify-between">
            <div class="space-y-6 relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                    <i data-lucide="layout" size="28" class="text-white"></i>
                </div>
                <h4 class="text-3xl font-black leading-tight">Optimization Tips</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">1</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Use high-contrast imagery for better visibility on all devices.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">2</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Keep primary marketing text under 60 characters for readability.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">3</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Update banners every 14 days to prevent audience fatigue.</p>
                    </li>
                </ul>
            </div>
            <button class="w-full py-5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl font-black text-xs uppercase tracking-widest transition-all relative z-10">
                View Detailed Analytics
            </button>
            <div class="absolute right-0 bottom-0 w-2/3 h-2/3 opacity-20 translate-x-1/4 translate-y-1/4">
                 <i data-lucide="monitor" style="width: 300px; height: 300px;"></i>
            </div>
        </div>
    </div>
</div>
@endsection
