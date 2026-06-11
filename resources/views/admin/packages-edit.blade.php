@extends('layouts.admin')

@section('admin_title', 'Edit Package')

@section('content')
@php
    $galleryUrls = json_decode($pkg->gallery, true) ?: [];
    $included = json_decode($pkg->included, true) ?: [];
    $excluded = json_decode($pkg->excluded, true) ?: [];
    $itinerary = json_decode($pkg->itinerary, true) ?: [];
    $agentData = json_decode($pkg->agent, true) ?: [];
    $agentName = $agentData['name'] ?? 'Miths Holidays';
@endphp
<div class="space-y-8 pb-12" x-data="{ 
    step: 1,
    title: '{{ addslashes($pkg->title) }}',
    location: '{{ addslashes($pkg->location) }}',
    duration: '{{ addslashes($pkg->duration) }}',
    price: '{{ $pkg->price }}',
    old_price: '{{ $pkg->old_price ?? '' }}',
    stock: '{{ addslashes($pkg->stock) }}',
    category: '{{ strtolower($pkg->category ?? 'domestic') }}',
    badge: '{{ addslashes($pkg->badge ?? '') }}',
    group_size: '{{ addslashes($pkg->group_size ?? 'Direct Flight') }}',
    rating: '{{ $pkg->rating ?? '4.8' }}',
    reviews: '{{ $pkg->reviews ?? '10' }}',
    previewUrl: '{{ $pkg->image }}', 
    galleryPreviews: [
        @foreach($galleryUrls as $url)
            { url: '{{ $url }}', name: '{{ basename($url) }}', size: 'Existing' },
        @endforeach
    ],
    brochureName: '{{ $pkg->brochure ? basename($pkg->brochure) : '' }}',
    showInclusions: true,
    showExclusions: true,
    hidePrice: false,
    transfers: [],
    hotels: [
        { name: 'Taj Palace, New Delhi', room: 'Luxury Suite &bull; King Bed', image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100' }
    ],
    newTransfer: '',
    newHotelName: '',
    newHotelRoom: '',
    newHotelImage: '',
    showAddTransfer: false,
    showAddHotel: false,
    days: {!! count($itinerary) > 0 ? json_encode($itinerary) : json_encode([['title' => 'Day 1', 'desc' => 'Arrival & check-in', 'duration' => '3 Hours']]) !!},
    addDay() {
        this.days.push({ title: '', desc: '', duration: '3 Hours' });
    },
    removeDay(index) {
        if (this.days.length > 1) {
            this.days.splice(index, 1);
        }
    },
    handleGalleryChange(event) {
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            this.galleryPreviews.push({
                url: URL.createObjectURL(files[i]),
                name: files[i].name,
                size: (files[i].size / (1024 * 1024)).toFixed(1) + ' MB',
                file: files[i]
            });
        }
    },
    removeGalleryPhoto(index) {
        this.galleryPreviews.splice(index, 1);
    }
}">
    <!-- Custom Style Tags for Step Track and Segmented Controls -->
    <style>
        .step-track-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            width: 100%;
            padding: 0 1rem;
        }
        .step-track-line {
            position: absolute;
            top: 24px;
            left: 64px;
            right: 64px;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 1;
        }
        .step-track-line-active {
            position: absolute;
            top: 24px;
            left: 64px;
            height: 2px;
            background-color: #B33A00;
            z-index: 2;
            transition: width 0.3s ease;
        }
        .step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
        }
        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 2px solid #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .step-node.active .step-circle {
            border-color: #B33A00;
            background-color: #B33A00;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(179, 58, 0, 0.3);
        }
        .step-node.completed .step-circle {
            border-color: #B33A00;
            background-color: #ffffff;
            color: #B33A00;
        }
        .step-label {
            margin-top: 0.5rem;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            transition: color 0.3s ease;
        }
        .step-node.active .step-label, .step-node.completed .step-label {
            color: #B33A00;
        }
        .segmented-control {
            display: flex;
            background-color: #F3F4F6;
            padding: 4px;
            border-radius: 16px;
        }
        .segmented-btn {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            font-weight: 800;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .segmented-btn.active {
            background-color: #ffffff;
            color: #B33A00;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>

    <!-- Header Actions Panel -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-border-soft">
        <div class="flex items-center gap-4">
            <a href="{{ url('/admin/packages') }}" class="p-3 bg-white hover:bg-gray-50 border border-border-soft rounded-2xl transition-all shadow-sm text-muted-text hover:text-primary">
                <i data-lucide="arrow-left" size="20"></i>
            </a>
            <div>
                <h2 class="font-black text-gray-800 tracking-tight text-2xl" x-text="step === 1 ? 'Edit Travel Package' : 'Build Your Journey'"></h2>
                <p class="text-muted-text font-medium text-xs mt-0.5" x-text="step === 1 ? 'Step 1: Configure core metadata, location, logistics & base pricing.' : 'Step 2: Upload brochures, edit itineraries, and add gallery portfolio.'"></p>
            </div>
        </div>
        
        <!-- Header Step Actions -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ url('/admin/packages') }}" class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                Discard
            </a>
            <button type="button" @click="step = 2" x-show="step === 1" class="px-6 py-3 bg-[#B33A00] hover:bg-[#943000] text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-lg shadow-orange-700/20" style="background-color: #B33A00 !important; color: #ffffff !important;">
                Save & Next <i data-lucide="chevron-right" size="14"></i>
            </button>
            <button type="submit" form="packageMainForm" x-show="step === 2" class="px-6 py-3 bg-[#B33A00] hover:bg-[#943000] text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-lg shadow-orange-700/20" style="background-color: #B33A00 !important; color: #ffffff !important;">
                Save And Exit <i data-lucide="check" size="14"></i>
            </button>
        </div>
    </div>

    <!-- Step Tracker Bar -->
    <div class="step-track-container max-w-4xl mx-auto py-6">
        <div class="step-track-line"></div>
        <div class="step-track-line-active" :style="step === 2 ? 'width: 100%' : 'width: 0%'"></div>
        
        <!-- Step 1 Node -->
        <div class="step-node" :class="step === 1 ? 'active' : 'completed'" @click="step = 1">
            <div class="step-circle">1</div>
            <span class="step-label">Identity & Logistics</span>
        </div>

        <!-- Step 2 Node -->
        <div class="step-node" :class="step === 2 ? 'active' : ''" @click="step = 2">
            <div class="step-circle">2</div>
            <span class="step-label">Itinerary, Meals & Photos</span>
        </div>
    </div>

    <!-- Form Container -->
    <form id="packageMainForm" action="{{ url('/admin/packages/update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf
        <input type="hidden" name="id" value="{{ $pkg->id }}" />

        <!-- ==================== STEP 1: IDENTITY & LOGISTICS ==================== -->
        <div x-show="step === 1" class="space-y-8" x-transition>
            <!-- Package Identity Card -->
            <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                        <i data-lucide="info" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Package Identity</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Package Name</label>
                        <input required type="text" name="title" x-model="title" placeholder="The Ultimate Bali Escape" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Destination Type (Segmented control) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Destination Type</label>
                        <div class="segmented-control">
                            <div class="segmented-btn" :class="category === 'domestic' ? 'active' : ''" @click="category = 'domestic'">Domestic</div>
                            <div class="segmented-btn" :class="category === 'international' ? 'active' : ''" @click="category = 'international'">International</div>
                        </div>
                        <input type="hidden" name="category" :value="category" />
                    </div>
                </div>

                <!-- Cities List (location) -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">City List (Select cities included)</label>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex flex-wrap items-center gap-2 border border-transparent focus-within:bg-white focus-within:ring-2 focus-within:ring-[#B33A00]/25 transition-all">
                        <span class="px-3.5 py-1.5 bg-[#B33A00] text-white rounded-full text-xs font-bold flex items-center gap-1.5">
                            Ubud <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <span class="px-3.5 py-1.5 bg-[#B33A00] text-white rounded-full text-xs font-bold flex items-center gap-1.5">
                            Seminyak <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <span class="px-3.5 py-1.5 bg-[#B33A00] text-white rounded-full text-xs font-bold flex items-center gap-1.5">
                            Uluwatu <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <button type="button" class="px-3 py-1.5 bg-white text-gray-700 rounded-full text-xs font-bold border border-dashed border-gray-300 hover:bg-gray-50 flex items-center gap-1 transition-all">
                            + Add City
                        </button>
                        <input type="hidden" name="location" :value="location" />
                    </div>
                </div>
            </div>

            <!-- Logistics & Departure Card -->
            <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                        <i data-lucide="calendar" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Logistics & Departure</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Duration -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Duration</label>
                        <select name="duration" x-model="duration" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm">
                            <option value="3 Days / 2 Nights">3 Days / 2 Nights</option>
                            <option value="5 Days / 4 Nights">5 Days / 4 Nights</option>
                            <option value="7 Days / 6 Nights">7 Days / 6 Nights</option>
                            <option value="10 Days / 9 Nights">10 Days / 9 Nights</option>
                        </select>
                    </div>

                    <!-- Package Validity (stock) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Package Validity</label>
                        <div class="relative">
                            <i data-lucide="calendar" size="16" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="stock" x-model="stock" placeholder="20 Dec 2024 - 30 Mar 2025" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <!-- Transit Type (group_size) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Transit Type</label>
                        <select name="group_size" x-model="group_size" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm">
                            <option value="Direct Flight">Direct Flight</option>
                            <option value="Connecting Flight">Connecting Flight</option>
                            <option value="Cruise Liner">Cruise Liner</option>
                            <option value="Luxury Bus">Luxury Bus</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Departure City -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Departure City</label>
                        <input type="text" name="badge" x-model="badge" placeholder="New Delhi" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Departure State -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Departure State</label>
                        <input type="text" placeholder="Delhi" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Departure Country -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Departure Country</label>
                        <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm">
                            <option value="India">India</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Thailand">Thailand</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lower Grid: Pricing and Specifics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Pricing Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                            <i data-lucide="wallet" size="20"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Pricing</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price Per Person (INR)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">₹</span>
                            <input required type="number" name="price" x-model="price" placeholder="45999" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <!-- Hide Price Toggle -->
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="hide_price" x-model="hidePrice" class="w-5 h-5 rounded border-gray-300 text-[#B33A00] focus:ring-0 cursor-pointer" />
                        <span class="text-xs font-bold text-gray-600">Hide price from package listing</span>
                    </label>
                </div>

                <!-- Specifics Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                            <i data-lucide="compass" size="20"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Specifics</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Theme Selection</label>
                            <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm">
                                <option value="Solo Travelers">Solo Travelers</option>
                                <option value="Family Friendly">Family Friendly</option>
                                <option value="Honeymoon Special">Honeymoon Special</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Holiday Type</label>
                            <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm">
                                <option value="Multi City">Multi City</option>
                                <option value="Beach Resort">Beach Resort</option>
                                <option value="Hill Station">Hill Station</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Keywords Card -->
            <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                        <i data-lucide="tag" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Trip Keywords</h3>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Search Keywords (Helps travelers find you)</label>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex flex-wrap items-center gap-2 border border-transparent focus-within:bg-white focus-within:ring-2 focus-within:ring-[#B33A00]/25 transition-all">
                        <span class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5">
                            Bali Beaches <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <span class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5">
                            Scuba Diving <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <span class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5">
                            Temple Tour <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <span class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5">
                            Nightlife <i class="cursor-pointer" data-lucide="x" size="12"></i>
                        </span>
                        <input type="text" placeholder="Type and press enter..." class="bg-transparent border-none outline-none text-sm font-bold text-gray-700 py-1 px-2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 2: ITINERARY, MEALS & PHOTOS ==================== -->
        <div x-show="step === 2" class="space-y-10" x-transition>
            <!-- Multi-column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left 2 Columns -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Brochure Upload & Itinerary Text Area -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Brochure upload -->
                        <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-4 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-text" size="18" class="text-[#B33A00]"></i>
                                <h4 class="text-sm font-black text-gray-800">Upload Brochure</h4>
                            </div>
                            
                            <div class="w-full bg-[#F5F5F5] rounded-2xl p-6 border-2 border-dashed border-gray-200 text-center cursor-pointer hover:bg-gray-100 transition-all flex flex-col items-center justify-center min-h-[140px]" @click="$refs.brochureInput.click()">
                                <i data-lucide="upload-cloud" class="text-gray-400 mb-2" size="24"></i>
                                <span class="text-xs font-bold text-gray-800" x-text="brochureName ? brochureName : 'Drop your brochure here'">Drop your brochure here</span>
                                <span class="text-[9px] text-gray-400 font-semibold mt-1">PDF FORMAT ONLY &bull; MAX 5MB</span>
                                <input type="file" name="brochure_file" x-ref="brochureInput" accept=".pdf" class="hidden" @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                            </div>
                        </div>

                        <!-- Add Your Itinerary -->
                        <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2">
                                <i data-lucide="pencil" size="18" class="text-[#B33A00]"></i>
                                <h4 class="text-sm font-black text-gray-800">Add Your Itinerary</h4>
                            </div>
                            <textarea name="editorial_itinerary" rows="6" placeholder="Explain why this tour is unique..." class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm resize-none">{{ $pkg->editorial_itinerary }}</textarea>
                        </div>
                    </div>

                    <!-- Editorial Details Card -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <h3 class="text-lg font-black text-gray-800">Editorial Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Transfers -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 pl-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B33A00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest">Transfers</label>
                                    </div>
                                    <button type="button" @click="const name = prompt('Enter transfer details:'); if(name) transfers.push(name);" class="text-[10px] font-black text-[#B33A00] hover:underline flex items-center gap-0.5">
                                        <i data-lucide="plus" size="10"></i> Add
                                    </button>
                                </div>
                                
                                <div class="space-y-2">
                                    <template x-for="(tr, idx) in transfers" :key="idx">
                                        <div class="w-full bg-[#F5F5F5] rounded-2xl py-3 px-4 flex items-center justify-between text-xs font-bold text-foreground shadow-sm">
                                            <span x-text="tr"></span>
                                            <button type="button" @click="transfers.splice(idx, 1)" class="text-gray-400 hover:text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="transfers.length === 0">
                                        <div class="w-full bg-[#F5F5F5] rounded-2xl py-3 px-4 text-xs font-bold text-muted-text pl-4 italic">No transfers added yet</div>
                                    </template>
                                </div>
                            </div>

                            <!-- Hotels -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 pl-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B33A00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h20"/><path d="M22 4v16"/><path d="M2 12h20"/><path d="M2 16h20"/></svg>
                                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest">Hotels</label>
                                    </div>
                                    <button type="button" @click="const name = prompt('Enter Hotel Name:'); if(name) { const details = prompt('Enter Room Type / Details:'); hotels.push({ name: name, room: details, image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100' }); }" class="text-[10px] font-black text-[#B33A00] hover:underline flex items-center gap-0.5">
                                        <i data-lucide="plus" size="10"></i> Add
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="(ht, idx) in hotels" :key="idx">
                                        <div class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex items-center justify-between border border-transparent hover:border-gray-200/50 transition-all shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <img :src="ht.image || 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100'" class="w-10 h-10 rounded-xl object-cover" />
                                                <div class="space-y-0.5">
                                                    <p class="text-xs font-black text-gray-800" x-text="ht.name"></p>
                                                    <p class="text-[9px] text-muted-text font-bold uppercase" x-html="ht.room || 'Standard Room'"></p>
                                                </div>
                                            </div>
                                            <button type="button" @click="hotels.splice(idx, 1)" class="text-gray-400 hover:text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="hotels.length === 0">
                                        <p class="text-xs text-muted-text font-bold pl-1 italic">No hotels added yet</p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Meals Included -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Meals Included</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 bg-[#F5F5F5] px-4 py-2.5 rounded-full cursor-pointer select-none">
                                    <input type="checkbox" name="included[]" value="Breakfast" checked class="rounded border-gray-300 text-[#B33A00] focus:ring-0 cursor-pointer accent-[#B33A00]">
                                    <span class="text-xs font-bold text-gray-700">Breakfast</span>
                                </label>
                                <label class="flex items-center gap-2 bg-[#F5F5F5] px-4 py-2.5 rounded-full cursor-pointer select-none">
                                    <input type="checkbox" name="included[]" value="Lunch" class="rounded border-gray-300 text-[#B33A00] focus:ring-0 cursor-pointer accent-[#B33A00]">
                                    <span class="text-xs font-bold text-gray-700">Lunch</span>
                                </label>
                                <label class="flex items-center gap-2 bg-[#F5F5F5] px-4 py-2.5 rounded-full cursor-pointer select-none">
                                    <input type="checkbox" name="included[]" value="Dinner" checked class="rounded border-gray-300 text-[#B33A00] focus:ring-0 cursor-pointer accent-[#B33A00]">
                                    <span class="text-xs font-bold text-gray-700">Dinner</span>
                                </label>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Terms & Conditions</label>
                            <textarea name="excluded[]" rows="3" placeholder="Specify booking policies for this package..." class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#B33A00]/25 transition-all font-bold text-foreground text-sm resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Sightseeing Details Card (Itinerary Days Builder) -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-50 text-[#B33A00] rounded-xl flex items-center justify-center">
                                    <i data-lucide="eye" size="20"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">Sightseeing Details</h3>
                            </div>
                            <button type="button" @click="addDay()" class="px-5 py-2.5 bg-[#B33A00] hover:bg-[#943000] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-lg shadow-orange-700/10" style="background-color: #B33A00 !important; color: #ffffff !important;">
                                <i data-lucide="plus" size="14"></i> Add Point
                            </button>
                        </div>

                        <div class="overflow-hidden border border-gray-100 rounded-2xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F5F5F5] border-b border-gray-100">
                                        <th class="py-4 px-6 text-[10px] font-black text-muted-text uppercase tracking-widest">Location</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-muted-text uppercase tracking-widest">Activity</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-muted-text uppercase tracking-widest">Duration</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(day, index) in days" :key="index">
                                        <tr class="border-b border-gray-100/50 hover:bg-gray-50/30 transition-all">
                                            <td class="py-4 px-6">
                                                <input required type="text" name="itinerary_titles[]" x-model="day.title" class="w-full bg-transparent border-none outline-none font-bold text-gray-800 focus:ring-0 p-0 text-sm" placeholder="e.g. Red Fort" />
                                            </td>
                                            <td class="py-4 px-6">
                                                <input required type="text" name="itinerary_descriptions[]" x-model="day.desc" class="w-full bg-transparent border-none outline-none font-medium text-gray-600 focus:ring-0 p-0 text-sm" placeholder="e.g. Historical Guided Tour" />
                                            </td>
                                            <td class="py-4 px-6">
                                                <input type="text" name="itinerary_durations[]" x-model="day.duration" class="w-full bg-transparent border-none outline-none font-medium text-gray-500 focus:ring-0 p-0 text-sm" placeholder="e.g. 3 Hours" />
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <button type="button" @click="removeDay(index)" class="p-1 text-muted-text hover:text-red-500 transition-all" x-show="days.length > 1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Inclusions & Exclusions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Inclusions Card -->
                        <div class="bg-[#F2FBF7] rounded-[32px] border border-green-100 p-8 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 text-[#0ca678]">
                                <i data-lucide="check-circle" size="20"></i>
                                <h4 class="text-sm font-black uppercase tracking-wider">Inclusions</h4>
                            </div>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="included[]" value="All airport transfers" checked class="rounded text-[#0ca678] focus:ring-0 accent-[#0ca678]">
                                    <span class="text-xs font-semibold text-gray-700">All airport transfers</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="included[]" value="Daily breakfast and dinner" checked class="rounded text-[#0ca678] focus:ring-0 accent-[#0ca678]">
                                    <span class="text-xs font-semibold text-gray-700">Daily breakfast and dinner</span>
                                </label>
                            </div>
                        </div>

                        <!-- Exclusions Card -->
                        <div class="bg-[#FFF5F5] rounded-[32px] border border-red-100 p-8 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 text-[#f03e3e]">
                                <i data-lucide="x-circle" size="20"></i>
                                <h4 class="text-sm font-black uppercase tracking-wider">Exclusions</h4>
                            </div>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="excluded[]" value="International flights" checked class="rounded text-[#f03e3e] focus:ring-0 accent-[#f03e3e]">
                                    <span class="text-xs font-semibold text-gray-700">International flights</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="excluded[]" value="Travel insurance" checked class="rounded text-[#f03e3e] focus:ring-0 accent-[#f03e3e]">
                                    <span class="text-xs font-semibold text-gray-700">Travel insurance</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Column -->
                <div class="space-y-8">

                    <!-- Pricing & Dates -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Pricing & Dates</h4>
                        
                        <div class="space-y-4">
                            <!-- Base Price -->
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Base Price</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">₹</span>
                                    <input type="number" name="old_price" placeholder="2450" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none transition-all font-bold text-foreground text-sm" />
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Package Start Date</label>
                                <div class="relative">
                                    <i data-lucide="calendar" size="16" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" placeholder="12 October, 2026" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Essential Amenities -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-4 shadow-sm">
                        <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Essential Amenities</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" checked class="rounded border-gray-300 text-[#B33A00] focus:ring-0 accent-[#B33A00]">
                                <span class="text-xs font-semibold text-gray-700">Wi-Fi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="rounded border-gray-300 text-[#B33A00] focus:ring-0 accent-[#B33A00]">
                                <span class="text-xs font-semibold text-gray-700">Laundry</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" checked class="rounded border-gray-300 text-[#B33A00] focus:ring-0 accent-[#B33A00]">
                                <span class="text-xs font-semibold text-gray-700">AC Room</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" checked class="rounded border-gray-300 text-[#B33A00] focus:ring-0 accent-[#B33A00]">
                                <span class="text-xs font-semibold text-gray-700">Kitchen</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tour Category Pills Selection -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-4 shadow-sm">
                        <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Tour Category</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#B33A00] text-white cursor-pointer transition-all" style="background-color: #B33A00 !important; color: #ffffff !important;">Adventure</span>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F5F5F5] text-gray-600 hover:bg-gray-100 cursor-pointer transition-all">Cultural</span>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F5F5F5] text-gray-600 hover:bg-gray-100 cursor-pointer transition-all">Hill Station</span>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F5F5F5] text-gray-600 hover:bg-gray-100 cursor-pointer transition-all">Wildlife</span>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F5F5F5] text-gray-600 hover:bg-gray-100 cursor-pointer transition-all">Religious</span>
                        </div>
                    </div>

                    <!-- Craft In Progress status -->
                    <div class="bg-orange-50/50 border border-orange-100 rounded-[24px] p-6 flex items-start gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#B33A00] flex items-center justify-center shrink-0">
                            <i data-lucide="info" size="20"></i>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-xs font-black text-gray-800 uppercase tracking-wide">Craft in Progress</h5>
                            <p class="text-[10px] text-gray-500 font-semibold leading-relaxed">Pricing metrics look good. Model auto-adjusts occupancy rate predictions before ecological pricing.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Showcase (Gallery Portfolio) -->
            <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                <div class="space-y-1">
                    <h3 class="text-lg font-black text-gray-800">Visual Showcase</h3>
                    <p class="text-xs text-muted-text font-medium">Curate the visual identity of your travel package. High-resolution imagery increases booking conversion by 40%.</p>
                </div>

                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Gallery Portfolio</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Photo Previews dynamically managed by Alpine.js -->
                        <template x-for="(photo, index) in galleryPreviews" :key="index">
                            <div class="bg-[#F5F5F5] rounded-3xl p-4 border border-gray-100 flex flex-col space-y-3">
                                <div class="h-44 rounded-2xl overflow-hidden relative">
                                    <img :src="photo.url" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <div class="space-y-0.5">
                                        <p class="font-bold text-gray-800 text-ellipsis overflow-hidden whitespace-nowrap max-w-[120px]" x-text="photo.name"></p>
                                        <p class="text-[9px] text-muted-text font-semibold" x-text="photo.size"></p>
                                    </div>
                                    <button type="button" class="text-red-500 hover:text-red-600" @click="removeGalleryPhoto(index)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
 
                        <!-- Add More dashed box -->
                        <div class="border-2 border-dashed border-gray-200 rounded-3xl bg-gray-50/50 flex flex-col items-center justify-center p-6 cursor-pointer hover:bg-gray-100 transition-all min-h-[220px]" @click="$refs.galleryFilesInput.click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 mb-2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            <span class="text-xs font-bold text-gray-800">Add More</span>
                            <span class="text-[9px] text-gray-400 font-semibold mt-1">Upload multiple photos</span>
                            <input type="file" name="gallery_files[]" x-ref="galleryFilesInput" multiple class="hidden" @change="handleGalleryChange($event)" />
                        </div>
                    </div>
                </div>
 
                <!-- Primary featured photo upload hidden input -->
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-black text-gray-800">Main Featured Image</p>
                        <p class="text-xs text-muted-text font-medium">Select a single thumbnail banner for card listing.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-xs" @click="$refs.mainImageInput.click()">
                            Choose File
                        </button>
                        <input type="file" name="image_file" x-ref="mainImageInput" class="hidden" accept="image/*" @change="previewUrl = URL.createObjectURL($event.target.files[0])" />
                        <span class="text-xs text-muted-text font-bold" x-text="previewUrl ? 'Image Selected' : 'No file chosen'"></span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Footer Actions Panel -->
        <div class="flex items-center justify-between pt-8 border-t border-border-soft mt-8">
            <button type="button" x-show="step === 2" @click="step = 1" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i data-lucide="chevron-left" size="14"></i> Previous
            </button>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ url('/admin/packages') }}" class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                    Discard
                </a>
                <button type="button" @click="step = 2" x-show="step === 1" class="px-8 py-3.5 bg-[#B33A00] hover:bg-[#943000] text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20" style="background-color: #B33A00 !important; color: #ffffff !important;">
                    Save & Next
                </button>
                <button type="submit" x-show="step === 2" class="px-8 py-3.5 bg-[#B33A00] hover:bg-[#943000] text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20" style="background-color: #B33A00 !important; color: #ffffff !important;">
                    Save And Exit
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
