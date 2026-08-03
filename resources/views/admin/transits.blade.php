@extends('layouts.admin')

@section('admin_title', 'Transits')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    addTransitIcon: 'bus',
    editItem: { id: '', name: '', selected_icon: '', description: '', status: '', image: '', svg_icon: '' }
}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80">Settings / Platform Preferences</span>
            </div>
            <h2 class="text-3xl font-black text-foreground tracking-tight pl-9">Transit Infrastructure</h2>
            <p class="text-muted-text font-medium text-sm pl-9">Configure global transit types, custom vector icons, and vehicle capacity filters.</p>
        </div>
        <button @click="showAddModal = true; addTransitIcon = 'bus';" class="bg-primary hover:bg-primary-hover text-white px-6 py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
            <i data-lucide="plus" size="18"></i> Add Transit Package
        </button>
    </div>

    {{-- ===== STATS / METRICS CARDS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Card 1: Total Bookings --}}
        <div class="lg:col-span-4 bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center">
                    <i data-lucide="trending-up" class="text-primary" size="18"></i>
                </div>
                <span class="text-[10px] font-black text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-wider">Trending</span>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Total Bookings</p>
                <p class="text-4xl font-black text-foreground mt-1">2,845</p>
            </div>
            <p class="text-xs font-black text-green-500 flex items-center gap-1">
                <i data-lucide="arrow-up" size="14"></i> 12.5% increase vs last month
            </p>
        </div>

        {{-- Card 2: Expedition Metrics --}}
        <div class="lg:col-span-8 bg-[#FFF5F2]/40 rounded-[28px] border border-border-soft shadow-premium p-8 flex items-center justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="space-y-4 max-w-xl z-10">
                <h3 class="text-xl font-black text-foreground">Expedition Metrics</h3>
                <p class="text-sm font-semibold text-muted-text leading-relaxed">
                    Your fleet is currently operating at 84% capacity. <br class="hidden md:block">
                    Aerial and Marine packages have seen the highest growth this quarter.
                </p>
                <div class="flex items-center gap-6 pt-2">
                    <span class="flex items-center gap-2 text-xs font-black text-foreground">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                        ACTIVE
                    </span>
                    <span class="flex items-center gap-2 text-xs font-black text-muted-text">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        MAINTENANCE
                    </span>
                </div>
            </div>
            
            {{-- Compass Graphic on Far Right --}}
            <div class="hidden md:flex w-24 h-24 rounded-full bg-white/60 border border-border-soft items-center justify-center shrink-0 shadow-sm z-10 mr-4">
                <i data-lucide="compass" class="text-muted-text/30" size="48"></i>
            </div>
        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        
        {{-- Table Title + Filters --}}
        <div class="px-10 py-6 flex items-center justify-between border-b border-border-soft">
            <h3 class="text-lg font-black text-foreground">Active Fleet Inventory</h3>
            <button class="flex items-center gap-2 text-xs font-black text-primary hover:text-primary-hover transition-colors">
                <i data-lucide="sliders-horizontal" size="14"></i>
                Advanced Filters
            </button>
        </div>

        {{-- Table Container --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest w-20">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest min-w-[280px]">TRANSIT PACKAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">TYPE ICON</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">FLEET STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">OPERATIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($transits as $index => $item)
                        @php
                            $srNo = str_pad($transits->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            {{-- SR NO --}}
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- Name + Description --}}
                            <td class="py-6 px-10">
                                <div>
                                    <p class="text-sm font-black text-foreground leading-tight">{{ $item->name }}</p>
                                    @if($item->description)
                                        <p class="text-xs text-muted-text font-medium mt-1">{{ $item->description }}</p>
                                    @else
                                        <p class="text-xs text-muted-text font-medium mt-1">Global platform transit</p>
                                    @endif
                                </div>
                            </td>

                            {{-- Type Icon Badge (Circle) --}}
                            <td class="py-6 px-10">
                                <div class="flex justify-center">
                                    <div class="w-10 h-10 bg-[#FFF5F2] rounded-full flex items-center justify-center text-primary">
                                        @if($item->svg_icon)
                                            {{-- Custom uploaded SVG Icon --}}
                                            <img src="{{ asset($item->svg_icon) }}" class="w-5 h-5 object-contain" alt="Custom Transit Icon">
                                        @else
                                            {{-- Presets standard lucide icon --}}
                                            <i data-lucide="{{ $item->selected_icon ?: 'truck' }}" size="18"></i>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Status Toggle (Fleet Status) --}}
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/settings/preferences/transits/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" {{ $item->status === 'Active' ? 'checked' : '' }} disabled>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $item->status === 'Active' ? 'text-green-600' : 'text-gray-400' }}">{{ $item->status }}</span>
                                </a>
                            </td>

                            {{-- Operations (Edit/Delete) --}}
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', selected_icon: '{{ $item->selected_icon ?: 'bus' }}', description: '{{ addslashes($item->description) }}', status: '{{ $item->status }}', image: '{{ $item->image }}', svg_icon: '{{ $item->svg_icon }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/transits/delete/' . $item->id) }}" 
                                        onclick="return confirm('Delete this transit package?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center">
                                        <i data-lucide="truck" size="28" class="text-primary opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No transits yet</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "Add Transit Package" to get started</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text font-semibold">
                Showing {{ $transits->firstItem() ?? 0 }} to {{ $transits->lastItem() ?? 0 }} of {{ $transits->total() }} transit assets
            </p>
            <div class="flex items-center gap-2">
                @if($transits->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-left" size="20"></i>
                    </button>
                @else
                    <a href="{{ $transits->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
                        <i data-lucide="chevron-left" size="20"></i>
                    </a>
                @endif
                @foreach(range(1, $transits->lastPage()) as $i)
                    @if($i == 1 || $i == $transits->lastPage() || abs($i - $transits->currentPage()) <= 1)
                        @if($i == $transits->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">{{ $i }}</button>
                        @else
                            <a href="{{ $transits->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black text-muted-text hover:bg-white hover:text-primary flex items-center justify-center transition-all">{{ $i }}</a>
                        @endif
                    @elseif($i == 2 || $i == $transits->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                @if($transits->hasMorePages())
                    <a href="{{ $transits->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
                        <i data-lucide="chevron-right" size="20"></i>
                    </a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-right" size="20"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== ADD MODAL ===== --}}
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-2xl w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-2xl font-black text-foreground">Add New Transit</h3>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="24"></i></button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/transits/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Transit Name --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Transit Name</label>
                    <input required type="text" name="name" placeholder="e.g., Luxury Sedan, Coastal Cruise" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                </div>

                {{-- Featured Image & SVG Upload Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Featured Image Upload --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text pl-1">Featured Image</label>
                        <div class="relative group border-2 border-dashed border-[#FFF5F2] hover:border-primary/30 rounded-[24px] p-6 bg-[#FFF5F2]/20 flex flex-col items-center justify-center transition-colors cursor-pointer" onclick="document.getElementById('add_image_input').click()">
                            <input type="file" id="add_image_input" name="image" class="hidden" onchange="document.getElementById('add_img_preview_name').innerText = this.files[0] ? this.files[0].name : 'Drop image here'">
                            <i data-lucide="image-plus" class="text-primary w-8 h-8 mb-2"></i>
                            <p class="text-xs font-semibold text-foreground" id="add_img_preview_name">Drop image here or <span class="text-primary underline">browse</span></p>
                            <p class="text-[10px] text-muted-text mt-1">Supports JPG, PNG, WEBP</p>
                        </div>
                    </div>

                    {{-- Custom SVG Icon --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text pl-1">Custom SVG Icon</label>
                        <div class="relative group border-2 border-dashed border-[#FFF5F2] hover:border-primary/30 rounded-[24px] p-6 bg-[#FFF5F2]/20 flex flex-col items-center justify-center transition-colors cursor-pointer" onclick="document.getElementById('add_svg_input').click()">
                            <input type="file" id="add_svg_input" name="svg_icon" accept=".svg" class="hidden" onchange="document.getElementById('add_svg_preview_name').innerText = this.files[0] ? this.files[0].name : 'Upload Vector SVG'">
                            <i data-lucide="shapes" class="text-primary w-8 h-8 mb-2"></i>
                            <p class="text-xs font-semibold text-foreground" id="add_svg_preview_name">Upload Vector <span class="text-primary underline">SVG</span></p>
                            <p class="text-[10px] text-muted-text mt-1">Recommended 48x48px</p>
                        </div>
                    </div>
                </div>

                {{-- Travel Icon Picker --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between pr-2">
                        <label class="text-xs font-black text-muted-text pl-1">Travel Icon Picker</label>
                        <span class="text-[9px] font-black text-primary bg-primary/10 px-2 py-1 rounded-md uppercase tracking-wider">Quick Selection</span>
                    </div>
                    <input type="hidden" name="selected_icon" :value="addTransitIcon">
                    <div class="flex flex-wrap items-center gap-3 p-4 bg-[#FFF5F2]/20 rounded-2xl border border-border-soft">
                        @foreach([
                            ['bus','Bus'], ['car','Car'], ['ship','Cruise'], ['plane','Flight'], ['helicopter','Helicopter'], 
                            ['train','Train'], ['bike','Bike'], ['motorcycle','Scooter'], ['sailboat','Sailboat'], ['tram-front','Tram']
                        ] as [$ic, $lb])
                            <button type="button" @click="addTransitIcon = '{{ $ic }}'"
                                :class="addTransitIcon === '{{ $ic }}' ? 'ring-2 ring-primary ring-offset-2 bg-white shadow-md text-primary' : 'text-foreground hover:bg-white/50'"
                                class="w-12 h-12 rounded-xl flex items-center justify-center transition-all" title="{{ $lb }}">
                                <i data-lucide="{{ $ic }}" class="w-5 h-5"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Description</label>
                    <textarea name="description" placeholder="Describe the transit details, capacity, and amenities..." rows="4" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground leading-relaxed"></textarea>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Status</label>
                    <select name="status" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Transit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-2xl w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-2xl font-black text-foreground">Edit Transit Package</h3>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="24"></i></button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/transits/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                
                {{-- Transit Name --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Transit Name</label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                </div>

                {{-- Featured Image & SVG Upload Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Featured Image Upload --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text pl-1">Featured Image</label>
                        <div class="relative group border-2 border-dashed border-[#FFF5F2] hover:border-primary/30 rounded-[24px] p-6 bg-[#FFF5F2]/20 flex flex-col items-center justify-center transition-colors cursor-pointer" onclick="document.getElementById('edit_image_input').click()">
                            <input type="file" id="edit_image_input" name="image" class="hidden" onchange="document.getElementById('edit_img_preview_name').innerText = this.files[0] ? this.files[0].name : 'Drop image here'">
                            <i data-lucide="image-plus" class="text-primary w-8 h-8 mb-2"></i>
                            <p class="text-xs font-semibold text-foreground" id="edit_img_preview_name">Drop image here or <span class="text-primary underline">browse</span></p>
                            <p class="text-[10px] text-muted-text mt-1">Supports JPG, PNG, WEBP</p>
                        </div>
                    </div>

                    {{-- Custom SVG Icon --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text pl-1">Custom SVG Icon</label>
                        <div class="relative group border-2 border-dashed border-[#FFF5F2] hover:border-primary/30 rounded-[24px] p-6 bg-[#FFF5F2]/20 flex flex-col items-center justify-center transition-colors cursor-pointer" onclick="document.getElementById('edit_svg_input').click()">
                            <input type="file" id="edit_svg_input" name="svg_icon" accept=".svg" class="hidden" onchange="document.getElementById('edit_svg_preview_name').innerText = this.files[0] ? this.files[0].name : 'Upload Vector SVG'">
                            <i data-lucide="shapes" class="text-primary w-8 h-8 mb-2"></i>
                            <p class="text-xs font-semibold text-foreground" id="edit_svg_preview_name">Upload Vector <span class="text-primary underline">SVG</span></p>
                            <p class="text-[10px] text-muted-text mt-1">Recommended 48x48px</p>
                        </div>
                    </div>
                </div>

                {{-- Travel Icon Picker --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between pr-2">
                        <label class="text-xs font-black text-muted-text pl-1">Travel Icon Picker</label>
                        <span class="text-[9px] font-black text-primary bg-primary/10 px-2 py-1 rounded-md uppercase tracking-wider">Quick Selection</span>
                    </div>
                    <input type="hidden" name="selected_icon" :value="editItem.selected_icon">
                    <div class="flex flex-wrap items-center gap-3 p-4 bg-[#FFF5F2]/20 rounded-2xl border border-border-soft">
                        @foreach([
                            ['bus','Bus'], ['car','Car'], ['ship','Cruise'], ['plane','Flight'], ['helicopter','Helicopter'], 
                            ['train','Train'], ['bike','Bike'], ['motorcycle','Scooter'], ['sailboat','Sailboat'], ['tram-front','Tram']
                        ] as [$ic, $lb])
                            <button type="button" @click="editItem.selected_icon = '{{ $ic }}'"
                                :class="editItem.selected_icon === '{{ $ic }}' ? 'ring-2 ring-primary ring-offset-2 bg-white shadow-md text-primary' : 'text-foreground hover:bg-white/50'"
                                class="w-12 h-12 rounded-xl flex items-center justify-center transition-all" title="{{ $lb }}">
                                <i data-lucide="{{ $ic }}" class="w-5 h-5"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Description</label>
                    <textarea name="description" x-model="editItem.description" placeholder="Describe the transit details, capacity, and amenities..." rows="4" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground leading-relaxed"></textarea>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Status</label>
                    <select name="status" x-model="editItem.status" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
