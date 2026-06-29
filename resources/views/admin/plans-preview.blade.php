@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Breadcrumb and Back Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs text-muted-text font-bold">
        <div class="flex items-center gap-2">
            <a href="{{ url('/admin/dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span>&rsaquo;</span>
            <a href="{{ url('/admin/plans') }}" class="hover:text-primary transition-colors">Plans</a>
            <span>&rsaquo;</span>
            <span class="text-gray-900">Plan Preview</span>
        </div>
        <a href="{{ url('/admin/plans') }}" class="inline-flex px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider transition-all items-center gap-1.5 w-fit">
            <i data-lucide="arrow-left" size="12"></i> Back to Plans
        </a>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
        <div class="space-y-3 max-w-3xl">
            <!-- Active Status Badge -->
            <div>
                @if($plan->status === 'Active')
                    <span class="inline-flex items-center gap-1.5 bg-[#e6fcf5] text-[#0ca678] text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 bg-[#0ca678] rounded-full"></span>
                        Active Plan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                        Inactive Plan
                    </span>
                @endif
            </div>

            <!-- Title & Description -->
            <h1 class="font-black text-foreground tracking-tight text-4xl leading-tight">{{ $plan->name }}</h1>
            <p class="text-muted-text font-medium text-sm leading-relaxed">{{ $plan->description ?? 'A specialized introductory subscription tier designed for elite agents entering the Horizon Ascent ecosystem. Includes premium placement and expanded travel package visibility.' }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ url('/admin/plans/duplicate/' . $plan->id) }}" class="px-5 py-3.5 bg-gray-100 hover:bg-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="copy" size="14"></i> Duplicate
            </a>
            <a href="{{ url('/admin/plans/edit/' . $plan->id) }}" class="px-5 py-3.5 bg-primary hover:bg-primary-hover text-white rounded-2xl font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-md">
                <i data-lucide="edit-3" size="14"></i> Edit Plan
            </a>
            <a href="{{ url('/admin/paid-users/create') }}" class="px-5 py-3.5 bg-foreground hover:bg-black text-white rounded-2xl font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-md">
                <i data-lucide="plus" size="14"></i> Add New user/agent
            </a>
        </div>
    </div>

