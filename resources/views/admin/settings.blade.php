@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ activeTab: 'profile' }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Platform Admin</p>
            <h2 class="font-black text-foreground tracking-tight">System Settings & Profile</h2>
            <p class="text-muted-text font-medium">Configure core platform parameters and administrator profile defaults.</p>
        </div>
        
        <!-- Tab Switches -->
        <div class="bg-gray-100 p-1.5 rounded-2xl flex max-w-sm w-full">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-white text-foreground shadow-md' : 'text-muted-text'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Profile Settings</button>
            <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-white text-foreground shadow-md' : 'text-muted-text'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Global System</button>
        </div>
    </div>

    <!-- Active Tab Profile Settings -->
    <div x-show="activeTab === 'profile'" class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8" style="display: none;">
        <div class="flex items-center gap-3 border-b border-border-soft pb-4">
            <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="user-round" size="22"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-foreground">Admin Profile Settings</h3>
                <p class="text-xs text-muted-text font-medium">Update your Super Admin identity and system credentials.</p>
            </div>
        </div>

        @php
            $activeAdmin = Auth::check() ? Auth::user() : (\DB::table('users')->where('id', 1)->first() ?? (object)[
                'name' => 'Super Admin',
                'email' => 'admin@tourraja.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin'
            ]);
            $adminAvatar = ($activeAdmin && !empty($activeAdmin->avatar)) ? $activeAdmin->avatar : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($activeAdmin->name ?? 'Admin');
        @endphp

        <form action="{{ url('/admin/profile/update') }}" method="POST" class="space-y-6 max-w-2xl">
            @csrf
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-primary to-orange-400 p-[3px] shadow-lg shadow-primary/20 shrink-0">
                    <div class="w-full h-full rounded-[21px] bg-white p-1 overflow-hidden">
                        <img src="{{ $adminAvatar }}" alt="Avatar" class="w-full h-full object-cover rounded-[18px]">
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-black text-foreground">Avatar Representation</h4>
                    <p class="text-xs text-muted-text font-medium leading-relaxed mt-0.5">Your profile avatar is dynamically generated based on your name seed.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Display Name</label>
                    <input required type="text" name="name" value="{{ $activeAdmin->name }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Email Address</label>
                    <input required type="email" name="email" value="{{ $activeAdmin->email }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-border-soft">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                    <i data-lucide="save" size="18"></i> Update Profile Credentials
                </button>
            </div>
        </form>
    </div>

    <!-- Active Tab Global System Config -->
    <div x-show="activeTab === 'general'" class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8" style="display: none;">
        <div class="flex items-center gap-3 border-b border-border-soft pb-4">
            <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="settings" size="22"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-foreground">Global Platform Settings</h3>
                <p class="text-xs text-muted-text font-medium">Update key-value pairs stored in the platform settings database.</p>
            </div>
        </div>

        <form action="{{ url('/admin/settings/update') }}" method="POST" class="space-y-6 max-w-4xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Platform Name</label>
                    <input required type="text" name="site_name" value="{{ $settings['site_name'] ?? 'TourRaja' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Site Logo URL</label>
                    <input required type="text" name="site_logo" value="{{ $settings['site_logo'] ?? 'https://tourraja.com/logo.png' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Contact Support Email</label>
                    <input required type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@tourraja.com' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Support Helpline Phone</label>
                    <input required type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+91 99999 88888' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Global Commission Rate (%)</label>
                    <input required type="number" name="commission_rate" value="{{ $settings['commission_rate'] ?? '12' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Platform Currency Symbol</label>
                    <input required type="text" name="currency" value="{{ $settings['currency'] ?? '₹' }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-border-soft">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                    <i data-lucide="save" size="18"></i> Save Global System Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
