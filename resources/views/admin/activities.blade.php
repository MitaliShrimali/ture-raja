@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{
    showAddModal: false,
    showEditModal: false,
    addActivityIcon: 'mountain',
    editItem: { id: '', name: '', icon: '', intensity: '', price: '', status: '' }
}">

    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ url('admin/settings/preferences') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] opacity-80 block mb-1">Settings / Platform Preferences</span>
            </div>
            <h2 class="font-black text-foreground tracking-tight pl-9">Activities</h2>
            <p class="text-muted-text font-medium text-sm pl-9">Manage experiential travel activities and adventure sports.</p>
        </div>
        <button @click="showAddModal = true; addActivityIcon = 'mountain';" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add Activity
        </button>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Activities --}}
        <a href="{{ url('/admin/settings/preferences/activities') }}" class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex flex-col gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer group">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <i data-lucide="rocket" size="18" class="text-primary"></i>
                </div>
                <span class="text-[10px] font-black text-green-500 bg-green-50 px-2 py-1 rounded-full">+12% VS LY</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Total Activities</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $totalCount }}</p>
            </div>
        </a>

        {{-- Active Listing --}}
        <a href="{{ url('/admin/settings/preferences/activities') }}?status=active" class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex flex-col gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer group">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-green-50 rounded-2xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                    <i data-lucide="trending-up" size="18" class="text-green-500"></i>
                </div>
                <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-full uppercase tracking-wider">Active</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Active Listing</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $activeCount }}</p>
            </div>
        </a>

        {{-- Inactive --}}
        <a href="{{ url('/admin/settings/preferences/activities') }}?status=inactive" class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex flex-col gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer group">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                    <i data-lucide="eye-off" size="18" class="text-muted-text"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 bg-gray-100 px-2 py-1 rounded-full uppercase tracking-wider">Inactive</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">Inactive</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $inactiveCount }}</p>
            </div>
        </a>

        {{-- Generate Insights --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex items-center justify-center hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer group">
            <div class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-all">
                    <i data-lucide="sparkles" size="20" class="text-primary"></i>
                </div>
                <span class="text-sm font-black text-primary">Generate Insights</span>
            </div>
        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">

        {{-- Header Tabs + Actions --}}
        <div class="px-8 pt-6 pb-0 flex items-center justify-between border-b border-border-soft">
            <div class="flex items-center gap-1">
                <a href="{{ url('/admin/settings/preferences/activities') }}" class="flex items-center gap-2 pb-5 px-4 text-sm font-black {{ !request('status') ? 'text-primary border-b-2 border-primary' : 'text-muted-text hover:text-foreground border-b-2 border-transparent' }} transition-all">
                    All Activities
                    <span class="{{ !request('status') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500' }} text-[10px] font-black px-2 py-0.5 rounded-full transition-all">{{ $totalCount }}</span>
                </a>
                <a href="{{ url('/admin/settings/preferences/activities') }}?status=active" class="flex items-center gap-2 pb-5 px-4 text-sm font-black {{ request('status') === 'active' ? 'text-primary border-b-2 border-primary' : 'text-muted-text hover:text-foreground border-b-2 border-transparent' }} transition-all">
                    Active
                    <span class="{{ request('status') === 'active' ? 'bg-primary text-white' : 'bg-green-100 text-green-600' }} text-[10px] font-black px-2 py-0.5 rounded-full transition-all">{{ $activeCount }}</span>
                </a>
                <a href="{{ url('/admin/settings/preferences/activities') }}?status=inactive" class="flex items-center gap-2 pb-5 px-4 text-sm font-black {{ request('status') === 'inactive' ? 'text-primary border-b-2 border-primary' : 'text-muted-text hover:text-foreground border-b-2 border-transparent' }} transition-all">
                    Inactive
                    <span class="{{ request('status') === 'inactive' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500' }} text-[10px] font-black px-2 py-0.5 rounded-full transition-all">{{ $inactiveCount }}</span>
                </a>
            </div>
            <div class="flex items-center gap-2 pb-5">
                <button class="w-9 h-9 rounded-xl border border-border-soft text-muted-text hover:text-primary hover:border-primary/30 flex items-center justify-center transition-all">
                    <i data-lucide="sliders-horizontal" size="16"></i>
                </button>
                <button class="w-9 h-9 rounded-xl border border-border-soft text-muted-text hover:text-primary hover:border-primary/30 flex items-center justify-center transition-all">
                    <i data-lucide="arrow-up-down" size="16"></i>
                </button>
            </div>
        </div>

        {{-- Table Container with Horizontal Scroll Protection --}}
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest w-20">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest min-w-[240px]">ACTIVITY NAME & ICON</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">INTENSITY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($activities as $index => $item)
                        @php
                            $srNo = str_pad($activities->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                            $gradientMap = [
                                'High'   => 'from-red-400 to-orange-500',
                                'Medium' => 'from-amber-400 to-yellow-500',
                                'Low'    => 'from-emerald-400 to-teal-500',
                            ];
                            $grad = $gradientMap[$item->intensity] ?? 'from-primary to-primary-hover';
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            {{-- SR NO --}}
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>

                            {{-- Name + Gradient Thumbnail --}}
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center text-white shadow-md shrink-0">
                                        <i data-lucide="{{ $item->icon }}" size="20"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground leading-tight">{{ $item->name }}</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Lucide: {{ $item->icon }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Intensity Badge --}}
                            <td class="py-6 px-10">
                                @php
                                    $badge = match($item->intensity) {
                                        'High'   => 'bg-red-50 text-red-500',
                                        'Medium' => 'bg-amber-50 text-amber-600',
                                        default  => 'bg-emerald-50 text-emerald-600',
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[11px] font-black {{ $badge }}">
                                    {{ $item->intensity }}
                                </span>
                            </td>

                            {{-- Price --}}
                            <td class="py-6 px-10">
                                <span class="text-sm font-black text-foreground">
                                    @if($item->price && $item->price > 0)
                                        ${{ number_format($item->price, 2) }}
                                    @else
                                        <span class="text-muted-text font-medium">â€”</span>
                                    @endif
                                </span>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="py-6 px-10">
                                <a href="{{ url('/admin/settings/preferences/activities/toggle/' . $item->id) }}" class="inline-flex items-center gap-2 cursor-pointer group/toggle">
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
                                        @click="showEditModal = true; editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', icon: '{{ addslashes($item->icon) }}', intensity: '{{ $item->intensity }}', price: '{{ $item->price ?? 0 }}', status: '{{ $item->status }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/settings/preferences/activities/delete/' . $item->id) }}" 
                                        onclick="return confirm('Delete this activity?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center">
                                        <i data-lucide="mountain" size="28" class="text-primary opacity-40"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">No activities yet</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">Click "Add Activity" to get started</p>
                                    </div>
                                    <button @click="showAddModal = true" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black shadow-lg shadow-primary/20 hover:bg-primary-hover transition-all flex items-center gap-2">
                                        <i data-lucide="plus" size="14"></i> Add First Activity
                                    </button>
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
                Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} entries
            </p>
            <div class="flex items-center gap-2">
                @if($activities->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-left" size="20"></i>
                    </button>
                @else
                    <a href="{{ $activities->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
                        <i data-lucide="chevron-left" size="20"></i>
                    </a>
                @endif
                @foreach(range(1, $activities->lastPage()) as $i)
                    @if($i == 1 || $i == $activities->lastPage() || abs($i - $activities->currentPage()) <= 1)
                        @if($i == $activities->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">{{ $i }}</button>
                        @else
                            <a href="{{ $activities->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black text-muted-text hover:bg-white hover:text-primary flex items-center justify-center transition-all">{{ $i }}</a>
                        @endif
                    @elseif($i == 2 || $i == $activities->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                @if($activities->hasMorePages())
                    <a href="{{ $activities->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors">
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
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Add Activity</h3>
                    <p class="text-xs text-muted-text font-medium">Create a new experiential travel activity.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="20"></i></button>
            </div>
            <form action="{{ url('/admin/settings/preferences/activities/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Activity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Scuba Diving" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Icon</label>
                    <input type="hidden" name="icon" :value="addActivityIcon">
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach([['mountain','Trekking'],['waves','Water Sports'],['bike','Cycling'],['camera','Photography'],['fish','Fishing'],['tent','Camping'],['anchor','Diving'],['trophy','Sports'],['zap','Adventure'],['footprints','Walking'],['wind','Paragliding'],['flame','Extreme']] as [$ic,$lb])
                        <button type="button" @click="addActivityIcon = '{{ $ic }}'"
                            :class="addActivityIcon === '{{ $ic }}' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all" title="{{ $lb }}">
                            <i data-lucide="{{ $ic }}" class="w-4 h-4"></i>
                        </button>
                        @endforeach
                        <button type="button" @click="addActivityIcon = 'custom'"
                            :class="!['mountain','waves','bike','camera','fish','tent','anchor','trophy','zap','footprints','wind','flame'].includes(addActivityIcon) ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg' : 'border-2 border-dashed border-[#F4EBE3] text-[#A8988C] hover:bg-gray-50'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all"><i data-lucide="plus" class="w-4 h-4"></i></button>
                    </div>
                    <div x-show="!['mountain','waves','bike','camera','fish','tent','anchor','trophy','zap','footprints','wind','flame'].includes(addActivityIcon)" class="pt-1" style="display: none;">
                        <input type="text" placeholder="Type Lucide icon name..." x-on:input="if($el.value) addActivityIcon = $el.value" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Intensity</label>
                        <select name="intensity" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Low">ðŸŸ¢ Low</option>
                            <option value="Medium">ðŸŸ¡ Medium</option>
                            <option value="High">ðŸ”´ High</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price ($)</label>
                        <input type="number" step="0.01" min="0" name="price" placeholder="0.00" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Activity</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Activity</h3>
                    <p class="text-xs text-muted-text font-medium">Update activity details and settings.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="x" size="20"></i></button>
            </div>
            <form action="{{ url('/admin/settings/preferences/activities/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editItem.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Activity Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editItem.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Icon</label>
                    <input type="hidden" name="icon" :value="editItem.icon">
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach([['mountain','Trekking'],['waves','Water Sports'],['bike','Cycling'],['camera','Photography'],['fish','Fishing'],['tent','Camping'],['anchor','Diving'],['trophy','Sports'],['zap','Adventure'],['footprints','Walking'],['wind','Paragliding'],['flame','Extreme']] as [$ic,$lb])
                        <button type="button" @click="editItem.icon = '{{ $ic }}'"
                            :class="editItem.icon === '{{ $ic }}' ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg shadow-primary/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all" title="{{ $lb }}">
                            <i data-lucide="{{ $ic }}" class="w-4 h-4"></i>
                        </button>
                        @endforeach
                        <button type="button" @click="editItem.icon = 'custom'"
                            :class="!['mountain','waves','bike','camera','fish','tent','anchor','trophy','zap','footprints','wind','flame'].includes(editItem.icon) ? 'ring-2 ring-primary ring-offset-2 bg-primary text-white shadow-lg' : 'border-2 border-dashed border-[#F4EBE3] text-[#A8988C] hover:bg-gray-50'"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all"><i data-lucide="plus" class="w-4 h-4"></i></button>
                    </div>
                    <div x-show="!['mountain','waves','bike','camera','fish','tent','anchor','trophy','zap','footprints','wind','flame'].includes(editItem.icon)" class="pt-1" style="display: none;">
                        <input type="text" placeholder="Type Lucide icon name..." x-on:input="if($el.value) editItem.icon = $el.value" :value="editItem.icon" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Intensity</label>
                        <select name="intensity" x-model="editItem.intensity" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                            <option value="Low">ðŸŸ¢ Low</option>
                            <option value="Medium">ðŸŸ¡ Medium</option>
                            <option value="High">ðŸ”´ High</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price ($)</label>
                        <input type="number" step="0.01" min="0" name="price" x-model="editItem.price" placeholder="0.00" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                        <select name="status" x-model="editItem.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
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

