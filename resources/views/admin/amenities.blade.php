@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, editItem: { id: '', name: '', icon: '', category: '', status: '' } }">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Amenities</h2>
            <p class="text-muted-text font-medium">Manage global property features and traveler perks.</p>
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
                                <a href="{{ url('/admin/amenities/toggle/' . $item->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $item->status === 'Active' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $item->status }}
                                    </span>
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
                                        href="{{ url('/admin/amenities/delete/' . $item->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this amenity?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
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
            
            <form action="{{ url('/admin/amenities/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amenity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Breakfast Included" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Lucide Icon Class Name<span class="text-primary">*</span></label>
                    <input required type="text" name="icon" placeholder="E.g. coffee, wifi, car, wind" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
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
            
            <form action="{{ url('/admin/amenities/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Amenity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Lucide Icon Class Name<span class="text-primary">*</span></label>
                    <input required type="text" name="icon" x-model="editItem.icon" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
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
