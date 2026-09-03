@extends('layouts.admin')

@section('admin_title', 'Plans Create')

@section('content')
<div class="space-y-6 pb-12" x-data="{ 
    name: 'New Plan Tier', 
    price: '99.00', 
    package_limit: '15', 
    description: 'Specialized subscription tier for travel agents.',
    features: '15 Package Limit\nPriority Support\nDedicated Support',
    status: 'Active',
    gst: '18'
}">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs text-muted-text font-bold">
                <a href="{{ url('/admin/dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ url('/admin/plans') }}" class="hover:text-primary transition-colors">Plans</a>
                <span>&rsaquo;</span>
                <span class="text-gray-900">Add New Plan</span>
            </div>
            <h2 class="font-black text-foreground tracking-tight text-3xl">Create Subscription Plan</h2>
            <p class="text-muted-text font-medium text-sm">Design a new platform tier to publish for registered travel agents.</p>
        </div>
        <div>
            <a href="{{ url('/admin/plans') }}" class="px-6 py-3 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" size="14"></i> Back to Plans
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <form action="{{ url('/admin/plans/store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf
        <!-- Left Column: Form Details -->
        <div class="lg:col-span-8 bg-white rounded-[32px] border border-border-soft p-8 space-y-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h3 class="text-lg font-black text-foreground">Plan Details</h3>
                    <p class="text-xs text-muted-text font-medium">Configure the core parameters for your subscription offering.</p>
                </div>
                
                <!-- Status Toggle -->
                <div class="flex items-center justify-between bg-gray-50/50 px-8 py-4 rounded-[32px] border border-gray-100 shadow-sm gap-6">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-black text-gray-700 uppercase tracking-wider leading-none">Active</span>
                        <span class="text-[11px] font-black text-gray-700 uppercase tracking-wider leading-none mt-1">Status</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="status" value="Active" class="sr-only peer" :checked="status === 'Active'" @change="status = ($event.target.checked ? 'Active' : 'Inactive')">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
            </div>

            <!-- Fields -->
            <div class="space-y-6">
    <div class="bg-white rounded-[32px] border border-border-soft shadow-sm">
        <div class="p-6 space-y-4">
            <div class="space-y-1 mb-6">
                <h4 class="text-lg font-black text-foreground uppercase tracking-widest">Plan Configuration</h4>
                <p class="text-xs text-muted-text font-medium">Set the limits and features for this plan.</p>
            </div>

            <!-- List Layout -->
            <div class="space-y-2">
                <!-- Package Name -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Name</span>
                    <input required type="text" name="name" x-model="name" placeholder="E.g. Premium" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>

                <!-- Suggested Price -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Suggested Price</span>
                    <div class="flex items-center relative">
                        <span class="absolute right-3 text-xs text-gray-400 font-bold">/ year</span>
                        <input required type="number" step="1" name="price" x-model="price" placeholder="149" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 pl-3 pr-12 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                    </div>
                </div>
                
                <!-- GST -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">GST (%)</span>
                    <input type="number" step="0.01" name="gst" x-model="gst" placeholder="18" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>
                
                <!-- Duration -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Expiry (Duration)</span>
                    <div x-data="{ open: false, selected: '30 Days' }" class="relative w-48 text-left">
                        <input type="hidden" name="duration" :value="selected">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                            <span x-text="selected"></span>
                            <i data-lucide="chevron-down" size="14"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                            @for($i=1; $i<=365; $i++)
                                <div @click="selected = '{{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                    {{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}
                                </div>
                            @endfor
                            <div @click="selected = 'Unlimited'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                        </div>
                    </div>
                </div>

                @php
                    $features = [
                        ['key' => 'feat_business_profile', 'label' => 'Business Profile', 'type' => 'boolean'],
                        ['key' => 'package_limit', 'label' => 'Package Listings', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_domestic_packages', 'label' => 'Domestic Packages', 'type' => 'boolean'],
                        ['key' => 'feat_international_packages', 'label' => 'International Packages', 'type' => 'boolean'],
                        ['key' => 'limit_package_photos', 'label' => 'Package Photos', 'type' => 'numeric_dropdown'],
                        ['key' => 'limit_hotel_options', 'label' => 'Hotel Options', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_add_gallery', 'label' => 'Add Gallery', 'type' => 'boolean'],
                        ['key' => 'feat_theme_options', 'label' => 'Holiday / Theme Options', 'type' => 'boolean'],
                        ['key' => 'feat_hide_package_price', 'label' => 'Hide Package Price', 'type' => 'boolean'],
                        ['key' => 'feat_website_on_profile', 'label' => 'Website on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_email_on_profile', 'label' => 'Email on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_whatsapp_on_profile', 'label' => 'WhatsApp on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_package_boosting', 'label' => 'Package Boosting', 'type' => 'boolean_with_days'],
                        ['key' => 'feat_featured_destination', 'label' => 'Featured Destination', 'type' => 'boolean'],
                        ['key' => 'feat_trusted_seller', 'label' => 'Trusted Seller Badge', 'type' => 'boolean'],
                        ['key' => 'feat_reviews_ratings', 'label' => 'Reviews & Ratings', 'type' => 'boolean'],
                        ['key' => 'feat_profile_analytics', 'label' => 'Profile Analytics', 'type' => 'boolean'],
                        ['key' => 'limit_branches', 'label' => 'Multiple Branches', 'type' => 'numeric_dropdown'],
                    ];
                @endphp

                @foreach($features as $feat)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="visible_features[]" value="{{ $feat['key'] }}" class="w-4 h-4 text-[#ea580c] rounded border-gray-300 focus:ring-[#ea580c]" checked title="Show on pricing page">
                            <span class="text-xs font-bold text-gray-700">{{ $feat['label'] }}</span>
                        </div>
                        
                        @if($feat['type'] === 'boolean')
                            <div class="flex items-center gap-2" x-data="{ active: true }">
                                <input type="hidden" name="permissions[{{ $feat['key'] }}]" :value="active ? '1' : '0'">
                                <button type="button" @click="active = true" :class="active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="check" size="14" stroke-width="3"></i>
                                </button>
                                <button type="button" @click="active = false" :class="!active ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="x" size="14" stroke-width="3"></i>
                                </button>
                            </div>
                        @elseif($feat['type'] === 'boolean_with_days')
                            <div class="flex items-center gap-3" x-data="{ active: true, open: false, selectedDays: '30 Days', daysVal: 30 }">
                                <input type="hidden" name="permissions[{{ $feat['key'] }}]" :value="active ? '1' : '0'">
                                <input type="hidden" name="permissions_limit[{{ $feat['key'] }}]" :value="active ? daysVal : 0">
                                
                                <div class="flex items-center gap-1.5 bg-gray-50 p-1 rounded-full border border-gray-100">
                                    <button type="button" @click="active = true" :class="active ? 'bg-green-100 text-green-600 border border-green-300' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer">
                                        <i data-lucide="check" size="14" stroke-width="3"></i>
                                    </button>
                                    <button type="button" @click="active = false" :class="!active ? 'bg-red-100 text-red-600 border border-red-300' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer">
                                        <i data-lucide="x" size="14" stroke-width="3"></i>
                                    </button>
                                </div>

                                <div x-show="active" x-transition class="relative w-36 text-left">
                                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary shadow-xs">
                                        <span x-text="selectedDays"></span>
                                        <i data-lucide="chevron-down" size="14"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                                        @foreach([7, 15, 30, 60, 90, 180, 365] as $days)
                                            <div @click="selectedDays = '{{ $days }} Days'; daysVal = {{ $days }}; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                                {{ $days }} Days
                                            </div>
                                        @endforeach
                                        <div @click="selectedDays = 'Unlimited'; daysVal = 0; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                                    </div>
                                </div>
                            </div>
                        @elseif($feat['type'] === 'numeric_dropdown')
                            @php
                                $inputName = $feat['key'] === 'package_limit' ? 'package_limit' : 'permissions[' . $feat['key'] . ']';
                            @endphp
                            <div x-data="{ open: false, selected: '0', val: 0 }" class="relative w-48 text-left">
                                <input type="hidden" name="{{ $inputName }}" :value="val">
                                <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                                    <span x-text="selected === '0' ? 'Unlimited' : selected"></span>
                                    <i data-lucide="chevron-down" size="14"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                                    @for($i=1; $i<=100; $i++)
                                        <div @click="selected = '{{ $i }}'; val = {{ $i }}; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                            {{ $i }}
                                        </div>
                                    @endfor
                                    <div @click="selected = '0'; val = 0; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-8 py-4 bg-primary hover:bg-primary-hover text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 transition-all flex items-center gap-2">
                    <i data-lucide="check" size="16"></i> Save Plan Changes
                </button>
                <a href="{{ url('/admin/plans') }}" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 text-muted-text rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Right Column: Live Preview & Guidance -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Live Preview Card -->
            <div class="bg-white rounded-[32px] border border-border-soft shadow-sm flex flex-col">
                <div class="relative bg-cover bg-center flex items-end p-6" style="height: 110px; background-image: linear-gradient(to top, rgba(0,0,0,0.3), transparent), url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=800'); border-top-left-radius: 32px; border-top-right-radius: 32px;">
                    <span class="absolute top-4 left-4 bg-[#e85d26] text-white text-[11px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider" style="background-color: #e85d26; color: #ffffff; font-weight: 900; border-radius: 9999px;">Live Preview</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <h4 class="text-lg font-black text-gray-800 uppercase tracking-tight" x-text="name"></h4>
                        <p class="text-xs text-muted-text font-medium leading-relaxed" x-text="description"></p>
                    </div>

                    <!-- Features Live List -->
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <template x-for="feature in features.split('\n').filter(f => f.trim() !== '')">
                            <div class="flex items-start gap-2">
                                <span class="w-4 h-4 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-[10px] font-bold shrink-0">✓</span>
                                <span class="text-xs text-gray-600 font-semibold" x-text="feature"></span>
                            </div>
                        </template>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 space-y-3">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-muted-text">Subscribers</span>
                            <span class="text-gray-800">0</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-muted-text">Revenue (MTD)</span>
                            <span class="text-primary font-black" style="color: #e85d26 !important;">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-muted-text">Last Modified</span>
                            <span class="text-gray-800">Just now</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategy Tip Card -->
            <div class="bg-orange-50/50 border border-orange-100 rounded-[24px] p-6 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-primary flex items-center justify-center shrink-0">
                    <i data-lucide="info" size="20"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="text-xs font-black text-foreground">Pricing Strategy Tip</h5>
                    <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Plans with price points ending in .99 or .00 typically see 15% higher conversion rates for Explorer packages.</p>
                </div>
            </div>

            <!-- Accent Infrastructure Card -->
            <div class="bg-gray-50 rounded-[24px] border border-gray-100 p-6 text-center text-xs font-black text-muted-text uppercase tracking-widest opacity-60">
                Ascent Infrastructure
            </div>
        </div>
    </form>
</div>
@endsection
