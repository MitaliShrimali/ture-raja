@extends('layouts.admin')

@section('admin_title', 'Ads')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, addPosition: 'Home Hero', editAd: { id: '', campaign_name: '', position: '', image: '', link: '', status: '', subtitle: '', agent_id: '' } }">
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Pages</span>
            <span class="opacity-40">/</span>
            <span class="text-primary">Dashboard</span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="font-black text-foreground tracking-tight">Advertisement Campaigns</h2>
                <p class="text-muted-text font-medium">Monitor, track, and optimize your global advertising reach.</p>
            </div>
            <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
                <i data-lucide="plus" size="20"></i> Create New Ad
            </button>
        </div>
    </div>

    <!-- Active Ads Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h3 class="text-xl font-black">Active Advertisements</h3>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">CAMPAIGN TITLE & LINK</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER PREVIEW</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">POSITION</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">CLICKS / VIEWS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($ads as $index => $ad)
                        @php
                            $srNo = str_pad($ads->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-8">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $ad->campaign_name }}</p>
                                    <p class="text-[10px] font-bold text-muted-text uppercase tracking-tighter">URL: {{ $ad->link }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="w-24 h-12 rounded-xl overflow-hidden border border-border-soft bg-gray-100">
                                    <img src="{{ asset($ad->image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=200') }}" alt="Preview" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-bold text-muted-text">{{ $ad->position }}</td>
                            <td class="py-6 px-8 text-xs font-bold text-muted-text">
                                <span class="text-foreground font-black">{{ $ad->clicks }}</span> Clicks / <span class="text-foreground font-black">{{ $ad->views }}</span> Views
                            </td>
                            <td class="py-6 px-8">
                                <a href="{{ url('/admin/ads/toggle/' . $ad->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full 
                                        {{ $ad->status === 'Active' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100' }} 
                                        text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $ad->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button 
                                        @click="showEditModal = true; editAd = { id: '{{ $ad->id }}', campaign_name: '{{ addslashes($ad->campaign_name) }}', position: '{{ addslashes($ad->position) }}', image: '{{ addslashes($ad->image) }}', link: '{{ addslashes($ad->link) }}', status: '{{ $ad->status }}', subtitle: '{{ addslashes($ad->subtitle ?? '') }}', agent_id: '{{ $ad->agent_id ?? '' }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/ads/delete/' . $ad->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this ad campaign?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No ad campaigns registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $ads->firstItem() ?? 0 }} to {{ $ads->lastItem() ?? 0 }} of {{ $ads->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($ads->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $ads->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $ads->lastPage()) as $i)
                    @if($i == 1 || $i == $ads->lastPage() || abs($i - $ads->currentPage()) <= 1)
                        @if($i == $ads->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $ads->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $ads->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($ads->hasMorePages())
                    <a href="{{ $ads->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Ad Modal -->
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
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-3xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between border-b border-border-soft p-6 md:px-10 md:pt-10 md:pb-6 shrink-0">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Create New Campaign</h3>
                    <p class="text-xs text-muted-text font-medium">Log a new premium marketing advertisement.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 md:px-10 md:pb-10 md:pt-6 custom-scroll">
                <form action="{{ url('/admin/ads/store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    
                    <!-- Full width image upload zone -->
                    <div class="col-span-1 md:col-span-2 space-y-2" x-data="{ imagePreview: null }">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Image Upload <span class="text-primary">*</span></label>
                        <div class="relative w-full border-2 border-dashed border-border-soft hover:border-primary/50 rounded-2xl p-8 transition-colors bg-gray-50 flex flex-col items-center justify-center gap-3 text-center cursor-pointer group overflow-hidden" :class="imagePreview ? 'border-primary/50' : ''">
                            
                            <!-- Preview Image -->
                            <img x-show="imagePreview" :src="imagePreview" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity" style="display: none;" />
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform relative z-10">
                                <i data-lucide="upload-cloud" size="24"></i>
                            </div>
                            <input required type="file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                                   @change="if($event.target.files.length) imagePreview = URL.createObjectURL($event.target.files[0]); else imagePreview = null" />
                            <div class="relative z-10">
                                <span class="text-sm font-bold text-foreground block" x-text="imagePreview ? 'Click or drag to replace image' : 'Click or drag image to upload'"></span>
                                <p class="text-[10px] text-muted-text font-bold mt-1 flex items-center justify-center gap-1">
                                    <i data-lucide="image" size="12"></i> Max size limit: 
                                    <span x-text="addPosition === 'Home Hero' ? '1920x600px' : '1200x200px'"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Campaign Name<span class="text-primary">*</span></label>
                        <input required type="text" name="campaign_name" placeholder="E.g. Summer Expedition Promo" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Placement Position</label>
                        <select name="position" x-model="addPosition" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Home Hero">Home Hero</option>
                            <option value="Package Sidebar">Package Sidebar</option>
                            <option value="Footer Banner">Footer Banner</option>
                            <option value="Under Domestic Packages">Under Domestic Packages</option>
                        </select>
                    </div>
                    
                    <!-- Conditional fields for Under Domestic Packages -->
                    <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in" x-show="addPosition === 'Under Domestic Packages'" style="display: none;">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Subtitle / Slogan</label>
                            <input type="text" name="subtitle" placeholder="E.g. Stop Searching, Start Traveling" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Select Agent (For Logo display)</label>
                            <select name="agent_id" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                                <option value="">-- No Agent Logo --</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Click Target URL</label>
                        <input type="text" name="link" placeholder="E.g. /discover" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Paused">Paused</option>
                        </select>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-4 pt-4 mt-2 border-t border-border-soft">
                        <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Campaign</button>
                    </div>
                </form>
            </div>
    </div>
    </template>

    <!-- Edit Ad Modal -->
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
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-3xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between border-b border-border-soft p-6 md:px-10 md:pt-10 md:pb-6 shrink-0">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Campaign</h3>
                    <p class="text-xs text-muted-text font-medium">Update advertisement details.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 md:px-10 md:pb-10 md:pt-6 custom-scroll">
                <form action="{{ url('/admin/ads/update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <input type="hidden" name="id" x-model="editAd.id">
                    
                    <!-- Full width image upload zone -->
                    <div class="col-span-1 md:col-span-2 space-y-2" x-data="{ editPreview: null }">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Image Upload</label>
                        <div class="relative w-full border-2 border-dashed border-border-soft hover:border-primary/50 rounded-2xl p-8 transition-colors bg-gray-50 flex flex-col items-center justify-center gap-3 text-center cursor-pointer group overflow-hidden" :class="(editPreview || editAd.image) ? 'border-primary/50' : ''">
                            
                            <!-- Preview Image -->
                            <img x-show="editPreview || editAd.image" :src="editPreview || editAd.image" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity" style="display: none;" />
                            
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform relative z-10">
                                <i data-lucide="upload-cloud" size="24"></i>
                            </div>
                            <input type="file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                                   @change="if($event.target.files.length) editPreview = URL.createObjectURL($event.target.files[0]); else editPreview = null" />
                            <div class="relative z-10">
                                <span class="text-sm font-bold text-foreground block" x-text="(editPreview || editAd.image) ? 'Click or drag to replace image' : 'Click or drag image to upload'"></span>
                                <p class="text-[10px] text-muted-text font-bold mt-1 flex items-center justify-center gap-1">
                                    <i data-lucide="image" size="12"></i> Max size limit: 
                                    <span x-text="editAd.position === 'Home Hero' ? '1920x600px' : (editAd.position === 'Package Sidebar' ? '800x800px' : '1200x200px')"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Campaign Name<span class="text-primary">*</span></label>
                        <input required type="text" name="campaign_name" x-model="editAd.campaign_name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Placement Position</label>
                        <select name="position" x-model="editAd.position" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Home Hero">Home Hero</option>
                            <option value="Package Sidebar">Package Sidebar</option>
                            <option value="Footer Banner">Footer Banner</option>
                            <option value="Under Domestic Packages">Under Domestic Packages</option>
                        </select>
                    </div>

                    <!-- Conditional fields for Under Domestic Packages -->
                    <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in" x-show="editAd.position === 'Under Domestic Packages'" style="display: none;">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Subtitle / Slogan</label>
                            <input type="text" name="subtitle" x-model="editAd.subtitle" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Select Agent (For Logo display)</label>
                            <select name="agent_id" x-model="editAd.agent_id" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                                <option value="">-- No Agent Logo --</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Ad Click Target URL</label>
                        <input type="text" name="link" x-model="editAd.link" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" x-model="editAd.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Paused">Paused</option>
                        </select>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-4 pt-4 mt-2 border-t border-border-soft">
                        <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Update Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>
</div>
@endsection
