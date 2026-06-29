@extends('layouts.admin')

@section('admin_title', 'Explore Destinations')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    editItem: { id: '', name: '', timezone: '', state: '', country: '', status: '', image_url: '' }
}">

    {{-- ===== PAGE HEADER & METRICS ===== --}}
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-[#b13c0b] uppercase tracking-[0.2em] opacity-80 block mb-1">Geographical Atlas</span>
            </div>
            <h2 class="text-3xl font-black text-foreground tracking-tight pl-9">Explore Destinations</h2>
            <p class="text-xs text-gray-400 font-semibold pl-9 leading-relaxed max-w-xl">
                Manage the global database of urban centers and travel hubs. Define administrative hierarchy and operational status.
            </p>
        </div>

        {{-- Metrics & Action --}}
        <div class="flex flex-wrap items-center gap-4 w-full xl:w-auto xl:justify-end">
            {{-- Card 1: Total Cities --}}
            <div class="bg-white rounded-2xl border border-border-soft p-4 min-w-[140px] flex flex-col justify-between shadow-premium">
                <span class="text-[9px] font-black text-muted-text uppercase tracking-wider">TOTAL CITIES</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-foreground">{{ number_format($totalCities) }}</span>
                    <span class="text-[9px] font-black text-[#b13c0b] ml-1">↗12%</span>
                </div>
            </div>

            {{-- Card 2: Active Now --}}
            <div style="background-color: #b13c0b;" class="rounded-2xl p-4 min-w-[140px] flex flex-col justify-between text-white shadow-xl">
                <span class="text-[9px] font-black text-white/70 uppercase tracking-wider">ACTIVE NOW</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-2xl font-black text-white">{{ number_format($activeCities) }}</span>
                    <i data-lucide="globe" class="w-4 h-4 text-white/80"></i>
                </div>
            </div>

            {{-- New City Button --}}
            <button @click="showAddModal = true" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-2 uppercase tracking-wider">
                <i data-lucide="plus" class="w-4 h-4"></i> New City
            </button>
        </div>
    </div>

    {{-- ===== MAIN DIRECTORY CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        
        {{-- Header Tab --}}
        <div class="px-8 pt-6 pb-4 flex items-center justify-between border-b border-border-soft">
            <h3 class="text-lg font-black text-foreground leading-tight">City Inventory</h3>
            <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-50 hover:bg-gray-100 rounded-xl text-gray-500 transition-colors">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                </button>
                <button class="p-2 bg-gray-50 hover:bg-gray-100 rounded-xl text-gray-500 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#FCF8F7]">
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest w-24">SR. NO</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest min-w-[280px]">CITY NAME</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest">STATE / PROVINCE</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest">COUNTRY</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest">STATUS</th>
                        <th class="py-5 px-10 text-[10px] font-black text-[#A0938F] uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($cities as $index => $item)
                        @php
                            $srNo = str_pad($cities->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            {{-- SR NO --}}
                            <td class="py-5 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- City Name + Image + Timezone --}}
                            <td class="py-5 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0 border border-gray-100 shadow-sm">
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="map-pin" class="text-gray-400 w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-extrabold text-foreground leading-tight">{{ $item->name }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 mt-0.5">{{ $item->timezone ?: 'UTC +0:00' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- State / Province --}}
                            <td class="py-5 px-10">
                                <span class="text-sm font-extrabold text-foreground">
                                    {{ $item->state ?: '—' }}
                                </span>
                            </td>

                            {{-- Country --}}
                            <td class="py-5 px-10">
                                <div class="flex items-center gap-2">
                                    <div style="color: #b13c0b; background-color: #FFEBE6;" class="w-5 h-5 rounded-md flex items-center justify-center shrink-0">
                                        <i data-lucide="globe" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-xs font-black text-foreground uppercase tracking-wider">{{ $item->country }}</span>
                                </div>
                            </td>

                            {{-- Status Switch --}}
                            <td class="py-5 px-10">
                                <a href="{{ url('/admin/settings/preferences/cities/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
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
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', timezone: '{{ $item->timezone }}', state: '{{ addslashes($item->state) }}', country: '{{ addslashes($item->country) }}', status: '{{ $item->status }}', image_url: '{{ $item->image }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/cities/delete/' . $item->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this city?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-[#FFF5F2] rounded-3xl flex items-center justify-center">
                                        <i data-lucide="map-pin" size="28" class="text-[#b13c0b] opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No cities found</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "New City" to register your first city.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($cities->hasPages() || $cities->total() > 0)
            <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-xs font-bold text-muted-text uppercase tracking-wider">Showing {{ $cities->firstItem() ?? 0 }} - {{ $cities->lastItem() ?? 0 }} of {{ number_format($totalCities) }} cities</p>
                <div class="flex items-center gap-2">
                    @if($cities->onFirstPage())
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                    @else
                        <a href="{{ $cities->previousPageUrl() }}" class="p-2 text-muted-text hover:text-[#b13c0b] transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                    @endif
                    
                    @foreach(range(1, $cities->lastPage()) as $i)
                        @if($i == 1 || $i == $cities->lastPage() || abs($i - $cities->currentPage()) <= 1)
                            @if($i == $cities->currentPage())
                                <button class="w-10 h-10 rounded-full text-sm font-black bg-[#b13c0b] text-white shadow-lg shadow-[#b13c0b]/20 transition-all">
                                    {{ $i }}
                                </button>
                            @else
                                <a href="{{ $cities->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-[#b13c0b] flex items-center justify-center">
                                    {{ $i }}
                                </a>
                            @endif
                        @elseif($i == 2 || $i == $cities->lastPage() - 1)
                            <span class="text-muted-text font-black px-1">...</span>
                        @endif
                    @endforeach
                    
                    @if($cities->hasMorePages())
                        <a href="{{ $cities->nextPageUrl() }}" class="p-2 text-muted-text hover:text-[#b13c0b] transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                    @else
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ===== BOTTOM SECTION: REGIONAL EXPANSION & SMART CATEGORIZATION ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Widget 1: Regional Growth (Wide Card) --}}
        <div class="bg-white rounded-[32px] p-8 border border-border-soft flex flex-col justify-between min-h-[220px] hover:shadow-lg transition-all lg:col-span-2 relative overflow-hidden">
            <div class="space-y-4 max-w-xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-[#FFEBE6] text-[#b13c0b]">
                    REGIONAL GROWTH
                </span>
                <h3 class="text-2xl font-black text-foreground tracking-tight leading-snug">
                    Middle Eastern expansion is trending
                </h3>
                <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                    Database activity shows a 24% increase in tourism inquiries for emerging cities in Jordan and Oman.
                </p>
            </div>
            
            <div class="mt-6">
                <a href="#" class="inline-flex items-center gap-1.5 text-xs font-black text-[#b13c0b] hover:opacity-80 transition-opacity">
                    Review Analytics <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        {{-- Widget 2: Smart Categorization --}}
        <div class="bg-[#FFF5F2] rounded-[32px] p-8 border border-border-soft flex flex-col justify-between min-h-[220px] hover:shadow-lg transition-all relative overflow-hidden">
            <div class="space-y-4">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#b13c0b] shadow-sm">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <h4 class="text-sm font-black text-foreground tracking-tight">
                    Smart Categorization
                </h4>
                <p class="text-xs text-gray-500 font-semibold leading-relaxed">
                    AI suggests grouping 12 existing entries under the new 'Coastal Paradise' category.
                </p>
            </div>
            
            <div class="flex items-center gap-2 mt-6">
                <div class="flex -space-x-2 overflow-hidden">
                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=100" alt="">
                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" alt="">
                </div>
                <div class="px-2 py-0.5 rounded-full bg-white text-[9px] font-black text-[#b13c0b] shadow-sm">+11</div>
            </div>
        </div>

    </div>

    {{-- ================= MODALS ================= --}}

    {{-- Add City Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">New City</h3>
                    <p class="text-xs text-muted-text font-medium">Add a new destination city to the registry.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/cities/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- City Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">City Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Paris, Dubai, Kyoto" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Timezone --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Timezone</label>
                    <input type="text" name="timezone" placeholder="E.g. UTC +1:00, UTC -5:00" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- State / Province --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">State / Province</label>
                    <select name="state" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="">Select State (Optional)</option>
                        @foreach($statesList as $s)
                            <option value="{{ $s->name }}">{{ $s->name }} ({{ $s->country }})</option>
                        @endforeach
                    </select>
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
                        <option value="France">France</option>
                        <option value="UAE">UAE</option>
                        <option value="Japan">Japan</option>
                        <option value="Egypt">Egypt</option>
                        <option value="India">India</option>
                        <option value="Italy">Italy</option>
                    </select>
                </div>

                {{-- Image Upload --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Display Picture</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-[24px] cursor-pointer bg-gray-50 hover:bg-gray-100/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400 mb-2"></i>
                                <p class="text-xs text-gray-500 font-bold"><span class="text-primary">Click to upload</span> or drag</p>
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
                    <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl transition-all">Save City</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit City Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">Edit City</h3>
                    <p class="text-xs text-muted-text font-medium">Modify city settings.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/cities/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                
                {{-- City Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">City Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Timezone --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Timezone</label>
                    <input type="text" name="timezone" x-model="editItem.timezone" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- State / Province --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">State / Province</label>
                    <select name="state" x-model="editItem.state" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="">Select State (Optional)</option>
                        @foreach($statesList as $s)
                            <option value="{{ $s->name }}">{{ $s->name }} ({{ $s->country }})</option>
                        @endforeach
                    </select>
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
                        <option value="France">France</option>
                        <option value="UAE">UAE</option>
                        <option value="Japan">Japan</option>
                        <option value="Egypt">Egypt</option>
                        <option value="India">India</option>
                        <option value="Italy">Italy</option>
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