<style>
.preview-flex-container {
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
    width: 100% !important;
}
.preview-card-1 {
    width: 100% !important;
    border-radius: 32px !important;
    color: #ffffff !important;
}
.preview-card-2, .preview-card-3 {
    width: 100% !important;
    border-radius: 32px !important;
}
@media (min-width: 1024px) {
    .preview-flex-container {
        flex-direction: row !important;
    }
    .preview-card-1 {
        width: 50% !important;
        flex-shrink: 0 !important;
    }
    .preview-card-2 {
        width: 25% !important;
        flex-shrink: 0 !important;
    }
    .preview-card-3 {
        width: 25% !important;
        flex-shrink: 0 !important;
    }
}
</style>

    <!-- Stats Cards Row -->
    <div class="preview-flex-container">
        <!-- Annual Price Card (Gradient Orange) -->
        <div class="preview-card-1 p-8 shadow-xl flex flex-col justify-between min-h-[160px] relative overflow-hidden" style="background-image: linear-gradient(to right, #af3a03, #e8460a); flex-shrink: 0; border-radius: 32px; color: #ffffff !important;">
            <div class="absolute right-0 bottom-0 opacity-10 translate-x-4 translate-y-4" style="color: #ffffff !important;">
                <i data-lucide="credit-card" class="w-32 h-32" style="stroke: #ffffff;"></i>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest" style="color: #ffffff !important; opacity: 0.9; font-weight: 900; margin: 0;">Annual Price</p>
            <div class="space-y-1 mt-4">
                <h3 class="text-5xl font-black font-syne leading-none" style="color: #ffffff !important; font-weight: 900; margin: 0;">₹{{ number_format($plan->price, 2) }}</h3>
                <p class="text-xs font-semibold" style="color: #ffffff !important; opacity: 0.9; margin: 0;">Billed annually in full</p>
            </div>
        </div>

        <!-- Package Limit Card -->
        <div class="preview-card-2 bg-white border border-border-soft p-8 shadow-sm flex flex-col justify-between" style="flex-shrink: 0; border-radius: 32px;">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-primary flex items-center justify-center">
                <i data-lucide="package" size="20"></i>
            </div>
            <div class="space-y-1 mt-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-muted-text">Package Limit</p>
                <h3 class="text-3xl font-black text-foreground">{{ $plan->package_limit ?? 15 }}</h3>
                <p class="text-[11px] font-semibold text-muted-text leading-none">Simultaneous listings</p>
            </div>
        </div>

        <!-- Duration Card -->
        <div class="preview-card-3 bg-white border border-border-soft p-8 shadow-sm flex flex-col justify-between" style="flex-shrink: 0; border-radius: 32px;">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i data-lucide="calendar" size="20"></i>
            </div>
            <div class="space-y-1 mt-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-muted-text">Duration</p>
                <h3 class="text-3xl font-black text-foreground">{{ $plan->duration ?? '12 Months' }}</h3>
                <p class="text-[11px] font-semibold text-muted-text leading-none">Standard contract cycle</p>
            </div>
        </div>
    </div>

    <!-- Subscribed Agents Table Section -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-gray-800">Subscribed Agents</h3>
                    <p class="text-xs text-muted-text font-semibold">Directly managing {{ count($subscribedAgents) }} active accounts on this plan</p>
                </div>
                <!-- Avatar Stack Indicator -->
                <div class="flex items-center -space-x-2.5">
                    @foreach($subscribedAgents->take(3) as $agent)
                        <img class="w-8 h-8 rounded-full border-2 border-white object-cover shadow-sm" src="{{ asset($agent->logo ?: 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agent->name)) }}" alt="{{ $agent->name }}">
                    @endforeach
                    @if(count($subscribedAgents) > 3)
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-black text-muted-text shadow-sm">+{{ count($subscribedAgents) - 3 }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Agent Name</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Email Address</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Activation Date</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Performance</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($subscribedAgents as $agent)
                        @php
                            // Mocking performance metrics or dates for visual quality matching the mockup
                            $randomPerformance = [85, 94, 76, 92, 42, 60][rand(0, 5)];
                            $randomPerfColor = $randomPerformance >= 80 ? 'bg-green-500' : ($randomPerformance >= 60 ? 'bg-orange-500' : 'bg-red-500');
                            $randomPerfTextColor = $randomPerformance >= 80 ? 'text-green-600' : ($randomPerformance >= 60 ? 'text-orange-600' : 'text-red-600');
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full object-cover border border-gray-100 shrink-0 bg-gray-50" src="{{ asset($agent->logo ?: 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agent->name)) }}" alt="{{ $agent->name }}" />
                                    <div class="space-y-0.5">
                                        <p class="text-sm font-black text-gray-800">{{ $agent->name }}</p>
                                        <p class="text-[10px] text-muted-text font-bold">Top Producer &bull; {{ $agent->region }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $agent->email }}</td>
                            <td class="py-6 px-8 text-sm font-semibold text-gray-500">
                                {{ $agent->created_at ? \Carbon\Carbon::parse($agent->created_at)->format('M d, Y') : 'Jan 12, 2024' }}
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-gray-100 h-1.5 rounded-full overflow-hidden shrink-0">
                                        <div class="h-full {{ $randomPerfColor }} rounded-full" style="width: {{ $randomPerformance }}%;"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $randomPerfTextColor }}">{{ $randomPerformance }}%</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div class="flex justify-center">
                                    <a href="{{ url('/admin/agents/edit/' . $agent->id) }}" class="p-2 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <i data-lucide="edit-3" size="16"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No travel agents currently subscribed to this plan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer Links -->
        <div class="p-6 bg-gray-50/50 border-t border-border-soft text-center">
            <a href="{{ url('/admin/registered-agents') }}" class="text-xs font-black text-primary hover:text-primary-hover uppercase tracking-widest transition-colors">
                View All Subscribed Agents ({{ count($subscribedAgents) }})
            </a>
        </div>
    </div>

    <!-- Bottom Banners Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
        <!-- Scale Plan Card -->
        <div class="bg-gray-100 rounded-[32px] p-8 flex flex-col justify-between space-y-6 border border-border-soft">
            <div class="space-y-3">
                <h3 class="text-2xl font-black text-gray-800 leading-tight">Looking to scale this plan?</h3>
                <p class="text-gray-500 text-xs font-semibold leading-relaxed">Our algorithm suggests that agents on '{{ $plan->name }}' transition to 'Explorer Pro' within 4 months of activation for optimal ROI.</p>
            </div>
            <div>
                <a href="#" class="inline-block px-6 py-3.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-2xl font-black text-xs transition-all">
                    View Analytics Report
                </a>
            </div>
        </div>

        <!-- Featured Destination Card -->
        <div class="rounded-[32px] overflow-hidden min-h-[260px] relative bg-cover bg-center flex flex-col justify-end p-8 text-white shadow-lg border border-border-soft" style="background-image: linear-gradient(to top, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.15)), url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800');">
            <div class="space-y-1 z-10">
                <p class="text-[9px] font-black uppercase tracking-widest text-white/80">Featured Destination</p>
                <h3 class="text-2xl font-black leading-tight text-white">Swiss Alps Expedition</h3>
            </div>
        </div>
    </div>
</div>
@endsection
