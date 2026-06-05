@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, viewType: 'desktop', editBanner: { id: '', title: '', subtitle: '', image: '', link: '', status: '' }, newBannerPreview: null, newBannerIsVideo: false, editBannerPreview: null, editBannerIsVideo: false }">
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Management</span>
            <span class="opacity-40">/</span>
            <span class="text-primary">Home Banners</span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="font-black text-foreground tracking-tight">Manage Banners</h2>
                <p class="text-muted-text font-medium max-w-2xl">
                    Curate the first impression for your travelers. High-impact banners drive 40% more conversions.
                </p>
            </div>
            <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                <i data-lucide="plus" size="20"></i> Add New Banner
            </button>
        </div>
    </div>

    <!-- Background Music Section -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden p-8 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="space-y-1">
                <h3 class="text-xl font-black text-foreground">Background Music</h3>
                <p class="text-xs text-muted-text font-medium">Upload a global background music file (.mp3) for the home page hero section.</p>
            </div>
        </div>
        <form action="{{ url('/admin/home-editor/upload-music') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-start md:items-center gap-4">
            @csrf
            <input type="file" name="music_file" accept=".mp3,audio/mpeg" required class="block w-full text-sm text-muted-text file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-primary/20 whitespace-nowrap">
                Upload Music
            </button>
        </form>
    </div>

    <!-- Banner List -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
<!-- <tr>
    <th class="py-6 px-4"></th>
    <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER IMAGE</th>
    <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DESCRIPTION & MARKETING</th>
    <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET LINK</th>
    <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
    <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
</tr> -->
                    <tr class="bg-gray-50/50 border-b border-border-soft">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER IMAGE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DESCRIPTION & MARKETING</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET LINK</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    <tbody id="bannersSortable" class="divide-y divide-border-soft" data-url="{{ url('/admin/home-editor/reorder') }}">
@forelse($banners as $banner)
                        <tr class="group hover:bg-gray-50/30 transition-colors" data-id="{{ $banner->id }}">
    <!-- <td class="py-8 px-4 cursor-move" title="Drag to reorder"><i data-lucide="move"></i></td> -->
                            <td class="py-8 px-10">
                                <div class="w-40 h-24 rounded-2xl overflow-hidden border border-border-soft bg-gray-100 group-hover:scale-105 transition-transform duration-500">
                                    @if (Str::endsWith(strtolower($banner->image ?? ''), '.mp4'))
    <video autoplay loop muted playsinline class="w-full h-full object-cover">
        <source src="{{ $banner->image }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
@else
    <img src="{{ $banner->image ?? 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&q=80&w=400' }}" alt="Banner" class="w-full h-full object-cover">
