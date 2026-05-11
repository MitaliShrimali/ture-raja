@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Financial Reports</p>
            <h2 class="font-black text-foreground tracking-tight">Payments</h2>
            <p class="text-muted-text font-medium">Monitoring platform revenue and transaction history.</p>
        </div>
        <button class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
            <i data-lucide="download" size="20"></i> Export Reports
        </button>
    </div>

    <!-- Financial Overview Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center text-green-500">
                <i data-lucide="arrow-up-right" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Monthly Revenue</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">₹128,430</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div class="w-16 h-16 bg-red-50 rounded-3xl flex items-center justify-center text-red-500">
                <i data-lucide="arrow-down-left" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Payouts</p>
                <h4 class="text-3xl font-black text-foreground tracking-tight">₹42,100</h4>
            </div>
        </div>
        <div class="bg-primary p-8 rounded-[40px] shadow-xl text-white flex items-center gap-6 relative overflow-hidden">
            <div class="w-16 h-16 bg-white/20 rounded-3xl flex items-center justify-center text-white relative z-10">
                <i data-lucide="credit-card" size="32"></i>
            </div>
            <div class="relative z-10">
                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Available Balance</p>
                <h4 class="text-3xl font-black tracking-tight">₹86,330</h4>
            </div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 blur-3xl rounded-full"></div>
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h3 class="text-xl font-black">Transaction History</h3>
            <button class="flex items-center gap-2 text-xs font-bold text-muted-text hover:text-foreground transition-all">
                <i data-lucide="filter" size="16"></i> Filters
            </button>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRANS ID</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">USER / AGENCY</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">METHOD</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AMOUNT</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $transactions = [
                            ['id' => 'TX1092', 'user' => 'Himalayan Treks', 'date' => 'Oct 24, 2024', 'amount' => '₹4,200.00', 'method' => 'Stripe', 'status' => 'Completed', 'type' => 'in'],
                            ['id' => 'TX1091', 'user' => 'Goa Beach Travels', 'date' => 'Oct 23, 2024', 'amount' => '₹1,500.00', 'method' => 'PayPal', 'status' => 'Completed', 'type' => 'in'],
                            ['id' => 'TX1090', 'user' => 'Refund: User_12', 'date' => 'Oct 22, 2024', 'amount' => '₹199.00', 'method' => 'Bank Transfer', 'status' => 'Pending', 'type' => 'out'],
                            ['id' => 'TX1089', 'user' => 'Global Travels', 'date' => 'Oct 21, 2024', 'amount' => '₹12,800.00', 'method' => 'Stripe', 'status' => 'Completed', 'type' => 'in'],
                        ];
                    @endphp
                    @foreach($transactions as $tx)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-primary">{{ $tx['id'] }}</td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $tx['user'] }}</td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $tx['date'] }}</td>
                            <td class="py-6 px-8 text-sm font-bold text-muted-text">{{ $tx['method'] }}</td>
                            <td class="py-6 px-8">
                                <span class="text-sm font-black {{ $tx['type'] === 'in' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $tx['type'] === 'in' ? '+' : '-' }}{{ $tx['amount'] }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <span class="px-3 py-1 rounded-full {{ $tx['status'] === 'Completed' ? 'bg-green-50 text-green-500' : 'bg-yellow-50 text-yellow-500' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $tx['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
