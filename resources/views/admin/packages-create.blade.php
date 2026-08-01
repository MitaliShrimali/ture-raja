@extends('layouts.admin')

@section('admin_title', 'Add New Package')

@section('content')
    <!-- Load Lucide for this view -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="space-y-4 pb-12" @itinerary-updated.window="itineraryContent = $event.detail" x-data="{ 
        step: 1,
        title: '',
        location: '',
        duration: '',
        price: '',
        inrPrice: '',
        currency: '₹',
        rates: { '₹': 1, '$': 86.5, '€': 89.2, '£': 105.4, 'AED': 23.5 },
        updatePrice(fromBase) {
            if (fromBase) {
                this.inrPrice = (this.price * this.rates[this.currency]).toFixed(2);
                if(this.inrPrice.endsWith('.00')) this.inrPrice = Math.round(this.inrPrice);
            } else {
                if(this.inrPrice) {
                    this.price = (this.inrPrice / this.rates[this.currency]).toFixed(2);
                    if(this.price.endsWith('.00')) this.price = Math.round(this.price);
                } else {
                    this.price = '';
                }
            }
        },
        old_price: '',
        stock: '',
        validity: '',
        sightseeing: '',
        category: '{{ strtolower($category ?? "domestic") }}',
        categories: [],
        badge: '',
        group_size: '',
        rating: '',
        reviews: '',
        previewUrl: '', 
        galleryPreviews: [],
        brochureName: '',
        itineraryContent: '',
        inclusions: [],
        exclusions: [],
        newInclusion: '',
        newExclusion: '',
        editingInclusionIndex: null,
        editingExclusionIndex: null,
        customAmenities: [],
        newAmenity: '',
        addAmenity() {
            if (this.newAmenity.trim()) {
                this.customAmenities.push(this.newAmenity.trim());
                this.newAmenity = '';
            }
        },
        cities: [],
        newCity: '',
        keywords: [],
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
        hotels: [],
        newTransfer: '',
        newHotelName: '',
        newHotelRoom: '',
        newHotelImage: '',
        editingHotelIndex: null,
        previewPdf() {
            if (this.$refs.brochureInput && this.$refs.brochureInput.files && this.$refs.brochureInput.files[0]) {
                window.open(URL.createObjectURL(this.$refs.brochureInput.files[0]), '_blank');
            }
        },
        clearPdf() {
            this.brochureName = '';
            if (this.$refs.brochureInput) this.$refs.brochureInput.value = '';
        },
        validity_from: '',
        validity_to: '',
        toPicker: null,
        nights: '',
        updateDurationFromNights() {
            if (this.nights && !isNaN(parseInt(this.nights))) {
                let n = parseInt(this.nights);
                this.duration = `${n} Nights / ${n + 1} Days`;
            } else {
                this.duration = '';
            }
        },
        init() {
            if (this.duration && this.duration.includes(' Nights')) {
                this.nights = parseInt(this.duration.split(' ')[0]);
            }

            flatpickr(this.$refs.validityPicker, {
                dateFormat: 'd M Y',
                defaultDate: this.validity,
                minDate: 'today',
                onChange: (selectedDates, dateStr) => {
                    this.validity = dateStr;
                }
            });
        },
        days: [
            { title: '', desc: '', duration: '' }
        ],
        addDay() {
            this.days.push({ title: '', desc: '' });
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
                    file: files[i],
                    is_existing: false
                });
            }
        },
        removeGalleryPhoto(index) {
            this.galleryPreviews.splice(index, 1);
        },
        
        // Gallery Modal State
        isGalleryModalOpen: false,
        galleryCurrentFolder: null,
        galleryFolders: [],
        galleryImages: [],
        galleryBreadcrumbs: [],
        galleryLoading: false,

        fetchGallery(folderId = null) {
            this.galleryLoading = true;
            this.galleryCurrentFolder = folderId;
            let url = '/admin/api/gallery';
            if (folderId) url += '?folder=' + folderId;
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.galleryFolders = data.folders;
                    this.galleryImages = data.images;
                    this.galleryBreadcrumbs = data.breadcrumbs;
                    this.galleryLoading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.galleryLoading = false;
                });
        },
        openGalleryModal() {
            this.isGalleryModalOpen = true;
            this.fetchGallery();
        },
        toggleGalleryImage(image) {
            const index = this.galleryPreviews.findIndex(p => p.id === image.id || p.url === '/' + image.file_path);
            if (index === -1) {
                this.galleryPreviews.push({
                    id: image.id,
                    url: '/' + image.file_path,
                    name: image.name,
                    size: 'From Gallery',
                    is_existing: true
                });
            } else {
                this.galleryPreviews.splice(index, 1);
            }
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

            .step-node.active .step-label,
            .step-node.completed .step-label {
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
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            /* Meal pill toggle */
            .meal-pill input:checked~span {
                color: #ffffff;
            }

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
                <a href="{{ url('/admin/packages') }}"
                    class="p-3 bg-white hover:bg-gray-50 border border-gray-100 rounded-2xl transition-all shadow-sm text-gray-500 hover:text-[#e85d26]">
                    <i data-lucide="arrow-left" size="20"></i>
                </a>
                <div>
                    <h2 class="font-black text-gray-800 tracking-tight text-2xl">Create Travel Package</h2>
                    <p class="text-gray-400 font-medium text-xs mt-0.5">Configure package details, upload brochures, and add
                        gallery portfolio.</p>
                </div>
            </div>

        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <p class="font-bold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Container -->
        <form id="packageMainForm" action="{{ url('/admin/packages/store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-10">
            @csrf

            <!-- ==================== STEP 1: IDENTITY & LOGISTICS ==================== -->
            <div class="space-y-8">
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
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package
                                Name</label>
                            <input required type="text" name="title" x-model="title" placeholder="The Ultimate Bali Escape"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                        </div>

                        <!-- Agent Dropdown -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Publish On
                                Behalf Of (Agent)</label>
                            @php
                                $paidAgents = $agents->filter(function ($a) {
                                    return !empty($a->plan_id) && $a->plan_id > 1; });
                                $freeAgents = $agents->filter(function ($a) {
                                    return empty($a->plan_id) || $a->plan_id <= 1; });
                            @endphp
                            <div class="relative" x-data="{
                                open: false,
                                search: '',
                                selected: '{{ old('agent') ?? "" }}',
                                options: [
                                    { value: '', label: 'Admin (Default / Miths Holidays)', group: 'Default' },
                                    @foreach($paidAgents as $ag)
                                    { value: '{{ addslashes($ag->name) }}', label: '{{ addslashes($ag->name . ($ag->agency_name ? " (".$ag->agency_name.")" : "")) }}', group: 'Paid Agents' },
                                    @endforeach
                                    @foreach($freeAgents as $ag)
                                    { value: '{{ addslashes($ag->name) }}', label: '{{ addslashes($ag->name . ($ag->agency_name ? " (".$ag->agency_name.")" : "")) }}', group: 'Free Agents' },
                                    @endforeach
                                ],
                                get groupedFilteredOptions() {
                                    let opts = this.search === '' 
                                        ? this.options 
                                        : this.options.filter(opt => opt.label.toLowerCase().includes(this.search.toLowerCase()));
                                    
                                    let groups = {};
                                    opts.forEach(opt => {
                                        let g = opt.group || 'Default';
                                        if (!groups[g]) groups[g] = [];
                                        groups[g].push(opt);
                                    });
                                    
                                    return Object.keys(groups).map(k => ({ name: k, items: groups[k] }));
                                },
                                get selectedLabel() {
                                    const opt = this.options.find(o => o.value === this.selected);
                                    return opt ? opt.label : 'Admin (Default / Miths Holidays)';
                                },
                                selectOption(val) {
                                    this.selected = val;
                                    this.open = false;
                                    this.search = '';
                                }
                            }" @click.outside="open = false">
                                <!-- Hidden real input -->
                                <input type="hidden" name="agent" :value="selected">
                                
                                <!-- Custom Dropdown Button -->
                                <button type="button" @click="open = !open; if(open) { $nextTick(() => $refs.searchInput.focus()); }"
                                    class="w-full text-left bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 pr-10 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-gray-800 text-sm">
                                    <span x-text="selectedLabel" class="block truncate"></span>
                                </button>
                                <i data-lucide="chevron-down" size="16" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-xl flex flex-col" style="display: none; max-height: 350px;">
                                    <!-- Search Bar Inside Dropdown -->
                                    <div class="p-3 border-b border-gray-100 sticky top-0 bg-white z-10 rounded-t-2xl">
                                        <div class="relative">
                                            <i data-lucide="search" size="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                            <input x-ref="searchInput" type="text" x-model="search" placeholder="Search agent..." class="w-full bg-[#F5F5F5] border-none rounded-xl py-2 pl-9 pr-4 outline-none text-sm font-semibold focus:ring-2 focus:ring-[#e85d26]/25" @keydown.escape="open = false">
                                        </div>
                                    </div>
                                    
                                    <!-- Options List -->
                                    <ul class="overflow-y-auto flex-1 p-2 space-y-1" style="max-height: 250px;">
                                        <template x-if="groupedFilteredOptions.length === 0">
                                            <li class="px-4 py-3 text-sm text-gray-400 font-medium text-center">No agents found</li>
                                        </template>
                                        <template x-for="group in groupedFilteredOptions" :key="group.name">
                                            <div>
                                                <template x-if="group.name !== 'Default' && group.items.length > 0">
                                                    <div class="px-4 py-1 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2 mb-1" x-text="group.name"></div>
                                                </template>
                                                <template x-for="opt in group.items" :key="opt.value">
                                                    <li @click="selectOption(opt.value)" class="px-4 py-2 text-sm font-bold rounded-xl cursor-pointer hover:bg-orange-50 hover:text-[#e85d26] transition-colors truncate" :class="selected === opt.value ? 'bg-orange-50 text-[#e85d26]' : 'text-gray-700'" x-text="opt.label">
                                                    </li>
                                                </template>
                                            </div>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Destination Type (Segmented control) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Destination
                                Type</label>
                            <div class="segmented-control">
                                <div class="segmented-btn" :class="category === 'domestic' ? 'active' : ''"
                                    @click="category = 'domestic'">Domestic</div>
                                <div class="segmented-btn" :class="category === 'international' ? 'active' : ''"
                                    @click="category = 'international'">International</div>
                            </div>
                            <input type="hidden" name="category" :value="category">
                        </div>

                        <!-- Categories (Multi-select) -->
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Categories</label>
                            <div class="flex flex-wrap gap-2">
                                <template
                                    x-for="cat in ['Mountain', 'Safari', 'Desert', 'Flower', 'Beach', 'Temples', 'Yacht']">
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 border rounded-xl cursor-pointer transition-all"
                                        :class="categories.includes(cat) ? 'border-[#e85d26] bg-orange-50 text-[#e85d26]' : 'border-gray-200 bg-white text-gray-600'">
                                        <input type="checkbox" name="categories_list[]" :value="cat" x-model="categories"
                                            class="hidden">
                                        <span class="text-xs font-bold" x-text="cat"></span>
                                    </label>
                                </template>
                            </div>
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
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Duration
                                (Nights)</label>
                            <input type="hidden" name="duration" x-model="duration">
                            <div class="flex items-center gap-4">
                                <input type="number" min="1" x-model="nights" @input="updateDurationFromNights"
                                    class="w-1/3 bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm"
                                    placeholder="Enter nights" />
                                <span class="text-sm font-bold text-gray-500"
                                    x-text="nights ? (parseInt(nights) + 1) + ' Days' : 'Days will calculate automatically'"></span>
                            </div>
                        </div>

                        <!-- Package Validity -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package
                                Expiry Date</label>
                            <div class="relative">
                                <i data-lucide="calendar" size="16"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="validity" x-model="validity" x-ref="validityPicker"
                                    placeholder="Select Expiry Date"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-10 pr-4 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                            </div>
                        </div>

                        <!-- Transit Type (group_size) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Transit
                                Type</label>
                            @php
                                $dbTransits = DB::table('transits')->where('status', 'Active')->get();
                                $dbTransits = $dbTransits->sortBy(function($t) {
                                    $name = strtolower(trim($t->name));
                                    $orderMap = [
                                        'land' => 1,
                                        'bullet' => 2,
                                        'flight' => 3,
                                        'train' => 4,
                                        'bus' => 5,
                                        'cruise' => 6,
                                        'tracking' => 7,
                                        'helicopter' => 8,
                                    ];
                                    $norm = $name;
                                    if (str_contains($name, 'land') || str_contains($name, 'custom')) $norm = 'land';
                                    elseif (str_contains($name, 'bullet') || str_contains($name, 'bike')) $norm = 'bullet';
                                    elseif (str_contains($name, 'flight') || str_contains($name, 'air')) $norm = 'flight';
                                    elseif (str_contains($name, 'train') || str_contains($name, 'rail')) $norm = 'train';
                                    elseif (str_contains($name, 'bus') || str_contains($name, 'coach')) $norm = 'bus';
                                    elseif (str_contains($name, 'cruise') || str_contains($name, 'ship') || str_contains($name, 'boat')) $norm = 'cruise';
                                    elseif (str_contains($name, 'track') || str_contains($name, 'hike') || str_contains($name, 'trek')) $norm = 'tracking';
                                    elseif (str_contains($name, 'helicopter') || str_contains($name, 'sky')) $norm = 'helicopter';
                                    return $orderMap[$norm] ?? 999;
                                });
                            @endphp
                            <select name="group_size" x-model="group_size"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                                @foreach($dbTransits as $t)
                                    <option value="{{ $t->name }}">{{ $t->name }}</option>
                                @endforeach
                                @if($dbTransits->isEmpty())
                                    <option value="Direct Flight">Direct Flight</option>
                                    <option value="Connecting Flight">Connecting Flight</option>
                                    <option value="Cruise Liner">Cruise Liner</option>
                                    <option value="Luxury Bus">Luxury Bus</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Departure City -->
                        <div class="space-y-2 relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                City</label>
                            <input type="text" name="departure_city" id="departureCity" placeholder="New Delhi"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm"
                                autocomplete="off" />
                            <div id="departureCitySuggestions"
                                class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>

                        <!-- Departure State -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                State</label>
                            <input type="text" name="departure_state" id="departureState" placeholder="Delhi"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                        </div>

                        <!-- Departure Country -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                Country</label>
                            <input type="text" name="departure_country" id="departureCountry" placeholder="India"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
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
                            <h3 class="text-lg font-black text-gray-800">Pricing & Currency</h3>
                        </div>

                        <!-- Currency Dropdown -->
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Currency</label>
                            <div class="relative">
                                <i data-lucide="coins" size="16"
                                    class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select name="currency" x-model="currency" @change="updatePrice(false)"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-gray-800 text-sm appearance-none">
                                    <option value="₹">INR (₹)</option>
                                    <option value="$">USD ($)</option>
                                    <option value="AED">AED</option>
                                    <option value="€">EUR (€)</option>
                                    <option value="£">GBP (£)</option>
                                </select>
                                <i data-lucide="chevron-down" size="16"
                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Price Per
                                Person (<span x-text="currency"></span>)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400"
                                        x-text="currency"></span>
                                    <input required type="number" step="0.01" name="price" x-model="price"
                                        @input="updatePrice(true)" placeholder="45999"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                                </div>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400"
                                        x-text="currency"></span>
                                    <input type="number" step="0.01" name="old_price" x-model="old_price"
                                        placeholder="Old Price"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm line-through text-gray-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Hide Price Toggle -->
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="hide_price" x-model="hidePrice"
                                class="w-5 h-5 rounded border-gray-300 text-[#e85d26] focus:ring-[#e85d26]/25 cursor-pointer" />
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
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Theme
                                    Selection</label>
                                <select name="theme"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                                    <option value="">Select Theme</option>
                                    @foreach($themes as $t)
                                        <option value="{{ $t->name }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Holiday
                                    Type</label>
                                <select name="holiday_type"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm">
                                    <option value="">Select Holiday Type</option>
                                    @foreach($holidayTypes as $h)
                                        <option value="{{ $h->name }}">{{ $h->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags & Keywords Card -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                            <i data-lucide="tag" size="20"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Tags & Keywords</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                        <!-- Tag Name (1 column) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Tag Name
                                (e.g. 25% Off)</label>
                            <input type="text" name="badge" placeholder="e.g. 25% Off"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm" />
                        </div>

                        <!-- Search Keywords (2 columns) -->
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Trip Location/Search Keywords <span class="text-red-500">*</span></label>
                            
                            <!-- Tags Flex Container -->
                            <div class="flex flex-wrap gap-3">
                                <template x-for="(keyword, i) in keywords" :key="i">
                                    <div class="flex items-center justify-between min-w-[140px] px-4 py-2.5 bg-white rounded-md border border-gray-200 shadow-sm">
                                        <span class="text-xs font-medium text-gray-700" x-text="keyword"></span>
                                        <button type="button" @click="removeKeyword(i)" class="text-gray-400 hover:text-red-500 ml-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Add More Input & Button -->
                            <div class="flex items-center gap-3">
                                <input type="text" x-model="newKeyword" @keydown.enter.prevent="addKeyword()" placeholder="Type keyword..." class="w-48 bg-white border border-gray-200 rounded-md py-2.5 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-primary/30 shadow-sm" />
                                <button type="button" @click="addKeyword()" class="px-4 py-2.5 bg-[#EEF2FF] text-[#4F46E5] rounded-md text-xs font-bold hover:bg-[#E0E7FF] transition-colors">Add more</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- ==================== STEP 2: ITINERARY, MEALS & PHOTOS ==================== -->
            <div class="space-y-8 mt-8">

                <!-- ── Full-width row: Upload Brochure  OR  Itinerary (Day-by-Day Plan) ── -->
                <div class="flex flex-col md:flex-row gap-4 items-stretch">

                    <!-- Brochure card  ~40% -->
                    <div x-show="!itineraryContent"
                        class="md:w-[40%] bg-white rounded-[28px] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" size="16" class="text-[#e85d26]"></i>
                            </div>
                            <h4 class="text-sm font-bold text-gray-800">Upload Brochure</h4>
                        </div>
                        <div class="flex-1 w-full rounded-2xl p-5 border-2 border-dashed border-red-200 text-center cursor-pointer hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center min-h-[200px]"
                            @click="if(!brochureName) $refs.brochureInput.click()">
                            <template x-if="!brochureName">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="upload-cloud" class="text-primary" size="22"></i>
                                    </div>
                                    <span class="text-sm font-bold text-gray-800">Drop your brochure here</span>
                                    <span class="text-xs text-gray-400 font-medium mt-1">Or click to browse from your
                                        computer</span>
                                    <button type="button"
                                        class="mt-3 px-5 py-2 border border-gray-200 bg-white rounded-full text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-all">Choose
                                        File</button>
                                    <span class="text-[10px] text-gray-400 font-medium mt-2 uppercase tracking-wide">PDF
                                        FORMAT ONLY &bull; MAX 5MB</span>
                                </div>
                            </template>
                            <template x-if="brochureName">
                                <div class="flex flex-col items-center justify-center w-full space-y-4">
                                    <div
                                        class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    </div>
                                    <div class="text-center px-4 w-full">
                                        <p class="text-sm font-black text-gray-800 truncate max-w-[220px] mx-auto"
                                            x-text="brochureName"></p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">
                                            Brochure Selected</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click.stop="previewPdf()"
                                            class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-100 transition-colors shadow-sm"
                                            title="Preview PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                        <button type="button" @click.stop="clearPdf()"
                                            class="w-10 h-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors shadow-sm"
                                            title="Delete PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <input type="file" name="brochure_file" x-ref="brochureInput" accept=".pdf" class="hidden"
                                @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                        </div>
                      </div>

                    <!-- Itinerary card  ~60% -->
                    <div x-show="!brochureName"
                        class="flex-1 bg-white rounded-[28px] border border-gray-100 p-6 space-y-3 shadow-sm flex flex-col">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="pencil" size="16" class="text-primary"></i>
                            </div>
                            <h4 class="text-sm font-bold text-gray-800">Itinerary (Day-by-Day Plan)</h4>
                        </div>
                        <textarea id="itinerary-textarea" name="editorial_itinerary" rows="9"
                            placeholder="Explain why this tour is unique..."
                            class="w-full flex-1 bg-transparent border-none py-4 px-5 outline-none text-gray-700 text-sm resize-none"></textarea>
                        <div class="flex justify-end items-center pt-2 border-t border-gray-50">
                            <button type="button"
                                @click="if(tinymce.get('itinerary-textarea')) { tinymce.get('itinerary-textarea').setContent(''); window.dispatchEvent(new CustomEvent('itinerary-updated', { detail: '' })); }"
                                class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1.5 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                                Clear All Written
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ── 3-col layout: left content + right sidebar ── -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <!-- Left 2 Columns -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Editorial Details Card -->
                        <div x-show="!brochureName"
                            class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-6 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900">Editorial Details</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                                <!-- Hotels sub-card -->
                                <div class="bg-[#FFF5F0] rounded-2xl p-5 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 4v16" />
                                                <path d="M2 8h20" />
                                                <path d="M22 4v16" />
                                                <rect x="6" y="12" width="4" height="4" />
                                                <rect x="14" y="12" width="4" height="4" />
                                            </svg>
                                            <span class="text-sm font-bold text-gray-800">Hotels</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(ht, idx) in hotels" :key="idx">
                                            <div
                                                class="bg-white rounded-xl p-3 flex items-center justify-between shadow-sm">
                                                <input type="hidden" name="hotels[]" :value="JSON.stringify(ht)">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <img :src="ht.image || 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100'"
                                                        class="w-11 h-11 rounded-xl object-cover" />
                                                    <div class="flex-1">
                                                        <template x-if="editingHotelIndex !== idx">
                                                            <div>
                                                                <p class="text-xs font-bold text-gray-800 cursor-pointer hover:underline"
                                                                    @click="editingHotelIndex = idx" x-text="ht.name"></p>
                                                                <p class="text-[10px] text-gray-400 font-medium cursor-pointer hover:underline"
                                                                    @click="editingHotelIndex = idx"
                                                                    x-html="ht.room || 'Standard Room'"></p>
                                                            </div>
                                                        </template>
                                                        <template x-if="editingHotelIndex === idx">
                                                            <div class="space-y-1 pr-4">
                                                                <input type="text" x-model="ht.name"
                                                                    class="w-full bg-gray-50 border border-gray-100 rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                                    @keydown.enter.prevent="editingHotelIndex = null" />
                                                                <input type="text" x-model="ht.room"
                                                                    class="w-full bg-gray-50 border border-gray-100 rounded-lg py-1 px-2 text-[10px] outline-none focus:ring-1 focus:ring-primary/20"
                                                                    @keydown.enter.prevent="editingHotelIndex = null" />
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                    <button type="button"
                                                        @click="editingHotelIndex = (editingHotelIndex === idx ? null : idx)"
                                                        class="text-gray-400 hover:text-blue-500 transition-colors"
                                                        title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 20h9"></path>
                                                            <path
                                                                d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button type="button" @click="hotels.splice(idx, 1)"
                                                        class="text-gray-400 hover:text-red-500 transition-colors"
                                                        title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="hotels.length === 0">
                                            <p class="text-xs text-gray-400 bg-white rounded-xl py-2.5 px-4">No hotels added
                                                yet</p>
                                        </template>
                                    </div>
                                    <div class="space-y-2 pt-2 border-t border-dashed border-orange-200">
                                        <input type="text" x-model="newHotelName" placeholder="Hotel Name..."
                                            class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="newHotelRoom"
                                                placeholder="Room Details (e.g. Luxury Room)..."
                                                class="flex-1 bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                            <button type="button"
                                                @click="if(newHotelName.trim()){ hotels.push({ name: newHotelName.trim(), room: newHotelRoom.trim(), image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100' }); newHotelName=''; newHotelRoom=''; }"
                                                class="w-10 h-10 shrink-0 text-white rounded-xl text-sm font-bold flex items-center justify-center shadow-sm hover:opacity-90 transition-opacity"
                                                style="background-color: #e85d26 !important; color: white !important;">+</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- About Tours sub-card -->
                                <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F0642F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        <span class="text-sm font-bold text-red-500">About Tours</span>
                                    </div>
                                    <textarea name="about_tours" rows="5"
                                        class="w-full h-[calc(100%-2.5rem)] bg-[#E8E8E8] border-none rounded-xl py-3 px-4 text-xs font-medium text-gray-700 outline-none focus:ring-2 focus:ring-primary/20 resize-none"
                                        placeholder="Brief overview about the tour..."></textarea>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="space-y-2">


                                <!-- Sightseeing Details List -->
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terms &
                                    Conditions</label>
                                <textarea name="terms" rows="3" placeholder="Specific booking policies for this package..."
                                    class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm text-gray-600 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Sightseeing Details Card -->
                        <div x-show="!brochureName"
                            class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                        fill="none" stroke="#1c7ed6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>
                                </div>
                                <button type="button" @click="addDay()"
                                    class="px-5 py-2.5 text-white rounded-full text-sm font-semibold transition-all flex items-center gap-1.5"
                                    style="background-color: #e85d26 !important; color: #ffffff !important;">
                                    + Add Point
                                </button>
                            </div>

                            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th
                                                class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                Location</th>
                                            <th
                                                class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                Activity</th>
                                            <th
                                                class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(day, index) in days" :key="index">
                                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all">
                                                <td class="py-4 px-6">
                                                    <input :required="!brochureName" type="text" name="itinerary_titles[]"
                                                        x-model="day.title"
                                                        class="w-full bg-transparent border-none outline-none font-bold text-gray-800 focus:ring-0 p-0 text-sm"
                                                        placeholder="e.g. Red Fort" />
                                                </td>
                                                <td class="py-4 px-6">
                                                    <input :required="!brochureName" type="text"
                                                        name="itinerary_descriptions[]" x-model="day.desc"
                                                        class="w-full bg-transparent border-none outline-none text-gray-500 focus:ring-0 p-0 text-sm"
                                                        placeholder="e.g. Historical Guided Tour" />
                                                    <input type="hidden" name="itinerary_durations[]"
                                                        x-model="day.duration" />
                                                </td>
                                                <td class="py-4 px-6 text-right">
                                                    <button type="button" @click="removeDay(index)"
                                                        class="p-1.5 text-gray-400 hover:text-red-500 transition-all"
                                                        title="Remove">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="inline-block">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Inclusions & Exclusions Grid -->
                        <div x-show="!brochureName" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Inclusions Card -->
                            <div class="bg-[#F0FAF5] rounded-[28px] border border-green-100 p-6 space-y-4 shadow-sm"
                                style="background-color: #F0FAF5 !important; border-color: #d3f9d8 !important;">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-[#2f9e44]">
                                        <i data-lucide="check-circle" size="20"></i>
                                        <h4 class="text-sm font-bold">Inclusions</h4>
                                    </div>
                                </div>
                                <ul class="space-y-2">
                                    <template x-for="(item, i) in inclusions" :key="i">
                                        <li
                                            class="flex items-center gap-2 text-xs font-medium text-gray-700 bg-white/60 p-2.5 rounded-xl border border-gray-100/50 shadow-sm w-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2f9e44] shrink-0"
                                                style="background-color: #2f9e44 !important;"></span>
                                            <div class="flex-1">
                                                <template x-if="editingInclusionIndex !== i">
                                                    <span x-text="item" class="cursor-pointer hover:underline"
                                                        @click="editingInclusionIndex = i"></span>
                                                </template>
                                                <template x-if="editingInclusionIndex === i">
                                                    <input type="text" x-model="inclusions[i]"
                                                        @keydown.enter.prevent="editingInclusionIndex = null"
                                                        @blur="editingInclusionIndex = null"
                                                        class="w-full bg-[#F5F5F5] border-none rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                        x-init="$nextTick(() => $el.focus())" />
                                                </template>
                                            </div>
                                            <input type="hidden" name="included[]" :value="item">
                                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                <button type="button"
                                                    @click="editingInclusionIndex = (editingInclusionIndex === i ? null : i)"
                                                    class="text-gray-400 hover:text-blue-500 transition-colors"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 20h9"></path>
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button type="button" @click="removeInclusion(i)"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                                <div class="flex gap-2">
                                    <input type="text" x-model="newInclusion" @keydown.enter.prevent="addInclusion()"
                                        placeholder="Add inclusion..."
                                        class="flex-1 bg-white border-none rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-green-300" />
                                    <button type="button" @click="addInclusion()"
                                        class="px-3 py-2 bg-[#2f9e44] text-white rounded-xl text-xs font-bold"
                                        style="background-color: #2f9e44 !important;">+</button>
                                </div>
                            </div>

                            <!-- Exclusions Card -->
                            <div class="bg-[#FFF5F5] rounded-[28px] border border-red-100 p-6 space-y-4 shadow-sm"
                                style="background-color: #FFF5F5 !important; border-color: #ffe3e3 !important;">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-[#e03131]">
                                        <i data-lucide="x-circle" size="20"></i>
                                        <h4 class="text-sm font-bold">Exclusions</h4>
                                    </div>
                                </div>
                                <ul class="space-y-2">
                                    <template x-for="(item, i) in exclusions" :key="i">
                                        <li
                                            class="flex items-center gap-2 text-xs font-medium text-gray-700 bg-white/60 p-2.5 rounded-xl border border-gray-100/50 shadow-sm w-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#e03131] shrink-0"
                                                style="background-color: #e03131 !important;"></span>
                                            <div class="flex-1">
                                                <template x-if="editingExclusionIndex !== i">
                                                    <span x-text="item" class="cursor-pointer hover:underline"
                                                        @click="editingExclusionIndex = i"></span>
                                                </template>
                                                <template x-if="editingExclusionIndex === i">
                                                    <input type="text" x-model="exclusions[i]"
                                                        @keydown.enter.prevent="editingExclusionIndex = null"
                                                        @blur="editingExclusionIndex = null"
                                                        class="w-full bg-[#F5F5F5] border-none rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                        x-init="$nextTick(() => $el.focus())" />
                                                </template>
                                            </div>
                                            <input type="hidden" name="excluded[]" :value="item">
                                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                <button type="button"
                                                    @click="editingExclusionIndex = (editingExclusionIndex === i ? null : i)"
                                                    class="text-gray-400 hover:text-blue-500 transition-colors"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 20h9"></path>
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button type="button" @click="removeExclusion(i)"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                                <div class="flex gap-2">
                                    <input type="text" x-model="newExclusion" @keydown.enter.prevent="addExclusion()"
                                        placeholder="Add exclusion..."
                                        class="flex-1 bg-white border-none rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-red-300" />
                                    <button type="button" @click="addExclusion()"
                                        class="px-3 py-2 bg-[#e03131] text-white rounded-xl text-xs font-bold"
                                        style="background-color: #e03131 !important;">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right 1 Column -->
                    <div class="space-y-8">

                        <!-- Essential Amenities -->
                        <div x-show="!brochureName"
                            class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6 shadow-sm">
                            <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Essential Amenities
                            </h4>

                            <div class="space-y-4">
                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="wifi" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Free Wifi</span>
                                    </div>
                                    <input type="checkbox" checked
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="coffee" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Breakfast Included</span>
                                    </div>
                                    <input type="checkbox" checked
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Travel Insurance</span>
                                    </div>
                                    <input type="checkbox"
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>
                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="chef-hat" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Private Chef Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Private Chef Included"
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="user-check" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Tour Manager Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Tour Manager Included"
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <div class="flex gap-2 pt-2">
                                    <input type="text" x-model="newAmenity" @keydown.enter.prevent="addAmenity()" placeholder="Custom amenity..." class="flex-1 bg-white border border-gray-200 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-primary/50" />
                                    <button type="button" @click="addAmenity()" class="px-3 py-2 bg-gray-800 text-white rounded-xl text-xs font-bold">+Amenity</button>
                                </div>
                                <template x-for="(am, idx) in customAmenities" :key="idx">
                                    <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all mt-2">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span class="text-xs font-bold text-gray-700" x-text="am"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="amenities[]" :value="am" checked class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                            <button type="button" @click.prevent="customAmenities.splice(idx, 1)" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Remove amenity">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Media Uploads (Gallery) -->
                        <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-8 shadow-sm">
                            <div class="h-px w-full bg-gray-100"></div>

                            <!-- Gallery Portfolio Card -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Gallery
                                    Portfolio</h4>
                                <p class="text-[10px] text-gray-400 font-medium pl-1 -mt-3">Upload multiple photos for the
                                    package gallery.</p>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <template x-for="(img, idx) in galleryPreviews" :key="idx">
                                        <div
                                            class="relative aspect-[4/3] rounded-2xl overflow-hidden group border border-gray-100 shadow-sm">
                                            <img :src="img.url" class="w-full h-full object-cover" />
                                            <template x-if="img.is_existing">
                                                <input type="hidden" name="existing_gallery_urls[]" :value="img.url.replace(/^\//, '')">
                                            </template>
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click="removeGalleryPhoto(idx)"
                                                    class="p-2 bg-white/20 hover:bg-white/40 text-white rounded-full backdrop-blur-sm transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
</svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Upload from Local -->
                                    <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-orange-50/20"
                                        @click="$refs.galleryFilesInput.click()">
                                        <i data-lucide="upload-cloud" class="text-gray-400 mb-1" size="20"></i>
                                        <span class="text-xs font-bold text-gray-800">Upload Local</span>
                                        <input type="file" name="gallery_files[]" x-ref="galleryFilesInput" multiple
                                            class="hidden" @change="handleGalleryChange($event)" />
                                    </div>

                                    <!-- Select from Gallery -->
                                    <div class="aspect-[4/3] rounded-2xl border border-orange-200 hover:border-orange-300 transition-all flex flex-col items-center justify-center cursor-pointer bg-orange-50 hover:bg-orange-100 shadow-sm"
                                        @click="openGalleryModal()">
                                        <i data-lucide="image" class="text-primary mb-1" size="20"></i>
                                        <span class="text-xs font-bold text-primary">From Gallery</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Actions Panel -->
            <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-8">
                <div></div> <!-- Spacer -->
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ url('/admin/packages') }}"
                        class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                        Discard
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20"
                        style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save Package
                    </button>
                </div>
            </div>

            <!-- Gallery Modal -->
            <div x-show="isGalleryModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
                <div class="bg-white rounded-3xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden" @click.away="isGalleryModalOpen = false">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                            <i data-lucide="image" size="20" class="text-primary"></i>
                            Select from Gallery
                        </h3>
                        <button type="button" @click="isGalleryModalOpen = false" class="p-2 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">
                            <i data-lucide="x" size="20"></i>
                        </button>
                    </div>
                    
                    <!-- Breadcrumbs -->
                    <div class="px-6 py-3 bg-white border-b border-gray-100 flex items-center gap-2 text-sm">
                        <button type="button" @click="fetchGallery(null)" class="text-gray-500 hover:text-primary transition-colors font-semibold">
                            <i data-lucide="home" size="16"></i>
                        </button>
                        <template x-for="crumb in galleryBreadcrumbs" :key="crumb.id">
                            <div class="flex items-center gap-2">
                                <i data-lucide="chevron-right" size="14" class="text-gray-400"></i>
                                <button type="button" @click="fetchGallery(crumb.id)" class="text-gray-600 hover:text-primary transition-colors font-medium" x-text="crumb.name"></button>
                            </div>
                        </template>
                    </div>

                    <!-- Gallery Content -->
                    <div class="p-6 overflow-y-auto flex-1 relative min-h-[300px]">
                        <div x-show="galleryLoading" class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center">
                            <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <!-- Folders -->
                            <template x-for="folder in galleryFolders" :key="'folder_'+folder.id">
                                <div @click="fetchGallery(folder.id)" class="aspect-square rounded-2xl bg-orange-50 border border-orange-100 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-orange-100 transition-colors group">
                                    <i data-lucide="folder" size="32" class="text-orange-400 group-hover:text-orange-500 transition-colors"></i>
                                    <span class="text-xs font-bold text-gray-700 text-center px-2 truncate w-full" x-text="folder.name"></span>
                                </div>
                            </template>

                            <!-- Images -->
                            <template x-for="image in galleryImages" :key="'image_'+image.id">
                                <div class="relative aspect-square rounded-2xl border border-gray-200 overflow-hidden group cursor-pointer" @click="toggleGalleryImage(image)">
                                    <img :src="'/' + image.file_path" class="w-full h-full object-cover" />
                                    
                                    <!-- Selection Overlay -->
                                    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    </div>

                                    <!-- Checkbox -->
                                    <div class="absolute top-2 right-2 flex items-center justify-center">
                                        <input type="checkbox" 
                                               class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer pointer-events-none" 
                                               :checked="galleryPreviews.some(p => p.id === image.id || p.url === '/' + image.file_path)">
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <div x-show="!galleryLoading && galleryFolders.length === 0 && galleryImages.length === 0" class="col-span-full py-12 flex flex-col items-center justify-center text-gray-400">
                                <i data-lucide="image-off" size="48" class="mb-3 opacity-50"></i>
                                <p class="text-sm font-medium">This folder is empty.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                        <button type="button" @click="isGalleryModalOpen = false" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-700 transition-colors">
                            Done
                        </button>
                    </div>
                </div>
            </div>
            
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .tox-notifications-container {
            display: none !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            tinymce.init({
                selector: '#itinerary-textarea',
                plugins: 'lists link image table code help wordcount',
                toolbar: 'undo redo | blocks | ' +
                    'bold italic strikethrough | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                menubar: false,
                promotion: false,
                height: 400,
                setup: function (editor) {
                    editor.on('init change keyup setcontent input', function () {
                        window.dispatchEvent(new CustomEvent('itinerary-updated', { detail: editor.getContent({ format: 'text' }).trim() }));
                    });
                }
            });

            // Autocomplete for Departure City
            const input = document.getElementById('departureCity');
            const suggestionsDiv = document.getElementById('departureCitySuggestions');
            if (input && suggestionsDiv) {
                let debounceTimer;
                input.addEventListener('input', () => {
                    const query = input.value.trim();

                    clearTimeout(debounceTimer);
                    if (!query || query.length < 3) {
                        suggestionsDiv.innerHTML = '';
                        suggestionsDiv.classList.add('hidden');
                        return;
                    }

                    // Show loading indicator
                    suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-orange-800"></i> Searching cities...</div>';
                    suggestionsDiv.classList.remove('hidden');

                    debounceTimer = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=10&accept-language=en&q=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                suggestionsDiv.innerHTML = '';
                                if (data && data.length > 0) {
                                    const seen = new Set();
                                    data.forEach(item => {
                                        const address = item.address || {};

                                        // Determine city name
                                        let city = address.city || address.town || address.village || address.suburb || address.municipality || address.county || address.state_district || '';

                                        if (!city && item.display_name) {
                                            city = item.display_name.split(',')[0].trim();
                                        }

                                        const state = address.state || address.region || '';
                                        const country = address.country || '';

                                        if (city && country) {
                                            const key = `${city.toLowerCase()}_${state.toLowerCase()}_${country.toLowerCase()}`;
                                            if (seen.has(key)) return;
                                            seen.add(key);

                                            const row = document.createElement('div');
                                            row.className = 'px-4 py-2.5 hover:bg-orange-50 cursor-pointer text-xs font-semibold text-gray-700 transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                                            row.innerHTML = `<span>${city}</span><span class="text-[10px] text-gray-400 font-medium">${state ? state + ', ' : ''}${country}</span>`;
                                            row.onclick = () => {
                                                input.value = city;
                                                document.getElementById('departureState').value = state;
                                                document.getElementById('departureCountry').value = country;
                                                suggestionsDiv.classList.add('hidden');
                                            };
                                            suggestionsDiv.appendChild(row);
                                        }
                                    });

                                    if (suggestionsDiv.children.length > 0) {
                                        suggestionsDiv.classList.remove('hidden');
                                    } else {
                                        suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium">No cities found</div>';
                                    }
                                } else {
                                    suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium">No cities found</div>';
                                }
                            })
                            .catch(err => {
                                console.error('Error fetching cities:', err);
                                suggestionsDiv.classList.add('hidden');
                            });
                    }, 400);
                });

                // Close suggestions dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                        suggestionsDiv.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- TomSelect Library -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#agentSelect", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Search for an Agent...",
                maxOptions: 50
            });
        });
    </script>
@endsection