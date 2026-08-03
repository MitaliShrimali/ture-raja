@extends('layouts.admin')

@section('admin_title', 'Offer Stickers')

@section('content')
<div class="space-y-8 pb-12" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    addPreviewUrl: '', 
    editPreviewUrl: '', 
    editPkg: { id: '', title: '', subtitle: '', link: '/discover', bg_color: '', status: 'Live', image: '' }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight" style="font-size:30px;">Offer Stickers</h2>
            <p class="text-muted-text font-medium">Manage the promotional sticker banners displayed in the homepage offer section.</p>
        </div>
        <button @click="showAddModal = true; addPreviewUrl = ''" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group shrink-0">
            <i data-lucide="plus" size="20" class="group-hover:scale-110 transition-transform"></i> Add Sticker
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex flex-col justify-center relative overflow-hidden">
            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-2 relative z-10">Live Stickers</p>
            <h3 class="text-3xl font-black text-foreground relative z-10">{{ $stickers->where('status', 'Live')->count() }}</h3>
            <div class="absolute bottom-0 left-6 right-6 h-1 bg-primary/20 rounded-t-full"></div>
            <div class="absolute bottom-0 left-6 w-1/3 h-1 bg-primary rounded-t-full"></div>
        </div>
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex flex-col justify-center">
            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-2">Total Listed</p>
            <h3 class="text-3xl font-black text-foreground">{{ $stickers->total() }}</h3>
        </div>
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-border-soft flex items-center justify-between cursor-pointer hover:border-primary/30 transition-all group" @click="showAddModal = true; addPreviewUrl = ''">
            <div>
                <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-1">New Record</p>
                <h3 class="text-lg font-black text-foreground group-hover:text-primary transition-colors">Add Sticker</h3>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl font-medium text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Main Table Area -->
    <div class="bg-white rounded-[32px] shadow-sm border border-border-soft overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-border-soft/50">
            <div class="flex items-center gap-2 bg-gray-50/80 p-1.5 rounded-full">
                <button class="px-5 py-2 text-sm font-bold bg-primary text-white rounded-full shadow-sm">Active Stickers</button>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Preview</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Title</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Subtitle</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Link</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black text-muted-text uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft/40">
                    @forelse($stickers as $sticker)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                @if($sticker->image)
                                    <img src="{{ asset($sticker->image) }}" class="w-20 h-14 rounded-xl object-cover border border-border-soft" alt="{{ $sticker->title }}">
                                @else
                                    <div class="w-20 h-14 rounded-xl flex items-center justify-center bg-gray-100 text-muted-text">
                                        <i data-lucide="image" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-black text-foreground text-sm">{{ $sticker->title }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-muted-text">{{ $sticker->subtitle ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-muted-text truncate max-w-[140px]">{{ $sticker->link ?? '/discover' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="/admin/offer-stickers/toggle/{{ $sticker->id }}"
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold transition-all {{ $sticker->status === 'Live' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sticker->status === 'Live' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                    {{ $sticker->status }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="showEditModal = true; showAddModal = false; editPreviewUrl = ''; editPkg = {
                                            id: '{{ $sticker->id }}',
                                            title: '{{ addslashes($sticker->title) }}',
                                            subtitle: '{{ addslashes($sticker->subtitle ?? '') }}',
                                            link: '{{ addslashes($sticker->link ?? '/discover') }}',
                                            status: '{{ $sticker->status }}',
                                            bg_color: '{{ $sticker->bg_color ?? '' }}',
                                            image: '{{ $sticker->image }}'
                                        }"
                                        class="p-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <a href="/admin/offer-stickers/delete/{{ $sticker->id }}"
                                       onclick="return confirm('Delete this sticker?')"
                                       class="p-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 transition-colors" title="Delete">
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-muted-text">
                                        <i data-lucide="image" class="w-8 h-8"></i>
                                    </div>
                                    <p class="font-black text-foreground">No Offer Stickers Yet</p>
                                    <p class="text-sm text-muted-text">Click "Add Sticker" to create your first promotional banner.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stickers->hasPages())
            <div class="p-6 border-t border-border-soft/50">
                {{ $stickers->links() }}
            </div>
        @endif
    </div>

    <!-- ================= MODALS ================= -->
    <div x-show="showAddModal || showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
         style="display:none;">

        <!-- Popup Box -->
        <div x-show="showAddModal || showEditModal"
             @click.away="showAddModal = false; showEditModal = false; addPreviewUrl = ''; editPreviewUrl = ''; editPkg = {}"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
             class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">

            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground" x-text="showAddModal ? 'Add Offer Sticker' : 'Edit Sticker'"></h3>
                    <p class="text-xs text-muted-text font-medium">Appears in the homepage offer section slider.</p>
                </div>
                <button @click="showAddModal = false; showEditModal = false; addPreviewUrl = ''; editPreviewUrl = ''; editPkg = {}"
                        class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form :action="showAddModal ? '/admin/offer-stickers/store' : '/admin/offer-stickers/update'"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                <!-- Hidden ID for edit -->
                <template x-if="showEditModal">
                    <input type="hidden" name="id" :value="editPkg.id">
                </template>

                <!-- Image Upload with Preview -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Sticker Image</label>
                    <div class="w-full h-44 bg-gray-50 border-2 border-dashed border-border-soft rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-primary/50 transition-colors group relative overflow-hidden">
                        <input type="file" name="image" accept="image/*"
                            @change="showAddModal ? addPreviewUrl = URL.createObjectURL($event.target.files[0]) : editPreviewUrl = URL.createObjectURL($event.target.files[0])"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <!-- Preview -->
                        <template x-if="showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image)">
                            <img :src="showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image)"
                                 class="absolute inset-0 w-full h-full object-cover z-10 rounded-3xl">
                        </template>

                        <div class="flex flex-col items-center gap-2 z-0 relative"
                             x-show="!(showAddModal ? addPreviewUrl : (editPreviewUrl || editPkg.image))">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-primary">
                                <i data-lucide="upload" class="w-6 h-6"></i>
                            </div>
                            <span class="text-sm font-bold text-muted-text group-hover:text-primary">Upload Sticker Image</span>
                            <span class="text-[10px] text-muted-text">JPG, PNG, WEBP — displayed in fixed card size</span>
                        </div>
                    </div>
                </div>

                <!-- 2-col grid for title + subtitle -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Title <span class="text-primary">*</span></label>
                        <input type="text" name="title" :value="editPkg.title ?? ''" required
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm"
                               placeholder="e.g. Buy 1, Get 1 Free">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Subtitle / Label</label>
                        <input type="text" name="subtitle" :value="editPkg.subtitle ?? ''"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm"
                               placeholder="e.g. Limited Offers">
                    </div>
                </div>

                <!-- Link URL -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Link URL</label>
                    <input type="text" name="link" :value="editPkg.link ?? '/discover'"
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm"
                           placeholder="/discover">
                </div>

                <!-- 2-col grid for color + status -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Title Text Color</label>
                        <select name="bg_color"
                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="" :selected="!editPkg.bg_color || editPkg.bg_color === ''">Default (Dark)</option>
                            <option value="#FFFFFF" :selected="editPkg.bg_color === '#FFFFFF'">White</option>
                            <option value="#111827" :selected="editPkg.bg_color === '#111827'">Black</option>
                            <option value="#FCE08F" :selected="editPkg.bg_color === '#FCE08F'">Yellow</option>
                            <option value="#FFC0CB" :selected="editPkg.bg_color === '#FFC0CB'">Pink</option>
                            <option value="#BFDBFE" :selected="editPkg.bg_color === '#BFDBFE'">Light Blue</option>
                            <option value="#BBF7D0" :selected="editPkg.bg_color === '#BBF7D0'">Light Green</option>
                            <option value="#3b82f6" :selected="editPkg.bg_color === '#3b82f6'">Blue</option>
                            <option value="#E85D26" :selected="editPkg.bg_color === '#E85D26'">Orange</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status"
                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Live" :selected="editPkg.status === 'Live' || showAddModal">Live</option>
                            <option value="Drafting" :selected="editPkg.status === 'Drafting'">Drafting</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button"
                            @click="showAddModal = false; showEditModal = false; addPreviewUrl = ''; editPreviewUrl = ''; editPkg = {}"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
                        Save Sticker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
