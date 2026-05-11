@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">User Subscription Plans</h2>
            <p class="text-muted-text font-medium">Manage and monitor agent-level subscription assignments.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Assign New Plan
        </button>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Active Plans</p>
            <h4 class="text-4xl font-black text-foreground">842</h4>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Platinum Users</p>
            <h4 class="text-4xl font-black text-primary">124</h4>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Expiring Soon</p>
            <h4 class="text-4xl font-black text-orange-500">32</h4>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Revenue MTD</p>
            <h4 class="text-4xl font-black text-green-500">$12.4k</h4>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Agent Assignments</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size="16"></i>
                    <input type="text" placeholder="Search agents..." class="bg-gray-50 rounded-xl py-2 pl-12 pr-4 text-xs font-bold outline-none" />
                </div>
            </div>
        </div>
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CURRENT PLAN</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EXPIRY DATE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $userPlans = [
                            ['name' => 'Rahul Sharma', 'plan' => 'Platinum Elite', 'status' => 'Active', 'expiry' => 'Dec 24, 2024'],
                            ['name' => 'Sneha Gupta', 'plan' => 'Gold Plus', 'status' => 'Active', 'expiry' => 'Nov 12, 2024'],
                            ['name' => 'Amit Patel', 'plan' => 'Silver Standard', 'status' => 'Expired', 'expiry' => 'May 01, 2024'],
                            ['name' => 'Priya Verma', 'plan' => 'Platinum Elite', 'status' => 'Active', 'expiry' => 'Jan 30, 2025'],
                        ];
                    @endphp
                    @foreach($userPlans as $up)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 font-bold text-sm text-foreground">{{ $up['name'] }}</td>
                            <td class="py-6 px-10">
                                <span class="px-4 py-1.5 bg-gray-100 rounded-full text-[10px] font-black text-muted-text uppercase tracking-wider">
                                    {{ $up['plan'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full {{ $up['status'] === 'Active' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $up['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $up['expiry'] }}</td>
                            <td class="py-6 px-10 text-right">
                                <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="edit-3" size="18"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
