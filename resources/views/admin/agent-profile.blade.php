@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-16 font-sans">
    
    <!-- Top Header Profile Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#FFF2EB] text-[#D35400] text-[10px] font-black uppercase tracking-wider rounded-full border border-[#FDEBD0]">
                Verified Agency
            </span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight uppercase">{{ $agent->name }}</h1>
            <p class="text-sm text-slate-500 font-medium max-w-2xl leading-relaxed">
                Providing premium travel experiences across {{ $agent->state ?? 'Gujarat' }} and beyond since 2010. Your gateway to seamless journeys.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ url('/admin/agents/edit/' . $agent->id) }}" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-slate-700 font-black text-xs uppercase tracking-widest rounded-2xl transition-all">
                Edit Profile
            </a>
            <button onclick="window.location.reload()" style="background-color: #D35400 !important;" class="px-6 py-3.5 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-orange-500/10 hover:opacity-90 transition-all">
                Verify changes
            </button>
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        
        <!-- Left Card: Agency Information -->
        <div class="lg:col-span-3 bg-white border border-slate-100 rounded-[32px] p-8 md:p-10 shadow-premium flex flex-col justify-between relative overflow-hidden">
            <div class="space-y-8">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#D35400] flex items-center justify-center">
                            <i data-lucide="info" size="18"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Agency Information</h3>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">GENERAL</span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-slate-100/50">
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Company Name</span>
                        <p class="text-sm font-extrabold text-slate-800">{{ $agent->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                        <p class="text-sm font-extrabold text-slate-800 break-all">{{ $agent->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Mobile Number</span>
                        <p class="text-sm font-extrabold text-slate-800">{{ $agent->phone }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Phone</span>
                        <p class="text-sm font-extrabold text-slate-800">{{ $agent->landline ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- About section -->
                <div class="space-y-2 pt-6 border-t border-slate-100/50">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">About Agency</span>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        {{ $agent->about }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Card: Headquarters -->
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-[32px] p-8 shadow-premium space-y-6 flex flex-col justify-between">
            <div class="space-y-5">
                <!-- Header -->
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#D35400] flex items-center justify-center">
                        <i data-lucide="map-pin" size="18"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Headquarters</h3>
                </div>

                <!-- Details Column -->
                <div class="space-y-3">
                    <!-- Address Subcard -->
                    <div class="bg-slate-50 rounded-2xl p-4 flex items-start gap-3 border border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#D35400] shrink-0 mt-0.5">
                            <i data-lucide="home" size="16"></i>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Address</span>
                            <p class="text-xs font-bold text-slate-700 leading-snug">{{ $agent->address }}</p>
                        </div>
                    </div>

                    <!-- City & Pincode Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">City</span>
                            <span class="text-xs font-extrabold text-slate-700">{{ $agent->city }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Pincode</span>
                            <span class="text-xs font-extrabold text-slate-700">{{ $agent->pincode }}</span>
                        </div>
                    </div>

                    <!-- State Box with Country Badge -->
                    <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">State</span>
                            <span class="text-xs font-extrabold text-slate-700">{{ $agent->state }}</span>
                        </div>
                        <span class="text-[9px] font-black text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded uppercase tracking-wide">INDIA</span>
                    </div>
                </div>
            </div>

            <!-- Map Vector Section (Clean styled route grid with locator dot) -->
            <div class="h-[120px] rounded-2xl relative overflow-hidden bg-[#E2E8F0] border border-slate-100 shadow-inner mt-2 shrink-0">
                <!-- Grid SVG -->
                <svg class="absolute inset-0 w-full h-full text-slate-300 opacity-60" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern)" />
                    <!-- Random Route curves -->
                    <path d="M-10,40 Q60,10 120,60 T250,30" fill="none" stroke="#CBD5E1" stroke-width="3" />
                    <path d="M30,120 Q110,60 170,100 T290,50" fill="none" stroke="#94A3B8" stroke-width="2" />
                </svg>
                <!-- Radar Pulse locator dot -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center">
                    <span class="absolute w-6 h-6 rounded-full bg-[#D35400] opacity-35 animate-ping"></span>
                    <span class="absolute w-3 h-3 rounded-full bg-[#D35400] border-2 border-white shadow-md"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Package Stat Card -->
        <div style="background-color: #D35400;" class="p-8 rounded-[32px] text-white flex flex-col justify-between relative overflow-hidden min-h-[160px] shadow-lg shadow-orange-500/10">
            <span class="absolute right-6 top-6 text-[9px] font-black uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">
                This Month
            </span>
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <i data-lucide="package" size="20"></i>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold tracking-tight">24</h3>
                <p class="text-xs text-white/80 font-bold uppercase tracking-wider mt-0.5">Active Tour Packages</p>
            </div>
        </div>

        <!-- Clients Stat Card -->
        <div class="bg-[#0074A6] p-8 rounded-[32px] text-white flex flex-col justify-between relative overflow-hidden min-h-[160px] shadow-lg shadow-blue-500/10">
            <span class="absolute right-6 top-6 text-[9px] font-black uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">
                TOTAL
            </span>
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <i data-lucide="users" size="20"></i>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold tracking-tight">1,208</h3>
                <p class="text-xs text-white/80 font-bold uppercase tracking-wider mt-0.5">Clients Served</p>
            </div>
        </div>

        <!-- Ratings Stat Card -->
        <div class="bg-white border border-slate-100 p-8 rounded-[32px] shadow-premium flex flex-col justify-between relative overflow-hidden min-h-[160px]">
            <div class="absolute right-6 top-6 flex items-center gap-0.5 text-[#D35400]">
                <i data-lucide="star" class="fill-[#D35400] w-3 h-3"></i>
                <i data-lucide="star" class="fill-[#D35400] w-3 h-3"></i>
                <i data-lucide="star" class="fill-[#D35400] w-3 h-3"></i>
                <i data-lucide="star" class="fill-[#D35400] w-3 h-3"></i>
                <i data-lucide="star" class="fill-[#D35400] w-3 h-3"></i>
            </div>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Agent Rating</span>
            <div class="mt-4">
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">4.8</h3>
                    <span class="text-xs text-slate-400 font-bold">/5.0</span>
                </div>
                <p class="text-[10px] text-[#D35400] font-black uppercase tracking-widest mt-1">Top Rated Partner</p>
            </div>
        </div>
    </div>

    <!-- Plan & Purchases Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">
        <!-- Current Plan Status -->
        <div class="bg-white border border-slate-100 rounded-[32px] p-8 shadow-premium">
            <h3 class="text-xl font-black text-slate-900 mb-6">Plan Status</h3>
            @if($activePlan)
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-blue-600 font-bold uppercase tracking-wider text-xs">Active Plan</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-full uppercase">Active</span>
                    </div>
                    <h4 class="text-3xl font-black text-slate-900 mb-2">{{ $activePlan->name }}</h4>
                    <p class="text-sm font-medium text-slate-600">₹{{ number_format($activePlan->price) }} / mo</p>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm font-bold text-slate-500">Package Limit</span>
                        <span class="text-sm font-black text-slate-900">{{ $activePlan->package_limit >= 9999 ? 'Unlimited' : $activePlan->package_limit }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm font-bold text-slate-500">Duration</span>
                        <span class="text-sm font-black text-slate-900">{{ $activePlan->duration ?? 'Monthly' }}</span>
                    </div>
                    <div class="flex justify-between pb-3">
                        <span class="text-sm font-bold text-slate-500">Service Guaranteed</span>
                        <span class="text-sm font-black text-slate-900">{{ $agent->service_guaranteed ? 'Yes (Verified)' : 'No' }}</span>
                    </div>
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                    <p class="text-slate-500 font-medium">No active premium plan.</p>
                </div>
                <div class="mt-6 space-y-4">
                    <div class="flex justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm font-bold text-slate-500">Service Guaranteed</span>
                        <span class="text-sm font-black text-slate-900">{{ $agent->service_guaranteed ? 'Yes (Verified)' : 'No' }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Purchase History -->
        <div class="bg-white border border-slate-100 rounded-[32px] p-8 shadow-premium overflow-hidden">
            <h3 class="text-xl font-black text-slate-900 mb-6">Purchase History</h3>
            @if($payments && $payments->count() > 0)
                <div class="space-y-4 overflow-y-auto max-h-[300px] pr-2">
                    @foreach($payments as $payment)
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center justify-between">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900">{{ $payment->plan_type }}</h5>
                                <p class="text-xs font-medium text-slate-500 mt-1">{{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }} • {{ $payment->payment_id }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-slate-900">₹{{ number_format($payment->amount) }}</span>
                                <span class="block mt-1 text-[10px] font-black uppercase tracking-wider {{ $payment->status === 'Success' || $payment->status === 'Completed' ? 'text-green-500' : 'text-red-500' }}">{{ $payment->status }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                    <p class="text-slate-500 font-medium">No purchase history available.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Fallback inline color styles for exact matching layout rendering -->
<style>
.bg-\[\#FFF2EB\] {
    background-color: #FFF2EB !important;
}
.text-\[\#D35400\] {
    color: #D35400 !important;
}
.bg-\[\#FFF9F6\] {
    background-color: #FFF9F6 !important;
}
.border-\[\#FDEBD0\] {
    border-color: #FDEBD0 !important;
}
.bg-\[\#0074A6\] {
    background-color: #0074A6 !important;
}
</style>
@endsection
