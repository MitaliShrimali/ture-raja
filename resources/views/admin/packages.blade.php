@extends('layouts.admin')

@section('content')
@php
    $activeListings = DB::table('packages')->where('status', 'Active')->count();
    $avgPrice = DB::table('packages')->where('status', 'Active')->avg('price') ?: 24000;
    $avgPriceFormatted = $avgPrice >= 1000 ? '₹' . number_format($avgPrice / 1000, 1) . 'k' : '₹' . number_format($avgPrice);
    
    $expiringSoon = DB::table('packages')->where('status', 'Active')->where('created_at', '<', now()->subMonths(3))->count() ?: 8;
    
    $totalRevenue = DB::table('payments')->where('status', 'Completed')->sum('amount') ?: 420800;
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
            <p class="text-muted-text font-medium">Curate and manage high-end travel experiences. Monitor package lifespan, agent assignments, and booking statuses in real-time.</p>
        </div>
        <a href="{{ url('/admin/packages/create') }}" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group shrink-0">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i> Add New Package
        </a>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Active Listings -->
        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Active Listings</p>
                <span class="text-xs font-black text-green-500 bg-green-50 px-2.5 py-1 rounded-lg">+12%</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ $activeListings }}</h3>
        </div>

        <!-- Avg. Package Price -->
        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Avg. Package Price</p>
                <span class="text-xs font-black text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">Stable</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ $avgPriceFormatted }}</h3>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-black text-muted-text uppercase tracking-widest">Expiring Soon</p>
                <span class="text-xs font-black text-red-500 bg-red-50 px-2.5 py-1 rounded-lg">Critical</span>
            </div>
            <h3 class="text-4xl font-black font-syne text-foreground">{{ str_pad($expiringSoon, 2, '0', STR_PAD_LEFT) }}</h3>
        </div>

        <!-- Total Revenue (Primary Dark Orange Filled Card) -->
        <div class="p-8 rounded-[32px] shadow-premium space-y-4 relative overflow-hidden text-white" style="background-color: #af3a03;">
            <div class="absolute right-0 bottom-0 opacity-10 translate-x-4 translate-y-4">
                <i data-lucide="ticket" class="w-32 h-32"></i>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs font-black uppercase tracking-widest opacity-80">Total Revenue</p>
            </div>
            <h3 class="text-4xl font-white font-syne">{{ $totalRevenueFormatted }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Search Form -->
            <form method="GET" action="{{ url('/admin/packages') }}" class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search packages by title or location..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </form>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO.</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PACKAGE NAME</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STOCK</th>
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
                                        <img src="{{ $pkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
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
                                <div class="flex items-center gap-2 text-sm font-bold text-foreground">
                                    <i data-lucide="clock" size="14" class="text-muted-text"></i>
                                    {{ $pkg->duration }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">₹{{ number_format($pkg->price, 2) }}</td>
                            <td class="py-6 px-8 text-sm font-bold text-orange-500">{{ $pkg->stock }}</td>
                            <td class="py-6 px-8">
                                <a href="{{ url('/admin/packages/toggle/' . $pkg->id) }}" class="inline-block">
                                    <span class="px-3 py-1 rounded-full {{ $pkg->status === 'Active' ? 'bg-green-50 text-green-500 hover:bg-green-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }} text-[10px] font-black uppercase tracking-wider transition-all">
                                        {{ $pkg->status }}
                                    </span>
                                </a>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex items-center justify-end gap-2">
                                     @php
                                         $agentName = 'Miths Holidays';
                                         if (!empty($pkg->agent)) {
                                             $agentDecoded = json_decode($pkg->agent, true);
                                             if (is_array($agentDecoded)) {
                                                 $agentName = $agentDecoded['name'] ?? 'Miths Holidays';
                                             } else {
                                                 $agentName = $pkg->agent;
                                             }
                                         }
                                     @endphp
                                    <button 
                                        @click="showEditModal = true; 
                                                editPkg = { 
                                                    id: '{{ $pkg->id }}', 
                                                    title: '{{ addslashes($pkg->title) }}', 
                                                    location: '{{ addslashes($pkg->location) }}', 
                                                    price: '{{ $pkg->price }}', 
                                                    old_price: '{{ $pkg->old_price ?? '' }}', 
                                                    rating: '{{ $pkg->rating ?? '4.8' }}', 
                                                    reviews: '{{ $pkg->reviews ?? '10' }}', 
                                                    duration: '{{ addslashes($pkg->duration) }}', 
                                                    group_size: '{{ addslashes($pkg->group_size ?? '4-6 guest') }}', 
                                                    image: '{{ addslashes($pkg->image) }}', 
                                                    stock: '{{ addslashes($pkg->stock) }}', 
                                                    status: '{{ $pkg->status }}', 
                                                    category: '{{ addslashes($pkg->category ?? 'Tropical') }}', 
                                                    badge: '{{ addslashes($pkg->badge ?? '') }}',
                                                    agent: '{{ addslashes($agentName) }}',
                                                    brochure: '{{ addslashes($pkg->brochure ?? '') }}',
                                                    included: {{ json_encode(json_decode($pkg->included ?? '[]', true) ?: []) }},
                                                    excluded: {{ json_encode(json_decode($pkg->excluded ?? '[]', true) ?: []) }},
                                                    itinerary: {{ $pkg->itinerary ?: '[]' }}
                                                };
                                                editPreviewUrl = '';"
                                        class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/packages/delete/' . $pkg->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this package?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
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
 
 @push('modals')
     <!-- Edit Package Modal -->
     <div 
         x-show="showEditModal" 
         class="fixed inset-0 z-[100] overflow-y-auto flex items-start justify-center p-6 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;"
     >
         <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-7xl w-full p-10 space-y-8 my-8 relative" x-data="{ showInclusions: true, showExclusions: true, brochureName: '' }" x-init="$watch('showEditModal', value => { if(value) brochureName = editPkg.brochure ? editPkg.brochure.split('/').pop() : '' })">
             <div class="flex items-center justify-between border-b border-border-soft pb-4">
                 <div class="space-y-1">
                     <h3 class="text-xl font-black text-foreground">Edit Package</h3>
                     <p class="text-xs text-muted-text font-medium">Update the listing details for this tour package.</p>
                 </div>
                 <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                     <i data-lucide="x" size="20"></i>
                 </button>
             </div>
             
             <form action="{{ url('/admin/packages/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                 @csrf
                 <input type="hidden" name="id" x-model="editPkg.id" />
                 
                 <!-- Image Uploads -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <!-- Main Image -->
                     <div class="space-y-2 bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200 flex flex-col justify-center">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Upload Main Package Pic</label>
                         <input type="file" name="image_file" @change="editPreviewUrl = URL.createObjectURL($event.target.files[0])" class="w-full bg-white border border-gray-100 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white cursor-pointer" />
                     </div>
                     
                     <div class="h-36 rounded-3xl overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center relative">
                         <img :src="editPreviewUrl || editPkg.image" class="w-full h-full object-cover" />
                     </div>
                 </div>

                 <!-- Gallery Images -->
                 <div class="space-y-2 bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200">
                     <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Upload Gallery Pics (Replaces existing gallery)</label>
                     <input type="file" name="gallery_files[]" multiple class="w-full bg-white border border-gray-100 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white cursor-pointer" />
                 </div>

                 <!-- Brochure PDF -->
                 <div class="space-y-2 bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200">
                     <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Upload Brochure PDF</label>
                     <input type="file" name="brochure_file" accept=".pdf" @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : (editPkg.brochure ? editPkg.brochure.split('/').pop() : '')" class="w-full bg-white border border-gray-100 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-xs text-foreground shadow-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white cursor-pointer" />
                     <template x-if="brochureName">
                         <div class="mt-2 text-xs text-primary font-bold flex items-center gap-1.5">
                             <i data-lucide="file-text" size="14"></i>
                             <span>Current / Selected file: <span class="underline" x-text="brochureName"></span></span>
                         </div>
                     </template>
                 </div>

                 <!-- General Info -->
                 <div class="space-y-2">
                     <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Package Title<span class="text-primary">*</span></label>
                     <input required type="text" name="title" x-model="editPkg.title" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                 </div>
                 <div class="grid grid-cols-2 gap-4">
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Location<span class="text-primary">*</span></label>
                         <input required type="text" name="location" x-model="editPkg.location" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Duration<span class="text-primary">*</span></label>
                         <input required type="text" name="duration" x-model="editPkg.duration" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                 </div>
                 <div class="grid grid-cols-2 gap-4">
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Assigned Travel Agent</label>
                         <select name="agent" x-model="editPkg.agent" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                             @foreach(DB::table('agents')->orderBy('name', 'asc')->get() as $agent)
                                 <option value="{{ $agent->name }}">{{ $agent->name }} ({{ $agent->region }})</option>
                             @endforeach
                         </select>
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Category</label>
                         <select name="category" x-model="editPkg.category" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                             <option value="Tropical">Tropical</option>
                             <option value="Mountains">Mountains</option>
                             <option value="City">City</option>
                             <option value="Adventure">Adventure</option>
                             <option value="domestic">Domestic</option>
                             <option value="international">International</option>
                             <option value="religious">Religious</option>
                         </select>
                     </div>
                 </div>

                 <!-- Pricing & Slots -->
                 <div class="grid grid-cols-3 gap-4">
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price (INR)<span class="text-primary">*</span></label>
                         <input required type="number" name="price" x-model="editPkg.price" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Old Price (INR)</label>
                         <input type="number" name="old_price" x-model="editPkg.old_price" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Stock Slots Left</label>
                         <input type="text" name="stock" x-model="editPkg.stock" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                 </div>
                 <div class="grid grid-cols-2 gap-4">
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Group Size</label>
                         <input type="text" name="group_size" x-model="editPkg.group_size" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Promo Badge</label>
                         <input type="text" name="badge" x-model="editPkg.badge" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                 </div>

                 <!-- Inclusions & Exclusions -->
                 <div class="space-y-6">
                     <div class="flex items-center gap-6 pl-1">
                         <h3 class="text-xs font-black text-muted-text uppercase tracking-widest">Inclusions & Exclusions</h3>
                         <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer">
                             <input type="checkbox" x-model="showInclusions" class="rounded border-gray-300 text-primary focus:ring-primary/20" />
                             <span>Show Inclusions</span>
                         </label>
                         <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer">
                             <input type="checkbox" x-model="showExclusions" class="rounded border-gray-300 text-primary focus:ring-primary/20" />
                             <span>Show Exclusions</span>
                         </label>
                     </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div class="space-y-4" x-show="showInclusions">
                             <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">What's Included</label>
                             <div class="bg-gray-50 border-none rounded-2xl p-6 space-y-3">
                                 @foreach(['Hotel Stay', 'Daily Breakfast', 'Tour Guide', 'Transfers'] as $inc)
                                     <label class="flex items-center gap-3 cursor-pointer select-none">
                                         <input type="checkbox" name="included[]" value="{{ $inc }}" :checked="editPkg.included.includes('{{ $inc }}')" :disabled="!showInclusions" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer" />
                                         <span class="text-sm font-bold text-foreground">{{ $inc }}</span>
                                     </label>
                                 @endforeach
                             </div>
                         </div>
                         <div class="space-y-4" x-show="showExclusions">
                             <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">What's Excluded</label>
                             <div class="bg-gray-50 border-none rounded-2xl p-6 space-y-3">
                                 @foreach(['Flights', 'Personal Expenses', 'Visa Fees'] as $exc)
                                     <label class="flex items-center gap-3 cursor-pointer select-none">
                                         <input type="checkbox" name="excluded[]" value="{{ $exc }}" :checked="editPkg.excluded.includes('{{ $exc }}')" :disabled="!showExclusions" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer" />
                                         <span class="text-sm font-bold text-foreground">{{ $exc }}</span>
                                     </label>
                                 @endforeach
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Itinerary Section -->
                 <div class="space-y-6">
                     <div class="flex items-center justify-between pl-1">
                         <h3 class="text-xs font-black text-muted-text uppercase tracking-widest">Tour Itinerary</h3>
                         <button type="button" @click="editPkg.itinerary.push({ title: 'New Day Tour', desc: 'Activities details...' })" class="text-xs font-black text-primary hover:text-primary-hover flex items-center gap-1">
                             <i data-lucide="plus" size="14"></i> Add Day
                         </button>
                     </div>
                     
                     <div class="space-y-4">
                         <template x-for="(day, index) in editPkg.itinerary" :key="index">
                             <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 space-y-4 relative">
                                 <button type="button" @click="editPkg.itinerary.splice(index, 1)" class="absolute top-4 right-4 text-muted-text hover:text-red-500" x-show="editPkg.itinerary.length > 1">
                                     <i data-lucide="trash-2" size="16"></i>
                                 </button>
                                 <div class="flex items-center gap-4">
                                     <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-xs shrink-0" x-text="'D' + (index + 1)"></div>
                                     <input required type="text" name="itinerary_titles[]" x-model="day.title" placeholder="Day Title" class="w-full bg-white border border-gray-100 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-sm text-foreground shadow-sm" />
                                 </div>
                                 <textarea rows="2" name="itinerary_descriptions[]" x-model="day.desc" placeholder="Day description activities..." class="w-full bg-white border border-gray-100 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-sm text-foreground shadow-sm resize-none"></textarea>
                             </div>
                         </template>
                     </div>
                 </div>

                 <!-- Ratings & Reviews -->
                 <div class="grid grid-cols-2 gap-4">
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Rating</label>
                         <input type="text" name="rating" x-model="editPkg.rating" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                     <div class="space-y-2">
                         <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Reviews Count</label>
                         <input type="number" name="reviews" x-model="editPkg.reviews" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                     </div>
                 </div>
                 <div class="space-y-2">
                     <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Image URL (Fallback option)</label>
                     <input type="text" name="image" x-model="editPkg.image" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                 </div>
                 
                 <div class="flex items-center justify-end gap-4 pt-4">
                     <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                     <button type="submit" class="px-8 py-4 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                 </div>
             </form>
         </div>
     </div>
 @endpush
 @endsection
