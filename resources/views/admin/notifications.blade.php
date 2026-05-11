@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Notifications Management</h2>
            <p class="text-muted-text font-medium">Overview of communication performance and agent reach across the platform.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i>
            New Notification
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
            <div class="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center text-primary relative z-10 transition-colors group-hover:bg-primary group-hover:text-white">
                <i data-lucide="send" size="28"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Sent</p>
                    <span class="text-[10px] font-bold text-green-500">+12.5%</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">2,842</h4>
                <p class="text-[10px] text-muted-text font-medium uppercase mt-1">Lifetime platform broadcasts</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
            <div class="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500 relative z-10 transition-colors group-hover:bg-blue-500 group-hover:text-white">
                <i data-lucide="bell" size="28"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Alerts</p>
                    <span class="text-[10px] font-bold text-orange-500">High Priority</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">14</h4>
                <p class="text-[10px] text-muted-text font-medium uppercase mt-1">System-wide critical updates</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
            <div class="w-16 h-16 bg-gray-50 rounded-3xl flex items-center justify-center text-muted-text relative z-10 transition-colors group-hover:bg-foreground group-hover:text-white">
                <i data-lucide="users" size="28"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Agent Reach</p>
                    <span class="text-[10px] font-bold text-muted-text">98% Coverage</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">856</h4>
                <p class="text-[10px] text-muted-text font-medium uppercase mt-1">Active agents currently reached</p>
            </div>
        </div>
    </div>

    <!-- Dispatches Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Recent Dispatches</h3>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">Filter</button>
                <button class="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">Export CSV</button>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-border-soft">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">NOTIFICATION TITLE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET GROUP</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE SENT</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">OPEN RATE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $dispatches = [
                            ['id' => 'NOT-9021', 'title' => 'Update: Seasonal Commission Shift', 'group' => 'All Agents', 'date' => 'Oct 24, 2023', 'openRate' => 82, 'status' => 'Delivered'],
                            ['id' => 'NOT-8542', 'title' => 'Premium Tier Bonus Announcement', 'group' => 'Premium Only', 'date' => 'Oct 22, 2023', 'openRate' => 95, 'status' => 'Delivered'],
                            ['id' => 'NOT-8261', 'title' => 'Maintenance Alert: Dashboard API', 'group' => 'All Agents', 'date' => 'Oct 20, 2023', 'openRate' => 44, 'status' => 'Draft'],
                            ['id' => 'NOT-8020', 'title' => 'New Compliance Policy Requirements', 'group' => 'All Agents', 'date' => 'Oct 18, 2023', 'openRate' => 61, 'status' => 'Sending...'],
                        ];
                    @endphp
                    @foreach($dispatches as $dispatch)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-8 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground group-hover:text-primary transition-colors">{{ $dispatch['title'] }}</p>
                                    <p class="text-[10px] font-bold text-muted-text uppercase tracking-tighter">ID: {{ $dispatch['id'] }}</p>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <span class="px-4 py-1.5 bg-[#F5F5F5] rounded-full text-[10px] font-black text-muted-text uppercase">
                                    {{ $dispatch['group'] }}
                                </span>
                            </td>
                            <td class="py-8 px-10">
                                <p class="text-xs font-bold text-muted-text leading-tight">{{ $dispatch['date'] }}</p>
                                <p class="text-[10px] font-medium text-muted-text/60 mt-1">09:45 AM</p>
                            </td>
                            <td class="py-8 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden w-24">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ $dispatch['openRate'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-black text-foreground">{{ $dispatch['openRate'] }}%</span>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $dispatch['status'] === 'Delivered' ? 'bg-green-50 text-green-500' : 
                                       ($dispatch['status'] === 'Draft' ? 'bg-gray-50 text-gray-400' : 'bg-orange-50 text-orange-500') }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $dispatch['status'] }}
                                </span>
                            </td>
                            <td class="py-8 px-10 text-right">
                                <button class="p-2 text-muted-text hover:text-foreground"><i data-lucide="more-vertical" size="18"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Engagement Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-10 rounded-[40px] shadow-premium border border-border-soft space-y-6">
            <h4 class="text-2xl font-black text-foreground">Engagement Insights</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Notifications sent during early morning (08:00 - 10:00) see a 15% higher open rate among registered agents. Consider scheduling critical policy updates during this window for maximum visibility.
            </p>
            <div class="grid grid-cols-2 gap-6 pt-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="bar-chart" size="20"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-muted-text uppercase">Highest Open Rate</p>
                        <p class="text-xs font-bold text-foreground">Tuesday Mornings</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                        <i data-lucide="layout" size="20"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-muted-text uppercase">Device Split</p>
                        <p class="text-xs font-bold text-foreground">72% Mobile App</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#1A1A24] p-10 rounded-[40px] shadow-2xl text-white relative overflow-hidden group">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                        <i data-lucide="target" size="28" class="text-primary"></i>
                    </div>
                    <h4 class="text-3xl font-black leading-tight">Advanced Audience <br>Segmentation</h4>
                    <p class="text-white/60 text-sm font-medium max-w-sm">
                        Target specific tiers, regions, or activity levels to deliver more relevant updates.
                    </p>
                </div>
                <button class="w-fit flex items-center gap-3 px-8 py-4 bg-white text-foreground rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
                    <i data-lucide="eye" size="18"></i> View Full Analytics Report
                </button>
            </div>
            <div class="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-primary/10 to-transparent"></div>
        </div>
    </div>
</div>
@endsection
