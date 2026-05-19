@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Reports</p>
            <h2 class="font-black text-foreground tracking-tight">System Reports & Exports</h2>
            <p class="text-muted-text font-medium max-w-2xl">
                Download and export system data in CSV format for offline analysis.
            </p>
        </div>
    </div>

    <!-- Aggregate Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-border-soft space-y-2">
            <p class="text-xs font-black text-muted-text uppercase tracking-widest">Total Inquiries</p>
            <h3 class="text-3xl font-black font-syne text-foreground">{{ $totalInquiries }}</h3>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-border-soft space-y-2">
            <p class="text-xs font-black text-muted-text uppercase tracking-widest">Total Leads</p>
            <h3 class="text-3xl font-black font-syne text-foreground">{{ $totalLeads }}</h3>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-border-soft space-y-2">
            <p class="text-xs font-black text-muted-text uppercase tracking-widest">Total Bookings</p>
            <h3 class="text-3xl font-black font-syne text-foreground">{{ $totalBookings }}</h3>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-border-soft space-y-2">
            <p class="text-xs font-black text-muted-text uppercase tracking-widest">Total Revenue</p>
            <h3 class="text-3xl font-black font-syne text-primary">₹{{ number_format($totalRevenue) }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Inquiry Reports Export -->
        <div class="bg-white rounded-[32px] shadow-soft p-8 space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <i data-lucide="message-square" size="20"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-foreground">Inquiry Reports</h3>
                    <p class="text-sm text-muted-text font-medium">Export all contact form submissions and package inquiries.</p>
                </div>
            </div>
            
            <a href="{{ url('/admin/reports/inquiries/download') }}" class="w-full bg-primary hover:bg-primary-hover text-white rounded-2xl py-4 font-black text-sm shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
                <i data-lucide="download" size="18" class="group-hover:-translate-y-1 transition-transform"></i>
                Download Inquiry Report (CSV)
            </a>
        </div>

        <!-- Leads Reports Export -->
        <div class="bg-white rounded-[32px] shadow-soft p-8 space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 rounded-2xl flex items-center justify-center text-green-500">
                    <i data-lucide="users" size="20"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-foreground">Leads Reports</h3>
                    <p class="text-sm text-muted-text font-medium">Export all package booking requests and hot leads.</p>
                </div>
            </div>
            
            <a href="{{ url('/admin/reports/leads/download') }}" class="w-full bg-green-500 hover:bg-green-600 text-white rounded-2xl py-4 font-black text-sm shadow-xl shadow-green-500/20 transition-all flex items-center justify-center gap-3 group">
                <i data-lucide="download" size="18" class="group-hover:-translate-y-1 transition-transform"></i>
                Download Leads Report (CSV)
            </a>
        </div>
    </div>
</div>
@endsection
