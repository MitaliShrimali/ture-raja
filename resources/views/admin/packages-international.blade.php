@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Home International Packages</h2>
            <p class="text-muted-text font-medium">Manage the primary visual narrative of the Tourraja homepage. Curate immersive experiences through high-quality background imagery.</p>
        </div>
        <button @click="showAddModal = true; addPreviewUrl = ''" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group shrink-0">
            <i data-lucide="image" size="20" class="group-hover:scale-110 transition-transform"></i> Add Image
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex flex-col justify-center relative overflow-hidden">
            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-2 relative z-10">Active Highlights</p>
            <h3 class="text-3xl font-black text-foreground relative z-10">{{ $packages->where('status', 'Live')->count() }}</h3>
            <div class="absolute bottom-0 left-6 right-6 h-1 bg-primary/20 rounded-t-full"></div>
            <div class="absolute bottom-0 left-6 w-1/3 h-1 bg-primary rounded-t-full"></div>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex flex-col justify-center">
            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-2">Total Listed</p>
            <h3 class="text-3xl font-black text-foreground">{{ $packages->total() }}</h3>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex flex-col justify-center">
            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-2">Top Region</p>
            <h3 class="text-3xl font-black text-foreground">Europe</h3>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex items-center justify-between cursor-pointer hover:border-primary/30 transition-all group" @click="showAddModal = true; addPreviewUrl = ''">
            <div>
                <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-1">New Record</p>
                <h3 class="text-lg font-black text-foreground group-hover:text-primary transition-colors">Add Package</h3>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Main Table Area -->
    <div class="bg-white rounded-[32px] shadow-sm border border-border-soft overflow-hidden">
        <!-- Toolbar -->
        <div class="flex items-center justify-between p-6 border-b border-border-soft/50">
            <div class="flex items-center gap-2 bg-gray-50/80 p-1.5 rounded-full">
                <button class="px-5 py-2 text-sm font-bold bg-primary text-white rounded-full shadow-sm">Active Packages</button>
            </div>
        </div>

        <!-- Table -->
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ID</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">City & Destination</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Visual Preview</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Status</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Price Start</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($packages as $pkg)
                    <tr class="group hover:bg-gray-50/30 transition-colors">
                        <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">#PKG-{{ str_pad($pkg->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-6 px-8">
                            <p class="font-bold text-foreground">{{ $pkg->title }}</p>
                            <p class="text-xs text-text-muted">{{ $pkg->subtitle }}</p>
                        </td>
                        <td class="py-6 px-8">
                            <div class="w-16 h-10 rounded-full overflow-hidden border border-border-soft shadow-sm">
                                <img src="{{ $pkg->image }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <a href="{{ url('/admin/home-packages/toggle/' . $pkg->id) }}">
                                <span class="inline-flex items-center justify-center px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-md transition-colors {{ $pkg->status === 'Live' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">
                                    {{ $pkg->status }}
                                </span>
                            </a>
                        </td>
                        <td class="py-6 px-8 font-bold text-foreground">{{ $pkg->currency ?? '₹' }}{{ number_format($pkg->price) }}</td>
                        <td class="py-6 px-8 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="showEditModal = true; editPkg = { id: '{{ $pkg->id }}', title: '{{ addslashes($pkg->title) }}', subtitle: '{{ addslashes($pkg->subtitle) }}', price: '{{ $pkg->price }}', status: '{{ $pkg->status }}' }" class="w-8 h-8 rounded-xl bg-gray-50 text-text-muted hover:text-primary hover:bg-primary/5 flex items-center justify-center transition-all" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <a href="{{ url('/admin/home-packages/delete/' . $pkg->id) }}" onclick="return confirm('Are you sure you want to delete this?')" class="w-8 h-8 rounded-xl bg-gray-50 text-text-muted hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-all" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-sm font-bold text-muted-text">No packages found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-border-soft/50 flex items-center justify-between text-sm">
            <p class="text-text-muted text-xs">Showing {{ $packages->firstItem() ?? 0 }} to {{ $packages->lastItem() ?? 0 }} of {{ $packages->total() }} packages</p>
            <div>
                {{ $packages->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Add/Edit Form Modal -->
<template x-teleport="body">
    <div 
        x-show="showAddModal || showEditModal" 
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        style="display: none;"
    >
        <div @click.away="showAddModal = false; showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-border-soft p-6 md:p-8 shrink-0">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground" x-text="showAddModal ? 'Add New Package' : 'Edit Package'"></h3>
                    <p class="text-xs text-muted-text font-medium">Update the details for this international package.</p>
                </div>
                <button @click="showAddModal = false; showEditModal = false; addPreviewUrl = ''; editPreviewUrl = ''; editPkg = { id: '', title: '', subtitle: '', price: '', status: '' }" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form :action="showAddModal ? '{{ url('/admin/home-packages/store') }}' : '{{ url('/admin/home-packages/update') }}'" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 overflow-y-auto space-y-6">
                @csrf
                <input type="hidden" name="type" value="international">
                <input type="hidden" name="id" x-model="editPkg.id" x-if="showEditModal">

                <!-- Media Upload Square -->
                <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-border-soft rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-primary/50 transition-colors group relative overflow-hidden">
                    <input type="file" name="image" @change="showAddModal ? addPreviewUrl = URL.createObjectURL($event.target.files[0]) : editPreviewUrl = URL.createObjectURL($event.target.files[0])" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    <!-- Preview Image -->
                    <template x-if="showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image)">
                        <img :src="showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image)" class="absolute inset-0 w-full h-full object-cover z-10">
                    </template>

                    <div class="flex flex-col items-center gap-2 group-hover:scale-105 transition-transform z-0 relative" x-show="!(showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image))">
                        <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-primary">
                            <i data-lucide="upload" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-bold text-muted-text group-hover:text-primary">Upload Package Image</span>
                        <span class="text-[10px] text-muted-text">JPG, PNG, WEBP (Max 2MB)</span>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Title</label>
                        <input type="text" name="title" x-model="editPkg.title" placeholder="e.g. Rome" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Subtitle (Location)</label>
                        <input type="text" name="subtitle" x-model="editPkg.subtitle" placeholder="e.g. Italy, Europe" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                            <select name="status" x-model="editPkg.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                                <option value="Live">Live</option>
                                <option value="Drafting">Drafting</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price Start</label>
                            <input type="number" name="price" x-model="editPkg.price" placeholder="₹" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" @click="showAddModal = false; showEditModal = false; addPreviewUrl = ''; editPreviewUrl = ''; editPkg = { id: '', title: '', subtitle: '', price: '', status: '' }" class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 text-muted-text rounded-2xl font-black text-sm transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-black text-sm transition-all shadow-premium hover:shadow-primary/20">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</template>
@endpush
@endsection
