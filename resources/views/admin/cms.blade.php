@extends('layouts.admin')

@section('admin_title', 'Cms')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Content Management</h2>
            <p class="text-muted-text font-medium">Edit and manage all static pages and system content.</p>
        </div>
    </div>

    <!-- Page Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Platform Pages</h3>
            <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
                <i data-lucide="plus" size="18"></i> Add New Page
            </button>
        </div>
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PAGE TITLE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SLUG / URL</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">LAST UPDATED</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($pages as $page)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-muted-text">
                                        <i data-lucide="layout" size="14"></i>
                                    </div>
                                    <span class="text-sm font-bold text-foreground">{{ $page->title }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <code class="bg-gray-50 px-3 py-1 rounded-lg text-[10px] font-black text-primary">{{ $page->slug }}</code>
                            </td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ \Carbon\Carbon::parse($page->updated_at)->diffForHumans() }}</td>
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/cms/toggle/' . $page->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $page->status === 'Published' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $page->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="{{ url('/admin/cms/edit/' . $page->id) }}" 
                                        class="p-2 text-muted-text hover:text-primary transition-colors"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No active pages created.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $pages->firstItem() ?? 0 }} to {{ $pages->lastItem() ?? 0 }} of {{ $pages->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($pages->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $pages->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $pages->lastPage()) as $i)
                    @if($i == 1 || $i == $pages->lastPage() || abs($i - $pages->currentPage()) <= 1)
                        @if($i == $pages->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $pages->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $pages->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($pages->hasMorePages())
                    <a href="{{ $pages->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== ADD MODAL ===== --}}
    <template x-teleport="body">
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-2xl w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-2xl font-black text-foreground">Add New Page</h3>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="24"></i></button>
            </div>
            
            <form action="{{ url('/admin/cms/store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Page Title<span class="text-primary">*</span></label>
                        <input required type="text" name="title" placeholder="e.g., Anti-Fraud Policy" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">URL Slug<span class="text-primary">*</span></label>
                        <input required type="text" name="slug" placeholder="e.g., anti-fraud-policy" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm appearance-none">
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Content (Basic)</label>
                    <textarea name="content" rows="4" placeholder="Initial content... You can format this later using the Edit button." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Create Page</button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
