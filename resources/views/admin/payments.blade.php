@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Financial Reports</p>
            <h2 class="font-black text-foreground tracking-tight">Payments</h2>
            <p class="text-muted-text font-medium">Monitoring platform revenue and transaction history.</p>
        </div>
        <a href="{{ url('/admin/reports/payments/download') }}" class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
            <i data-lucide="download" size="20"></i> Export Reports
        </a>
    </div>

    <!-- Financial Overview Metrics -->
    @php
        $totalRevenue = \DB::table('payments')->where('status', 'Completed')->sum('amount');
        $pendingRevenue = \DB::table('payments')->where('status', 'Pending')->sum('amount');
        $availableBalance = $totalRevenue - \DB::table('payments')->where('status', 'Failed')->sum('amount');
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center text-green-500">
                <i data-lucide="arrow-up-right" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Revenue</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">₹{{ number_format($totalRevenue, 2) }}</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-yellow-50 rounded-3xl flex items-center justify-center text-yellow-500">
                <i data-lucide="arrow-down-left" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Pending Volume</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">₹{{ number_format($pendingRevenue, 2) }}</h4>
            </div>
        </div>
        <div class="bg-primary p-8 rounded-[40px] shadow-xl text-white flex items-center gap-6 relative overflow-hidden">
            <div class="w-16 h-16 bg-white/20 rounded-3xl flex items-center justify-center text-white relative z-10">
                <i data-lucide="credit-card" size="32"></i>
            </div>
            <div class="relative z-10">
                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Available Balance</p>
                <h4 class="text-3xl font-black tracking-tight">₹{{ number_format($availableBalance, 2) }}</h4>
            </div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 blur-3xl rounded-full"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h3 class="text-xl font-black">Transaction History</h3>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRANS ID</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">USER / AGENCY</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PLAN TYPE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AMOUNT</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($payments as $tx)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-primary">{{ $tx->payment_id }}</td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $tx->user_name }}</td>
                            <td class="py-6 px-8 text-xs font-bold text-muted-text">{{ $tx->email }}</td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ \Carbon\Carbon::parse($tx->date)->format('M d, Y') }}</td>
                            <td class="py-6 px-8 text-sm font-bold text-muted-text uppercase tracking-widest">{{ $tx->plan_type }}</td>
                            <td class="py-6 px-8 text-sm font-black text-green-500">
                                ₹{{ number_format($tx->amount, 2) }}
                            </td>
                            <td class="py-6 px-8 text-right">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $tx->status === 'Completed' ? 'bg-green-50 text-green-500' : 
                                       ($tx->status === 'Pending' ? 'bg-yellow-50 text-yellow-500' : 'bg-red-50 text-red-500') }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No transactions logged in the system.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($payments->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $payments->lastPage()) as $i)
                    @if($i == 1 || $i == $payments->lastPage() || abs($i - $payments->currentPage()) <= 1)
                        @if($i == $payments->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $payments->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $payments->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
