@extends('layouts.admin')

@section('admin_title', 'Plans Create')

@section('content')
<div class="space-y-6 pb-12" x-data="{ 
    name: 'New Plan Tier', 
    price: '99.00', 
    package_limit: '15', 
    description: 'Specialized subscription tier for travel agents.',
    status: 'Active'
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
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="name" placeholder="E.g. Premium Explorer Pack" class="w-full bg-[#F8F9FA] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Package Limit -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Number of Packages<span class="text-primary">*</span></label>
                        <div class="relative">
                            <i data-lucide="package" size="18" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input required type="number" name="package_limit" x-model="package_limit" placeholder="12" class="w-full bg-[#F8F9FA] border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price (INR)<span class="text-primary">*</span></label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">₹</span>
                            <input required type="number" step="0.01" name="price" x-model="price" placeholder="149.00" class="w-full bg-[#F8F9FA] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                        </div>
                    </div>
                </div>

                <!-- Internal Description -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Internal Description</label>
                    <textarea name="description" x-model="description" rows="4" placeholder="Standard tier for seasonal travelers..." class="w-full bg-[#F8F9FA] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground resize-none"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Duration Threshold</label>
                    <select name="duration" class="w-full bg-[#F8F9FA] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground">
                        <option value="1 Month">1 Month</option>
                        <option value="3 Months">3 Months</option>
                        <option value="6 Months">6 Months</option>
                        <option value="1 Year">1 Year</option>
                        <option value="Custom">Custom / Unlimited</option>
                    </select>
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
            <div class="bg-white rounded-[32px] border border-border-soft overflow-hidden shadow-sm flex flex-col">
                <div class="relative bg-cover bg-center flex items-end p-6" style="height: 110px; background-image: linear-gradient(to top, rgba(0,0,0,0.3), transparent), url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=800'); border-top-left-radius: 32px; border-top-right-radius: 32px;">
                    <span class="absolute top-4 left-4 bg-[#e85d26] text-white text-[11px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider" style="background-color: #e85d26; color: #ffffff; font-weight: 900; border-radius: 9999px;">Live Preview</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <h4 class="text-lg font-black text-gray-800 uppercase tracking-tight" x-text="name"></h4>
                        <p class="text-xs text-muted-text font-medium leading-relaxed" x-text="description"></p>
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
