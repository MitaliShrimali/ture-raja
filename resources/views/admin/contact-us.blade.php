@extends('layouts.admin')

@section('admin_title', 'Contact Us')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showEditModal: false, editContact: { id: '', name: '', email: '', phone: '', subject: '', message: '', status: '' } }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Contact Inquiries</h2>
            <p class="text-muted-text font-medium">Manage traveler queries and support tickets efficiently.</p>
        </div>
        <a href="{{ url('/admin/reports/inquiries/download?' . http_build_query(request()->query())) }}" class="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
            <i data-lucide="download" size="20"></i> Download Inquiry Report
        </a>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h3 class="text-xl font-black shrink-0">Recent Inquiries</h3>
            
            <!-- Date Filter Form -->
            <form method="GET" action="{{ url('/admin/contact') }}" class="flex items-center gap-2 w-full md:w-auto justify-end">
                <div class="relative w-full sm:w-auto">
                    <span class="absolute left-3 -top-2 bg-white px-1.5 text-[8px] font-black text-muted-text tracking-wider">FROM</span>
                    <input 
                        type="date" 
                        name="from_date" 
                        value="{{ request('from_date') }}"
                        onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-100 rounded-xl py-2.5 px-3 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-bold text-xs text-foreground cursor-pointer"
                    >
                </div>
                <span class="text-xs font-bold text-muted-text">to</span>
                <div class="relative w-full sm:w-auto">
                    <span class="absolute left-3 -top-2 bg-white px-1.5 text-[8px] font-black text-muted-text tracking-wider">TO</span>
                    <input 
                        type="date" 
                        name="to_date" 
                        value="{{ request('to_date') }}"
                        onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-100 rounded-xl py-2.5 px-3 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-bold text-xs text-foreground cursor-pointer"
                    >
                </div>
            </form>
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
                                <span class="px-3 py-1 rounded-full 
                                    {{ $inq->status === 'New' ? 'bg-blue-50 text-blue-500' : ($inq->status === 'Contacted' ? 'bg-purple-50 text-purple-500' : ($inq->status === 'Pending' ? 'bg-yellow-50 text-yellow-500' : ($inq->status === 'Booked' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'))) }} 
                                    text-[10px] font-black uppercase tracking-wider transition-all">
                                    {{ $inq->status }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editContact = { id: '{{ $inq->id }}', name: '{{ addslashes($inq->name) }}', email: '{{ addslashes($inq->email) }}', phone: '{{ addslashes($inq->phone) }}', subject: '{{ addslashes($inq->subject ?? 'General Feedback') }}', message: '{{ addslashes($inq->message) }}', status: '{{ addslashes($inq->status) }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="pencil" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/contact/delete/' . $inq->id) }}" 
                                        onclick="return confirm('Are you sure you want to clear this message?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
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

    <!-- Edit Contact Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Edit Contact Status</h3>
                    <p class="text-xs text-muted-text font-medium" x-text="'Submitted by: ' + editContact.name"></p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/contact/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editContact.id">
                <div class="grid grid-cols-2 gap-4 text-xs font-bold text-muted-text">
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Email</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="editContact.email"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Phone</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="editContact.phone || 'N/A'"></p>
                    </div>
                </div>
                <div class="space-y-1 text-xs font-bold text-muted-text">
                    <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Subject</p>
                    <p class="bg-gray-50 p-3 rounded-xl text-sm font-black text-primary" x-text="editContact.subject"></p>
                </div>
                <div class="space-y-1 text-xs font-bold text-muted-text">
                    <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Message Body</p>
                    <p class="bg-gray-50 p-4 rounded-2xl text-xs font-medium text-foreground leading-relaxed whitespace-pre-line" x-text="editContact.message"></p>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" x-model="editContact.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="New">New</option>
                        <option value="Contacted">Contacted</option>
                        <option value="Pending">Pending</option>
                        <option value="Booked">Booked</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
