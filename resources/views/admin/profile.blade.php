@extends('layouts.admin')

@section('admin_title', 'Profile')

@section('content')
<div class="space-y-10 pb-12" x-data="{ activeTab: 'general' }">
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Admin Control Panel</span>
            <span class="opacity-40">/</span>
            <span class="text-primary">Admin Profile</span>
        </div>
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Profile & Credentials</h2>
            <p class="text-muted-text font-medium max-w-2xl">
                Manage your system credentials, view server specifications, and overview overall system performance indices.
            </p>
        </div>
    </div>

    <!-- Admin Overview Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Main Card -->
        <div class="lg:col-span-1 bg-white rounded-[40px] shadow-premium border border-border-soft p-10 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
            
            @php
                $adminAvatar = ($admin && !empty($admin->avatar)) ? $admin->avatar : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($admin->name ?? 'Admin');
            @endphp
            <div class="space-y-6 w-full flex flex-col items-center">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-primary/20 bg-gray-50 flex items-center justify-center shadow-lg">
                        <img src="{{ asset($adminAvatar) }}" alt="Admin Avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-black/60 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all cursor-pointer">
                        <i data-lucide="camera" class="text-white" size="24"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-foreground">{{ $admin->name ?? 'Administrator' }}</h3>
                    <span class="inline-block px-4 py-1.5 bg-primary/10 border border-primary/20 rounded-full text-[10px] font-black text-primary uppercase tracking-widest">
                        {{ $admin->role ?? 'SUPER ADMIN' }}
                    </span>
                </div>

                <div class="w-full border-t border-border-soft/60 pt-6 space-y-4 text-left text-sm font-medium">
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Email Address</span>
                        <span class="font-bold text-foreground">{{ $admin->email ?? 'admin@tourraja.com' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Joined Date</span>
                        <span class="font-bold text-foreground">18 May 2026</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Access Level</span>
                        <span class="font-black text-red-500 uppercase tracking-tighter">Root Level</span>
                    </div>
                </div>
            </div>

            <div class="w-full mt-10">
                <a href="{{ url('/admin/dashboard') }}" class="w-full py-4 bg-gray-50 hover:bg-gray-100 rounded-2xl text-[10px] font-black text-muted-text uppercase tracking-widest transition-all inline-block">
                    Return to Dashboard
                </a>
            </div>
        </div>

        <!-- Credentials and System Stats Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tabs Menu -->
            <div class="flex items-center gap-4 bg-gray-100/60 p-2 rounded-2xl w-fit">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-white shadow-sm text-primary' : 'text-muted-text hover:text-foreground'" class="px-6 py-3 rounded-xl font-bold text-xs transition-all">
                    General Credentials
                </button>
                <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'bg-white shadow-sm text-primary' : 'text-muted-text hover:text-foreground'" class="px-6 py-3 rounded-xl font-bold text-xs transition-all">
                    System Health & Server Specs
                </button>
            </div>

            <!-- General credentials Tab -->
            <div x-show="activeTab === 'general'" class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 md:p-12 space-y-8 animate-in fade-in duration-300">
                <h3 class="text-2xl font-black text-foreground">Account Credentials</h3>

                <form action="{{ url('/admin/profile/update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Full Name</label>
                            <input required type="text" name="name" value="{{ $admin->name ?? '' }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address</label>
                            <input required type="email" name="email" value="{{ $admin->email ?? '' }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                    </div>

                    <div class="border-t border-border-soft/60 pt-6 flex items-center justify-between">
                        <p class="text-xs text-muted-text font-bold">Changes will reflect instantly upon saving.</p>
                        <button type="submit" class="px-8 py-4 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- System specs Tab -->
            <div x-show="activeTab === 'system'" class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 md:p-12 space-y-8 animate-in fade-in duration-300" style="display: none;">
                <h3 class="text-2xl font-black text-foreground">Operational Specs & Health</h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">System Status</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-base font-black text-foreground">Optimal</span>
                        </div>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">Laravel Version</span>
                        <span class="text-base font-black text-foreground">v12.58.0</span>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">PHP Version</span>
                        <span class="text-base font-black text-foreground">8.4.3</span>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">Active Packages</span>
                        <span class="text-lg font-black text-primary">{{ $totalPackages ?? 10 }} Available</span>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">Total Registered Users</span>
                        <span class="text-lg font-black text-foreground">{{ $totalUsers ?? 15 }} Users</span>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-border-soft/60 space-y-2">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">Total Revenue Managed</span>
                        <span class="text-lg font-black text-green-600">₹{{ number_format($totalRevenue ?? 125000) }}</span>
                    </div>
                </div>

                <div class="bg-blue-50/30 border border-blue-100 p-6 rounded-3xl flex items-start gap-4">
                    <i data-lucide="info" class="text-blue-500 shrink-0 mt-1" size="20"></i>
                    <div class="space-y-1">
                        <h4 class="text-sm font-black text-blue-600 uppercase tracking-wide">Environment Context</h4>
                        <p class="text-xs text-blue-700/80 font-medium leading-relaxed">
                            This application is currently running in local development mode (`APP_ENV=local`). Ensure configurations are optimized before deploying to live production servers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
