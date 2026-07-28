@extends('layouts.admin')

@section('admin_title', 'Subscribers')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Subscriber Management</h2>
            <p class="text-muted-text font-medium">Oversee your community engagement and platform growth metrics.</p>
        </div>
    </div>

    <!-- Subscriber List -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Subscriber Directory</h3>
            <a href="{{ url('/admin/reports/subscribers/download') }}" class="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
                <i data-lucide="download" size="16"></i> Export
            </a>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL ADDRESS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">JOINED</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($subscribers as $index => $sub)
                        @php
                            $srNo = str_pad($subscribers->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center text-[10px] font-black text-primary uppercase">
                                        {{ $sub->email[0] }}
                                    </div>
                                    <span class="text-sm font-bold text-foreground">{{ $sub->email }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-center text-sm font-medium text-muted-text">{{ \Carbon\Carbon::parse($sub->created_at)->format('d M, Y') }}</td>
                            <td class="py-6 px-10 text-center">
                                <a href="{{ url('/admin/subscribers/toggle/' . $sub->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $sub->status === 'Subscribed' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-red-50 text-red-500 hover:bg-red-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $sub->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a 
                                        href="{{ url('/admin/subscribers/delete/' . $sub->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this newsletter subscriber?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all inline-block"
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
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No active subscribers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $subscribers->firstItem() ?? 0 }} to {{ $subscribers->lastItem() ?? 0 }} of {{ $subscribers->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($subscribers->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $subscribers->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $subscribers->lastPage()) as $i)
                    @if($i == 1 || $i == $subscribers->lastPage() || abs($i - $subscribers->currentPage()) <= 1)
                        @if($i == $subscribers->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $subscribers->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $subscribers->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($subscribers->hasMorePages())
                    <a href="{{ $subscribers->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Subscriber Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Add Subscriber</h3>
                    <p class="text-xs text-muted-text font-medium">Manually register a newsletter subscription email.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/subscribers/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                    <input required type="email" name="email" placeholder="E.g. traveler@domain.com" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Subscriber</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
