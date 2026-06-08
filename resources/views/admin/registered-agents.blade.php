@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
            <h2 class="font-black text-foreground tracking-tight">All paid user</h2>
            <p class="text-muted-text font-medium">Viewing all active and inactive paid users.</p>
        </div>
        <a href="{{ url('/admin/agents') }}" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i>
            Add Paid User
        </a>
    </div>

    <!-- Existing Agents List -->
    <div class="bg-white rounded-[32px] shadow-soft border border-border-soft overflow-hidden">
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
            
            <div class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    placeholder="Search agents..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </div>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-border-soft">
                <tr>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Agent Name</th>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">JOINED DATE</th>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PLAN</th>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AMOUNT PAID</th>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                    <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-soft">
                @forelse($agents ?? [] as $agent)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100/60 flex items-center justify-center overflow-hidden border border-gray-200/50 flex-shrink-0">
                                    @if($agent->logo)
                                        <img src="{{ asset($agent->logo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                                    @else
                                        @php
                                            $initials = collect(explode(' ', $agent->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                                        @endphp
                                        <span class="text-xs font-black text-muted-text/80 uppercase">{{ $initials }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-foreground">{{ $agent->name }}</p>
                                    <p class="text-[10px] text-muted-text font-medium">{{ $agent->email }} • {{ $agent->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-sm font-bold text-foreground">{{ $agent->created_at ? \Carbon\Carbon::parse($agent->created_at)->format('M d, Y') : 'N/A' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-[11px] font-black uppercase tracking-wider {{ strtolower($agent->tier) === 'premium' ? 'text-purple-500 bg-purple-50' : 'text-primary bg-primary/10' }} inline-block px-3 py-1 rounded-full">{{ $agent->tier ?? 'Basic' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-sm font-bold text-foreground">{{ strtolower($agent->tier) === 'premium' ? '₹4,999' : (strtolower($agent->tier) === 'enterprise' ? '₹9,999' : '₹0') }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $agent->status === 'Active' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                                {{ $agent->status }}
                            </span>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-3">
                                <a href="{{ url('/admin/agents/toggle/' . $agent->id) }}" class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-muted-text hover:text-primary transition-colors" title="Toggle Status">
                                    <i data-lucide="power" size="14"></i>
                                </a>
                                <a href="{{ url('/admin/agents/delete/' . $agent->id) }}" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:text-red-500 transition-colors" onclick="return confirm('Are you sure you want to remove this agent?');" title="Delete">
                                    <i data-lucide="trash-2" size="14"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-sm font-bold text-muted-text">No agents registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
