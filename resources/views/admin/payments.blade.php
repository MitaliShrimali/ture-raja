@extends('layouts.admin')

@section('admin_title', 'Payments')

@section('content')
<div class="space-y-10 pb-12" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    editTx: { id: '', user_name: '', email: '', plan_type: '', amount: '', payment_id: '', date: '', status: '', service_guaranteed: '', generate_bill: '', gst_number: '' } 
}">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Financial Reports</p>
            <h2 class="font-black text-foreground tracking-tight">Payments</h2>
            <p class="text-muted-text font-medium">Monitoring platform revenue and transaction history.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                <i data-lucide="plus" size="20"></i> Add Payment
            </button>
            <a href="{{ url('/admin/payments/print') }}?{{ http_build_query(request()->only(['search','plan_type','status','from_date','to_date','service_guaranteed','generate_bill'])) }}&autoprint=1"
               target="_blank"
               class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
                <i data-lucide="download" size="20"></i> Download PDF
            </a>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none !important;
            }
        }
    </style>

    <!-- Financial Overview Metrics -->
    @php
        $isLive = in_array(request()->getHost(), ['tour-raja.com', 'www.tour-raja.com']);
        $totalRevenue = $isLive ? 0 : \DB::table('payments')->whereIn('status', ['Completed', 'Success'])->sum('amount');
        $pendingRevenue = $isLive ? 0 : \DB::table('payments')->where('status', 'Pending')->sum('amount');
        $availableBalance = $isLive ? 0 : ($totalRevenue - \DB::table('payments')->where('status', 'Failed')->sum('amount'));
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
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Pending Payment</p>
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

    @if(!$isLive)
    <!-- Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden print-section">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h3 class="text-xl font-black">Transaction History</h3>
            
            <form action="{{ url('/admin/payments') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-text"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name, Email..." class="w-full bg-[#F5F5F5] border-none rounded-xl py-2 pl-10 pr-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <select name="plan_type" class="w-full md:w-40 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">All Plans</option>
                    @foreach($plans ?? [] as $plan)
                        <option value="{{ $plan->name }}" {{ request('plan_type') == $plan->name ? 'selected' : '' }}>{{ strtoupper($plan->name) }}</option>
                    @endforeach
                </select>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full md:w-32 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20" title="From Date">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full md:w-32 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20" title="To Date">
                <select name="status" class="w-full md:w-32 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">All Status</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <select name="service_guaranteed" class="w-full md:w-40 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">All Services</option>
                    <option value="1" {{ request('service_guaranteed') === '1' ? 'selected' : '' }}>Guaranteed</option>
                    <option value="0" {{ request('service_guaranteed') === '0' ? 'selected' : '' }}>Not Guaranteed</option>
                </select>
                <select name="generate_bill" class="w-full md:w-32 bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">Bill Gen</option>
                    <option value="1" {{ request('generate_bill') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('generate_bill') === '0' ? 'selected' : '' }}>No</option>
                </select>
                <button type="submit" class="w-full md:w-auto bg-primary text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:bg-primary-hover transition-all">Filter</button>
                @if(request()->hasAny(['search', 'plan_type', 'status', 'service_guaranteed', 'generate_bill']))
                    <a href="{{ url('/admin/payments') }}" class="w-full md:w-auto bg-gray-100 text-muted-text px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all text-center">Clear</a>
                @endif
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRANS ID</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">USER / AGENCY</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">PLAN TYPE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">AMOUNT</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">SERVICE GUARANTEED</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">BILL GENERATE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right print:hidden">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($payments as $tx)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-primary">
                                <a href="{{ url('/admin/payments/invoice/' . $tx->id) }}" class="hover:underline hover:text-primary-hover">
                                    {{ $tx->payment_id }}
                                </a>
                            </td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $tx->user_name }}</td>
                            <td class="py-6 px-8 text-xs font-bold text-muted-text">{{ $tx->email }}</td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ \Carbon\Carbon::parse($tx->date)->format('M d, Y') }}</td>
                            <td class="py-6 px-8 text-center">
                                @php
                                    $planColor = 'bg-gray-50 text-gray-500';
                                    if (str_contains(strtoupper($tx->plan_type), 'WELCOME')) {
                                        $planColor = 'bg-green-50 text-green-500';
                                    } elseif (str_contains(strtoupper($tx->plan_type), 'PREMIUM')) {
                                        $planColor = 'bg-orange-50 text-orange-500';
                                    } elseif (str_contains(strtoupper($tx->plan_type), 'ENTERPRISE')) {
                                        $planColor = 'bg-purple-50 text-purple-500';
                                    } elseif (str_contains(strtoupper($tx->plan_type), 'CUSTOM')) {
                                        $planColor = 'bg-blue-50 text-blue-500';
                                    }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $planColor }}">
                                    {{ $tx->plan_type }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-center text-sm font-black text-green-500">
                                ₹{{ number_format($tx->amount, 2) }}
                            </td>
                            <td class="py-6 px-8 text-center">
                                @if($tx->service_guaranteed)
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-500 text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5" title="Trusted Agent">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> YES
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-muted-text opacity-60 text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5" title="Standard Agent">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> NO
                                    </span>
                                @endif
                            </td>
                            <td class="py-6 px-8 text-center">
                                @if($tx->generate_bill)
                                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-500 text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5" title="Invoice generated automatically">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> YES
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-muted-text opacity-60 text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5" title="Invoice not needed">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> NO
                                    </span>
                                @endif
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $tx->status === 'Completed' ? 'bg-green-50 text-green-500' : 
                                       ($tx->status === 'Pending' ? 'bg-yellow-50 text-yellow-500' : 'bg-red-50 text-red-500') }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $tx->status }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-right print:hidden">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editTx = { id: '{{ $tx->id }}', user_name: '{{ addslashes($tx->user_name) }}', email: '{{ addslashes($tx->email) }}', plan_type: '{{ $tx->plan_type }}', amount: '{{ $tx->amount }}', payment_id: '{{ $tx->payment_id }}', date: '{{ $tx->date }}', status: '{{ $tx->status }}', service_guaranteed: '{{ $tx->service_guaranteed }}', generate_bill: '{{ $tx->generate_bill ?? 0 }}', gst_number: '{{ $tx->gst_number ?? '' }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/payments/delete/' . $tx->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this payment record?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-sm font-bold text-muted-text">No transactions logged in the system.</td>
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
    @endif

    <!-- ================= MODALS ================= -->

    <!-- Add Payment Modal -->
    <template x-teleport="body">
    <div 
        x-show="showAddModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Add Payment Record</h3>
                    <p class="text-xs text-muted-text font-medium">Manually log a transaction in the database.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/payments/store') }}" method="POST" class="space-y-6">
                @csrf
                <datalist id="agentsList">
                    @foreach($agentsList as $agent)
                        <option value="{{ $agent->name }}">
                    @endforeach
                </datalist>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">User / Agency Name<span class="text-primary">*</span></label>
                    <input required list="agentsList" name="user_name" placeholder="E.g. Nomad Ventures" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                    <input required type="email" name="email" placeholder="E.g. contact@nomadventures.com" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan type</label>
                        <select name="plan_type" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->name }}">{{ strtoupper($plan->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amount Paid (INR)</label>
                        <input required type="number" step="0.01" name="amount" placeholder="E.g. 99" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">GST No. (Optional)</label>
                    <input type="text" name="gst_number" placeholder="E.g. 22AAAAA0000A1Z5" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Transaction ID</label>
                        <input type="text" name="payment_id" placeholder="Auto-generated if blank" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Payment Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Service Guaranteed</label>
                        <select name="service_guaranteed" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Bill Generate</label>
                        <select name="generate_bill" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
    </template>

    <!-- Edit Payment Modal -->
    <template x-teleport="body">
    <div 
        x-show="showEditModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Payment Details</h3>
                    <p class="text-xs text-muted-text font-medium">Modify transactional record and status.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/payments/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editTx.id" />
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">User / Agency Name<span class="text-primary">*</span></label>
                    <input required list="agentsList" name="user_name" x-model="editTx.user_name" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                    <input required type="email" name="email" x-model="editTx.email" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan type</label>
                        <select name="plan_type" x-model="editTx.plan_type" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->name }}">{{ strtoupper($plan->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amount Paid (INR)</label>
                        <input required type="number" step="0.01" name="amount" x-model="editTx.amount" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">GST No. (Optional)</label>
                    <input type="text" name="gst_number" x-model="editTx.gst_number" placeholder="E.g. 22AAAAA0000A1Z5" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Transaction ID<span class="text-primary">*</span></label>
                        <input required type="text" name="payment_id" x-model="editTx.payment_id" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Payment Date</label>
                        <input required type="date" name="date" x-model="editTx.date" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" x-model="editTx.status" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Service Guaranteed</label>
                        <select name="service_guaranteed" x-model="editTx.service_guaranteed" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Bill Generate</label>
                        <select name="generate_bill" x-model="editTx.generate_bill" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
