@extends('layouts.admin')

@section('admin_title', 'Packages')

@section('content')
@php
    $activeListings = DB::table('packages')->where('status', 'Active')->count();
    
    $expiredPackages = DB::table('packages')
        ->where('status', '!=', 'Pending')
        ->whereNotNull('expiry_date')
        ->whereDate('expiry_date', '<', now())
        ->count();
    
    $expiringSoon = DB::table('packages')
        ->where('status', '!=', 'Pending')
        ->whereNotNull('expiry_date')
        ->whereDate('expiry_date', '>=', now())
        ->whereDate('expiry_date', '<=', now()->addDays(7))
        ->count();

    $pendingPackages = DB::table('packages')->where('status', 'Pending')->count();
    
    $totalRevenue = DB::table('payments')->whereIn('status', ['Completed', 'Success'])->sum('amount') ?: 0;
    $totalRevenueFormatted = $totalRevenue >= 100000 ? '₹' . number_format($totalRevenue / 1000, 1) . 'k' : '₹' . number_format($totalRevenue);
@endphp

<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Inventory & Stays</p>
            <div class="flex items-center gap-3">
                <h2 class="font-black text-foreground tracking-tight">Tour Packages</h2>
                <span class="text-xs font-bold text-muted-text bg-gray-100 rounded-full px-3 py-1">{{ $packages->total() }} Total</span>
            </div>
    <p class="text-muted-text font-medium">Manage all approved tour packages. Toggle visibility to control which appear on the customer site. Packages pending agent review are under <a href="{{ url('/admin/packages/pending') }}" class="text-primary font-black hover:underline">Pending Approvals</a>.</p>
        </div>
        <a href="{{ url('/admin/packages/create' . (isset($destinationType) && $destinationType ? '?category=' . $destinationType : '')) }}" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group shrink-0">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i> Add New Package
        </a>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-6">
        <!-- Active Listings -->
        <a href="{{ url('/admin/packages?filter=active') }}" class="block bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Active Listings</p>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ $activeListings }}</h3>
        </a>

        <!-- Expired Packages -->
        <a href="{{ url('/admin/packages?filter=expired') }}" class="block bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Expired Packages</p>
                <span class="text-xs font-black text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">Inactive</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ $expiredPackages }}</h3>
        </a>

        <!-- Expiring Soon -->
        <a href="{{ url('/admin/packages?filter=expiring') }}" class="block bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Expiring Soon</p>
                <span class="text-xs font-black text-red-500 bg-red-50 px-2.5 py-1 rounded-lg">Critical</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ str_pad($expiringSoon, 2, '0', STR_PAD_LEFT) }}</h3>
        </a>

        <!-- Pending Packages -->
        <a href="{{ url('/admin/packages/pending') }}" class="block bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Pending Approvals</p>
                <span class="text-xs font-black text-orange-500 bg-orange-50 px-2.5 py-1 rounded-lg">Review</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ $pendingPackages }}</h3>
        </a>

        <!-- Total Revenue (Primary Dark Orange Filled Card) -->
        <a href="{{ url('/admin/payments') }}" class="block p-8 rounded-[32px] shadow-premium space-y-4 relative overflow-hidden text-white hover:opacity-90 transition-all" style="background-color: #af3a03;">
            <div class="absolute right-0 bottom-0 opacity-10 translate-x-4 translate-y-4">
                <i data-lucide="ticket" class="w-32 h-32"></i>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs font-black uppercase tracking-widest opacity-80" style="color: white !important;">Total Revenue</p>
            </div>
            <h3 class="text-4xl font-syne font-black" style="color: white !important;">{{ $totalRevenueFormatted }}</h3>
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Search Form -->
            <form method="GET" action="{{ url('/admin/packages') }}" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-64 group">
                    <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search packages by title or location..." 
                        class="w-full bg-gray-50 border-none rounded-xl py-2 pl-12 pr-4 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                    >
                </div>
                
                <select name="destination_type" class="w-full md:w-40 bg-gray-50 border-none rounded-xl py-2 px-4 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm text-muted-text">
                    <option value="">All Types</option>
                    <option value="international" {{ request('destination_type') == 'international' ? 'selected' : '' }}>International</option>
                    <option value="domestic" {{ request('destination_type') == 'domestic' ? 'selected' : '' }}>Domestic</option>
                </select>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full md:w-32 bg-gray-50 border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/10" title="From Date">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full md:w-32 bg-gray-50 border-none rounded-xl py-2 px-4 text-sm font-medium outline-none focus:ring-2 focus:ring-primary/10" title="To Date">
                
                <button type="submit" class="w-full md:w-auto bg-primary text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:bg-primary-hover transition-all">Filter</button>
                @if(request()->hasAny(['search', 'from_date', 'to_date', 'destination_type']))
                    <a href="{{ url('/admin/packages' . (isset($destinationType) && $destinationType && !request()->has('search') && !request()->has('from_date') && !request()->has('to_date') ? '' : '')) }}" class="w-full md:w-auto bg-gray-100 text-muted-text px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all text-center">Clear</a>
                @endif
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO.</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PACKAGE NAME</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">VALID FROM</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">VALID TO</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($packages as $pkg)
                        @php
                            $srNo = ($packages->currentPage() - 1) * $packages->perPage() + $loop->iteration;
                            $srNoFormatted = str_pad($srNo, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $srNoFormatted }}</td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-gray-50">
                                        <img src="{{ asset($pkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800') }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-foreground">{{ $pkg->title }}</p>
                                        <div class="flex items-center gap-1.5 text-xs text-muted-text">
                                            <i data-lucide="map-pin" size="12" class="text-primary"></i>
                                            <span>{{ $pkg->location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                @php
                                    $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
                                    $agentName = $agentData['name'] ?? 'Unknown Agent';
                                    $agentLogo = $agentData['logo'] ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);
                                @endphp
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset($agentLogo) }}" alt="{{ $agentName }}" class="w-8 h-8 rounded-full border border-gray-100 object-cover bg-gray-50">
                                    <span class="text-sm font-bold text-foreground">{{ $agentName }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-2 text-sm font-bold text-foreground">
                                    <i data-lucide="clock" size="14" class="text-muted-text"></i>
                                    {{ $pkg->duration }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $pkg->currency ?? '₹' }}{{ number_format($pkg->price, 2) }}</td>
                            @php
                                $validFrom = 'N/A';
                                $validTo = 'N/A';
                                if (!empty($pkg->validity)) {
                                    if (strpos($pkg->validity, ' to ') !== false) {
                                        $parts = explode(' to ', $pkg->validity);
                                        $validFrom = \Carbon\Carbon::parse(trim($parts[0]))->format('M d, Y');
                                        $validTo = \Carbon\Carbon::parse(trim($parts[1]))->format('M d, Y');
                                    } else {
                                        $validFrom = \Carbon\Carbon::parse(trim($pkg->validity))->format('M d, Y');
                                        $validTo = $validFrom;
                                    }
                                }
                            @endphp
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $validFrom }}</td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $validTo }}</td>
                            <td class="py-6 px-8">
                                <a href="{{ url('/admin/packages/toggle/' . $pkg->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $pkg->status === 'Active' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $pkg->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ url('/admin/packages/view/' . $pkg->id) }}" class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="View Details">
                                        <i data-lucide="eye" size="18"></i>
                                    </a>
                                    <a href="{{ url('/admin/packages/edit/' . $pkg->id) }}" class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <i data-lucide="edit-3" size="18"></i>
                                    </a>
                                    <a 
                                        href="{{ url('/admin/packages/delete/' . $pkg->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this package?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No travel packages in inventory.</td>
                         </tr>
                     @endforelse
                 </tbody>
             </table>
         </div>
 
         <!-- Custom Pagination -->
         <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
             <p class="text-sm font-bold text-muted-text">Showing {{ $packages->firstItem() ?? 0 }} to {{ $packages->lastItem() ?? 0 }} of {{ $packages->total() }} entries</p>
             <div class="flex items-center gap-2">
                 @if($packages->onFirstPage())
                     <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                 @else
                     <a href="{{ $packages->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                 @endif
                 
                 @foreach(range(1, $packages->lastPage()) as $i)
                     @if($i == 1 || $i == $packages->lastPage() || abs($i - $packages->currentPage()) <= 1)
                         @if($i == $packages->currentPage())
                             <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                 {{ $i }}
                             </button>
                         @else
                             <a href="{{ $packages->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                 {{ $i }}
                             </a>
                         @endif
                     @elseif($i == 2 || $i == $packages->lastPage() - 1)
                         <span class="text-muted-text font-black px-1">...</span>
                     @endif
                 @endforeach
                 
                 @if($packages->hasMorePages())
                     <a href="{{ $packages->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                 @else
                     <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                 @endif
             </div>
         </div>
     </div>
 </div>
@endsection
