@extends('layouts.admin')

@section('admin_title', 'Cms')

@section('content')
<div class="space-y-10 pb-12" x-data>
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
</div>
@endsection