@endif
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <div class="max-w-xs space-y-1">
                                    <p class="text-sm font-black text-foreground leading-tight">{{ $banner->title }}</p>
                                    <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest opacity-60">{{ $banner->subtitle }}</p>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full w-fit">
                                    <i data-lucide="external-link" size="12"></i>
                                    <span class="text-[10px] font-black tracking-tighter uppercase">{{ $banner->link }}</span>
                                </div>
                            </td>
                            <td class="py-8 px-10">
                                <a href="{{ url('/admin/home-editor/toggle/' . $banner->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $banner->status === 'Active' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $banner->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-8 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editBanner = { id: '{{ $banner->id }}', title: '{{ addslashes($banner->title) }}', subtitle: '{{ addslashes($banner->subtitle) }}', image: '{{ addslashes($banner->image) }}', link: '{{ addslashes($banner->link) }}', status: '{{ $banner->status }}' }"
                                        class="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-2xl transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/home-editor/delete/' . $banner->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this marketing banner?');"
                                        class="p-3 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No active marketing banners registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $banners->firstItem() ?? 0 }} to {{ $banners->lastItem() ?? 0 }} of {{ $banners->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($banners->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $banners->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $banners->lastPage()) as $i)
                    @if($i == 1 || $i == $banners->lastPage() || abs($i - $banners->currentPage()) <= 1)
                        @if($i == $banners->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $banners->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $banners->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($banners->hasMorePages())
                    <a href="{{ $banners->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- Live Preview Component Simulation -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 bg-white rounded-[40px] shadow-premium border border-border-soft p-10 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                        <i data-lucide="layout" size="22"></i>
                    </div>
                    <h3 class="text-2xl font-black text-foreground tracking-tight">Live Preview</h3>
                </div>
                <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-xl">
                    <button type="button" @click="viewType = 'desktop'" :class="viewType === 'desktop' ? 'bg-white rounded-lg shadow-sm text-primary' : 'text-muted-text hover:text-foreground'" class="p-2 transition-all"><i data-lucide="monitor" size="18"></i></button>
                    <button type="button" @click="viewType = 'mobile'" :class="viewType === 'mobile' ? 'bg-white rounded-lg shadow-sm text-primary' : 'text-muted-text hover:text-foreground'" class="p-2 transition-all"><i data-lucide="smartphone" size="18"></i></button>
                </div>
            </div>

            @if($banners->count() > 0)
                <div class="flex justify-center transition-all duration-300">
                    <div :class="viewType === 'mobile' ? 'w-[360px] aspect-[9/16]' : 'w-full aspect-[16/7]'" class="relative rounded-[32px] overflow-hidden shadow-2xl transition-all duration-300">
                        @if (isset($banners[0]) && Str::endsWith(strtolower($banners[0]->image ?? ''), '.mp4'))
    <video autoplay loop muted playsinline class="w-full h-full object-cover">
        <source src="{{ $banners[0]->image }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
@else
    <img src="{{ $banners[0]->image }}" alt="Preview" class="w-full h-full object-cover">
@endif
                        <div class="absolute inset-0 bg-black/40 flex items-center px-8 md:px-16">
                            <div class="max-w-md space-y-6">
                                <span class="bg-primary px-4 py-1.5 rounded-full text-[10px] font-black text-white uppercase tracking-widest">Active Slide</span>
                                <h2 :class="viewType === 'mobile' ? 'text-2xl' : 'text-4xl md:text-5xl'" class="font-black text-white leading-tight">{{ $banners[0]->title }}</h2>
                                <p class="text-white/80 font-medium text-sm">{{ $banners[0]->subtitle }}</p>
                                <a href="{{ $banners[0]->link }}" class="inline-block bg-foreground text-white px-6 py-3 md:px-8 md:py-4 rounded-2xl font-black text-xs md:text-sm uppercase tracking-widest hover:bg-primary transition-all">
                                    Explore
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="relative rounded-[32px] overflow-hidden aspect-[16/7] shadow-2xl bg-gray-100 flex items-center justify-center">
                    <p class="text-muted-text font-bold">No active banners for preview.</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 bg-primary p-10 rounded-[40px] shadow-2xl text-white relative overflow-hidden flex flex-col justify-between">
            <div class="space-y-6 relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                    <i data-lucide="layout" size="28" class="text-white"></i>
                </div>
                <h4 class="text-3xl font-black leading-tight">Optimization Tips</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">1</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Use high-contrast imagery for better visibility on all devices.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">2</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Keep primary marketing text under 60 characters for readability.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">3</div>
                        <p class="text-sm font-medium text-white/80 leading-relaxed">Update banners every 14 days to prevent audience fatigue.</p>
                    </li>
                </ul>
            </div>
            <div class="absolute right-0 bottom-0 w-2/3 h-2/3 opacity-20 translate-x-1/4 translate-y-1/4">
                 <i data-lucide="monitor" style="width: 300px; height: 300px;"></i>
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Banner Modal -->
    <template x-teleport="body">
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
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-border-soft p-6 md:p-8 shrink-0">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Add Marketing Banner</h3>
                    <p class="text-xs text-muted-text font-medium">Create a new high-impact slideshow banner.</p>
                </div>
                <button type="button" @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="p-6 md:p-8 overflow-y-auto">
                <form action="{{ url('/admin/home-editor/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Media Upload Area -->
                    <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-300 p-2 flex flex-col items-center justify-center relative overflow-hidden group hover:border-primary/50 transition-colors w-full h-40 shrink-0">
                        <template x-if="!newBannerPreview">
                            <div class="flex flex-col items-center justify-center text-center space-y-2">
                                <i data-lucide="image-plus" size="28" class="text-gray-400 group-hover:text-primary/50 transition-colors"></i>
                                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Upload Media</p>
                            </div>
                        </template>
                        <template x-if="newBannerPreview">
                            <div class="w-full h-full absolute inset-0">
                                <template x-if="newBannerIsVideo">
                                    <video :src="newBannerPreview" autoplay loop muted class="w-full h-full object-cover"></video>
                                </template>
                                <template x-if="!newBannerIsVideo">
                                    <img :src="newBannerPreview" class="w-full h-full object-cover" />
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <p class="text-white font-black text-sm uppercase tracking-widest bg-black/50 px-4 py-2 rounded-xl backdrop-blur-sm">Change Media</p>
                                </div>
                            </div>
                        </template>
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/*,video/mp4"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="
                                const file = $event.target.files[0]; 
                                if(file) { 
                                    newBannerPreview = URL.createObjectURL(file); 
                                    newBannerIsVideo = file.type.includes('video');
                                }
                            "
                        >
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-muted-text font-bold uppercase tracking-widest">OR USE EXTERNAL URL</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Banner Title<span class="text-primary">*</span></label>
                        <input required type="text" name="title" placeholder="E.g. Get Up to 20% OFF on south India" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Image URL</label>
                        <input type="text" name="image" placeholder="E.g. https://images.unsplash.com/..." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Target Link URL</label>
                        <input type="text" name="link" placeholder="E.g. /packages/south-india" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                        <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Banner Modal -->
    <template x-teleport="body">
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
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-border-soft p-6 md:p-8 shrink-0">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Banner</h3>
                    <p class="text-xs text-muted-text font-medium">Modify slide image and target destination.</p>
                </div>
                <button type="button" @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="p-6 md:p-8 overflow-y-auto">
                <form action="{{ url('/admin/home-editor/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id" x-model="editBanner.id" />
                    
                    <!-- Media Upload Area -->
                    <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-300 p-2 flex flex-col items-center justify-center relative overflow-hidden group hover:border-primary/50 transition-colors w-full h-40 shrink-0">
                        <template x-if="!editBannerPreview && !editBanner.image">
                            <div class="flex flex-col items-center justify-center text-center space-y-2">
                                <i data-lucide="image-plus" size="28" class="text-gray-400 group-hover:text-primary/50 transition-colors"></i>
                                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Upload Media</p>
                            </div>
                        </template>
                        <template x-if="editBannerPreview || editBanner.image">
                            <div class="w-full h-full absolute inset-0">
                                <template x-if="editBannerPreview && editBannerIsVideo">
                                    <video :src="editBannerPreview" autoplay loop muted class="w-full h-full object-cover"></video>
                                </template>
                                <template x-if="editBannerPreview && !editBannerIsVideo">
                                    <img :src="editBannerPreview" class="w-full h-full object-cover" />
                                </template>
                                <template x-if="!editBannerPreview && editBanner.image">
                                    <template x-if="editBanner.image.endsWith('.mp4')">
                                        <video :src="'{{ asset('') }}' + editBanner.image" autoplay loop muted class="w-full h-full object-cover"></video>
                                    </template>
                                </template>
                                <template x-if="!editBannerPreview && editBanner.image">
                                    <template x-if="!editBanner.image.endsWith('.mp4')">
                                        <img :src="editBanner.image.startsWith('http') ? editBanner.image : '{{ asset('') }}' + editBanner.image" class="w-full h-full object-cover" />
                                    </template>
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <p class="text-white font-black text-sm uppercase tracking-widest bg-black/50 px-4 py-2 rounded-xl backdrop-blur-sm">Change Media</p>
                                </div>
                            </div>
                        </template>
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/*,video/mp4"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="
                                const file = $event.target.files[0]; 
                                if(file) { 
                                    editBannerPreview = URL.createObjectURL(file); 
                                    editBannerIsVideo = file.type.includes('video');
                                }
                            "
                        >
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-muted-text font-bold uppercase tracking-widest">OR USE EXTERNAL URL</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Banner Title<span class="text-primary">*</span></label>
                        <input required type="text" name="title" x-model="editBanner.title" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Image URL</label>
                        <input type="text" name="image" x-model="editBanner.image" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Target Link URL</label>
                        <input type="text" name="link" x-model="editBanner.link" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" x-model="editBanner.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                        <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Update Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
