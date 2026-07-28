@extends('layouts.admin')

@section('admin_title', 'State Management')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    activeTab: 'all',
    editItem: { id: '', name: '', country: '', status: '', image_url: '' }
}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-[#b13c0b] uppercase tracking-[0.2em] opacity-80 block mb-1">Geographical Atlas</span>
            </div>
            <h2 class="text-3xl font-black text-foreground tracking-tight pl-9">State Management</h2>
            <p class="text-xs text-gray-400 font-semibold pl-9 leading-relaxed max-w-xl">
                Curate and organize regional administrative boundaries across the Tourraja network.
            </p>
        </div>
        <button @click="showAddModal = true" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-2 uppercase tracking-wider self-end sm:self-center">
            <i data-lucide="plus" class="w-4 h-4"></i> New State
        </button>
    </div>

    {{-- ===== STATS / METRICS CARDS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Card 1: Total States --}}
        <div class="bg-white rounded-[32px] border border-border-soft shadow-premium p-8 flex flex-col justify-between min-h-[160px] hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-[#FFF5F2] rounded-2xl flex items-center justify-center text-[#b13c0b]">
                    <i data-lucide="map" size="20"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-4xl font-black text-foreground block tracking-tight leading-none mb-1">{{ $totalStates }}</span>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest leading-normal">
                    Total<br>States
                </p>
            </div>
        </div>

        {{-- Card 2: Active Coverage --}}
        <div style="background-color: #b13c0b;" class="rounded-[32px] p-8 flex flex-col justify-between min-h-[160px] text-white shadow-xl relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                    <i data-lucide="zap" size="20"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-4xl font-black text-white block tracking-tight leading-none mb-1">{{ $utilizationRate }}%</span>
                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest leading-normal">
                    Active<br>Coverage
                </p>
            </div>
        </div>

        {{-- Card 3: Top Contributing Countries --}}
        <div class="bg-[#FCF5F2] rounded-[32px] border border-border-soft p-8 flex flex-col justify-between min-h-[160px] relative overflow-hidden">
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest mb-4">Top Contributing Countries</p>
                
                <div class="space-y-4">
                    @forelse($topCountries as $c)
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-extrabold text-foreground">{{ $c->country }}</span>
                                <span class="font-black text-[#b13c0b]">{{ $c->count }} States</span>
                            </div>
                            <div class="w-full bg-gray-200/50 h-2 rounded-full overflow-hidden">
                                <div style="background-color: #b13c0b; width: {{ $totalStates > 0 ? ($c->count / $totalStates) * 100 : 0 }}%;" class="h-full rounded-full"></div>
                            </div>
                        </div>
                    @empty
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-extrabold text-foreground">Indonesia</span>
                                    <span class="font-black text-[#b13c0b]">34 States</span>
                                </div>
                                <div class="w-full bg-gray-200/50 h-2 rounded-full overflow-hidden">
                                    <div style="background-color: #b13c0b; width: 65%;" class="h-full rounded-full"></div>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-extrabold text-foreground">Thailand</span>
                                    <span class="font-black text-[#b13c0b]">18 States</span>
                                </div>
                                <div class="w-full bg-gray-200/50 h-2 rounded-full overflow-hidden">
                                    <div style="background-color: #b13c0b; width: 35%;" class="h-full rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ===== MAIN DIRECTORY CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        
        {{-- Header Tabs & Search --}}
        <div class="px-8 pt-6 pb-2 flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-border-soft gap-4">
            <div class="flex items-center gap-4">
                <button 
                    @click="activeTab = 'all'" 
                    :class="activeTab === 'all' ? 'bg-[#FFEBE6] text-[#b13c0b]' : 'text-gray-400 hover:text-gray-600'"
                    class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all"
                >
                    All Records
                </button>
                <button 
                    @click="activeTab = 'pending'" 
                    :class="activeTab === 'pending' ? 'bg-[#FFEBE6] text-[#b13c0b]' : 'text-gray-400 hover:text-gray-600'"
                    class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all"
                >
                    Pending Review
                </button>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="flex items-center gap-1.5 text-xs font-black text-muted-text hover:text-primary transition-colors whitespace-nowrap">
                    <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5"></i> Advanced Filter
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#FCF8F7]">
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest w-24">SR. NO</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest min-w-[280px]">STATE NAME</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest">COUNTRY</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest">STATUS</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($states as $index => $item)
                        @php
                            $srNo = str_pad($states->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                            $badgeColor = '#FFEBE6';
                            $textColor = '#b13c0b';
                            
                            // Color mapping for countries if needed
                            $countryLower = strtolower($item->country);
                            if ($countryLower === 'india') {
                                $badgeColor = '#FFEBE6';
                                $textColor = '#b13c0b';
                            } elseif ($countryLower === 'italy') {
                                $badgeColor = '#FDF3EE';
                                $textColor = '#8F583F';
                            } elseif ($countryLower === 'canada') {
                                $badgeColor = '#FDF3EE';
                                $textColor = '#8F583F';
                            } elseif ($countryLower === 'ireland') {
                                $badgeColor = '#FDF3EE';
                                $textColor = '#8F583F';
                            }
                        @endphp
                        <tr 
                            x-show="activeTab === 'all' || (activeTab === 'pending' && '{{ $item->status }}' === 'Inactive')"
                            class="group hover:bg-gray-50/30 transition-colors"
                        >
                            {{-- SR NO --}}
                            <td class="py-5 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- State Name + Image --}}
                            <td class="py-5 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0 border border-gray-100 shadow-sm">
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="map" class="text-gray-400 w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <span class="text-sm font-extrabold text-foreground leading-tight">{{ $item->name }}</span>
                                </div>
                            </td>

                            {{-- Country Badge --}}
                            <td class="py-5 px-10">
                                <span style="background-color: {{ $badgeColor }}; color: {{ $textColor }};" class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    {{ $item->country }}
                                </span>
                            </td>

                            {{-- Status Switch --}}
                            <td class="py-5 px-10">
                                <a href="{{ url('/admin/settings/preferences/states/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" {{ $item->status === 'Active' ? 'checked' : '' }} disabled>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                    </div>
                                </a>
                            </td>

                            {{-- Actions --}}
                            <td class="py-5 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', country: '{{ addslashes($item->country) }}', status: '{{ $item->status }}', image_url: '{{ $item->image }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/states/delete/' . $item->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this state?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
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
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-[#FFF5F2] rounded-3xl flex items-center justify-center">
                                        <i data-lucide="map" size="28" class="text-[#b13c0b] opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No states found</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "New State" to register your first state.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($states->hasPages() || $states->total() > 0)
            <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-xs font-bold text-muted-text uppercase tracking-wider">Showing {{ $states->firstItem() ?? 0 }} - {{ $states->lastItem() ?? 0 }} of {{ $states->total() }} records</p>
                <div class="flex items-center gap-2">
                    @if($states->onFirstPage())
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                    @else
                        <a href="{{ $states->previousPageUrl() }}" class="p-2 text-muted-text hover:text-[#b13c0b] transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                    @endif
                    
                    @foreach(range(1, $states->lastPage()) as $i)
                        @if($i == 1 || $i == $states->lastPage() || abs($i - $states->currentPage()) <= 1)
                            @if($i == $states->currentPage())
                                <button class="w-10 h-10 rounded-full text-sm font-black bg-[#b13c0b] text-white shadow-lg shadow-[#b13c0b]/20 transition-all">
                                    {{ $i }}
                                </button>
                            @else
                                <a href="{{ $states->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-[#b13c0b] flex items-center justify-center">
                                    {{ $i }}
                                </a>
                            @endif
                        @elseif($i == 2 || $i == $states->lastPage() - 1)
                            <span class="text-muted-text font-black px-1">...</span>
                        @endif
                    @endforeach
                    
                    @if($states->hasMorePages())
                        <a href="{{ $states->nextPageUrl() }}" class="p-2 text-muted-text hover:text-[#b13c0b] transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                    @else
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ===== BOTTOM WIDGETS GRID ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        
        {{-- Widget 1: Most Searched --}}
        <div class="bg-[#FCF5F2] rounded-[32px] p-6 border border-border-soft flex flex-col justify-between min-h-[120px] hover:shadow-md transition-all">
            <div>
                <span class="text-[9px] font-black text-muted-text uppercase tracking-widest block mb-1">Most Searched</span>
                <span class="text-base font-black text-foreground block">Rajasthan, India</span>
            </div>
            <div class="flex items-center gap-1.5 mt-4 text-[11px] font-black text-[#b13c0b]">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                <span>24% Increase this month</span>
            </div>
        </div>

        {{-- Widget 2: Compliance Rating --}}
        <div class="bg-[#FCF5F2] rounded-[32px] p-6 border border-border-soft flex flex-col justify-between min-h-[120px] hover:shadow-md transition-all">
            <div>
                <span class="text-[9px] font-black text-muted-text uppercase tracking-widest block mb-1">Compliance Rating</span>
                <span class="text-base font-black text-foreground block">Grade A+</span>
            </div>
            <div class="flex items-center gap-1.5 mt-4 text-[11px] font-black text-[#b13c0b]">
                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                <span>Regional standards met</span>
            </div>
        </div>

        {{-- Widget 3: Pending Updates --}}
        <div class="bg-[#FCF5F2] rounded-[32px] p-6 border border-border-soft flex flex-col justify-between min-h-[120px] hover:shadow-md transition-all">
            <div>
                <span class="text-[9px] font-black text-muted-text uppercase tracking-widest block mb-1">Pending Updates</span>
                <span class="text-base font-black text-foreground block">12 Global Requests</span>
            </div>
            <div class="flex items-center gap-1.5 mt-4 text-[11px] font-black text-[#b13c0b]">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5 animate-spin-slow"></i>
                <span>Last sync 2h ago</span>
            </div>
        </div>

    </div>

    {{-- ================= MODALS ================= --}}

    {{-- Add State Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">New State</h3>
                    <p class="text-xs text-muted-text font-medium">Add a new destination state/province to the registry.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/states/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- State Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">State Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Rajasthan, Tuscany, Bali" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Country Select --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Country<span class="text-primary">*</span></label>
                    <select required name="country" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="">Select Country</option>
                        @foreach($countriesList as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                        {{-- Fallback default options --}}
                        <option value="India">India</option>
                        <option value="Italy">Italy</option>
                        <option value="Canada">Canada</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Thailand">Thailand</option>
                    </select>
                </div>

                {{-- Image Upload --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Display Picture</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-[24px] cursor-pointer bg-gray-50 hover:bg-gray-100/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400 mb-2"></i>
                                <p class="text-xs text-gray-500 font-bold"><span class="text-primary">Click to upload</span> or drag</p>
                                <p class="text-[10px] text-gray-400 mt-1">PNG, JPG, JPEG (Max. 2MB)</p>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>

                {{-- Status Select --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl transition-all">Save State</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit State Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">Edit State</h3>
                    <p class="text-xs text-muted-text font-medium">Modify state registry settings.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/states/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                
                {{-- State Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">State Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Country Select --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Country<span class="text-primary">*</span></label>
                    <select required name="country" x-model="editItem.country" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="">Select Country</option>
                        @foreach($countriesList as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                        {{-- Fallback default options --}}
                        <option value="India">India</option>
                        <option value="Italy">Italy</option>
                        <option value="Canada">Canada</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Thailand">Thailand</option>
                    </select>
                </div>

                {{-- Current Image Preview & Image Upload --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Display Picture</label>
                    <div class="flex items-center gap-4 mb-3" x-show="editItem.image_url">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                            <img :src="editItem.image_url" class="w-full h-full object-cover" />
                        </div>
                        <span class="text-xs text-muted-text font-bold">Current picture</span>
                    </div>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-[24px] cursor-pointer bg-gray-50 hover:bg-gray-100/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <i data-lucide="image" class="w-6 h-6 text-gray-400 mb-1"></i>
                                <p class="text-xs text-gray-500 font-bold"><span class="text-primary">Click to upload new</span> or drag</p>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>

                {{-- Status Select --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" x-model="editItem.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
