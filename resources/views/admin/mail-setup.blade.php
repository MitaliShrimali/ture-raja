@extends('layouts.admin')

@section('admin_title', 'Mail Configuration')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showPassword: false
}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Mail Configuration</h2>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed max-w-xl">
                Configure your SMTP server settings to enable automated email notifications for bookings, user verifications, and system alerts.
            </p>
        </div>
        <button type="button" class="border border-[#b13c0b] text-[#b13c0b] hover:bg-[#b13c0b]/5 px-5 py-3 rounded-2xl font-black text-xs transition-all flex items-center gap-2 uppercase tracking-wider">
            <i data-lucide="send" class="w-4 h-4"></i> Test Email
        </button>
    </div>

    {{-- Main Form Container --}}
    <form action="{{ url('/admin/settings/mail-setup/update') }}" method="POST" class="space-y-8">
        @csrf
        
        {{-- Two-Column Form Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Side: Main Fields --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Server Credentials --}}
                <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-6">
                    <div class="flex items-center gap-3 border-b border-border-soft pb-4">
                        <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                            <i data-lucide="server" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-black text-gray-900">Server Credentials</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Mail Driver --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Mail Driver</label>
                            <select name="mail_driver" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm">
                                <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="log" {{ ($settings['mail_driver'] ?? '') === 'log' ? 'selected' : '' }}>Log</option>
                                <option value="ses" {{ ($settings['mail_driver'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            </select>
                        </div>

                        {{-- Mail Host --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Mail Host</label>
                            <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" placeholder="smtp.gmail.com" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                        </div>

                        {{-- Mail Port --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Mail Port</label>
                            <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                        </div>

                        {{-- Encryption --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Encryption</label>
                            <select name="mail_encryption" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ ($settings['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        {{-- Username --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Username</label>
                            <input type="email" name="mail_username" value="{{ $settings['mail_username'] ?? 'admin@tourraja.com' }}" placeholder="admin@tourraja.com" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="mail_password" value="{{ $settings['mail_password'] ?? '••••••••••••' }}" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sender Identity --}}
                <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-6">
                    <div class="flex items-center gap-3 border-b border-border-soft pb-4">
                        <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                            <i data-lucide="at-sign" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-black text-gray-900">Sender Identity</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- From Name --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'Tourraja Concierge' }}" placeholder="E.g. Tourraja Concierge" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                        </div>

                        {{-- From Email --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">From Email Address</label>
                            <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? 'noreply@tourraja.com' }}" placeholder="E.g. noreply@tourraja.com" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Side: Setup Security Panel --}}
            <div>
                <div style="background-color: #b13c0b;" class="rounded-[32px] p-8 text-white shadow-xl min-h-[360px] flex flex-col justify-between relative overflow-hidden">
                    <div class="space-y-6">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-black tracking-tight">Setup Security</h3>
                            <p class="text-xs text-white/80 leading-relaxed font-semibold">
                                Always use TLS/SSL encryption for SMTP connections to ensure the safety of your administrative credentials and user data.
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/20 pt-6 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-white/60">STATUS</span>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black bg-[#FFEBE6] text-[#b13c0b] uppercase tracking-wider">
                            INCOMPLETE
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Save Panel --}}
        <div class="flex flex-col sm:flex-row items-center justify-between border-t border-border-soft pt-8 gap-4">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                <i data-lucide="clock" class="w-4 h-4"></i>
                <span>Last updated: 2 days ago by Admin</span>
            </div>
            
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <a href="{{ url('admin/settings') }}" class="w-1/2 sm:w-auto px-6 py-3.5 bg-gray-100 hover:bg-gray-200 rounded-2xl text-xs font-black text-muted-text uppercase tracking-widest text-center transition-all">
                    Discard
                </a>
                <button type="submit" style="background-color: #b13c0b;" class="w-1/2 sm:w-auto hover:opacity-90 text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl text-center transition-all">
                    Save Configuration
                </button>
            </div>
        </div>
    </form>

    {{-- ===== BOTTOM DOCS WIDGETS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        
        {{-- Quick Documentation --}}
        <div class="bg-[#FFF5F2] rounded-[32px] p-8 border border-border-soft flex flex-col justify-between min-h-[180px]">
            <div>
                <h4 class="text-sm font-black text-foreground tracking-tight mb-4">Quick Documentation</h4>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-[#b13c0b] cursor-pointer">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#b13c0b]"></i>
                        <span>Mail SMTP Setup</span>
                    </li>
                    <li class="flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-[#b13c0b] cursor-pointer">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#b13c0b]"></i>
                        <span>Using Amazon SES</span>
                    </li>
                    <li class="flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-[#b13c0b] cursor-pointer">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#b13c0b]"></i>
                        <span>Webhook Logs</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Center Card: Mail Infrastructure Graphic placeholder --}}
        <div class="bg-white rounded-[32px] border border-border-soft shadow-premium overflow-hidden flex flex-col md:col-span-2">
            <div class="bg-slate-900 p-6 flex items-center justify-between text-white border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center text-yellow-500">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 block leading-none">SYSTEM SERVICE</span>
                        <span class="text-xs font-black tracking-tight leading-none">Mail Infrastructure v2.4</span>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-green-500/20 text-green-400 border border-green-500/30">ONLINE</span>
            </div>
            
            <div class="p-6 flex flex-col justify-center flex-1">
                <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                    Your mail configurations are processed through our secure gateway. Changes take effect immediately after saving.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
