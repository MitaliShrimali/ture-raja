@extends('layouts.admin')

@section('admin_title', 'Gateway Configuration')

@section('content')
<div class="space-y-8 pb-12">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="space-y-1">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Gateway Configuration</h2>
        <p class="text-xs text-gray-400 font-semibold leading-relaxed max-w-xl">
            Establish a secure bridge between your expedition bookings and global financial networks. Ensure your API credentials are kept confidential.
        </p>
    </div>

    {{-- Main Layout --}}
    <form action="{{ url('/admin/settings/payment-setup/update') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Side: Merchant Credentials --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-8 h-full flex flex-col justify-between">
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 border-b border-border-soft pb-4">
                            <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                                <i data-lucide="wallet" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-gray-900">Merchant Credentials</h3>
                                <p class="text-[10px] text-gray-400 font-bold mt-0.5">Update your production keys to activate live payments.</p>
                            </div>
                        </div>

                        {{-- PayU Merchant Key --}}
                        <div class="space-y-2" x-data="{ show: false }">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">PayU Merchant Key</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="payu_merchant_key" value="{{ $settings['payu_merchant_key'] ?? '93MUBS' }}" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" @click="show = !show">
                                    <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                                    <i data-lucide="eye-off" x-show="show" class="w-5 h-5" style="display: none;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- PayU Merchant Salt --}}
                        <div class="space-y-2" x-data="{ show: false }">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">PayU Merchant Salt</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="payu_merchant_salt" value="{{ $settings['payu_merchant_salt'] ?? 'rYZSnOSPjauR4P0rVOMT3J8tKqM1uZJY' }}" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" @click="show = !show">
                                    <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                                    <i data-lucide="eye-off" x-show="show" class="w-5 h-5" style="display: none;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- PayU Test Mode --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">PayU Test Mode</label>
                            <div class="relative">
                                <select name="payu_test_mode" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm appearance-none">
                                    <option value="false" {{ ($settings['payu_test_mode'] ?? 'false') == 'false' ? 'selected' : '' }}>False (Production)</option>
                                    <option value="true" {{ ($settings['payu_test_mode'] ?? 'false') == 'true' ? 'selected' : '' }}>True (Sandbox)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>

                        {{-- PayU Base URL --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">PayU Base URL</label>
                            <div class="relative">
                                <input type="text" name="payu_base_url" value="{{ $settings['payu_base_url'] ?? 'https://secure.payu.in/_payment' }}" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i data-lucide="link" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions inside Card --}}
                    <div class="flex items-center gap-6 pt-8 border-t border-border-soft mt-8">
                        <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl flex items-center gap-2 transition-all">
                            Save Configuration <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                        <button type="button" class="text-[#b13c0b] hover:opacity-80 transition-opacity text-xs font-black uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="plug" class="w-4 h-4"></i> Test Connection
                        </button>
                    </div>

                </div>
            </div>

            {{-- Right Side: Quick Documentation, Safety, and Global Scale --}}
            <div class="space-y-6">
                
                {{-- Quick Documentation --}}
                <div class="bg-[#FFF5F2] rounded-[28px] p-6 border border-border-soft space-y-4">
                    <div class="flex items-center gap-2 text-[#b13c0b]">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">QUICK DOCUMENTATION</span>
                    </div>
                    <p class="text-xs text-gray-600 font-semibold leading-relaxed">
                        These credentials enable <strong class="text-gray-900">AES-256 encrypted</strong> communication between Expedition and your payment provider.
                    </p>
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-semibold leading-relaxed italic">
                            "Ensure that your Salt matches the one provided in your Merchant Dashboard under 'API Integration' settings."
                        </p>
                    </div>
                </div>

                {{-- Safety Protocol --}}
                <div class="bg-[#2E1C19] rounded-[28px] p-6 text-white space-y-4">
                    <div class="flex items-center gap-2 text-white/80">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-orange-400"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">SAFETY PROTOCOL</span>
                    </div>
                    <p class="text-xs text-white/70 font-semibold leading-relaxed">
                        Keys are hashed before storage. To ensure maximum security, rotate these keys every 90 days. Avoid sharing them in plain text via email or chat.
                    </p>
                </div>

                {{-- Global Scale Banner --}}
                <div class="bg-slate-950 rounded-[28px] p-6 text-white h-[140px] flex flex-col justify-end relative overflow-hidden shadow-lg">
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900/40 via-transparent to-transparent pointer-events-none"></div>
                    <div class="space-y-1 relative z-10">
                        <span class="text-xs font-black tracking-wide uppercase">GLOBAL SCALE</span>
                        <p class="text-[10px] text-white/60 font-semibold leading-relaxed">
                            Processing payments in over 140 currencies across the globe.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </form>

    {{-- ===== RECENT GATEWAY ACTIVITY ===== --}}
    <div class="space-y-4 pt-8">
        <h3 class="text-base font-black text-gray-900 tracking-tight pl-2">Recent Gateway Activity</h3>
        
        <div class="space-y-4">
            {{-- Row 1: API Connection Test --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-border-soft flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-50 text-green-500 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-xs font-black text-gray-900 block leading-none mb-1">API Connection Test</span>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block">AUTOMATED SYSTEM HEALTH CHECK</span>
                    </div>
                </div>
                
                <span class="text-xs font-bold text-gray-500">Production Server #04</span>
                
                <div class="flex items-center gap-4 justify-between w-full sm:w-auto">
                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-[#FFEBE6] text-[#b13c0b] uppercase tracking-wider">
                        SUCCESSFUL
                    </span>
                    <span class="text-[10px] font-bold text-gray-400">2 mins ago</span>
                </div>
            </div>

            {{-- Row 2: Credential Update --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-border-soft flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-xs font-black text-gray-900 block leading-none mb-1">Credential Update</span>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block">ACTION BY ALEX RIVERA</span>
                    </div>
                </div>
                
                <span class="text-xs font-bold text-gray-500">Gateway API Key</span>
                
                <div class="flex items-center gap-4 justify-between w-full sm:w-auto">
                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-[#FFEBE6] text-[#b13c0b] uppercase tracking-wider">
                        MODIFIED
                    </span>
                    <span class="text-[10px] font-bold text-gray-400">1 hour ago</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
