@extends('layouts.admin')

@section('admin_title', 'Customers')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Customers</p>
            <h2 class="font-black text-foreground tracking-tight">Registered Customers</h2>
            <p class="text-muted-text font-medium">View all registered customers and their account details.</p>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-sm font-bold text-muted-text">
                <span>Show</span>
                <select class="bg-gray-50 border-none rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>entries</span>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ url('/admin/customers') }}" class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search customer by name or email..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">NAME</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ROLE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($customers as $index => $user)
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            $colors = ['bg-orange-100 text-orange-600', 'bg-blue-100 text-blue-600', 'bg-green-100 text-green-600', 'bg-purple-100 text-purple-600', 'bg-pink-100 text-pink-600'];
                            $color = $colors[$user->id % count($colors)];
                            $srNo = str_pad($customers->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $srNo }}</td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center font-black text-xs uppercase">
                                        {{ $initials }}
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $user->email }}</td>
                            <td class="py-6 px-8">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-500 text-[10px] font-black uppercase tracking-wider">
                                    CUSTOMER
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="{{ url('/admin/customers/delete/' . $user->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this customer?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
</svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($customers->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $customers->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $customers->lastPage()) as $i)
                    @if($i == 1 || $i == $customers->lastPage() || abs($i - $customers->currentPage()) <= 1)
                        @if($i == $customers->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $customers->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $customers->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($customers->hasMorePages())
                    <a href="{{ $customers->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
