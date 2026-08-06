@extends('layouts.admin')

@section('admin_title', 'Hotel Category')

@section('content')
<!-- Embed custom style block to guarantee grid rendering regardless of Tailwind compilation state -->
<style>
    .modal-grid {
        display: grid;
        grid-template-columns: 1fr;
    }
    @media (min-width: 768px) {
        .modal-grid {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }
        .modal-left {
            grid-column: span 5 / span 5 !important;
        }
        .modal-right {
            grid-column: span 7 / span 7 !important;
        }
    }
</style>

<div x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    addIcon: 'bed',
    editCategory: { id: '', name: '', description: '', icon: 'bed' }
}" class="space-y-8 pb-12 relative">
    
    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80 block mb-1">Settings / Platform Preferences</span>
            </div>
            <h2 class="text-4xl font-black text-gray-900 tracking-tight pl-9">Hotel Category</h2>
        </div>
        
        <button type="button" @click="showAddModal = true" 
            class="bg-primary hover:bg-primary-hover text-white px-6 py-3.5 rounded-full text-xs font-black shadow-lg shadow-primary/20 flex items-center gap-2 transition-all duration-200">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Hotel Category
        </button>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side: Category List -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] overflow-hidden">
                
                <!-- Table Header Panel -->
                <div class="p-8 pb-4 flex items-center justify-between border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-primary">
                            <i data-lucide="list" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Category List</h3>
                    </div>
                    
                    <a href="#" class="border border-gray-200 text-gray-500 font-bold px-4 py-2 rounded-full text-xs hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i> Export CSV
                    </a>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" style="border-collapse: collapse;">
                        <thead>
                            <tr class="bg-[#FBFBFA]" style="border-bottom: 1px solid #E5E7EB;">
                                <th class="py-4 px-8 text-[10px] font-black text-gray-400 uppercase tracking-wider w-24" style="border: none;">Sr. No</th>
                                <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-wider" style="border: none;">Hotel Category</th>
                                <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-wider w-40" style="border: none;">Status</th>
                                <th class="py-4 px-8 text-[10px] font-black text-gray-400 uppercase tracking-wider w-24 text-right" style="border: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $cat)
                                <tr class="hover:bg-gray-50/50 transition-colors" style="border: none; border-bottom: 1px solid #F3F4F6;">
                                    <td class="py-5 px-8 text-sm font-semibold text-gray-400" style="border: none;">
                                        {{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}
                                    </td>
                                    <td class="py-5 px-4" style="border: none;">
                                        <div class="flex items-center gap-3">
                                            <!-- Category Icons styled in light orange background and orange icon color -->
                                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-primary flex items-center justify-center shrink-0">
                                                <i data-lucide="{{ $cat->icon ?? 'bed' }}" class="w-4 h-4"></i>
                                            </div>
                                            <span class="text-sm font-bold text-gray-900">{{ $cat->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-5 px-4" style="border: none;">
                                        <!-- Custom Toggle Switch aligned with plans.blade.php -->
                                        <a href="{{ url('admin/settings/preferences/hotel-categories/toggle/'.$cat->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" class="sr-only peer" {{ $cat->status ? 'checked' : '' }} disabled>
                                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                            </div>
                                            <span class="text-xs font-black {{ $cat->status ? 'text-green-600' : 'text-gray-400' }}">{{ $cat->status ? 'Active' : 'Inactive' }}</span>
                                        </a>
                                    </td>
                                    <td class="py-5 px-8 text-right" style="border: none;">
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false" 
                                                class="absolute right-0 mt-1 w-36 bg-white rounded-2xl border border-gray-100 shadow-xl py-2 z-50 transition-all"
                                                style="display: none;">
                                                <button type="button" @click="
                                                    editCategory = { id: '{{ $cat->id }}', name: '{{ $cat->name }}', description: '{{ $cat->description }}', icon: '{{ $cat->icon }}' };
                                                    showEditModal = true;
                                                    open = false;
                                                " class="w-full text-left px-4 py-2.5 text-xs font-black text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <i data-lucide="edit" class="w-3.5 h-3.5 text-gray-400"></i> Edit
                                                </button>
                                                <a href="{{ url('admin/settings/preferences/hotel-categories/delete/'.$cat->id) }}" onclick="return confirm('Are you sure you want to delete this category?')" 
                                                    class="block px-4 py-2.5 text-xs font-black text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                    <i data-lucide="trash-2" size="20"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-sm font-semibold text-gray-400" style="border: none;">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer & Pagination -->
                @if($categories->hasPages() || $categories->total() > 0)
                    <div class="p-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">
                            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
                        </span>
                        
                        <div class="flex items-center gap-2">
                            @if($categories->onFirstPage())
                                <span class="w-8 h-8 rounded-full border border-gray-100 flex items-center justify-center text-gray-300 pointer-events-none">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                @if ($page == $categories->currentPage())
                                    <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-black">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-xs font-black text-gray-500 hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-full border border-gray-100 flex items-center justify-center text-gray-300 pointer-events-none">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Right Side: Stats Panel -->
        <div class="lg:col-span-4 space-y-8">
            
            <!-- Most Booked Category -->
            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-wider block">Most Booked Category</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-[#DCFCE7] text-green-700">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +12% vs last month
                    </span>
                </div>
                <h4 class="text-2xl font-black text-gray-900 tracking-tight mt-4">5 Star Hotels</h4>
            </div>

            <!-- Total Active Categories -->
            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-wider block">Total Active Categories</span>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-[#FDF2E9] text-primary">
                        Primary Segment
                    </span>
                </div>
                <h4 class="text-2xl font-black text-gray-900 tracking-tight mt-4">{{ $categories->total() }} Entries</h4>
            </div>

            <!-- Orange Promo Card -->
            <div class="rounded-[32px] p-8 shadow-lg text-white flex flex-col justify-between relative overflow-hidden min-h-[200px] bg-primary">
                <div class="space-y-3 relative z-10">
                    <span class="text-xs font-black uppercase tracking-wider text-white/70 block">Need a specialized tag?</span>
                    <p class="text-xl font-black leading-tight">
                        Create custom categories for niche tours.
                    </p>
                </div>
                
                <div class="mt-6 pt-6 relative z-10">
                    <!-- Text color of Open Configurator should be orange (text-primary) -->
                    <button type="button" @click="showAddModal = true" class="bg-white text-primary hover:bg-gray-50 px-5 py-3 rounded-full text-xs font-black shadow-sm transition-all">
                        Open Configurator
                    </button>
                </div>

                <!-- Decorative star outline elements -->
                <div class="absolute -right-10 -bottom-10 opacity-10 text-white transform rotate-12 pointer-events-none">
                    <i data-lucide="star" class="w-48 h-48 fill-white"></i>
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- ADD CATEGORY MODAL REPLICA (3rd Image) -->
    <!-- ========================================== -->
    <template x-teleport="body">
    <template x-teleport="body">
    <div x-show="showAddModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" 
        style="display: none;">

        <!-- Modal Box: Enforce grid display & responsiveness via custom CSS class -->
        <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden max-w-4xl w-full modal-grid relative z-50 transform transition-all duration-300 border border-gray-100"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Left Side (col-span-5): High contrast orange overlay on the photo background -->
            <div class="modal-left p-8 md:p-12 text-white flex flex-col justify-between min-h-[350px] md:min-h-[500px] relative overflow-hidden bg-cover bg-center"
                style="background-image: linear-gradient(rgba(178, 59, 6, 0.88), rgba(178, 59, 6, 0.88)), url('{{ asset('images/hotel_category_popup_bg.jpg') }}');">
                <div class="absolute -right-20 -bottom-20 opacity-10 text-white transform rotate-45 pointer-events-none">
                    <i data-lucide="star" class="w-64 h-64 fill-white"></i>
                </div>

                <div class="relative z-10 space-y-6">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                        <i data-lucide="star" class="w-5 h-5 fill-white text-white"></i>
                    </div>
                    
                    <div class="space-y-4">
                        <h2 class="text-3xl md:text-4xl font-black leading-tight tracking-tight">
                            Elevate the<br>Stay Experience
                        </h2>
                        <p class="text-xs text-white/95 font-semibold leading-relaxed">
                            Define new luxury standards for our growing portfolio of boutique stays.
                        </p>
                    </div>
                </div>

                <!-- Glassmorphic Design Insight Card -->
                <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 mt-8">
                    <span class="text-[9px] font-black tracking-widest uppercase text-white/80 block mb-1">Design Insight</span>
                    <p class="text-xs font-semibold leading-relaxed text-white">
                        "A well-defined category helps travelers navigate their desires with absolute clarity."
                    </p>
                </div>
            </div>

            <!-- Right Side (col-span-7): Form Inputs Side -->
            <div class="modal-right p-8 md:p-12 flex flex-col justify-between bg-white relative">
                
                <!-- Close Button -->
                <button type="button" @click="showAddModal = false" class="absolute top-8 right-8 p-1.5 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <form action="{{ url('admin/settings/preferences/hotel-categories/store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Form Title -->
                    <div class="space-y-1">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">New Category</h3>
                        <p class="text-xs font-semibold text-gray-400">Fill in the core details below.</p>
                    </div>

                    <!-- Category Name (Underline style) -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Category Name</label>
                        <input type="text" name="name" placeholder="e.g. Heritage Suites" required
                            class="w-full bg-transparent border-0 border-b-2 border-gray-100 focus:border-primary focus:ring-0 px-0 py-3 text-lg font-bold text-gray-800 placeholder:text-gray-300 transition-all">
                    </div>

                    <!-- Category Description (Textarea inside grey box) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Category Description</label>
                        <textarea name="description" rows="3" placeholder="Describe the ambiance, target audience, and key features of this category..."
                            class="w-full bg-[#F5F4F2] border-0 rounded-2xl p-4 text-xs font-semibold text-gray-800 placeholder:text-gray-400 focus:ring-2 focus:ring-primary/20 transition-all leading-relaxed"></textarea>
                    </div>

                    <!-- Visual Anchor (Icons list select) -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Visual Anchor (Icon)</label>
                        <input type="hidden" name="icon" x-model="addIcon">
                        
                        <div class="flex items-center gap-3">
                            <!-- Bed Icon -->
                            <button type="button" @click="addIcon = 'bed'" 
                                :class="addIcon === 'bed' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="bed" class="w-4 h-4"></i>
                            </button>
                            <!-- Swimmer/Pool Icon -->
                            <button type="button" @click="addIcon = 'waves'" 
                                :class="addIcon === 'waves' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="waves" class="w-4 h-4"></i>
                            </button>
                            <!-- Leaf/Nature Icon -->
                            <button type="button" @click="addIcon = 'leaf'" 
                                :class="addIcon === 'leaf' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="leaf" class="w-4 h-4"></i>
                            </button>
                            <!-- Building Icon -->
                            <button type="button" @click="addIcon = 'building-2'" 
                                :class="addIcon === 'building-2' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </button>
                            <!-- Plus Icon -->
                            <button type="button" @click="addIcon = 'award'" 
                                :class="addIcon === 'award' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-dashed border-gray-200 transition-all">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                        <button type="submit" 
                            class="flex-1 bg-primary hover:bg-primary-hover text-white font-bold py-4 px-6 rounded-2xl flex items-center justify-center gap-2 transition-all">
                            <i data-lucide="file-check" class="w-4 h-4"></i> Create Category
                        </button>
                        <button type="button" @click="showAddModal = false"
                            class="bg-[#E5E7EB] hover:bg-[#D1D5DB] text-gray-700 font-bold py-4 px-6 rounded-2xl transition-all">
                            Draft
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    </template>
    </template>


    <!-- ========================================== -->
    <!-- EDIT CATEGORY MODAL REPLICA (Same UI) -->
    <!-- ========================================== -->
    <template x-teleport="body">
    <template x-teleport="body">
    <div x-show="showEditModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" 
        style="display: none;">

        <!-- Modal Box: Enforce grid display & responsiveness via custom CSS class -->
        <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden max-w-4xl w-full modal-grid relative z-50 transform transition-all duration-300 border border-gray-100"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Left Side (col-span-5): High contrast orange overlay on the photo background -->
            <div class="modal-left p-8 md:p-12 text-white flex flex-col justify-between min-h-[350px] md:min-h-[500px] relative overflow-hidden bg-cover bg-center"
                style="background-image: linear-gradient(rgba(178, 59, 6, 0.88), rgba(178, 59, 6, 0.88)), url('{{ asset('images/hotel_category_popup_bg.jpg') }}');">
                <div class="absolute -right-20 -bottom-20 opacity-10 text-white transform rotate-45 pointer-events-none">
                    <i data-lucide="star" class="w-64 h-64 fill-white"></i>
                </div>

                <div class="relative z-10 space-y-6">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                        <i data-lucide="edit" class="w-5 h-5 text-white"></i>
                    </div>
                    
                    <div class="space-y-4">
                        <h2 class="text-3xl md:text-4xl font-black leading-tight tracking-tight">
                            Elevate the<br>Stay Experience
                        </h2>
                        <p class="text-xs text-white/95 font-semibold leading-relaxed">
                            Define new luxury standards for our growing portfolio of boutique stays.
                        </p>
                    </div>
                </div>

                <!-- Glassmorphic Design Insight Card -->
                <div class="relative z-10 bg-black/30 backdrop-blur-md border border-white/10 rounded-2xl p-5 mt-8">
                    <span class="text-[9px] font-black tracking-widest uppercase text-white/70 block mb-1">Design Insight</span>
                    <p class="text-xs font-semibold leading-relaxed text-white/90">
                        "A well-defined category helps travelers navigate their desires with absolute clarity."
                    </p>
                </div>
            </div>

            <!-- Right Side (col-span-7): Form Inputs Side -->
            <div class="modal-right p-8 md:p-12 flex flex-col justify-between bg-white relative">
                
                <!-- Close Button -->
                <button type="button" @click="showEditModal = false" class="absolute top-8 right-8 p-1.5 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <form action="{{ url('admin/settings/preferences/hotel-categories/update') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="id" x-model="editCategory.id">
                    
                    <!-- Form Title -->
                    <div class="space-y-1">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Edit Category</h3>
                        <p class="text-xs font-semibold text-gray-400">Modify the core details below.</p>
                    </div>

                    <!-- Category Name (Underline style) -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Category Name</label>
                        <input type="text" name="name" x-model="editCategory.name" required
                            class="w-full bg-transparent border-0 border-b-2 border-gray-100 focus:border-primary focus:ring-0 px-0 py-3 text-lg font-bold text-gray-800 placeholder:text-gray-200 transition-all">
                    </div>

                    <!-- Category Description (Textarea inside grey box) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Category Description</label>
                        <textarea name="description" rows="3" x-model="editCategory.description"
                            class="w-full bg-[#F5F4F2] border-0 rounded-2xl p-4 text-xs font-semibold text-gray-800 placeholder:text-gray-400 focus:ring-2 focus:ring-primary/20 transition-all leading-relaxed"></textarea>
                    </div>

                    <!-- Visual Anchor (Icons list select) -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase text-primary tracking-wider block">Visual Anchor (Icon)</label>
                        <input type="hidden" name="icon" x-model="editCategory.icon">
                        
                        <div class="flex items-center gap-3">
                            <!-- Bed Icon -->
                            <button type="button" @click="editCategory.icon = 'bed'" 
                                :class="editCategory.icon === 'bed' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="bed" class="w-4 h-4"></i>
                            </button>
                            <!-- Swimmer/Pool Icon -->
                            <button type="button" @click="editCategory.icon = 'waves'" 
                                :class="editCategory.icon === 'waves' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="waves" class="w-4 h-4"></i>
                            </button>
                            <!-- Leaf/Nature Icon -->
                            <button type="button" @click="editCategory.icon = 'leaf'" 
                                :class="editCategory.icon === 'leaf' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="leaf" class="w-4 h-4"></i>
                            </button>
                            <!-- Building Icon -->
                            <button type="button" @click="editCategory.icon = 'building-2'" 
                                :class="editCategory.icon === 'building-2' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </button>
                            <!-- Plus Icon -->
                            <button type="button" @click="editCategory.icon = 'award'" 
                                :class="editCategory.icon === 'award' ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-dashed border-gray-200 transition-all">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                        <button type="submit" 
                            class="flex-1 bg-primary hover:bg-primary-hover text-white font-bold py-4 px-6 rounded-2xl flex items-center justify-center gap-2 transition-all">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                        </button>
                        <button type="button" @click="showEditModal = false"
                            class="bg-[#E5E7EB] hover:bg-[#D1D5DB] text-gray-700 font-bold py-4 px-6 rounded-2xl transition-all">
                            Cancel
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    </template>
    </template>

</div>
@endsection
