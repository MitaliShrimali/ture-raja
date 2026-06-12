@extends('layouts.admin')

@section('admin_title', 'Country Management')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    editItem: { id: '', name: '', region: '', status: '', image_url: '' }
}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80 block mb-1">Geographical Atlas</span>
            </div>
            <h2 class="text-3xl font-black text-foreground tracking-tight pl-9">Country Management</h2>
        </div>
        <button @click="showAddModal = true" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-2 uppercase tracking-wider">
            <i data-lucide="plus" size="18"></i> New Country
        </button>
    </div>

    {{-- ===== STATS / METRICS CARDS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Card 1: Total Countries --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <i data-lucide="globe" size="20"></i>
                </div>
                <div class="absolute -right-4 -top-4 text-gray-50 opacity-10 pointer-events-none">
                    <i data-lucide="globe" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Total Countries</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-foreground">{{ $totalCountries }}</span>
                    <span class="text-xs font-black text-primary uppercase tracking-wider">↗ +2 This Month</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Active Countries --}}
        <div style="background-color: #b13c0b;" class="rounded-[28px] p-8 flex flex-col justify-between gap-6 text-white shadow-xl relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                    <i data-lucide="check-circle" size="20"></i>
                </div>
                <div class="absolute -right-4 -top-4 text-white/5 opacity-10 pointer-events-none">
                    <i data-lucide="check-circle" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-white/60 uppercase tracking-widest">Active Countries</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-white">{{ $activeCountries }}</span>
                    <span class="text-xs font-bold text-white/80">✓ 72% of Global Coverage</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Primary Region --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-[#FFF4CE] rounded-2xl flex items-center justify-center text-[#E85D26]">
                    <i data-lucide="map" size="20"></i>
                </div>
                <div class="absolute -right-4 -top-4 text-gray-50 opacity-10 pointer-events-none">
                    <i data-lucide="map" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Primary Region</p>
                <h4 class="text-xl font-black text-foreground mt-1">{{ $primaryRegionName }}</h4>
                <div class="flex items-center -space-x-2 overflow-hidden mt-3">
                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100" alt="">
                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&q=80&w=100" alt="">
                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" alt="">
                    <div style="background-color: rgba(177, 60, 11, 0.1); color: #b13c0b;" class="flex items-center justify-center h-6 w-6 rounded-full ring-2 ring-white text-[9px] font-black">+42</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== MAIN DIRECTORY CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        
        {{-- Header Tab --}}
        <div class="px-8 pt-6 pb-4 flex items-center justify-between border-b border-border-soft">
            <h3 class="text-lg font-black text-foreground leading-tight">Country Directory</h3>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-1.5 text-xs font-black text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="sliders-horizontal" size="14"></i> Filter
                </button>
                <span class="text-gray-300">|</span>
                <button class="flex items-center gap-1.5 text-xs font-black text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="download" size="14"></i> Export CSV
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#FCF8F7]">
                        <th class="py-5 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest w-24">SR. NO</th>
                        <th class="py-5 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest min-w-[280px]">COUNTRY NAME</th>
                        <th class="py-5 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">REGION</th>
                        <th class="py-5 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-5 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($countries as $index => $item)
                        @php
                            $srNo = str_pad($countries->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            {{-- SR NO --}}
                            <td class="py-5 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- Country Name + Image + Flag Icon --}}
                            <td class="py-5 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0 border border-gray-100 shadow-sm">
                                        @if($item->image)
                                            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="globe" class="text-gray-400 w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div style="color: #b13c0b; background-color: #FFEBE6;" class="w-6 h-6 rounded-md flex items-center justify-center shrink-0">
                                            <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span class="text-sm font-extrabold text-foreground leading-tight">{{ $item->name }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Region Badge --}}
                            <td class="py-5 px-10">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F1F3F5] text-gray-700">
                                    {{ $item->region }}
                                </span>
                            </td>

                            {{-- Status Switch --}}
                            <td class="py-5 px-10">
                                <a href="{{ url('/admin/settings/preferences/countries/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" {{ $item->status === 'Active' ? 'checked' : '' }} disabled>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $item->status === 'Active' ? 'text-primary' : 'text-gray-400' }}">{{ $item->status }}</span>
                                </a>
                            </td>

                            {{-- Actions --}}
                            <td class="py-5 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', region: '{{ addslashes($item->region) }}', status: '{{ $item->status }}', image_url: '{{ $item->image }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/countries/delete/' . $item->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this country?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center">
                                        <i data-lucide="globe" size="28" class="text-primary opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No countries found</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "New Country" to register your first country.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($countries->hasPages() || $countries->total() > 0)
            <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-sm font-bold text-muted-text">Showing {{ $countries->firstItem() ?? 0 }} to {{ $countries->lastItem() ?? 0 }} of {{ $countries->total() }} entries</p>
                <div class="flex items-center gap-2">
                    @if($countries->onFirstPage())
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                    @else
                        <a href="{{ $countries->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                    @endif
                    
                    @foreach(range(1, $countries->lastPage()) as $i)
                        @if($i == 1 || $i == $countries->lastPage() || abs($i - $countries->currentPage()) <= 1)
                            @if($i == $countries->currentPage())
                                <button class="w-10 h-10 rounded-full text-sm font-black bg-[#b13c0b] text-white shadow-lg shadow-[#b13c0b]/20 transition-all">
                                    {{ $i }}
                                </button>
                            @else
                                <a href="{{ $countries->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                    {{ $i }}
                                </a>
                            @endif
                        @elseif($i == 2 || $i == $countries->lastPage() - 1)
                            <span class="text-muted-text font-black px-1">...</span>
                        @endif
                    @endforeach
                    
                    @if($countries->hasMorePages())
                        <a href="{{ $countries->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                    @else
                        <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ================= MODALS ================= --}}

    {{-- Add Country Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">New Country</h3>
                    <p class="text-xs text-muted-text font-medium">Add a new destination country to the registry.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/countries/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Country Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Country Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Indonesia, Vietnam" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Region --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Region<span class="text-primary">*</span></label>
                    <input required type="text" name="region" placeholder="E.g. Southeast Asia, East Asia" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
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
                    <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl transition-all">Save Country</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Country Modal --}}
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
                    <h3 class="text-xl font-black text-foreground">Edit Country</h3>
                    <p class="text-xs text-muted-text font-medium">Modify country registry settings.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/countries/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                
                {{-- Country Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Country Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                {{-- Region --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Region<span class="text-primary">*</span></label>
                    <input required type="text" name="region" x-model="editItem.region" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
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
