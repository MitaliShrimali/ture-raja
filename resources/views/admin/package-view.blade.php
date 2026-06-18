@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Package Review</p>
            <h2 class="font-black text-foreground tracking-tight text-3xl">{{ $pkg->title }}</h2>
            <div class="flex items-center gap-3 mt-2">
                <span class="text-xs font-bold text-muted-text bg-gray-100 rounded-full px-3 py-1"><i data-lucide="map-pin" size="14" class="inline mr-1 text-primary"></i> {{ $pkg->location }}</span>
                <span class="text-xs font-bold {{ $pkg->status === 'Pending' ? 'text-orange-600 bg-orange-50 border-orange-100' : ($pkg->status === 'Active' ? 'text-green-600 bg-green-50 border-green-100' : 'text-gray-600 bg-gray-50 border-gray-100') }} rounded-full px-3 py-1 border">{{ $pkg->status }}</span>
            </div>
        </div>
        <div class="flex gap-3 shrink-0">
            @if($pkg->status === 'Pending')
            <a href="{{ url('/admin/packages/approve/' . $pkg->id) }}" onclick="return confirm('Approve this package?')" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="check" size="18"></i> Approve
            </a>
            <a href="{{ url('/admin/packages/decline/' . $pkg->id) }}" onclick="return confirm('Decline this package?')" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="x" size="18"></i> Decline
            </a>
            @endif
            <a href="{{ url()->previous() }}" class="flex items-center gap-2 px-6 py-3 rounded-2xl font-black text-sm border border-border-soft bg-white text-muted-text hover:text-foreground transition-all">
                <i data-lucide="arrow-left" size="18"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-gray-100">
                    <img src="{{ $pkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800' }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-1">Duration</p>
                        <p class="text-sm font-black text-foreground">{{ $pkg->duration }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-1">Price</p>
                        <p class="text-sm font-black text-primary">₹{{ number_format($pkg->price, 2) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-1">Group Size</p>
                        <p class="text-sm font-black text-foreground">{{ $pkg->group_size }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-1">Category</p>
                        <p class="text-sm font-black text-foreground">{{ $pkg->category }}</p>
                    </div>
                </div>

                @if($pkg->editorial_itinerary)
                <div>
                    <h3 class="text-lg font-black text-foreground mb-4 border-b pb-2">Editorial Description</h3>
                    <div class="prose max-w-none text-muted-text text-sm">
                        {!! nl2br(e($pkg->editorial_itinerary)) !!}
                    </div>
                </div>
                @endif
                
                <div>
                    <h3 class="text-lg font-black text-foreground mb-4 border-b pb-2">Itinerary</h3>
                    @php $itinerary = json_decode($pkg->itinerary, true) ?: []; @endphp
                    @if(count($itinerary) > 0)
                        <div class="space-y-4">
                            @foreach($itinerary as $day)
                                <div class="p-4 bg-gray-50 rounded-2xl">
                                    <h4 class="font-bold text-foreground text-sm">{{ $day['title'] ?? '' }}</h4>
                                    <p class="text-xs text-muted-text mt-2">{{ $day['desc'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted-text">No itinerary details provided.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-8">
            <!-- Agent Info -->
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft">
                <h3 class="text-xs font-black text-muted-text uppercase tracking-widest mb-6">Uploaded By</h3>
                @php
                    $agentData = $pkg->agent ? json_decode($pkg->agent, true) : null;
                    $agentName = $agentData['name'] ?? 'Unknown Agent';
                    $dbAgent = \DB::table('agents')->where('name', $agentName)->first();
                    if ($dbAgent && !empty($dbAgent->agency_name)) {
                        $agentName = $dbAgent->agency_name;
                    }
                    $agentLogo = $agentData['logo'] ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agentName);
                @endphp
                <div class="flex items-center gap-4">
                    <img src="{{ $agentLogo }}" class="w-16 h-16 rounded-2xl object-cover bg-gray-50 border border-gray-100">
                    <div>
                        <h4 class="font-black text-foreground">{{ $agentName }}</h4>
                        <p class="text-xs text-muted-text font-medium">{{ $agentData['phone'] ?? 'No contact info' }}</p>
                    </div>
                </div>
            </div>

            <!-- Inclusions/Exclusions -->
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft">
                <h3 class="text-xs font-black text-muted-text uppercase tracking-widest mb-6">Included & Excluded</h3>
                @php 
                    $included = json_decode($pkg->included, true) ?: []; 
                    $excluded = json_decode($pkg->excluded, true) ?: []; 
                @endphp
                
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-green-600 flex items-center gap-2 mb-3"><i data-lucide="check-circle-2" size="16"></i> Included</h4>
                    <ul class="space-y-2">
                        @forelse($included as $inc)
                            <li class="text-xs text-muted-text flex items-start gap-2"><i data-lucide="check" size="14" class="text-green-500 mt-0.5 shrink-0"></i> {{ $inc }}</li>
                        @empty
                            <li class="text-xs text-muted-text">None specified</li>
                        @endforelse
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-sm font-bold text-red-600 flex items-center gap-2 mb-3"><i data-lucide="x-circle" size="16"></i> Excluded</h4>
                    <ul class="space-y-2">
                        @forelse($excluded as $exc)
                            <li class="text-xs text-muted-text flex items-start gap-2"><i data-lucide="x" size="14" class="text-red-500 mt-0.5 shrink-0"></i> {{ $exc }}</li>
                        @empty
                            <li class="text-xs text-muted-text">None specified</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft">
                <h3 class="text-xs font-black text-muted-text uppercase tracking-widest mb-6">Metadata</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Stock</span>
                        <span class="text-xs font-black text-foreground">{{ $pkg->stock }} units</span>
                    </div>
                    @if(!empty($pkg->validity))
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Validity</span>
                        <span class="text-xs font-black text-foreground">{{ $pkg->validity }}</span>
                    </div>
                    @endif
                    @if(!empty($pkg->sightseeing))
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Sightseeing</span>
                        <span class="text-xs font-black text-foreground">{{ $pkg->sightseeing }}</span>
                    </div>
                    @endif
                    @php
                        $meals = json_decode($pkg->meals, true) ?: [];
                        $hotels = json_decode($pkg->hotels, true) ?: [];
                    @endphp
                    @if(!empty($meals))
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Meals</span>
                        <span class="text-xs font-black text-foreground">{{ implode(', ', $meals) }}</span>
                    </div>
                    @endif
                    @if(!empty($hotels))
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Hotels</span>
                        <span class="text-xs font-black text-foreground">{{ count($hotels) }} Listed</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-xs text-muted-text font-bold">Created At</span>
                        <span class="text-xs font-black text-foreground">{{ \Carbon\Carbon::parse($pkg->created_at)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
