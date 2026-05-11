@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
            <h2 class="font-black text-foreground tracking-tight">Paid User</h2>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i>
            Add User
        </button>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">From Date</label>
                <input type="date" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">To Date</label>
                <input type="date" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search Country</label>
                <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
                    <option>All Countries</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search State</label>
                <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
                    <option>Select State</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search City</label>
                <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
                    <option>Select City</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="flex items-center gap-2 px-8 py-3 bg-gray-200 text-muted-text rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-300 transition-all">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">#</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRAVEL AGENT NAME</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">MOBILE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">GUARANTEED</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">PLAN</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center text-orange-500">PENDING</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center text-green-500">APPROVED</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $agents = [
                            ['sr' => '01', 'name' => 'Nomad Ventures', 'email' => 'contact@nomadventures.com', 'mobile' => '+91 98765 43210', 'guaranteed' => true, 'plan' => 'Premium', 'pending' => '03', 'approved' => '12', 'status' => 'active'],
                            ['sr' => '02', 'name' => 'Azure Horizons', 'email' => 'hello@azurehorizons.travel', 'mobile' => '+91 91234 56789', 'guaranteed' => false, 'plan' => 'Standard', 'pending' => '08', 'approved' => '05', 'status' => 'active'],
                            ['sr' => '03', 'name' => 'Globe Trotters Co', 'email' => 'support@globetrotters.org', 'mobile' => '+91 99988 77766', 'guaranteed' => true, 'plan' => 'Premium', 'pending' => '01', 'approved' => '24', 'status' => 'inactive'],
                            ['sr' => '04', 'name' => 'Alpine Escape', 'email' => 'info@alpine-escape.com', 'mobile' => '+91 94433 22110', 'guaranteed' => false, 'plan' => 'Standard', 'pending' => '11', 'approved' => '02', 'status' => 'active'],
                        ];
                    @endphp
                    @foreach($agents as $agent)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $agent['sr'] }}</td>
                            <td class="py-6 px-8 text-sm font-black text-primary">{{ $agent['name'] }}</td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $agent['email'] }}</td>
                            <td class="py-6 px-8 text-sm font-bold text-foreground whitespace-nowrap">{{ $agent['mobile'] }}</td>
                            <td class="py-6 px-8 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $agent['guaranteed'] ? 'bg-green-50 text-green-500' : 'bg-gray-100 text-muted-text opacity-50' }}">
                                    {{ $agent['guaranteed'] ? 'YES' : 'NO' }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $agent['plan'] === 'Premium' ? 'bg-orange-400' : 'bg-gray-300' }}"></div>
                                    <span class="text-xs font-bold">{{ $agent['plan'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-lg font-black text-orange-500">{{ $agent['pending'] }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-lg font-black text-green-500">{{ $agent['approved'] }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $agent['status'] === 'active' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-2 text-muted-text hover:text-primary transition-all"><i data-lucide="search" size="18"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing 1 to 4 of 63 entries</p>
            <div class="flex items-center gap-2">
                <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></button>
                @foreach([1, 2, 3, "...", 7] as $p)
                    <button class="w-10 h-10 rounded-full text-sm font-black transition-all {{ $p === 1 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-muted-text hover:bg-white hover:text-primary' }}">
                        {{ $p }}
                    </button>
                @endforeach
                <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></button>
            </div>
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $metrics = [
                ['label' => 'New Agents', 'value' => '12', 'icon' => 'user', 'color' => 'bg-orange-50 text-orange-600'],
                ['label' => 'Active Plans', 'value' => '45', 'icon' => 'shield', 'color' => 'bg-green-50 text-green-600'],
                ['label' => 'Pending Approvals', 'value' => '08', 'icon' => 'clock', 'color' => 'bg-yellow-50 text-yellow-600'],
                ['label' => 'Total Conversion', 'value' => '84%', 'icon' => 'bar-chart', 'color' => 'bg-blue-50 text-blue-600'],
            ];
        @endphp
        @foreach($metrics as $metric)
            <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl {{ $metric['color'] }} flex items-center justify-center">
                    <i data-lucide="{{ $metric['icon'] }}" size="28"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">{{ $metric['label'] }}</p>
                    <h4 class="text-3xl font-black text-foreground tracking-tight">{{ $metric['value'] }}</h4>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
