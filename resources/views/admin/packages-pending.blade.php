@extends('layouts.admin')

@section('admin_title', 'Pending Approvals')
@section('content')
@php
    $pendingCount = DB::table('packages')->where('status', 'Pending')->count();
@endphp

<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Agent Submissions</p>
            <div class="flex items-center gap-3">
                <h2 class="font-black text-foreground tracking-tight">Pending Approvals</h2>
                <span class="text-xs font-bold text-orange-600 bg-orange-50 rounded-full px-3 py-1 border border-orange-100">
                    {{ $pendingCount }} Awaiting Review
                </span>
            </div>
            <p class="text-muted-text font-medium">Review packages submitted by agents. Approve to publish them live on the customer site or decline to hide them.</p>
        </div>
        <a href="{{ url('/admin/packages') }}" class="flex items-center gap-2 px-6 py-3 rounded-2xl font-black text-sm border border-border-soft bg-white text-muted-text hover:text-foreground transition-all shrink-0">
            <i data-lucide="list" size="18"></i> All Packages
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Pending Review</p>
                <span class="text-xs font-black text-orange-500 bg-orange-50 px-2.5 py-1 rounded-lg">Awaiting</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ str_pad($pendingCount, 2, '0', STR_PAD_LEFT) }}</h3>
        </div>
        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Live Packages</p>
                <span class="text-xs font-black text-green-500 bg-green-50 px-2.5 py-1 rounded-lg">Active</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ str_pad(DB::table('packages')->where('status','Active')->count(), 2, '0', STR_PAD_LEFT) }}</h3>
        </div>
        <div class="p-8 rounded-[32px] shadow-premium space-y-4 relative overflow-hidden text-white" style="background-color: #af3a03;">
            <div class="absolute right-0 bottom-0 opacity-10 translate-x-4 translate-y-4">
                <i data-lucide="clock" class="w-32 h-32 text-white"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-widest opacity-80" style="color: white !important;">Review Queue</p>
            <h3 class="text-4xl font-syne font-black" style="color: white !important;">{{ $pendingCount > 0 ? 'Action Needed' : 'All Clear!' }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <form method="GET" action="{{ url('/admin/packages/pending') }}" class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search pending packages..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </form>
            <div class="flex items-center gap-2 text-xs font-bold text-muted-text bg-orange-50 border border-orange-100 rounded-2xl px-4 py-2">
                <i data-lucide="alert-circle" size="14" class="text-orange-500"></i>
                Approve to publish on customer site
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO.</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PACKAGE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SUBMITTED</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($pendingPackages as $pkg)
                        @php
                            $srNo = ($pendingPackages->currentPage() - 1) * $pendingPackages->perPage() + $loop->iteration;
                            $srNoFormatted = str_pad($srNo, 2, '0', STR_PAD_LEFT);
                            $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
                            $agentName = $agentData['name'] ?? 'Unknown Agent';
                            if (is_string($agentName) && str_starts_with(trim($agentName), '{')) {
                                $agentName = 'Unknown Agent';
                            }
                            $agentLogo = $agentData['logo'] ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);
                        @endphp
                        <tr class="group hover:bg-orange-50/20 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $srNoFormatted }}</td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden shrink-0 border border-gray-100 bg-gray-50 relative">
                                        <img src="{{ asset($pkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=200') }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-orange-500/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-foreground">{{ $pkg->title }}</p>
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="map-pin" size="11" class="text-primary"></i>
                                            <span class="text-xs text-muted-text font-medium">{{ $pkg->location }}</span>
                                            <span class="ml-1 text-[9px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Pending Review</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset($agentLogo) }}" alt="{{ $agentName }}" class="w-8 h-8 rounded-full border border-gray-100 object-cover bg-gray-50">
                                    <span class="text-sm font-bold text-foreground">{{ $agentName }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-2 text-sm font-bold text-foreground">
                                    <i data-lucide="clock" size="14" class="text-muted-text"></i>
                                    {{ $pkg->duration ?? '—' }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $pkg->currency ?? '₹' }}{{ number_format($pkg->price, 2) }}</td>
                            <td class="py-6 px-8 text-xs font-bold text-muted-text">
                                {{ $pkg->created_at ? \Carbon\Carbon::parse($pkg->created_at)->diffForHumans() : '—' }}
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- View Details --}}
                                    <a href="{{ url('/admin/packages/view/' . $pkg->id) }}" 
                                       class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all" 
                                       title="View Details">
                                        <i data-lucide="eye" size="18"></i>
                                    </a>
                                    {{-- Approve --}}
                                    <a href="{{ url('/admin/packages/approve/' . $pkg->id) }}" 
                                       onclick="return confirm('Approve this package? It will go live on the customer site.')"
                                       class="p-2.5 text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all shadow-sm" 
                                       title="Approve Package">
                                        <i data-lucide="check" size="18"></i>
                                    </a>
                                    {{-- Decline --}}
                                    <a href="{{ url('/admin/packages/decline/' . $pkg->id) }}" 
                                       onclick="return confirm('Decline this package? It will be hidden from the customer site.')"
                                       class="p-2.5 text-white bg-red-500 hover:bg-red-600 rounded-xl transition-all shadow-sm" 
                                       title="Decline Package">
                                        <i data-lucide="x" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center">
                                        <i data-lucide="check-circle-2" size="32" class="text-green-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-foreground">All caught up!</p>
                                        <p class="text-xs text-muted-text font-medium mt-1">No packages are currently pending approval.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pendingPackages->total() > 0)
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $pendingPackages->firstItem() ?? 0 }} to {{ $pendingPackages->lastItem() ?? 0 }} of {{ $pendingPackages->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($pendingPackages->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $pendingPackages->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $pendingPackages->lastPage()) as $i)
                    @if($i == 1 || $i == $pendingPackages->lastPage() || abs($i - $pendingPackages->currentPage()) <= 1)
                        @if($i == $pendingPackages->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20">{{ $i }}</button>
                        @else
                            <a href="{{ $pendingPackages->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">{{ $i }}</a>
                        @endif
                    @elseif($i == 2 || $i == $pendingPackages->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($pendingPackages->hasMorePages())
                    <a href="{{ $pendingPackages->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
