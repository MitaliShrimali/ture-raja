@extends('layouts.admin')

@section('admin_title', 'Leads')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, editLead: { id: '', name: '', email: '', phone: '', agent: '', package: '', status: '', message: '' } }">
    <!-- Header -->
    <div class="space-y-4">
        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Leads</p>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="font-black text-foreground tracking-tight">Lead Records</h2>
                <p class="text-muted-text font-medium">Manage your prospective travelers and track conversion performance.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/reports/leads/download?' . http_build_query(request()->query())) }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-border-soft rounded-2xl text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-50 transition-all">
                    <i data-lucide="download" size="16"></i> Export List
                </a>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ url('/admin/leads') }}" class="flex flex-col md:flex-row items-center gap-4 w-full">
                <!-- Search -->
                <div class="relative group flex-1 w-full">
                    <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search leads by name, email, agent..." 
                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                    >
                </div>

                <!-- Date Range Filters -->
                <div class="flex items-center gap-2 w-full md:w-auto my-2 md:my-0">
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
                </div>
                
                <!-- Package Type -->
                <div class="w-full md:w-48 relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <i data-lucide="filter" size="16"></i>
                    </div>
                    <select name="type" onchange="this.form.submit()" class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-10 pr-8 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-bold text-xs text-foreground appearance-none cursor-pointer hover:border-primary/50 shadow-sm">
                        <option value="">All Package Types</option>
                        <option value="Flight" {{ request('type') == 'Flight' ? 'selected' : '' }}>Flight Packages</option>
                        <option value="Train" {{ request('type') == 'Train' ? 'selected' : '' }}>Train Packages</option>
                        <option value="Bus" {{ request('type') == 'Bus' ? 'selected' : '' }}>Bus Packages</option>
                        <option value="Cruise" {{ request('type') == 'Cruise' ? 'selected' : '' }}>Cruise Packages</option>
                        <option value="Land" {{ request('type') == 'Land' ? 'selected' : '' }}>Land / Customize</option>
                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Other Types</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-text">
                        <i data-lucide="chevron-down" size="16"></i>
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TRAVELER NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT / PACKAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">MESSAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">INQUIRY DATE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($leads as $index => $lead)
                        @php
                            $srNo = str_pad($leads->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        @php
                            $matchedAgent = $agents->first(function($a) use ($lead) {
                                return strtolower($a->name) === strtolower($lead->agent) || 
                                       str_contains(strtolower($a->name), strtolower($lead->agent)) || 
                                       str_contains(strtolower($lead->agent), strtolower($a->name));
                            });
                            $agentProfileUrl = $matchedAgent ? url('/admin/agents/profile/' . $matchedAgent->id) : '#';
                            $leadMsgEscaped  = addslashes($lead->message ?? '');
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <a href="{{ $agentProfileUrl }}" class="hover:text-primary transition-all">
                                        <p class="text-sm font-black text-foreground hover:text-primary transition-all">{{ $lead->name }}</p>
                                    </a>
                                    <p class="text-[10px] text-muted-text font-medium">{{ $lead->email }} • {{ $lead->phone }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <a href="{{ $agentProfileUrl }}" class="hover:underline transition-all">
                                        <p class="text-sm font-bold text-primary hover:text-primary-hover transition-all">{{ $lead->agent }}</p>
                                    </a>
                                    <p class="text-[10px] text-muted-text font-black uppercase tracking-widest">{{ $lead->package }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-10 max-w-[220px]">
                                @if($lead->message)
                                    <p class="text-xs text-muted-text font-medium leading-relaxed line-clamp-2" title="{{ $lead->message }}">{{ $lead->message }}</p>
                                @else
                                    <span class="text-[10px] text-muted-text opacity-40">—</span>
                                @endif
                            </td>
                            <td class="py-6 px-10 text-xs font-bold text-muted-text">
                                {{ $lead->created_at ? \Carbon\Carbon::parse($lead->created_at)->format('d M Y, h:i A') : 'N/A' }}
                            </td>
                            <td class="py-6 px-10 text-center">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $lead->status === 'Booked' ? 'bg-green-50 text-green-500' : 
                                       ($lead->status === 'New' ? 'bg-blue-50 text-blue-500' : 
                                       ($lead->status === 'Contacted' ? 'bg-purple-50 text-purple-500' : 
                                       ($lead->status === 'Pending' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-500'))) }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $lead->status }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editLead = { id: '{{ $lead->id }}', name: '{{ addslashes($lead->name) }}', email: '{{ addslashes($lead->email) }}', phone: '{{ addslashes($lead->phone) }}', agent: '{{ addslashes($lead->agent) }}', package: '{{ addslashes($lead->package) }}', status: '{{ $lead->status }}', message: '{{ $leadMsgEscaped }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all" title="Edit Status"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/leads/delete/' . $lead->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this lead?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No travelers or leads logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} leads</p>
            <div class="flex items-center gap-2">
                @if($leads->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $leads->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $leads->lastPage()) as $i)
                    @if($i == 1 || $i == $leads->lastPage() || abs($i - $leads->currentPage()) <= 1)
                        @if($i == $leads->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $leads->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $leads->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($leads->hasMorePages())
                    <a href="{{ $leads->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- Tip Widgets Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="mouse-pointer-2" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Conversion Tip</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Follow up with leads within 24 hours to increase conversion rates by up to 40%.
            </p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                <i data-lucide="target" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Lead Quality</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Your current premium package provides access to verified high-intent leads.
            </p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-muted-text">
                <i data-lucide="help-circle" size="24"></i>
            </div>
            <h4 class="text-lg font-black">Need help?</h4>
            <p class="text-sm text-muted-text font-medium leading-relaxed">
                Contact your dedicated account manager for lead management strategies.
            </p>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Lead Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Add New Lead</h3>
                    <p class="text-xs text-muted-text font-medium">Log a prospective traveler lead in the system.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/leads/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Traveler Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Alice Johnson" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                        <input required type="email" name="email" placeholder="E.g. alice@example.com" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Phone Number</label>
                        <div class="flex gap-2 items-center">
                            <div class="relative w-28 shrink-0">
                                <select class="phone-country-code w-full bg-gray-50 border-none rounded-2xl py-4 px-3 outline-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                    <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                    <option value="+1" data-len="10">🇺🇸 +1</option>
                                    <option value="+44" data-len="10">🇬🇧 +44</option>
                                    <option value="+62" data-len="11">🇮🇩 +62</option>
                                    <option value="+65" data-len="8">🇸🇬 +65</option>
                                    <option value="+971" data-len="9">🇦🇪 +971</option>
                                    <option value="+61" data-len="9">🇦🇺 +61</option>
                                    <option value="+66" data-len="9">🇹🇭 +66</option>
                                    <option value="+60" data-len="10">🇲🇾 +60</option>
                                </select>
                            </div>
                            <div class="relative flex-grow">
                                <input type="tel" placeholder="Phone *"
                                    class="phone-number-val w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            </div>
                        </div>
                        <input type="hidden" class="phone-full-val" name="phone">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Assigned Agent</label>
                        <input type="text" name="agent" placeholder="E.g. Nomad Ventures" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Target Package</label>
                        <input type="text" name="package" placeholder="E.g. Bali Paradise" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="New">New</option>
                        <option value="Contacted">Contacted</option>
                        <option value="Pending">Pending</option>
                        <option value="Booked">Booked</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Lead</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Lead Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Edit Lead Record</h3>
                    <p class="text-xs text-muted-text font-medium">Update the traveler info and conversion details.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/leads/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editLead.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Traveler Name</label>
                    <input type="text" name="name" x-model="editLead.name" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm" readonly />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address</label>
                        <input type="email" name="email" x-model="editLead.email" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm" readonly />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Phone Number</label>
                        <input type="text" name="phone" x-model="editLead.phone" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm" readonly />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Assigned Agent</label>
                        <input type="text" name="agent" x-model="editLead.agent" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm" readonly />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Target Package</label>
                        <input type="text" name="package" x-model="editLead.package" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm" readonly />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Message</label>
                    <textarea x-model="editLead.message" class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 text-muted-text cursor-not-allowed shadow-sm text-sm resize-none" rows="2" readonly></textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" x-model="editLead.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
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
