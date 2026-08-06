@extends('layouts.admin')

@section('admin_title', 'Durations')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    editItem: { id: '', name: '', nights: 0, status: '' }
}">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80 block mb-1">Settings / Platform Preferences</span>
            </div>
            <h2 class="text-3xl font-black text-foreground tracking-tight pl-9">Duration Management</h2>
        </div>
        <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-6 py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
            <i data-lucide="plus" size="18"></i> Add Duration
        </button>
    </div>

    {{-- ===== STATS / METRICS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Card 1: Total Durations --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <i data-lucide="bar-chart-2" size="20"></i>
                </div>
                {{-- Decorative clock wheel bg element --}}
                <div class="absolute -right-4 -top-4 text-gray-50 opacity-10 pointer-events-none">
                    <i data-lucide="clock" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Total Durations</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-foreground">{{ $totalDurations }}</span>
                    <span class="text-xs font-black text-red-500">+3 this month</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Active Durations --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-600">
                    <i data-lucide="check-circle-2" size="20"></i>
                </div>
                {{-- Decorative check bg element --}}
                <div class="absolute -right-4 -top-4 text-gray-50 opacity-10 pointer-events-none">
                    <i data-lucide="check-circle" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Active Durations</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-foreground">{{ $activeDurations }}</span>
                    <span class="text-[9px] font-black bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded-md">90.4%</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Average Length --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-8 flex flex-col justify-between gap-6 hover:shadow-lg transition-shadow relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600">
                    <i data-lucide="activity" size="20"></i>
                </div>
                {{-- Decorative trend bg element --}}
                <div class="absolute -right-4 -top-4 text-gray-50 opacity-10 pointer-events-none">
                    <i data-lucide="trending-up" size="120"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-black text-muted-text uppercase tracking-widest">Avg. Length</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-foreground">{{ $avgLength }}</span>
                    <span class="text-xs font-black text-muted-text">Days</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        
        {{-- Table Title + Actions --}}
        <div class="px-10 py-6 flex items-center justify-between border-b border-border-soft">
            <h3 class="text-lg font-black text-foreground">Duration List</h3>
            <div class="flex items-center gap-4">
                <button class="flex items-center gap-1.5 text-xs font-black text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="sliders-horizontal" size="14"></i> Filter
                </button>
                <button class="flex items-center gap-1.5 text-xs font-black text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="download" size="14"></i> Export
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest w-24">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest min-w-[280px]">DURATION NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($durations as $index => $item)
                        @php
                            $srNo = str_pad($durations->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                            // Determine a nice badge color (e.g. orange vs grey depending on the duration length)
                            $days = $item->nights + 1;
                            $badgeBg = $days >= 15 ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500';
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            {{-- SR NO --}}
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- Duration Name with Badge --}}
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs shrink-0 {{ $badgeBg }}">
                                        {{ $days }}
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $item->name }}</span>
                                </div>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/settings/preferences/durations/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" class="sr-only peer" {{ $item->status === 'Active' ? 'checked' : '' }} disabled>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06] group-hover/toggle:opacity-80 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $item->status === 'Active' ? 'text-green-600' : 'text-gray-400' }}">{{ $item->status }}</span>
                                </a>
                            </td>

                            {{-- Actions --}}
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', nights: '{{ $item->nights }}', status: '{{ $item->status }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/durations/delete/' . $item->id) }}" 
                                        onclick="return confirm('Delete this duration configuration?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center">
                                        <i data-lucide="clock" size="28" class="text-primary opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No durations yet</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "Add Duration" to create one</p>
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
            <p class="text-sm font-bold text-muted-text">
                Showing {{ $durations->firstItem() ?? 0 }} to {{ $durations->lastItem() ?? 0 }} of {{ $durations->total() }} entries
            </p>
            <div class="flex items-center gap-2">
                @if($durations->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-left" size="20"></i>
                    </button>
                @else
                    <a href="{{ $durations->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
                        <i data-lucide="chevron-left" size="20"></i>
                    </a>
                @endif
                @foreach(range(1, $durations->lastPage()) as $i)
                    @if($i == 1 || $i == $durations->lastPage() || abs($i - $durations->currentPage()) <= 1)
                        @if($i == $durations->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">{{ $i }}</button>
                        @else
                            <a href="{{ $durations->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black text-muted-text hover:bg-white hover:text-primary flex items-center justify-center transition-all">{{ $i }}</a>
                        @endif
                    @elseif($i == 2 || $i == $durations->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                @if($durations->hasMorePages())
                    <a href="{{ $durations->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
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
    <template x-teleport="body">
    <template x-teleport="body">
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-2xl font-black text-foreground">Add Duration</h3>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="24"></i></button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/durations/store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Duration Name --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Duration Name</label>
                    <input required type="text" name="name" placeholder="e.g. 05 Days / 04 Nights" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                </div>

                {{-- Nights --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Number of Nights</label>
                    <input required type="number" name="nights" placeholder="e.g. 4" min="0" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
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
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save</button>
                </div>
            </form>
        </div>
    </div>
    </template>
    </template>

    {{-- ===== EDIT MODAL ===== --}}
    <template x-teleport="body">
    <template x-teleport="body">
    <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-2xl font-black text-foreground">Edit Duration</h3>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="24"></i></button>
            </div>
            
            <form action="{{ url('/admin/settings/preferences/durations/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                
                {{-- Duration Name --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Duration Name</label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
                </div>

                {{-- Nights --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted-text pl-1">Number of Nights</label>
                    <input required type="number" name="nights" x-model="editItem.nights" min="0" class="w-full bg-[#FFF5F2]/40 border border-border-soft rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground" />
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
    </template>
    </template>
</div>
@endsection
