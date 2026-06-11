@extends('layouts.admin')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="space-y-1.5">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Settings Hub</h2>
        <p class="text-sm text-gray-500 font-medium max-w-2xl leading-relaxed">
            Configure your platform's global parameters, aesthetic identity, and communication gateways from a single control plane.
        </p>
    </div>

    <!-- 8 Settings Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: General -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FFF4CE] flex items-center justify-center text-[#E85D26] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">General</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Core platform identification and global metadata.
            </p>
        </a>

        <!-- Card 2: Preference -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FFE4E6] flex items-center justify-center text-[#E11D48] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="sliders" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Preference</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Localization, timezone, and default unit behavior.
            </p>
        </a>

        <!-- Card 3: Mail Setup -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="mail" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Mail Setup</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                SMTP configurations and delivery protocol routing.
            </p>
        </a>

        <!-- Card 4: Homepage Banner -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#F3E8FF] flex items-center justify-center text-[#9333EA] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="layout-template" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Homepage Banner</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Hero visuals and seasonal promotional messaging.
            </p>
        </a>

        <!-- Card 5: Payment -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#DCFCE7] flex items-center justify-center text-[#16A34A] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Payment</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Gateway integration and currency management.
            </p>
        </a>

        <!-- Card 6: Roles -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#D97706] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Roles</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Access controllers and administrative hierarchy.
            </p>
        </a>

        <!-- Card 7: Whatsapp Template -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#ECE9FE] flex items-center justify-center text-[#4F46E5] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Whatsapp Template</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Direct mobile messaging and alert structures.
            </p>
        </a>

        <!-- Card 8: Email Template -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FCE7F3] flex items-center justify-center text-[#DB2777] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Email Template</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Rich HTML layouts for user lifecycle notifications.
            </p>
        </a>
    </div>

    <!-- Bottom Status and Activity Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4">
        <!-- API Health Status -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900">API Health Status</h3>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-200 bg-green-50 text-green-700">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-600 animate-pulse"></span>
                    System Operational
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-50">
                <div>
                    <span class="text-xs font-bold text-gray-400 block mb-1">Latency</span>
                    <span class="text-2xl font-black text-gray-900">124ms</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 block mb-1">Uptime</span>
                    <span class="text-2xl font-black text-gray-900">99.98%</span>
                </div>
            </div>
        </div>

        <!-- Admin Activity -->
        <div class="rounded-[32px] p-8 shadow-md flex flex-col justify-between text-white" style="background-color: #B23B06 !important;">
            <div class="space-y-3">
                <h3 class="text-lg font-bold">Admin Activity</h3>
                <p class="text-sm font-medium text-white/80 leading-relaxed">
                    Last modification: Homepage Banner updated by Super Admin 2 hours ago.
                </p>
            </div>
            
            <div class="mt-6 pt-6 flex justify-start">
                <a href="#" class="bg-white text-[#B23B06] px-5 py-2.5 rounded-full text-xs font-bold shadow-sm hover:shadow-md hover:bg-gray-50 transition-all">
                    View Audit Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
