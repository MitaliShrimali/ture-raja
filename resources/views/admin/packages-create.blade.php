@extends('layouts.admin')

@section('admin_title', 'Add New Package')

@section('content')
<!-- Load AlpineJS and Lucide for this view -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<div class="space-y-8 pb-12" x-data="{ 
    step: 1,
    title: 'The Ultimate Bali Escape',
    location: 'Ubud, Seminyak, Uluwatu',
    duration: '5 Days / 4 Nights',
    price: '45999',
    old_price: '55000',
    stock: '10 Left',
    category: 'domestic',
    badge: 'New Delhi',
    group_size: 'Direct Flight',
    rating: '4.8',
    reviews: '10',
    previewUrl: '', 
    galleryPreviews: [],
    brochureName: '',
    inclusions: ['Hotel Stay', 'Daily Breakfast', 'Airport Transfers'],
    exclusions: ['International Airfare', 'Travel Insurance'],
    newInclusion: '',
    newExclusion: '',
    cities: ['Ubud', 'Seminyak', 'Uluwatu'],
    newCity: '',
    keywords: ['Bali Beaches', 'Scuba Diving', 'Temple Tour', 'Nightlife'],
    newKeyword: '',
    addCity() {
        if (this.newCity.trim()) {
            this.cities.push(this.newCity.trim().replace(/,$/, ''));
            this.newCity = '';
        }
    },
    removeCity(i) { this.cities.splice(i, 1); },
    addKeyword() {
        if (this.newKeyword.trim()) {
            this.keywords.push(this.newKeyword.trim().replace(/,$/, ''));
            this.newKeyword = '';
        }
    },
    removeKeyword(i) { this.keywords.splice(i, 1); },
    addInclusion() {
        if (this.newInclusion.trim()) {
            this.inclusions.push(this.newInclusion.trim());
            this.newInclusion = '';
        }
    },
    removeInclusion(i) { this.inclusions.splice(i, 1); },
    addExclusion() {
        if (this.newExclusion.trim()) {
            this.exclusions.push(this.newExclusion.trim());
            this.newExclusion = '';
        }
    },
    removeExclusion(i) { this.exclusions.splice(i, 1); },
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
    days: [
        { title: 'Red Fort', desc: 'Historical Guided Tour', duration: '3 Hours' },
        { title: 'Chandni Chowk', desc: 'Street Food & Rickshaw Ride', duration: '2 Hours' }
    ],
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
    <!-- Custom Style Tags for Step Track, Segmented Controls, and forced styled fields -->
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
            background-color: #e85d26;
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
            cursor: pointer;
        }
        .step-node.active .step-circle {
            border-color: #e85d26;
            background-color: #e85d26;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(232, 93, 38, 0.3);
        }
        .step-node.completed .step-circle {
            border-color: #e85d26;
            background-color: #ffffff;
            color: #e85d26;
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
            color: #e85d26;
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
            color: #e85d26;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        /* Meal pill toggle */
        .meal-pill input:checked ~ span { color: #ffffff; }
        .meal-pill:has(input:checked) {
            background-color: #e85d26;
            border-color: #e85d26;
        }

        /* Force inputs to match agent's grey backgrounds and no border styling */
        #packageMainForm input[type="text"],
        #packageMainForm input[type="number"],
        #packageMainForm select,
        #packageMainForm textarea {
            background-color: #F5F5F5 !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        #packageMainForm input[type="text"]:focus,
        #packageMainForm input[type="number"]:focus,
        #packageMainForm select:focus,
        #packageMainForm textarea:focus {
            background-color: #ffffff !important;
            box-shadow: 0 0 0 2px rgba(232, 93, 38, 0.25) !important;
        }
    </style>

    <!-- Header Actions Panel -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100">
        <div class="flex items-center gap-4">
            <a href="{{ url('/admin/packages') }}" class="p-3 bg-white hover:bg-gray-50 border border-gray-100 rounded-2xl transition-all shadow-sm text-gray-500 hover:text-[#e85d26]">
                <i data-lucide="arrow-left" size="20"></i>
            </a>
            <div>
                <h2 class="font-black text-gray-800 tracking-tight text-2xl" x-text="step === 1 ? 'Create Travel Package' : 'Build Your Journey'"></h2>
                <p class="text-gray-400 font-medium text-xs mt-0.5" x-text="step === 1 ? 'Step 1: Configure core metadata, location, logistics & base pricing.' : 'Step 2: Upload brochures, edit itineraries, and add gallery portfolio.'"></p>
            </div>
        </div>
        
        <!-- Header Step Actions -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ url('/admin/packages') }}" class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                Discard
            </a>
            <button type="button" @click="step = 2" x-show="step === 1" class="px-6 py-3 text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-lg shadow-orange-700/20" style="background-color: #e85d26 !important; color: #ffffff !important;">
                Save & Next <i data-lucide="chevron-right" size="14"></i>
            </button>
            <button type="submit" form="packageMainForm" x-show="step === 2" class="px-6 py-3 text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-lg shadow-orange-700/20" style="background-color: #e85d26 !important; color: #ffffff !important;">
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
    <form id="packageMainForm" action="{{ url('/admin/packages/store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf

        <!-- ==================== STEP 1: IDENTITY & LOGISTICS ==================== -->
        <div x-show="step === 1" class="space-y-8" x-transition>
            <!-- Package Identity Card -->
            <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#e85d26] rounded-xl flex items-center justify-center">
                        <i data-lucide="info" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Package Identity</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package Name</label>
                        <input required type="text" name="title" x-model="title" placeholder="The Ultimate Bali Escape" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Destination Type (Segmented control) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Destination Type</label>
                        <div class="segmented-control">
                            <div class="segmented-btn" :class="category === 'domestic' ? 'active' : ''" @click="category = 'domestic'">Domestic</div>
                            <div class="segmented-btn" :class="category === 'international' ? 'active' : ''" @click="category = 'international'">International</div>
                        </div>
                        <input type="hidden" name="category" :value="category" />
                    </div>
                </div>

                <!-- Cities List (location) -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">City List (Select cities included)</label>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex flex-wrap items-center gap-2 border border-transparent focus-within:bg-white focus-within:ring-2 focus-within:ring-[#e85d26]/25 transition-all">
                        <template x-for="(city, idx) in cities" :key="idx">
                            <span class="px-3.5 py-1.5 text-white rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm" style="background-color: #e85d26 !important; color: #ffffff !important;">
                                <span x-text="city"></span>
                                <i class="cursor-pointer font-black text-xs leading-none" @click="removeCity(idx)">&times;</i>
                            </span>
                        </template>
                        <input type="text" x-model="newCity" @keydown.enter.prevent="addCity()" @keydown.comma.prevent="addCity()" @keydown.space.prevent="addCity()" placeholder="Type city & enter/space..." class="bg-transparent border-none outline-none text-xs font-bold text-gray-700 py-1 px-2 focus:ring-0" style="border: none !important; outline: none !important; box-shadow: none !important;" />
                        <input type="hidden" name="location" :value="cities.join(', ')" />
                    </div>
                </div>
            </div>

            <!-- Logistics & Departure Card -->
            <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#e85d26] rounded-xl flex items-center justify-center">
                        <i data-lucide="calendar" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Logistics & Departure</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Duration -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Duration</label>
                        <select name="duration" x-model="duration" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                            <option value="3 Days / 2 Nights">3 Days / 2 Nights</option>
                            <option value="5 Days / 4 Nights">5 Days / 4 Nights</option>
                            <option value="7 Days / 6 Nights">7 Days / 6 Nights</option>
                            <option value="10 Days / 9 Nights">10 Days / 9 Nights</option>
                        </select>
                    </div>

                    <!-- Package Validity (stock) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package Validity</label>
                        <div class="relative">
                            <i data-lucide="calendar" size="16" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="stock" x-model="stock" placeholder="20 Dec 2024 - 30 Mar 2025" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <!-- Transit Type (group_size) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Transit Type</label>
                        <select name="group_size" x-model="group_size" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
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
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure City</label>
                        <input type="text" name="badge" x-model="badge" placeholder="New Delhi" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Departure State -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure State</label>
                        <input type="text" placeholder="Delhi" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                    </div>

                    <!-- Departure Country -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure Country</label>
                        <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
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
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-[#e85d26] rounded-xl flex items-center justify-center">
                            <i data-lucide="wallet" size="20"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Pricing</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Price Per Person (INR)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">₹</span>
                            <input required type="number" name="price" x-model="price" placeholder="45999" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <!-- Hide Price Toggle -->
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="hide_price" x-model="hidePrice" class="w-5 h-5 rounded border-gray-300 text-[#e85d26] focus:ring-[#e85d26]/25 cursor-pointer" />
                        <span class="text-xs font-bold text-gray-600">Hide price from package listing</span>
                    </label>
                </div>

                <!-- Specifics Card -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-[#e85d26] rounded-xl flex items-center justify-center">
                            <i data-lucide="compass" size="20"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Specifics</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Theme Selection</label>
                            <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                                <option value="Solo Travelers">Solo Travelers</option>
                                <option value="Family Friendly">Family Friendly</option>
                                <option value="Honeymoon Special">Honeymoon Special</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Holiday Type</label>
                            <select class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                                <option value="Multi City">Multi City</option>
                                <option value="Beach Resort">Beach Resort</option>
                                <option value="Hill Station">Hill Station</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Keywords Card -->
            <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-[#e85d26] rounded-xl flex items-center justify-center">
                        <i data-lucide="tag" size="20"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Trip Keywords</h3>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Search Keywords (Helps travelers find you)</label>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex flex-wrap items-center gap-2 border border-transparent focus-within:bg-white focus-within:ring-2 focus-within:ring-[#e85d26]/25 transition-all">
                        <template x-for="(kw, idx) in keywords" :key="idx">
                            <span class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span x-text="kw"></span>
                                <i class="cursor-pointer font-black text-xs leading-none text-gray-400 hover:text-gray-600" @click="removeKeyword(idx)">&times;</i>
                            </span>
                        </template>
                        <input type="text" x-model="newKeyword" @keydown.enter.prevent="addKeyword()" @keydown.comma.prevent="addKeyword()" @keydown.space.prevent="addKeyword()" placeholder="Type keyword & enter/space..." class="bg-transparent border-none outline-none text-xs font-bold text-gray-700 py-1 px-2 focus:ring-0" style="border: none !important; outline: none !important; box-shadow: none !important;" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 2: ITINERARY, MEALS & PHOTOS ==================== -->
        <div x-show="step === 2" class="space-y-8" x-transition>

            <!-- ── Full-width row: Upload Brochure  OR  Add Your Itinerary ── -->
            <div class="flex flex-col md:flex-row gap-4 items-stretch">

                <!-- Brochure card  ~40% -->
                <div class="md:w-[40%] bg-white rounded-[28px] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-text" size="16" class="text-[#e85d26]"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Upload Brochure</h4>
                    </div>
                    <div class="flex-1 w-full rounded-2xl p-5 border-2 border-dashed border-red-200 text-center cursor-pointer hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center min-h-[200px]" @click="$refs.brochureInput.click()">
                        <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mb-3">
                            <i data-lucide="upload-cloud" class="text-[#e85d26]" size="22"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-800" x-text="brochureName ? brochureName : 'Drop your brochure here'"></span>
                        <span class="text-xs text-gray-400 font-medium mt-1">Or click to browse from your computer</span>
                        <button type="button" class="mt-3 px-5 py-2 border border-gray-200 bg-white rounded-full text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-all" @click.stop="$refs.brochureInput.click()">Choose File</button>
                        <span class="text-[10px] text-gray-400 font-medium mt-2 uppercase tracking-wide">PDF FORMAT ONLY &bull; MAX 5MB</span>
                        <input type="file" name="brochure_file" x-ref="brochureInput" accept=".pdf" class="hidden" @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                    </div>
                </div>

                <!-- OR divider -->
                <div class="flex items-center justify-center shrink-0 px-2">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">OR</span>
                </div>

                <!-- Itinerary card  ~60% -->
                <div class="flex-1 bg-white rounded-[28px] border border-gray-100 p-6 space-y-3 shadow-sm flex flex-col">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                            <i data-lucide="pencil" size="16" class="text-[#e85d26]"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Add Your Itinerary</h4>
                    </div>
                    <div class="flex-1 bg-[#F8F8F8] rounded-2xl overflow-hidden border border-gray-100 flex flex-col">
                        <div class="flex items-center gap-1 px-4 py-2.5 border-b border-gray-200 bg-white">
                            <button type="button" onclick="itineraryFormat('bold')" title="Bold" class="w-7 h-7 rounded-md flex items-center justify-center text-sm font-black text-gray-500 hover:bg-orange-50 hover:text-[#e85d26] transition-all">B</button>
                            <button type="button" onclick="itineraryFormat('italic')" title="Italic" class="w-7 h-7 rounded-md flex items-center justify-center text-sm italic font-black text-gray-500 hover:bg-orange-50 hover:text-[#e85d26] transition-all">I</button>
                            <div class="w-px h-4 bg-gray-200 mx-1"></div>
                            <button type="button" onclick="itineraryFormat('list')" title="Bullet list" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-500 hover:bg-orange-50 hover:text-[#e85d26] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </button>
                            <button type="button" onclick="itineraryFormat('link')" title="Insert link" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-500 hover:bg-orange-50 hover:text-[#e85d26] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </button>
                        </div>
                        <textarea id="itinerary-textarea" name="editorial_itinerary" rows="9" placeholder="Explain why this tour is unique..." class="w-full flex-1 bg-transparent border-none py-4 px-5 outline-none text-gray-700 text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- ── 3-col layout: left content + right sidebar ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left 2 Columns -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Editorial Details Card -->
                    <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900">Editorial Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Transfers sub-card -->
                            <div class="bg-[#FFF5F0] rounded-2xl p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                        <span class="text-sm font-bold text-gray-800">Transfers</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(tr, idx) in transfers" :key="idx">
                                        <div class="bg-white rounded-xl py-2.5 px-4 flex items-center justify-between text-xs font-semibold text-gray-700 shadow-sm">
                                            <span x-text="tr"></span>
                                            <button type="button" @click="transfers.splice(idx, 1)" class="text-gray-300 hover:text-gray-500 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="transfers.length === 0">
                                        <p class="text-xs text-gray-400 bg-white rounded-xl py-2.5 px-4">No transfers added yet</p>
                                    </template>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="newTransfer" @keydown.enter.prevent="if(newTransfer.trim()){transfers.push(newTransfer.trim()); newTransfer='';}" placeholder="Enter transfer details..." class="flex-1 bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                    <button type="button" @click="if(newTransfer.trim()){transfers.push(newTransfer.trim()); newTransfer='';}" class="w-10 h-10 shrink-0 text-white rounded-xl text-sm font-bold flex items-center justify-center shadow-sm hover:opacity-90 transition-opacity" style="background-color: #e85d26 !important; color: white !important;">+</button>
                                </div>
                            </div>

                            <!-- Hotels sub-card -->
                            <div class="bg-[#FFF5F0] rounded-2xl p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h20"/><path d="M22 4v16"/><rect x="6" y="12" width="4" height="4"/><rect x="14" y="12" width="4" height="4"/></svg>
                                        <span class="text-sm font-bold text-gray-800">Hotels</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(ht, idx) in hotels" :key="idx">
                                        <div class="bg-white rounded-xl p-3 flex items-center justify-between shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <img :src="ht.image || 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100'" class="w-11 h-11 rounded-xl object-cover" />
                                                <div>
                                                    <p class="text-xs font-bold text-gray-800" x-text="ht.name"></p>
                                                    <p class="text-[10px] text-gray-400 font-medium" x-html="ht.room || 'Standard Room'"></p>
                                                </div>
                                            </div>
                                            <button type="button" @click="hotels.splice(idx, 1)" class="text-gray-300 hover:text-red-500 ml-2 text-lg leading-none">&times;</button>
                                        </div>
                                    </template>
                                    <template x-if="hotels.length === 0">
                                        <p class="text-xs text-gray-400 bg-white rounded-xl py-2.5 px-4">No hotels added yet</p>
                                    </template>
                                </div>
                                <div class="space-y-2 pt-2 border-t border-dashed border-orange-200">
                                    <input type="text" x-model="newHotelName" placeholder="Hotel Name..." class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="newHotelRoom" placeholder="Room Details (e.g. Luxury Room)..." class="flex-1 bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                        <button type="button" @click="if(newHotelName.trim()){ hotels.push({ name: newHotelName.trim(), room: newHotelRoom.trim(), image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100' }); newHotelName=''; newHotelRoom=''; }" class="w-10 h-10 shrink-0 text-white rounded-xl text-sm font-bold flex items-center justify-center shadow-sm hover:opacity-90 transition-opacity" style="background-color: #e85d26 !important; color: white !important;">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meals Included - pill style -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meals Included</label>
                            <div class="flex items-center gap-3">
                                <label class="meal-pill flex items-center gap-2.5 px-4 py-2.5 rounded-full cursor-pointer select-none border border-gray-200 transition-all">
                                    <input type="checkbox" name="included[]" value="Breakfast" checked class="hidden">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-semibold">Breakfast</span>
                                </label>
                                <label class="meal-pill flex items-center gap-2.5 px-4 py-2.5 rounded-full cursor-pointer select-none border border-gray-200 transition-all">
                                    <input type="checkbox" name="included[]" value="Lunch" class="hidden">
                                    <span class="text-xs font-semibold text-gray-700">Lunch</span>
                                </label>
                                <label class="meal-pill flex items-center gap-2.5 px-4 py-2.5 rounded-full cursor-pointer select-none border border-gray-200 transition-all">
                                    <input type="checkbox" name="included[]" value="Dinner" checked class="hidden">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-semibold">Dinner</span>
                                </label>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>
                            <textarea name="excluded[]" rows="3" placeholder="Specific booking policies for this package..." class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-[#e85d26]/15 transition-all text-sm text-gray-600 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Sightseeing Details Card -->
                    <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1c7ed6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>
                            </div>
                            <button type="button" @click="addDay()" class="px-5 py-2.5 text-white rounded-full text-sm font-semibold transition-all flex items-center gap-1.5" style="background-color: #e85d26 !important; color: #ffffff !important;">
                                + Add Point
                            </button>
                        </div>

                        <div class="overflow-hidden border border-gray-100 rounded-2xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Location</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Activity</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Duration</th>
                                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(day, index) in days" :key="index">
                                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all">
                                            <td class="py-4 px-6">
                                                <input required type="text" name="itinerary_titles[]" x-model="day.title" class="w-full bg-transparent border-none outline-none font-bold text-gray-800 focus:ring-0 p-0 text-sm" placeholder="e.g. Red Fort" />
                                            </td>
                                            <td class="py-4 px-6">
                                                <input required type="text" name="itinerary_descriptions[]" x-model="day.desc" class="w-full bg-transparent border-none outline-none text-gray-500 focus:ring-0 p-0 text-sm" placeholder="e.g. Historical Guided Tour" />
                                            </td>
                                            <td class="py-4 px-6">
                                                <input type="text" name="itinerary_durations[]" x-model="day.duration" class="w-full bg-transparent border-none outline-none text-gray-500 focus:ring-0 p-0 text-sm" placeholder="e.g. 3 Hours" />
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <button type="button" @click="removeDay(index)" class="p-1.5 text-gray-300 hover:text-red-400 transition-all" x-show="days.length > 1" title="Remove">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Inclusions & Exclusions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Inclusions Card -->
                        <div class="bg-[#F0FAF5] rounded-[28px] border border-green-100 p-6 space-y-4 shadow-sm" style="background-color: #F0FAF5 !important; border-color: #d3f9d8 !important;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[#2f9e44]">
                                    <i data-lucide="check-circle" size="20"></i>
                                    <h4 class="text-sm font-bold">Inclusions</h4>
                                </div>
                            </div>
                            <ul class="space-y-2">
                                <template x-for="(item, i) in inclusions" :key="i">
                                    <li class="flex items-center gap-2 text-xs font-medium text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2f9e44] shrink-0" style="background-color: #2f9e44 !important;"></span>
                                        <span x-text="item" class="flex-1"></span>
                                        <input type="hidden" name="included[]" :value="item">
                                        <button type="button" @click="removeInclusion(i)" class="text-gray-300 hover:text-red-400 transition-all text-xs">×</button>
                                    </li>
                                </template>
                            </ul>
                            <div class="flex gap-2">
                                <input type="text" x-model="newInclusion" @keydown.enter.prevent="addInclusion()" placeholder="Add inclusion..." class="flex-1 bg-white border-none rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-green-300" />
                                <button type="button" @click="addInclusion()" class="px-3 py-2 bg-[#2f9e44] text-white rounded-xl text-xs font-bold" style="background-color: #2f9e44 !important;">+</button>
                            </div>
                        </div>

                        <!-- Exclusions Card -->
                        <div class="bg-[#FFF5F5] rounded-[28px] border border-red-100 p-6 space-y-4 shadow-sm" style="background-color: #FFF5F5 !important; border-color: #ffe3e3 !important;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[#e03131]">
                                    <i data-lucide="x-circle" size="20"></i>
                                    <h4 class="text-sm font-bold">Exclusions</h4>
                                </div>
                            </div>
                            <ul class="space-y-2">
                                <template x-for="(item, i) in exclusions" :key="i">
                                    <li class="flex items-center gap-2 text-xs font-medium text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#e03131] shrink-0" style="background-color: #e03131 !important;"></span>
                                        <span x-text="item" class="flex-1"></span>
                                        <input type="hidden" name="excluded[]" :value="item">
                                        <button type="button" @click="removeExclusion(i)" class="text-gray-300 hover:text-red-400 transition-all text-xs">×</button>
                                    </li>
                                </template>
                            </ul>
                            <div class="flex gap-2">
                                <input type="text" x-model="newExclusion" @keydown.enter.prevent="addExclusion()" placeholder="Add exclusion..." class="flex-1 bg-white border-none rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-red-300" />
                                <button type="button" @click="addExclusion()" class="px-3 py-2 bg-[#e03131] text-white rounded-xl text-xs font-bold" style="background-color: #e03131 !important;">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Column -->
                <div class="space-y-8">

                    <!-- Pricing & Dates -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest pl-1">Pricing & Dates</h4>
                        
                        <div class="space-y-4">
                            <!-- Base Price -->
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Base Price</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">₹</span>
                                    <input type="number" name="old_price" placeholder="55000" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none transition-all font-bold text-foreground text-sm" />
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package Start Date</label>
                                <div class="relative">
                                    <i data-lucide="calendar" size="16" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" placeholder="12 October, 2026" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Essential Amenities -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Essential Amenities</h4>
                        
                        <div class="space-y-4">
                            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="wifi" class="text-gray-400" size="18"></i>
                                    <span class="text-xs font-bold text-gray-700">Free Wifi</span>
                                </div>
                                <input type="checkbox" checked class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            </label>

                            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="coffee" class="text-gray-400" size="18"></i>
                                    <span class="text-xs font-bold text-gray-700">Breakfast Included</span>
                                </div>
                                <input type="checkbox" checked class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            </label>

                            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="shield" class="text-gray-400" size="18"></i>
                                    <span class="text-xs font-bold text-gray-700">Travel Insurance</span>
                                </div>
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            </label>
                        </div>
                    </div>

                    <!-- Gallery Portfolio Card -->
                    <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                        <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Gallery Portfolio</h4>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(img, idx) in galleryPreviews" :key="idx">
                                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden group border border-gray-100">
                                    <img :src="img.url" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" @click="removeGalleryPhoto(idx)" class="p-2 bg-white/20 hover:bg-white/40 text-white rounded-full backdrop-blur-sm transition-all">
                                            <i data-lucide="trash-2" size="14"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-orange-50/20" @click="$refs.galleryFilesInput.click()">
                                <i data-lucide="plus" class="text-gray-400 mb-1" size="20"></i>
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
            <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-8">
                <button type="button" x-show="step === 2" @click="step = 1" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                    <i data-lucide="chevron-left" size="14"></i> Previous
                </button>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ url('/admin/packages') }}" class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                        Discard
                    </a>
                    <button type="button" @click="step = 2" x-show="step === 1" class="px-8 py-3.5 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20" style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save & Next
                    </button>
                    <button type="submit" x-show="step === 2" class="px-8 py-3.5 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20" style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save And Exit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    // Inserts formatting at cursor / wraps selection in the itinerary textarea
    function itineraryFormat(type) {
        const ta = document.getElementById('itinerary-textarea');
        if (!ta) return;

        const start  = ta.selectionStart;
        const end    = ta.selectionEnd;
        const before = ta.value.substring(0, start);
        const sel    = ta.value.substring(start, end);
        const after  = ta.value.substring(end);

        let insert = '';
        let cursorOffset = 0;

        if (type === 'bold') {
            insert = sel ? `**${sel}**` : '**bold text**';
            cursorOffset = sel ? insert.length : 2; // land inside the stars if no selection
        } else if (type === 'italic') {
            insert = sel ? `_${sel}_` : '_italic text_';
            cursorOffset = sel ? insert.length : 1;
        } else if (type === 'list') {
            // Add bullet on new line
            const prefix = before.length > 0 && !before.endsWith('\n') ? '\n' : '';
            insert = `${prefix}• ${sel || 'List item'}`;
            cursorOffset = insert.length;
        } else if (type === 'link') {
            const url = prompt('Enter URL (e.g. https://example.com):');
            if (!url) return;
            const label = sel || 'Link text';
            insert = `[${label}](${url})`;
            cursorOffset = insert.length;
        }

        ta.value = before + insert + after;

        // Restore focus and place cursor after the inserted text
        ta.focus();
        const newPos = start + cursorOffset;
        ta.setSelectionRange(newPos, newPos);
    }
</script>
@endsection
