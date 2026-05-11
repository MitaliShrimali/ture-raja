@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="space-y-2">
        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
        <h2 class="font-black text-foreground tracking-tight">Onboard New Agent</h2>
        <p class="text-muted-text font-medium max-w-2xl">
            Expand the Horizon network by registering a new premium travel agent partner. All fields are required for secure portal access.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Form Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Entity Information Section -->
            <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                        <i data-lucide="building-2" size="22"></i>
                    </div>
                    <h3 class="text-2xl font-black text-foreground">Entity Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Travel Agent Name</label>
                        <input type="text" placeholder="e.g. Atlas Global Travels" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Email Address</label>
                        <div class="relative group">
                            <i data-lucide="mail" class="absolute left-6 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                            <input type="email" placeholder="contact@agency.com" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Mobile Number</label>
                        <div class="relative group">
                            <i data-lucide="phone" class="absolute left-6 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                            <input type="text" placeholder="+1 (555) 000-0000" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Assigned Region</label>
                        <select class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                            <option>North America</option>
                            <option>Europe</option>
                            <option>Asia Pacific</option>
                            <option>Middle East</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Service Configuration Section -->
            <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                        <i data-lucide="zap" size="22"></i>
                    </div>
                    <h3 class="text-2xl font-black text-foreground">Service Configuration</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-foreground">Service Guaranteed</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase">Enable automated SLA monitoring</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between opacity-50">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-foreground">API Access</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase">Allow third-party integrations</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Controls Area -->
        <div class="space-y-8">
            <!-- Tier Selection Card -->
            <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
                <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Tier Selection</h4>
                <div class="space-y-3">
                    @foreach([
                        ['name' => 'Standard', 'desc' => 'Up to 50 bookings/mo'],
                        ['name' => 'Premium', 'desc' => 'Unlimited bookings & VIP support', 'active' => true],
                        ['name' => 'Enterprise', 'desc' => 'Custom white-label solutions']
                    ] as $tier)
                        <button class="w-full p-5 rounded-3xl text-left border-2 transition-all {{ isset($tier['active']) ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 hover:border-gray-200 bg-white' }}">
                            <p class="text-sm font-black text-foreground">{{ $tier['name'] }}</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase mt-1">{{ $tier['desc'] }}</p>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
                <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Account Status</h4>
                <div class="bg-gray-100 p-1.5 rounded-2xl flex">
                    <button class="flex-1 py-3 px-6 rounded-xl text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">Active</button>
                    <button class="flex-1 py-3 px-6 rounded-xl text-sm font-black text-muted-text hover:text-foreground transition-all">Inactive</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <button class="w-full bg-primary hover:bg-primary-hover text-white rounded-3xl py-6 font-black text-lg shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
                    <i data-lucide="rocket" size="22" class="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    Create User Account
                </button>
                <button class="w-full bg-gray-200 hover:bg-gray-300 text-foreground rounded-3xl py-6 font-black text-lg transition-all flex items-center justify-center gap-3">
                    <i data-lucide="save" size="22"></i>
                    Save Draft
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
