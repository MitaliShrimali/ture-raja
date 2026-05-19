@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showViewModal: false, activeContact: { name: '', email: '', phone: '', subject: '', message: '' } }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Contact Inquiries</h2>
            <p class="text-muted-text font-medium">Manage traveler queries and support tickets efficiently.</p>
        </div>
        <a href="{{ url('/admin/reports/inquiries/download') }}" class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
            <i data-lucide="download" size="20"></i> Download Inquiry Report
        </a>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Recent Inquiries</h3>
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
                    @forelse($contacts as $index => $inq)
                        @php
                            $srNo = str_pad($contacts->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $inq->name }}</p>
                                    <div class="flex items-center gap-3 text-[10px] text-muted-text font-medium">
                                        <span class="flex items-center gap-1"><i data-lucide="mail" size="10"></i> {{ $inq->email }}</span>
                                        <span class="flex items-center gap-1"><i data-lucide="phone" size="10"></i> {{ $inq->phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-foreground">{{ $inq->subject ?? 'General Feedback' }}</td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ \Carbon\Carbon::parse($inq->created_at)->diffForHumans() }}</td>
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/contact/toggle/' . $inq->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full 
                                        {{ $inq->status === 'Pending' ? 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100' : 'bg-green-50 text-green-500 hover:bg-green-100' }} 
                                        text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $inq->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showViewModal = true; activeContact = { name: '{{ addslashes($inq->name) }}', email: '{{ addslashes($inq->email) }}', phone: '{{ addslashes($inq->phone) }}', subject: '{{ addslashes($inq->subject ?? 'General Feedback') }}', message: '{{ addslashes($inq->message) }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="eye" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/contact/delete/' . $inq->id) }}" 
                                        onclick="return confirm('Are you sure you want to clear this message?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-sm font-bold text-muted-text">No active contact messages logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($contacts->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $contacts->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $contacts->lastPage()) as $i)
                    @if($i == 1 || $i == $contacts->lastPage() || abs($i - $contacts->currentPage()) <= 1)
                        @if($i == $contacts->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $contacts->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $contacts->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($contacts->hasMorePages())
                    <a href="{{ $contacts->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- View Message Modal -->
    <div 
        x-show="showViewModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showViewModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">View Contact Message</h3>
                    <p class="text-xs text-muted-text font-medium" x-text="'Submitted by: ' + activeContact.name"></p>
                </div>
                <button @click="showViewModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 text-xs font-bold text-muted-text">
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Email</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeContact.email"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Phone</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeContact.phone || 'N/A'"></p>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Subject</p>
                    <p class="bg-gray-50 p-3 rounded-xl text-sm font-black text-primary" x-text="activeContact.subject"></p>
                </div>
                <div class="space-y-1">
                    <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Message Body</p>
                    <p class="bg-gray-50 p-4 rounded-2xl text-xs font-medium text-foreground leading-relaxed whitespace-pre-line" x-text="activeContact.message"></p>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-4">
                <button type="button" @click="showViewModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
