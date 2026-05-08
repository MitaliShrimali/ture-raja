@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="space-y-2">
        <h1 class="text-5xl font-black text-foreground tracking-tight">Subscriber Management</h1>
        <p class="text-muted-text font-medium">Oversee your community engagement and platform growth metrics.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="user-plus" size="24"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Subscribers</p>
                    <span class="text-[10px] font-bold text-green-500">+12.5%</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">12,482</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                <i data-lucide="mail" size="24"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">New This Month</p>
                    <span class="text-[10px] font-bold text-blue-500">+420</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">1,894</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-500">
                <i data-lucide="heart" size="24"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Rate</p>
                    <span class="text-[10px] font-bold text-green-500">Stable</span>
                </div>
                <h4 class="text-3xl font-black text-foreground tracking-tight">94.2%</h4>
            </div>
        </div>
        <div class="bg-gradient-to-br from-primary to-orange-400 p-8 rounded-[40px] shadow-xl text-white relative overflow-hidden group">
            <div class="relative z-10 space-y-4">
                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Health Score</p>
                <h4 class="text-3xl font-black">Excellent</h4>
                <div class="h-1.5 w-full bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white w-4/5"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriber List -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Subscriber Directory</h3>
            <button class="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
                <i data-lucide="download" size="16"></i> Export
            </button>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL ADDRESS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $subscribers = [
                            ['sr' => '01', 'email' => 'hello@traveler.com', 'date' => 'Oct 24, 2024', 'status' => 'Active'],
                            ['sr' => '02', 'email' => 'explorer@journal.com', 'date' => 'Oct 23, 2024', 'status' => 'Active'],
                            ['sr' => '03', 'email' => 'admin@ascent.co', 'date' => 'Oct 22, 2024', 'status' => 'Active'],
                            ['sr' => '04', 'email' => 'marketing@globaltrip.io', 'date' => 'Oct 21, 2024', 'status' => 'Active'],
                            ['sr' => '05', 'email' => 'support@wanderlust.net', 'date' => 'Oct 20, 2024', 'status' => 'Active'],
                        ];
                    @endphp
                    @foreach($subscribers as $sub)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $sub['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center text-[10px] font-black text-primary uppercase">
                                        {{ $sub['email'][0] }}
                                    </div>
                                    <span class="text-sm font-bold text-foreground">{{ $sub['email'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-4 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <button class="p-2 text-muted-text hover:text-primary"><i data-lucide="eye" size="18"></i></button>
                                    <button class="p-2 text-muted-text hover:text-red-500"><i data-lucide="trash-2" size="18"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex items-center justify-between">
            <p class="text-sm font-bold text-muted-text">Showing 1 - 5 of 1,248</p>
            <div class="flex items-center gap-2">
                <button class="p-2 text-muted-text hover:text-primary"><i data-lucide="chevron-left" size="20"></i></button>
                @foreach([1, 2, 3] as $p)
                    <button class="w-8 h-8 rounded-full text-xs font-black transition-all {{ $p === 1 ? 'bg-primary text-white' : 'text-muted-text hover:text-primary' }}">
                        {{ $p }}
                    </button>
                @endforeach
                <button class="p-2 text-muted-text hover:text-primary"><i data-lucide="chevron-right" size="20"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection
