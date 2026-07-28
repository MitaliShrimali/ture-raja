@extends('agent.layouts.app')

@section('title', 'Edit Package - Tour Raja Agent')

@section('content')
    <!-- Load AlpineJS and Lucide for this view -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    @php
        $galleryUrls = json_decode($pkg->gallery, true) ?: [];
        $included = json_decode($pkg->included, true) ?: [];
        $excluded = json_decode($pkg->excluded, true) ?: [];
        $itinerary = json_decode($pkg->itinerary, true) ?: [];
        $keywords = json_decode($pkg->keywords, true) ?: [];
        $transfers = json_decode($pkg->transfers, true) ?: [];
        $hotels = json_decode($pkg->hotels, true) ?: [];
        $meals = json_decode($pkg->meals, true) ?: [];
        $agentData = json_decode($pkg->agent, true) ?: [];
        $agentName = $agentData['name'] ?? 'Miths Holidays';

        $dbCategory = $pkg->categories_list ?? '[]';
        $catArray = [];
        if (is_string($dbCategory) && (str_starts_with(trim($dbCategory), '[') || str_starts_with(trim($dbCategory), '{'))) {
            $catArray = json_decode($dbCategory, true) ?: [];
        } elseif (is_string($dbCategory) && !empty($dbCategory)) {
            $catArray = [$dbCategory];
        } elseif (is_array($dbCategory)) {
            $catArray = $dbCategory;
        }
    @endphp

    <div class="space-y-8 pb-12" @itinerary-updated.window="itineraryContent = $event.detail" x-data="{ 
        step: 1,
          category: {{ json_encode($pkg->category ?? 'domestic') }},
        title: {{ json_encode($pkg->title) }},
        location: {{ json_encode($pkg->location) }},
        duration: {{ json_encode($pkg->duration) }},
        price: {{ json_encode($pkg->price) }},
        currency: {{ json_encode($pkg->currency ?? '₹') }},
        inrPrice: '',
        rates: { '₹': 1, '$': 86.5, '€': 89.2, '£': 105.4, 'AED': 23.5 },
        initPrice() {
            if (this.price) {
                this.inrPrice = (this.price * this.rates[this.currency]).toFixed(2);
                if(this.inrPrice.endsWith('.00')) this.inrPrice = Math.round(this.inrPrice);
            }
        },
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
        old_price: {{ json_encode($pkg->old_price ?? '') }},
        validity: {{ json_encode($pkg->validity ?? '') }},
        sightseeing: {{ json_encode($pkg->sightseeing ?? '') }},
        stock: {{ json_encode($pkg->stock) }},
        categories: {{ json_encode($catArray) }},
        badge: {{ json_encode($pkg->badge ?? '') }},
        group_size: {{ json_encode($pkg->group_size ?? 'Direct Flight') }},
        rating: {{ json_encode($pkg->rating ?? '4.8') }},
        reviews: {{ json_encode($pkg->reviews ?? '10') }},
        previewUrl: {{ json_encode($pkg->image ? asset($pkg->image) : '') }}, 
        galleryPreviews: {{ json_encode(array_values(array_map(function ($url) {
        return [
            'url' => asset($url),
            'name' => basename($url),
            'size' => 'Existing'
        ];
    }, $galleryUrls))) }},
        brochureName: {{ json_encode($pkg->brochure ? basename($pkg->brochure) : '') }},
        brochureUrl: {{ json_encode($pkg->brochure ? asset($pkg->brochure) : '') }},
        itineraryContent: {{ json_encode(strip_tags($pkg->editorial_itinerary ?? '') ? trim(strip_tags($pkg->editorial_itinerary)) : '') }},
        inclusions: {{ json_encode($included) }},
        exclusions: {{ json_encode($excluded) }},
        newInclusion: '',
        newExclusion: '',
        cities: {{ json_encode(array_values(array_filter(array_map('trim', explode(',', $pkg->location ?? ''))))) }},
        newCity: '',
        keywords: {{ json_encode($keywords) }},
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
        transfers: {{ json_encode($transfers) }},
        hotels: {{ json_encode($hotels) }},
        newTransfer: '',
        newHotelName: '',
        newHotelRoom: '',
        newHotelImage: '',
        editingHotelIndex: null,
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

            if (this.validity && this.validity.includes(' to ')) {
                let parts = this.validity.split(' to ');
                this.validity_from = parts[0];
                this.validity_to = parts[1];
            }

            let updateCalculations = () => {
                if (this.validity_from && this.validity_to) {
                    this.validity = `${this.validity_from} to ${this.validity_to}`;
                    let start = new Date(this.validity_from);
                    let end = new Date(this.validity_to);
                    if (!isNaN(start) && !isNaN(end)) {
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        this.nights = diffDays - 1;
                        this.updateDurationFromNights();
                    }
                }
            };

            flatpickr(this.$refs.validityFromPicker, {
                dateFormat: 'd M Y',
                defaultDate: this.validity_from,
                onChange: (selectedDates, dateStr) => {
                    this.validity_from = dateStr;
                    if (this.toPicker) this.toPicker.set('minDate', dateStr);
                    updateCalculations();
                }
            });

            this.toPicker = flatpickr(this.$refs.validityToPicker, {
                dateFormat: 'd M Y',
                defaultDate: this.validity_to,
                onChange: (selectedDates, dateStr) => {
                    this.validity_to = dateStr;
                    updateCalculations();
                }
            });
        },
        days: {{ (is_array($itinerary) && count($itinerary) > 0) ? json_encode($itinerary) : json_encode([['title' => 'Day 1', 'desc' => 'Arrival & check-in', 'duration' => '3 Hours']]) }},
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
                    file: files[i]
                });
            }
        },
        removeGalleryPhoto(index) {
            this.galleryPreviews.splice(index, 1);
        },
        // Gallery Modal State
        isGalleryModalOpen: false,
        galleryModalType: 'main', // 'main' or 'gallery'
        currentGalleryFolder: null,
        galleryBreadcrumbs: [],
        galleryFolders: [],
        galleryImages: [],

        openGalleryModal(type) {
            this.galleryModalType = type;
            this.isGalleryModalOpen = true;
            this.fetchGallery();
        },
        closeGalleryModal() {
            this.isGalleryModalOpen = false;
        },
        fetchGallery(folderId = null) {
            let url = '{{ route('agent.api.gallery') }}';
            if (folderId) url += '?folder=' + folderId;
            url += (url.includes('?') ? '&' : '?') + '_t=' + new Date().getTime();

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.galleryFolders = data.folders;
                    this.galleryImages = data.images;
                    this.galleryBreadcrumbs = data.breadcrumbs;
                    this.currentGalleryFolder = folderId;
                });
        },
        selectGalleryImage(image) {
            const baseUrl = '{{ asset('') }}';
            const targetPath = '/' + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path);
            const fullUrl = baseUrl + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path);
            
            if (this.galleryModalType === 'main') {
                this.previewUrl = fullUrl;
                const input = document.getElementById('image_url');
                if (input) input.value = targetPath;
            } else {
                this.galleryPreviews.push({
                    url: fullUrl,
                    name: image.name,
                    is_gallery: true,
                    path: targetPath
                });
            }
            this.closeGalleryModal();
        },
        previewPdf() {
            if (this.$refs.brochureInput && this.$refs.brochureInput.files && this.$refs.brochureInput.files[0]) {
                window.open(URL.createObjectURL(this.$refs.brochureInput.files[0]), '_blank');
            } else if (this.brochureUrl) {
                window.open(this.brochureUrl, '_blank');
            }
        },
        clearPdf() {
            this.brochureName = '';
            if (this.$refs.brochureInput) this.$refs.brochureInput.value = '';
            const existing = document.getElementById('existing-brochure-input');
            if (existing) {
                existing.value = '';
            }
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
                <a href="{{ route('agent.my-packages') }}"
                    class="p-3 bg-white hover:bg-gray-50 border border-gray-100 rounded-2xl transition-all shadow-sm text-gray-500 hover:text-primary">
                    <i data-lucide="arrow-left" size="20"></i>
                </a>
                <div>
                    <h2 class="font-black text-gray-800 tracking-tight text-2xl">Edit Travel Package</h2>
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
        <form id="packageMainForm" action="{{ route('agent.packages.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-10">
            @csrf
            <input type="hidden" name="id" value="{{ $pkg->id }}" />

            <!-- ==================== STEP 1: IDENTITY & LOGISTICS ==================== -->
            <div class="space-y-8">
                <!-- Package Identity Card -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
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
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
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
                        <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
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
                                    class="w-1/3 bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm"
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
                                <input type="text" name="validity" value="{{ $pkg->validity ?? '' }}"
                                    placeholder="Select Expiry Date"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-10 pr-4 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                            </div>
                        </div>

                        <!-- Transit Type (group_size) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Transit
                                Type</label>
                            <select name="group_size" x-model="group_size"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
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
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                City</label>
                            <input type="text" name="departure_city" value="{{ $pkg->departure_city ?? '' }}"
                                placeholder="New Delhi"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
                        </div>


                        <!-- Departure State -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                State</label>
                            <input type="text" name="departure_state" value="{{ $pkg->departure_state ?? '' }}"
                                placeholder="Delhi"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
                        </div>

                        <!-- Departure Country -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                Country</label>
                            <select name="departure_country"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                <option value="India" {{ ($pkg->departure_country ?? '') === 'India' ? 'selected' : '' }}>
                                    India</option>
                                <option value="Singapore" {{ ($pkg->departure_country ?? '') === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                <option value="Thailand" {{ ($pkg->departure_country ?? '') === 'Thailand' ? 'selected' : '' }}>Thailand</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Lower Grid: Pricing and Specifics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Pricing Card -->
                    <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
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
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm appearance-none">
                                    <option value="₹">INR (₹)</option>
                                    <option value="$">USD ($)</option>
                                    <option value="€">EUR (€)</option>
                                    <option value="£">GBP (£)</option>
                                    <option value="AED">AED</option>
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
                                class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            <span class="text-xs font-bold text-gray-600">Hide price from package listing</span>
                        </label>
                    </div>

                    <!-- Specifics Card -->
                    <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                                <i data-lucide="compass" size="20"></i>
                            </div>
                            <h3 class="text-lg font-black text-gray-800">Specifics</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Theme
                                    Selection</label>
                                <select name="theme"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                    <option value="" disabled>Select Theme</option>
                                    @php
                                        $themes = [
                                            'Spring',
                                            'Summer',
                                            'Autumn',
                                            'Winter',
                                            'Monsoon',
                                            'Honeymoon Special',
                                            'Family Friendly',
                                            'Solo Travelers',
                                            'Group Tour',
                                            'Adventure',
                                            'Wildlife',
                                            'Pilgrimage',
                                            'Heritage',
                                            'Luxury',
                                            'Budget',
                                            'Weekend Getaway',
                                            'Eco Tourism',
                                            'Cultural',
                                            'Backpacking',
                                            'Festival'
                                        ];
                                    @endphp
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme }}" {{ (isset($pkg->theme) && $pkg->theme == $theme) ? 'selected' : '' }}>{{ $theme }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Holiday
                                    Type</label>
                                <select name="holiday_type"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                    <option value="" disabled>Select Holiday Type</option>
                                    @php
                                        $holidayTypes = [
                                            'Multi City',
                                            'Beach Resort',
                                            'Hill Station',
                                            'Desert Safari',
                                            'Island Tour',
                                            'Cruise',
                                            'Trekking',
                                            'Skiing',
                                            'City Break',
                                            'Road Trip',
                                            'Train Journey',
                                            'Camping',
                                            'Farm Stay',
                                            'Yoga & Wellness',
                                            'Culinary Tour',
                                            'Photography Tour'
                                        ];
                                    @endphp
                                    @foreach($holidayTypes as $type)
                                        <option value="{{ $type }}" {{ (isset($pkg->holiday_type) && $pkg->holiday_type == $type) ? 'selected' : '' }}>{{ $type }}</option>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Tag Name -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Tag Name
                                (e.g. 25% Off, Popular)</label>
                            <input type="text" name="badge" value="{{ old('badge', $pkg->badge ?? '') }}"
                                placeholder="e.g. 25% Off"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
                        </div>

                        <!-- Search Keywords -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Search
                                Keywords (Helps travelers find you)</label>
                            <div
                                class="w-full bg-[#F5F5F5] rounded-2xl p-4 flex flex-wrap items-center gap-2 border border-transparent focus-within:bg-white focus-within:ring-2 focus-within:ring-primary/25 transition-all">
                                <template x-for="(kw, idx) in keywords" :key="idx">
                                    <span
                                        class="px-3.5 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                        <span x-text="kw"></span>
                                        <i class="cursor-pointer font-black text-xs leading-none text-gray-400 hover:text-gray-600"
                                            @click="removeKeyword(idx)">&times;</i>
                                    </span>
                                </template>
                                <input type="text" x-model="newKeyword" @keydown.enter.prevent="addKeyword()"
                                    @keydown.comma.prevent="addKeyword()" placeholder="Type keyword & enter/comma..."
                                    class="bg-transparent border-none outline-none text-xs font-bold text-gray-700 py-1 px-2 focus:ring-0"
                                    style="border: none !important; outline: none !important; box-shadow: none !important;" />
                                <input type="hidden" name="keywords" :value="keywords.join(',')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== STEP 2: ITINERARY, MEALS & PHOTOS ==================== -->
            <div class="space-y-8 mt-8">

                <!-- ── Full-width row: Upload Brochure  OR  Itinerary (Day-by-Day Plan) ── -->
                <div class="flex flex-col md:flex-row gap-4 items-stretch">

                    <!-- Brochure card  ~33% -->
                    <div
                        class="md:w-[40%] bg-white rounded-[28px] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col transition-all duration-300" x-show="brochureName || !itineraryContent">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" size="16" class="text-primary"></i>
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
                            @if($pkg->brochure)
                                <input type="hidden" name="existing_brochure" id="existing-brochure-input"
                                    value="{{ $pkg->brochure }}" />
                            @endif
                        </div>
                    </div>

                    <!-- OR divider -->
                    <div class="flex items-center justify-center shrink-0 px-2">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">OR</span>
                    </div>

                    <!-- Itinerary card  ~65% -->
                    <div
                        class="flex-1 bg-white rounded-[28px] border border-gray-100 p-6 space-y-3 shadow-sm flex flex-col transition-all duration-300" x-show="!brochureName">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="pencil" size="16" class="text-primary"></i>
                            </div>
                            <h4 class="text-sm font-bold text-gray-800">Itinerary (Day-by-Day Plan)</h4>
                        </div>
                        <textarea id="itinerary-textarea" name="editorial_itinerary" rows="9"
                            placeholder="Explain why this tour is unique..."
                            class="w-full flex-1 bg-transparent border-none py-4 px-5 outline-none text-gray-700 text-sm resize-none">{{ $pkg->editorial_itinerary ?? '' }}</textarea>
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
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-6 shadow-sm transition-all duration-300" x-show="!brochureName">
                            <h3 class="text-lg font-bold text-gray-900">Editorial Details</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Hotels sub-card -->
                                <div class="bg-[#FFF5F0] rounded-2xl p-5 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#F0642F" stroke-width="2"
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
                                                class="w-10 h-10 shrink-0 text-white rounded-xl text-sm font-bold flex items-center justify-center shadow-sm hover:opacity-90 transition-opacity bg-primary"
                                                style="background-color: #e85d26 !important; color: white !important;">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="space-y-2">


                                <!-- Sightseeing Details List -->
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terms &
                                    Conditions</label>
                                <textarea name="terms" rows="3" placeholder="Specific booking policies for this package..."
                                    class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm text-gray-600 resize-none">{{ $pkg->terms ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Sightseeing Details Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-5 shadow-sm transition-all duration-300" x-show="!brochureName">
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
                                    class="px-5 py-2.5 bg-primary hover:bg-orange-600 text-white rounded-full text-sm font-semibold transition-all flex items-center gap-1.5"
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
                                                </td>
                                                
                                                <td class="py-4 px-6 text-right">
                                                    <button type="button" @click="removeDay(index)"
            class="p-1.5 text-gray-300 hover:text-red-400 transition-all" x-show="days.length > 1" title="Remove">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300" x-show="!brochureName">
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
                                        <li class="flex items-center gap-2 text-xs font-medium text-gray-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2f9e44] shrink-0"
                                                style="background-color: #2f9e44 !important;"></span>
                                            <span x-text="item" class="flex-1"></span>
                                            <input type="hidden" name="included[]" :value="item">
                                            <button type="button" @click="newInclusion = item; removeInclusion(i)"
            class="text-blue-400 hover:text-blue-600 transition-all text-xs px-1" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 20h9"></path>
    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
</svg>
        </button>
        <button type="button" @click="removeInclusion(i)"
            class="text-red-400 hover:text-red-600 transition-all text-xs px-1" title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
</svg>
        </button>
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
                                        <li class="flex items-center gap-2 text-xs font-medium text-gray-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#e03131] shrink-0"
                                                style="background-color: #e03131 !important;"></span>
                                            <span x-text="item" class="flex-1"></span>
                                            <input type="hidden" name="excluded[]" :value="item">
                                            <button type="button" @click="newExclusion = item; removeExclusion(i)"
            class="text-blue-400 hover:text-blue-600 transition-all text-xs px-1" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 20h9"></path>
    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
</svg>
        </button>
        <button type="button" @click="removeExclusion(i)"
            class="text-red-400 hover:text-red-600 transition-all text-xs px-1" title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="3 6 5 6 21 6"></polyline>
    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
</svg>
        </button>
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
                        <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm transition-all duration-300" x-show="!brochureName">
                            <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Essential Amenities
                            </h4>

                            <div class="space-y-4">
                                @php
                                    $amenities = json_decode($pkg->amenities, true) ?: [];
                                @endphp
                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="wifi" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Free Wifi</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Free Wifi" {{ in_array('Free Wifi', $amenities) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="coffee" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Breakfast Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Breakfast Included" {{ in_array('Breakfast Included', $amenities) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Travel Insurance</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Travel Insurance" {{ in_array('Travel Insurance', $amenities) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>
                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="chef-hat" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Private Chef Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Private Chef Included" {{ in_array('Private Chef Included', $amenities) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>

                                <label
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="user-check" class="text-gray-400" size="18"></i>
                                        <span class="text-xs font-bold text-gray-700">Tour Manager Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Tour Manager Included" {{ in_array('Tour Manager Included', $amenities) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                                </label>
                            </div>
                        </div>

                        <!-- Primary featured photo upload hidden input -->
                        <div
                            class="bg-gray-50 p-6 rounded-[32px] border border-gray-100 flex items-center justify-between gap-4 shadow-sm">
                            <div class="space-y-1">
                                <p class="text-sm font-black text-gray-800">Main Featured Image</p>
                                <p class="text-[10px] text-gray-400 font-medium">Select a single thumbnail banner for card
                                    listing.</p>
                                <template x-if="previewUrl">
                                    <img :src="previewUrl"
                                        class="h-16 w-16 object-cover rounded-xl mt-2 border border-gray-200">
                                </template>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                    class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-xs shadow-sm transition-all"
                                    @click="$refs.mainImageInput.click()">
                                    Choose File
                                </button>
                                <input type="file" name="image_file" x-ref="mainImageInput" class="hidden" accept="image/*"
                                    @change="previewUrl = URL.createObjectURL($event.target.files[0]); document.getElementById('image_url').value = '';" />
                                <div class="text-[10px] text-gray-400 font-bold text-center">OR</div>
                                <button type="button"
                                    class="px-4 py-2 bg-orange-50 hover:bg-orange-100 text-primary border border-orange-200 rounded-xl font-bold text-xs shadow-sm transition-all"
                                    @click="openGalleryModal('main')">
                                    Choose from Gallery
                                </button>
                                <input type="hidden" name="image_url" id="image_url" />
                                <span class="text-xs text-gray-400 font-bold"
                                    x-text="previewUrl ? 'Image Selected' : 'No file chosen'"></span>
                            </div>
                        </div>

                        <!-- Gallery Portfolio Card -->
                        <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                            <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Gallery Portfolio
                            </h4>

                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="(img, idx) in galleryPreviews" :key="idx">
                                    <div
                                        class="relative aspect-[4/3] rounded-2xl overflow-hidden group border border-gray-100">
                                        <img :src="img.url" class="w-full h-full object-cover" />
                                        <template x-if="img.is_gallery">
                                            <input type="hidden" name="gallery_urls[]" :value="img.path" />
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

                                <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-orange-50/20"
                                    @click="$refs.galleryFilesInput.click()">
                                    <i data-lucide="plus" class="text-gray-400 mb-1" size="20"></i>
                                    <span class="text-xs font-bold text-gray-800">Add More</span>
                                    <span class="text-[9px] text-gray-400 font-semibold mt-1">Upload multiple photos</span>
                                    <input type="file" name="gallery_files[]" x-ref="galleryFilesInput" multiple
                                        class="hidden" accept="image/*" @change="handleGalleryChange($event)" />
                                </div>
                                <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-orange-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-orange-50/30 hover:bg-orange-50/60"
                                    @click="openGalleryModal('gallery')">
                                    <i data-lucide="image" class="text-primary mb-1" size="20"></i>
                                    <span class="text-xs font-bold text-primary text-center">From<br>Gallery</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions Panel -->
            <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-8">
                <div></div>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ route('agent.my-packages') }}"
                        class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                        Discard
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-primary hover:bg-orange-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20"
                        style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save And Exit
                    </button>
                </div>
            </div>
        </form>

        <!-- Gallery Selection Modal -->
        <template x-teleport="body">
            <div x-show="isGalleryModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-[32px] w-full max-w-4xl max-h-[80vh] flex flex-col shadow-2xl overflow-hidden"
                @click.away="closeGalleryModal()">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-gray-800">Select Image from Gallery</h3>
                    <button type="button" @click="closeGalleryModal()"
                        class="text-gray-400 hover:text-red-500 transition-colors">
                        <i data-lucide="x" size="24"></i>
                    </button>
                </div>

                <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <button type="button" @click="fetchGallery()"
                        class="text-sm font-bold text-gray-500 hover:text-primary transition-colors flex items-center gap-1">
                        <i data-lucide="home" size="16"></i> Home
                    </button>
                    <template x-for="(crumb, idx) in galleryBreadcrumbs" :key="idx">
                        <div class="flex items-center gap-2">
                            <i data-lucide="chevron-right" size="14" class="text-gray-400"></i>
                            <button type="button" @click="fetchGallery(crumb.id)"
                                class="text-sm font-bold text-gray-500 hover:text-primary transition-colors"
                                x-text="crumb.name"></button>
                        </div>
                    </template>
                </div>

                <div class="flex-1 overflow-y-auto p-6 bg-white">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="folder in galleryFolders" :key="folder.id">
                            <div class="group cursor-pointer" @click="fetchGallery(folder.id)">
                                <div
                                    class="aspect-square bg-gray-50 rounded-2xl border border-gray-100 flex flex-col items-center justify-center hover:bg-orange-50 hover:border-orange-200 transition-all">
                                    <i data-lucide="folder" size="32" class="text-yellow-400 mb-2"></i>
                                    <span class="text-xs font-bold text-gray-700 truncate w-full text-center px-2"
                                        x-text="folder.name"></span>
                                </div>
                            </div>
                        </template>

                        <template x-for="img in galleryImages" :key="img.id">
                            <div class="group cursor-pointer relative aspect-square rounded-2xl overflow-hidden border border-gray-100 hover:border-primary transition-all"
                                @click="selectGalleryImage(img)">
                                <img :src="'{{ asset('') }}' + img.file_path" class="w-full h-full object-cover" />
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span
                                        class="px-3 py-1 bg-white rounded-full text-xs font-bold text-gray-800">Select</span>
                                </div>
                            </div>
                        </template>

                        <div x-show="galleryFolders.length === 0 && galleryImages.length === 0"
                            class="col-span-full py-12 text-center text-gray-400">
                            <i data-lucide="image" size="48" class="mx-auto mb-3 opacity-20"></i>
                            <p class="text-sm font-bold">This folder is empty.</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
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
        });

        // Inserts formatting at cursor / wraps selection in the itinerary textarea
        // Fallback for customPrompt if not defined globally
        if (!window.customPrompt) {
            window.customPrompt = function (msg) {
                return Promise.resolve(prompt(msg));
            };
        }

        async function itineraryFormat(type) {
            const ta = document.getElementById('itinerary-textarea');
            if (!ta) return;

            const start = ta.selectionStart;
            const end = ta.selectionEnd;
            const before = ta.value.substring(0, start);
            const sel = ta.value.substring(start, end);
            const after = ta.value.substring(end);

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
                const url = await window.customPrompt('Enter URL (e.g. https://example.com):');
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