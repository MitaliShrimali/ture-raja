@extends('layouts.admin')

@section('admin_title', 'Amenities')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, editItem: { id: '', name: '', icon: '', category: '', status: '' } }">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80 block mb-1">Settings / Platform Preferences</span>
            </div>
            <h2 class="font-black text-foreground tracking-tight pl-9">Amenities</h2>
            <p class="text-muted-text font-medium pl-9">Manage global property features and traveler perks.</p>
        </div>
        <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add Amenity
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ICON & NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CATEGORY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($amenities as $index => $item)
                        @php
                            $srNo = str_pad($amenities->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-muted-text group-hover:text-primary transition-colors">
                                        <i data-lucide="{{ $item->icon }}" size="18"></i>
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-muted-text">{{ $item->category }}</td>
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/settings/preferences/amenities/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" {{ $item->status === 'Active' ? 'checked' : '' }} disabled>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $item->status === 'Active' ? 'text-green-600' : 'text-gray-400' }}">{{ $item->status }}</span>
                                </a>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', icon: '{{ addslashes($item->icon) }}', category: '{{ addslashes($item->category) }}', status: '{{ $item->status }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/amenities/delete/' . $item->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this amenity?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No amenities configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $amenities->firstItem() ?? 0 }} to {{ $amenities->lastItem() ?? 0 }} of {{ $amenities->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($amenities->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $amenities->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $amenities->lastPage()) as $i)
                    @if($i == 1 || $i == $amenities->lastPage() || abs($i - $amenities->currentPage()) <= 1)
                        @if($i == $amenities->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $amenities->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $amenities->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($amenities->hasMorePages())
                    <a href="{{ $amenities->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Amenity Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Add Amenity</h3>
                    <p class="text-xs text-muted-text font-medium">Create a global facility amenity perk.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/amenities/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amenity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Breakfast Included" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Icon Representation</label>
                    <input type="hidden" name="icon" x-model="addAmenityIcon">
                    
                    <div class="flex items-center gap-3">
                        <!-- Swimmer Icon -->
                        <button type="button" @click="addAmenityIcon = 'waves'" 
                            :class="addAmenityIcon === 'waves' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="waves" class="w-4 h-4"></i>
                        </button>
                        <!-- Leaf Icon -->
                        <button type="button" @click="addAmenityIcon = 'leaf'" 
                            :class="addAmenityIcon === 'leaf' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="leaf" class="w-4 h-4"></i>
                        </button>
                        <!-- Dumbbell Icon -->
                        <button type="button" @click="addAmenityIcon = 'dumbbell'" 
                            :class="addAmenityIcon === 'dumbbell' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="dumbbell" class="w-4 h-4"></i>
                        </button>
                        <!-- Utensils Icon -->
                        <button type="button" @click="addAmenityIcon = 'utensils'" 
                            :class="addAmenityIcon === 'utensils' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="utensils" class="w-4 h-4"></i>
                        </button>
                        <!-- Wifi Icon -->
                        <button type="button" @click="addAmenityIcon = 'wifi'" 
                            :class="addAmenityIcon === 'wifi' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="wifi" class="w-4 h-4"></i>
                        </button>
                        <!-- Bar Icon (Martini) -->
                        <button type="button" @click="addAmenityIcon = 'martini'" 
                            :class="addAmenityIcon === 'martini' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="martini" class="w-4 h-4"></i>
                        </button>
                        <!-- Plus Button for Custom Icon -->
                        <button type="button" @click="addAmenityIcon = 'custom'" 
                            :class="(addAmenityIcon !== 'waves' && addAmenityIcon !== 'leaf' && addAmenityIcon !== 'dumbbell' && addAmenityIcon !== 'utensils' && addAmenityIcon !== 'wifi' && addAmenityIcon !== 'martini') ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg' : 'border-2 border-dashed border-[#F4EBE3] text-[#A8988C] hover:bg-gray-50'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Custom input field -->
                    <div x-show="(addAmenityIcon !== 'waves' && addAmenityIcon !== 'leaf' && addAmenityIcon !== 'dumbbell' && addAmenityIcon !== 'utensils' && addAmenityIcon !== 'wifi' && addAmenityIcon !== 'martini')"
                         class="pt-2 animate-fade-in" style="display: none;">
                        <input type="text" placeholder="Type custom Lucide icon name (e.g. coffee, car)..."
                               x-on:input="if($el.value) addAmenityIcon = $el.value"
                               :value="(addAmenityIcon !== 'waves' && addAmenityIcon !== 'leaf' && addAmenityIcon !== 'dumbbell' && addAmenityIcon !== 'utensils' && addAmenityIcon !== 'wifi' && addAmenityIcon !== 'martini') ? addAmenityIcon : ''"
                               class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Category</label>
                        <input required type="text" name="category" placeholder="E.g. Dining" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Amenity</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Amenity Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Edit Amenity</h3>
                    <p class="text-xs text-muted-text font-medium">Update facility amenity configuration.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/amenities/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amenity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Icon Representation</label>
                    <input type="hidden" name="icon" x-model="editItem.icon">
                    
                    <div class="flex items-center gap-3">
                        <!-- Swimmer Icon -->
                        <button type="button" @click="editItem.icon = 'waves'" 
                            :class="editItem.icon === 'waves' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="waves" class="w-4 h-4"></i>
                        </button>
                        <!-- Leaf Icon -->
                        <button type="button" @click="editItem.icon = 'leaf'" 
                            :class="editItem.icon === 'leaf' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="leaf" class="w-4 h-4"></i>
                        </button>
                        <!-- Dumbbell Icon -->
                        <button type="button" @click="editItem.icon = 'dumbbell'" 
                            :class="editItem.icon === 'dumbbell' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="dumbbell" class="w-4 h-4"></i>
                        </button>
                        <!-- Utensils Icon -->
                        <button type="button" @click="editItem.icon = 'utensils'" 
                            :class="editItem.icon === 'utensils' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="utensils" class="w-4 h-4"></i>
                        </button>
                        <!-- Wifi Icon -->
                        <button type="button" @click="editItem.icon = 'wifi'" 
                            :class="editItem.icon === 'wifi' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="wifi" class="w-4 h-4"></i>
                        </button>
                        <!-- Bar Icon (Martini) -->
                        <button type="button" @click="editItem.icon = 'martini'" 
                            :class="editItem.icon === 'martini' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="martini" class="w-4 h-4"></i>
                        </button>
                        <!-- Plus Button for Custom Icon -->
                        <button type="button" @click="editItem.icon = 'custom'" 
                            :class="(editItem.icon !== 'waves' && editItem.icon !== 'leaf' && editItem.icon !== 'dumbbell' && editItem.icon !== 'utensils' && editItem.icon !== 'wifi' && editItem.icon !== 'martini') ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg' : 'border-2 border-dashed border-[#F4EBE3] text-[#A8988C] hover:bg-gray-50'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Custom input field -->
                    <div x-show="(editItem.icon !== 'waves' && editItem.icon !== 'leaf' && editItem.icon !== 'dumbbell' && editItem.icon !== 'utensils' && editItem.icon !== 'wifi' && editItem.icon !== 'martini')"
                         class="pt-2 animate-fade-in" style="display: none;">
                        <input type="text" placeholder="Type custom Lucide icon name (e.g. coffee, car)..."
                               x-on:input="if($el.value) editItem.icon = $el.value"
                               :value="(editItem.icon !== 'waves' && editItem.icon !== 'leaf' && editItem.icon !== 'dumbbell' && editItem.icon !== 'utensils' && editItem.icon !== 'wifi' && editItem.icon !== 'martini') ? editItem.icon : ''"
                               class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Category</label>
                        <input required type="text" name="category" x-model="editItem.category" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" x-model="editItem.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
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
