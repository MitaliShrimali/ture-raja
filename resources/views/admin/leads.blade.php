@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Dashboard</span>
            <span class="opacity-40">/</span>
            <span class="text-primary">Lead Management</span>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-4xl font-black text-foreground tracking-tight">Lead Records</h2>
                <p class="text-muted-text font-medium">Manage your prospective travelers and track conversion performance.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-6 py-3 bg-white border border-border-soft rounded-2xl text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-50 transition-all">
                    <i data-lucide="filter" size="16"></i> Filter
                </button>
                <button class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
                    <i data-lucide="download" size="16"></i> Export List
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TRAVELER NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT / PACKAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $leadData = [
                            ['sr' => '01', 'name' => 'Alice Johnson', 'email' => 'alice.j@example.com', 'phone' => '+1 555-0101', 'agent' => 'Nomad Ventures', 'package' => 'Bali Paradise', 'status' => 'Booked'],
                            ['sr' => '02', 'name' => 'Mark Wilson', 'email' => 'mark.w@example.com', 'phone' => '+1 555-0202', 'agent' => 'Azure Horizons', 'package' => 'Swiss Alps', 'status' => 'New'],
                            ['sr' => '03', 'name' => 'Sarah Connor', 'email' => 'sarah.c@example.com', 'phone' => '+1 555-0303', 'agent' => 'Globe Trotters', 'package' => 'Goa Retreat', 'status' => 'Contacted'],
                            ['sr' => '04', 'name' => 'John Wick', 'email' => 'john.w@example.com', 'phone' => '+1 555-0404', 'agent' => 'Alpine Escape', 'package' => 'Dubai Luxury', 'status' => 'Lost'],
                        ];
                    @endphp
                    @foreach($leadData as $lead)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $lead['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $lead['name'] }}</p>
                                    <p class="text-[10px] text-muted-text font-medium">{{ $lead['email'] }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-bold text-primary">{{ $lead['agent'] }}</p>
                                    <p class="text-[10px] text-muted-text font-black uppercase tracking-widest">{{ $lead['package'] }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-center">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $lead['status'] === 'Booked' ? 'bg-green-50 text-green-500' : 
                                       ($lead['status'] === 'New' ? 'bg-orange-50 text-orange-500' : 
                                       ($lead['status'] === 'Contacted' ? 'bg-blue-50 text-blue-500' : 'bg-red-50 text-red-500')) }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $lead['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2 text-muted-text hover:text-primary transition-all"><i data-lucide="eye" size="18"></i></button>
                                    <button class="p-2 text-muted-text hover:text-primary transition-all"><i data-lucide="edit-3" size="18"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex items-center justify-between">
            <p class="text-sm font-bold text-muted-text">Showing 1 to 4 of 128 leads</p>
            <div class="flex items-center gap-4">
                <i data-lucide="chevron-left" size="20" class="text-muted-text cursor-pointer hover:text-primary transition-colors"></i>
                <i data-lucide="chevron-right" size="20" class="text-muted-text cursor-pointer hover:text-primary transition-colors"></i>
            </div>
        </div>
    </div>

    <!-- Tip Widgets Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="mouse-pointer-2" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Conversion Tip</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Follow up with leads within 24 hours to increase conversion rates by up to 40%.
            </p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                <i data-lucide="target" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Lead Quality</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Your current premium package provides access to verified high-intent leads.
            </p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-muted-text">
                <i data-lucide="help-circle" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Need help?</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Contact your dedicated account manager for lead management strategies.
            </p>
        </div>
    </div>
</div>
@endsection
