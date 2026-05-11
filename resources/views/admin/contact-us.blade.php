@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Contact Inquiries</h2>
            <p class="text-muted-text font-medium">Manage traveler queries and support tickets efficiently.</p>
        </div>
        <button class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
            <i data-lucide="download" size="20"></i> Download Inquiry Report
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-orange-50 rounded-3xl flex items-center justify-center text-primary">
                <i data-lucide="message-square" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Pending Response</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">24</h4>
                <p class="text-[10px] text-red-500 font-bold uppercase mt-1">High priority tickets</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500">
                <i data-lucide="clock" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Avg. Response Time</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">4.2h</h4>
                <p class="text-[10px] text-green-500 font-bold uppercase mt-1">Faster than last week</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center text-green-500">
                <i data-lucide="check-circle" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Resolved Today</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">18</h4>
                <p class="text-[10px] text-muted-text font-bold uppercase mt-1">86% satisfaction rate</p>
            </div>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Recent Inquiries</h3>
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size="16"></i>
                    <input type="text" placeholder="Search by name or email..." class="bg-gray-100 rounded-xl py-2.5 pl-12 pr-6 outline-none text-xs font-bold">
                </div>
                <button class="p-2.5 text-muted-text hover:text-foreground"><i data-lucide="filter" size="20"></i></button>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CUSTOMER</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SUBJECT</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RECEIVED</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $inquiries = [
                            ['sr' => '01', 'name' => 'Ankit Sharma', 'email' => 'ankit.s@gmail.com', 'phone' => '+91 98765 43210', 'subject' => 'Custom Package Query', 'status' => 'Pending', 'time' => '2h ago'],
                            ['sr' => '02', 'name' => 'Priya Patel', 'email' => 'priya.p@outlook.com', 'phone' => '+91 91234 56789', 'subject' => 'Refund Request', 'status' => 'Replied', 'time' => '5h ago'],
                            ['sr' => '03', 'name' => 'Rahul Verma', 'email' => 'rahul.v@yahoo.com', 'phone' => '+91 99988 77766', 'subject' => 'Agent Registration', 'status' => 'Closed', 'time' => '1d ago'],
                            ['sr' => '04', 'name' => 'Sneha Reddy', 'email' => 'sneha.r@gmail.com', 'phone' => '+91 94433 22110', 'subject' => 'Hotel Availability', 'status' => 'Pending', 'time' => '3d ago'],
                        ];
                    @endphp
                    @foreach($inquiries as $inq)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $inq['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $inq['name'] }}</p>
                                    <div class="flex items-center gap-3 text-[10px] text-muted-text font-medium">
                                        <span class="flex items-center gap-1"><i data-lucide="mail" size="10"></i> {{ $inq['email'] }}</span>
                                        <span class="flex items-center gap-1"><i data-lucide="phone" size="10"></i> {{ $inq['phone'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-foreground">{{ $inq['subject'] }}</td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $inq['time'] }}</td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $inq['status'] === 'Pending' ? 'bg-yellow-50 text-yellow-500' : 
                                       ($inq['status'] === 'Replied' ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400') }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $inq['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <button class="p-2 text-muted-text hover:text-primary"><i data-lucide="more-horizontal" size="18"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
