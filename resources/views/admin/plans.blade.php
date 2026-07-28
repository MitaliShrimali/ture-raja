@extends('layouts.admin')

@section('admin_title', 'Plans')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showFilter: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight text-3xl">Subscription Records</h2>
            <p class="text-muted-text font-semibold text-sm">View and manage your previous payment transactions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/dashboard') }}" class="px-6 py-3.5 border-2 border-dashed border-gray-200 hover:border-gray-300 text-gray-700 rounded-2xl font-bold text-sm transition-all flex items-center gap-2">
                Go to User dashboard
            </a>
            <a href="{{ url('/admin/plans/create') }}" class="px-6 py-3.5 bg-primary hover:bg-primary-hover text-white rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
                <i data-lucide="plus" size="18"></i>
                Add New Plan
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Spent Card -->
        <div class="bg-white p-6 rounded-[32px] border border-border-soft flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-primary">
                <i data-lucide="wallet" size="24"></i>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Spent</p>
                <h3 class="text-2xl font-black text-gray-800">₹4,250.00</h3>
            </div>
        </div>

        <!-- Current Plan Card -->
        <div class="bg-white p-6 rounded-[32px] border border-border-soft flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i data-lucide="star" size="24"></i>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Current Plan</p>
                <h3 class="text-2xl font-black text-gray-800">Welcome Offer 1</h3>
                @php
                    $welcomePlanAgents = DB::table('agents')->whereIn('plan_id', function($query) {
                        $query->select('id')->from('plans')->where('name', 'like', '%Welcome Offer%');
                    })->count();
                @endphp
                <p class="text-xs font-semibold text-primary mt-1">{{ $welcomePlanAgents }} Agents Subscribed</p>
            </div>
        </div>

        <!-- Next Renewal Card -->
        <div class="bg-white p-6 rounded-[32px] border border-border-soft flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-500">
                <i data-lucide="calendar" size="24"></i>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Next Renewal</p>
                <h3 class="text-2xl font-black text-gray-800">Oct 24, 2024</h3>
                @php
                    $renewingAgents = DB::table('agents')->whereNotNull('plan_expires_at')->where('plan_expires_at', '<', now()->addDays(30))->count();
                @endphp
                <p class="text-xs font-semibold text-gray-500 mt-1">{{ $renewingAgents }} Agents need renewal</p>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <h3 class="text-xl font-black text-gray-800">Subscription Records</h3>
                <p class="text-xs text-muted-text font-semibold">View and manage your previous payment transactions</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showFilter = !showFilter" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-colors">
                    <i data-lucide="sliders-horizontal" size="14"></i> Filter
                </button>
                <a href="{{ url('/admin/plans/export') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition-colors">
                    <i data-lucide="download" size="14"></i> Export
                </a>
            </div>
        </div>

        <!-- Filter Area -->
        <div x-show="showFilter" x-collapse class="p-8 bg-gray-50/50 border-b border-border-soft space-y-4" style="display: none;">
            <form method="GET" action="{{ url('/admin/plans') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Search Plan Name</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="E.g. Premium" class="w-full bg-white border border-gray-200 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 text-sm font-medium" />
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan Status</label>
                    <select name="status" class="w-full bg-white border border-gray-200 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 text-sm font-medium">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ ($status ?? '') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white py-3.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all">Apply Filter</button>
                    <a href="{{ url('/admin/plans') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all">Reset</a>
                </div>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">#</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan Name</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan Status</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Start Date</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">End Date</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">No. of Package Listing</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Total Agents</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($plans as $index => $plan)
                        @php
                            $srNo = str_pad($plans->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                            $firstLetter = substr($plan->name, 0, 1);
                            
                            // Color schemes for plan avatars
                            $avatarColors = [
                                'W' => 'bg-orange-50 text-orange-500 border-orange-100',
                                'S' => 'bg-orange-50 text-orange-500 border-orange-100',
                                'P' => 'bg-blue-50 text-blue-500 border-blue-100',
                                'E' => 'bg-gray-100 text-gray-600 border-gray-200',
                                'C' => 'bg-purple-50 text-purple-500 border-purple-100',
                            ];
                            $avatarColor = $avatarColors[$firstLetter] ?? 'bg-gray-50 text-gray-500 border-gray-200';
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <!-- Serial Number -->
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">
                                {{ $index + 1 }}
                            </td>
                            
                            <!-- Plan Name with Circular Icon -->
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full border flex items-center justify-center font-black text-xs {{ $avatarColor }}">
                                        {{ $firstLetter }}
                                    </div>
                                    <span class="text-sm font-black text-gray-800 uppercase tracking-tight">
                                        {{ $plan->name }}
                                    </span>
                                </div>
                            </td>
                            
                             <!-- Plan Status Toggle -->
                             <td class="py-6 px-8">
                                 <a href="{{ url('/admin/plans/toggle/' . $plan->id) }}" class="inline-flex items-center cursor-pointer">
                                     <div class="relative inline-flex items-center">
                                         <input type="checkbox" class="sr-only peer" {{ strtolower($plan->status ?? 'active') === 'active' ? 'checked' : '' }} disabled>
                                         <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                     </div>
                                 </a>
                             </td>
                            
                            <!-- Start Date -->
                            <td class="py-6 px-8 text-sm font-semibold text-gray-500">
                                {{ $plan->created_at ? \Carbon\Carbon::parse($plan->created_at)->format('d M Y') : 'N/A' }}
                            </td>
                            
                            <!-- End Date -->
                            <td class="py-6 px-8 text-sm font-semibold text-gray-500">
                                {{ $plan->created_at ? \Carbon\Carbon::parse($plan->created_at)->addMonth()->format('d M Y') : 'N/A' }}
                            </td>
                            
                            <!-- Package Listings -->
                            <td class="py-6 px-8 text-center">
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-bold">
                                    {{ $plan->package_limit ?? 15 }}
                                </span>
                            </td>

                            <!-- Total Agents -->
                            <td class="py-6 px-8 text-center">
                                @php
                                    $agentCount = DB::table('agents')
                                        ->where('plan_id', $plan->id)
                                        ->orWhere('tier', $plan->name)
                                        ->count();
                                @endphp
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold">
                                    {{ $agentCount }} Agents
                                </span>
                            </td>
                            
                            <!-- Actions -->
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-center gap-2">
                                    <a 
                                        href="{{ url('/admin/plans/preview/' . $plan->id) }}"
                                        class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                        title="View Plan details"
                                    >
                                        <i data-lucide="eye" size="18"></i>
                                    </a>
                                    <a 
                                        href="{{ url('/admin/plans/delete/' . $plan->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this subscription plan tier?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                        title="Delete Plan"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
</svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No subscription plans registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $plans->firstItem() ?? 0 }} to {{ $plans->lastItem() ?? 0 }} of {{ $plans->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($plans->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $plans->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $plans->lastPage()) as $i)
                    @if($i == 1 || $i == $plans->lastPage() || abs($i - $plans->currentPage()) <= 1)
                        @if($i == $plans->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $plans->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $plans->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($plans->hasMorePages())
                    <a href="{{ $plans->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <style>
        .banner-container {
            --banner-left-width: 100%;
            --banner-right-width: 100%;
        }
        @media (min-width: 1024px) {
            .banner-container {
                --banner-left-width: calc(50% - 1rem);
                --banner-right-width: calc(50% - 1rem);
            }
        }
    </style>

    <!-- Bottom Banners -->
    <div class="banner-container flex flex-col lg:flex-row gap-8 pt-4 w-full items-stretch justify-between">
        <!-- Expand Your Inventory Banner -->
        <div class="banner-left w-full bg-cover bg-center overflow-hidden min-h-[240px] relative flex flex-col justify-end p-8 text-white shadow-lg border border-border-soft" style="width: var(--banner-left-width); flex-shrink: 0; border-radius: 32px; background-image: linear-gradient(to top, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.15)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800');">
            <div class="space-y-2 z-10 max-w-md">
                <h3 class="text-2xl font-black leading-tight text-white">Expand Your Inventory</h3>
                <p class="text-white/80 text-xs font-semibold leading-relaxed">Unlock unlimited package listings with our Enterprise Tier. Perfect for high-volume agencies.</p>
                <a href="#" class="inline-flex items-center gap-2 text-primary font-black text-xs hover:gap-3 transition-all pt-2">
                    Learn More <i data-lucide="arrow-right" size="14"></i>
                </a>
            </div>
        </div>

        <!-- Need Help Banner -->
        <div class="banner-right w-full bg-[#FAF9F5] border border-border-soft p-8 flex flex-col justify-between shadow-sm space-y-4" style="width: var(--banner-right-width); flex-shrink: 0; border-radius: 32px;">
            <div class="space-y-2">
                <h3 class="text-2xl font-black text-primary leading-tight">Need Help?</h3>
                <p class="text-gray-500 text-xs font-semibold leading-relaxed">Our dedicated billing support team is available 24/7 to assist with your transaction inquiries.</p>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <a href="#" class="px-6 py-3.5 bg-primary hover:bg-primary-hover text-white rounded-2xl font-black text-xs transition-all shadow-md">
                    Contact Support
                </a>
                <a href="#" class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 rounded-2xl font-black text-xs border border-gray-200 transition-all shadow-sm">
                    Billing FAQ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
