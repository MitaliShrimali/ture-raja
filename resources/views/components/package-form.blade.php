@props(['package' => null, 'isAdmin' => false, 'agents' => [], 'action' => '', 'method' => 'POST', 'categories' => [], 'themes' => [], 'holidayTypes' => [], 'transits' => [], 'hotelCategories' => []])

@php
    $pkg = $package ?? null;
    $hotelCategoriesList = $hotelCategories ?? [];
    if (empty($hotelCategoriesList) || (is_object($hotelCategoriesList) && method_exists($hotelCategoriesList, 'isEmpty') && $hotelCategoriesList->isEmpty())) {
        try {
            $hotelCategoriesList = \Illuminate\Support\Facades\DB::table('hotel_categories')->where('status', 1)->orderBy('name', 'asc')->get();
        } catch (\Exception $e) {
            $hotelCategoriesList = collect();
        }
    }

    if ($isAdmin) {
        $canDomestic = true;
        $canInternational = true;
        $canAddGallery = true;
        $canThemeOptions = true;
        $canHidePrice = true;
        $photoLimit = 0;
        $hotelLimit = 0;
    } else {
        $agentId = session('agent_id');
        $agentRecord = $agentId ? \Illuminate\Support\Facades\DB::table('agents')->where('id', $agentId)->first() : null;
        $planId = $agentRecord->plan_id ?? null;
        if (!$planId) {
            $planId = \Illuminate\Support\Facades\DB::table('plans')->where('price', 0)->where('status', 'Active')->value('id') ?? 1;
        }
        $perms = \Illuminate\Support\Facades\DB::table('plan_permissions')->where('plan_id', $planId)->get()->keyBy('permission_key');
        
        $canDomestic = isset($perms['feat_domestic_packages']) ? (bool)$perms['feat_domestic_packages']->boolean_value : true;
        $canInternational = isset($perms['feat_international_packages']) ? (bool)$perms['feat_international_packages']->boolean_value : true;
        $canAddGallery = isset($perms['feat_add_gallery']) ? (bool)$perms['feat_add_gallery']->boolean_value : true;
        $canThemeOptions = isset($perms['feat_theme_options']) ? (bool)$perms['feat_theme_options']->boolean_value : true;
        $canHidePrice = isset($perms['feat_hide_package_price']) ? (bool)$perms['feat_hide_package_price']->boolean_value : true;
        $photoLimit = isset($perms['limit_package_photos']) ? (int)$perms['limit_package_photos']->limit_value : 0;
        $hotelLimit = isset($perms['limit_hotel_options']) ? (int)$perms['limit_hotel_options']->limit_value : 0;
    }

    $catArray = [];
    if ($pkg && !empty($pkg->categories_list)) {
        $dbCategory = json_decode($pkg->categories_list, true);
        if (is_string($dbCategory)) {
            $catArray = json_decode($dbCategory, true) ?: [];
        } elseif (is_array($dbCategory)) {
            $catArray = $dbCategory;
        }
    }
    
    $galleryUrls = ($pkg->gallery ?? null) ? json_decode($pkg->gallery, true) : [];
    if (!is_array($galleryUrls)) $galleryUrls = [];

    $included = ($pkg->included ?? null) ? json_decode($pkg->included, true) : [];
    if (!is_array($included)) $included = [];

    $excluded = ($pkg->excluded ?? null) ? json_decode($pkg->excluded, true) : [];
    if (!is_array($excluded)) $excluded = [];
    
    $keywords = ($pkg->keywords ?? null) ? json_decode($pkg->keywords, true) : [];
    if (!is_array($keywords)) $keywords = [];
    
    $itinerary = ($pkg->itinerary ?? null) ? json_decode($pkg->itinerary, true) : []; $departureDates = ($pkg->departure_dates ?? null) ? json_decode($pkg->departure_dates, true) : []; if (!is_array($departureDates)) $departureDates = [];
@endphp

    <!-- Load AlpineJS, SweetAlert2 and Lucide for this view -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.showAlertLimit = function(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Plan Limit Exceeded',
                    text: message,
                    confirmButtonColor: '#e85d26',
                    confirmButtonText: 'Got It',
                    customClass: {
                        popup: 'rounded-[28px]',
                        confirmButton: 'rounded-xl font-bold px-6 py-3 text-xs uppercase tracking-wider'
                    }
                });
            } else {
                alert(message);
            }
        };
    </script>

    <div class="space-y-4 pb-12" @itinerary-updated.window="itineraryContent = $event.detail" x-data="{ 
        step: 1,
        showGalleryError: false,
        showBrochureError: false,
        category: {{ json_encode($pkg->category ?? ($canDomestic ? 'domestic' : ($canInternational ? 'international' : 'domestic'))) }},
        photoLimit: {{ (int)$photoLimit }},
        hotelLimit: {{ (int)$hotelLimit }},
        title: {{ json_encode($pkg->title ?? '') }},
        location: {{ json_encode($pkg->location ?? '') }},
        duration: {{ json_encode($pkg->duration ?? '') }},
        price: {{ json_encode($pkg->price ?? '') }},
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
        stock: {{ json_encode($pkg->stock ?? '') }},
        categories: {{ json_encode($catArray) }},
        badge: {{ json_encode($pkg->badge ?? '') }},
        group_size: {{ json_encode($pkg->group_size ?? 'Direct Flight') }},
        rating: {{ json_encode($pkg->rating ?? '4.8') }},
        reviews: {{ json_encode($pkg->reviews ?? '10') }},
        previewUrl: {{ json_encode(($pkg->image ?? null) ? asset($pkg->image) : '') }},
        uploadedFiles: [],
        syncFileInput() {
            try {
                const dt = new DataTransfer();
                this.uploadedFiles.forEach(file => dt.items.add(file));
                if (this.$refs.galleryFilesInput) {
                    this.$refs.galleryFilesInput.files = dt.files;
                }
            } catch (e) {
                console.error("DataTransfer sync error:", e);
            }
        },
        galleryPreviews: {{ json_encode(array_values(array_map(function ($url) {
            return [
                'url' => asset($url),
                'name' => basename($url),
                'size' => 'Existing',
                'is_gallery' => true,
                'path' => $url
            ];
        }, $galleryUrls))) }},
        brochureName: {{ json_encode(($pkg->brochure ?? null) ? basename($pkg->brochure) : '') }},
        brochureUrl: {{ json_encode(($pkg->brochure ?? null) ? asset($pkg->brochure) : '') }},
        itineraryContent: {{ json_encode(strip_tags($pkg->editorial_itinerary ?? '') ? trim(strip_tags($pkg->editorial_itinerary)) : '') }},
        overview: {{ json_encode($pkg->overview ?? '') }},
        highlights: {{ json_encode(($pkg->highlights ?? null) ? (is_string($pkg->highlights) ? json_decode($pkg->highlights, true) : $pkg->highlights) : []) }},
        newHighlight: '',
        addHighlight() {
            if (this.newHighlight.trim()) {
                this.highlights.push(this.newHighlight.trim());
                this.newHighlight = '';
            }
        },
        removeHighlight(i) { this.highlights.splice(i, 1); }, departureDates: {{ json_encode($departureDates) }}, addDepartureRow() { this.departureDates.push({ month: '', dates: [] }); }, removeDepartureRow(i) { this.departureDates.splice(i, 1); },
        inclusions: {{ json_encode($included) }},
        exclusions: {{ json_encode($excluded) }},
        newInclusion: '',
        newExclusion: '',
        editingInclusionIndex: null,
        editingExclusionIndex: null,
        amenitiesList: (() => {
            const defaultAmenities = [
                'Kitchen facilities', 'Dining', 'Casino', 'Wi-Fi', 
                'Health & Beauty treatments', 'Television', 'Parking', 
                'Workouts', 'Fitness Center', 'Bar & Lounge', 'Towels', 
                'Swimming pools', 'Room Service', 'Express Laundry'
            ];
            const savedAmenities = JSON.parse(atob('{{ base64_encode(json_encode(json_decode($pkg->amenities ?? "[]", true) ?: [])) }}'));
            const allAmenities = [...new Set([...defaultAmenities, ...savedAmenities])];
            return allAmenities.map(name => ({
                name: name,
                selected: savedAmenities.includes(name)
            }));
        })(),
        newAmenity: '',
        editingAmenityIndex: null,
        addAmenity() {
            if (this.newAmenity.trim()) {
                this.amenitiesList.push({
                    name: this.newAmenity.trim(),
                    selected: true
                });
                this.newAmenity = '';
            }
        },
        removeAmenity(index) {
            this.amenitiesList.splice(index, 1);
        },
        cities: [],
        newCity: '',
        keywords: {{ json_encode($keywords) }},
        keywordRows: [],
        initKeywords() {
            let kws = this.keywords || [];
            if(kws.length === 0) {
                this.keywordRows.push({ city: '', state: '', country: '' });
            } else {
                kws.forEach(k => {
                    let parts = k.split(',').map(p => p.trim());
                    this.keywordRows.push({
                        city: parts[0] || '',
                        state: parts[1] || '',
                        country: parts[2] || ''
                    });
                });
            }
        },
        addKeywordRow() {
            this.keywordRows.push({ city: '', state: '', country: '' });
        },
        removeKeywordRow(index) {
            this.keywordRows.splice(index, 1);
            if(this.keywordRows.length === 0) {
                this.keywordRows.push({ city: '', state: '', country: '' });
            }
        },

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
        hotels: (() => {
            let h = {{ json_encode($pkg->hotels ?? null) }};
            if (typeof h === 'string') {
                try { h = JSON.parse(h); } catch(e) { h = []; }
            }
            return Array.isArray(h) ? h : [];
        })(),
        newTransfer: '',
        newHotelName: '',
        newHotelCity: '',
        newHotelRoom: '',
        newHotelImage: '',
        editingHotelIndex: null,
        addHotel() {
            if (!Array.isArray(this.hotels)) this.hotels = [];
            if (this.hotelLimit > 0 && this.hotels.length >= this.hotelLimit) {
                window.showAlertLimit('Your plan allows a maximum of ' + this.hotelLimit + ' hotel options per package.');
                return;
            }
            if (this.newHotelName && this.newHotelName.trim()) {
                this.hotels.push({
                    name: this.newHotelName.trim(),
                    city: (this.newHotelCity || '').trim(),
                    room: (this.newHotelRoom || '').trim(),
                    image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=100'
                });
                this.newHotelName = '';
                this.newHotelCity = '';
                this.newHotelRoom = '';
            }
        },
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
        nights: '{{ $pkg->duration ?? "" }}' ? parseInt('{{ $pkg->duration ?? "" }}') || '' : '',
        updateDurationFromNights() {
            if (this.nights && !isNaN(parseInt(this.nights))) {
                let n = parseInt(this.nights);
                this.duration = `${n} Nights / ${n + 1} Days`;
            } else {
                this.duration = '';
            }
        },
        init() {
            this.initKeywords();
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
        get hasItineraryData() { return this.itineraryContent.trim() !== '' || this.days.some(d => (d.title || '').trim() !== '' || (d.desc || '').trim() !== ''); },
        days: {{ ($itinerary && count($itinerary) > 0) ? json_encode($itinerary) : json_encode([['title' => '', 'desc' => '', 'duration' => '']]) }},
        addDay() {
            this.days.push({ title: '', desc: '' });
        },
        removeDay(index) {
            this.days.splice(index, 1);
        },
        handleGalleryChange(event) {
            const files = event.target.files;
            if (!files || files.length === 0) return;
            for (let i = 0; i < files.length; i++) {
                if (this.photoLimit > 0 && this.galleryPreviews.length >= this.photoLimit) {
                    window.showAlertLimit('Your plan allows a maximum of ' + this.photoLimit + ' package photos in Gallery Portfolio.');
                    break;
                }
                const fileObj = files[i];
                const fileIdx = this.uploadedFiles.length;
                this.uploadedFiles.push(fileObj);
                this.galleryPreviews.push({
                    url: URL.createObjectURL(fileObj),
                    name: fileObj.name,
                    size: (fileObj.size / (1024 * 1024)).toFixed(1) + ' MB',
                    is_gallery: false,
                    file_index: fileIdx
                });
            }
            this.syncFileInput();
        },
        removeGalleryPhoto(index) {
            const item = this.galleryPreviews[index];
            if (item && !item.is_gallery && typeof item.file_index !== 'undefined') {
                const fileIdx = item.file_index;
                this.uploadedFiles.splice(fileIdx, 1);
                this.galleryPreviews.forEach(p => {
                    if (!p.is_gallery && typeof p.file_index !== 'undefined' && p.file_index > fileIdx) {
                        p.file_index--;
                    }
                });
                this.syncFileInput();
            }
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
            let url = '{{ request()->is("admin/*") ? route("admin.api.gallery") : route("agent.api.gallery") }}';
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
        toggleGalleryImage(image) {
            const baseUrl = '{{ asset('') }}';
            const targetPath = '/' + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path);
            const fullUrl = baseUrl + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path);
            
            const index = this.galleryPreviews.findIndex(p => p.url === fullUrl);
            if (index === -1) {
                if (this.photoLimit > 0 && this.galleryPreviews.length >= this.photoLimit) {
                    window.showAlertLimit('Your plan allows a maximum of ' + this.photoLimit + ' package photos in Gallery Portfolio.');
                    return;
                }
                this.galleryPreviews.push({
                    url: fullUrl,
                    name: image.name,
                    is_gallery: true,
                    path: targetPath,
                    size: 'From Gallery'
                });
            } else {
                this.galleryPreviews.splice(index, 1);
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
        </style>

        <!-- Header Actions Panel -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="p-3 bg-white hover:bg-gray-50 border border-gray-100 rounded-2xl transition-all shadow-sm text-gray-500 hover:text-primary">
                    <i data-lucide="arrow-left" size="20"></i>
                </a>
                <div>
                    <h2 class="font-black text-gray-800 tracking-tight text-4xl">{{ $pkg ? 'Edit Travel Package' : 'Create Travel Package' }}</h2>
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
        <script>
    // Define setupAutocompleteElement early so Alpine x-init can use it immediately without race conditions
    window.setupAutocompleteElement = (input, suggestionsDiv, type, onSelect, targetStateId, targetCountryId) => {
        if (!input || !suggestionsDiv) return;

        let debounceTimer;
        input.addEventListener('input', () => {
            const query = input.value.trim();
            clearTimeout(debounceTimer);
            if (!query || query.length < 2) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.add('hidden');
                return;
            }

            suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-orange-800"></i> Searching...</div>';
            suggestionsDiv.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                const urlIndia = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=40&bbox=68.1,6.7,97.4,35.5`;
                const urlGlobal = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=40`;

                Promise.all([
                    fetch(urlIndia).then(r => r.json()).catch(() => ({ features: [] })),
                    fetch(urlGlobal).then(r => r.json()).catch(() => ({ features: [] }))
                ]).then(([indiaData, globalData]) => {
                    suggestionsDiv.innerHTML = '';
                    const seen = new Set();
                    let results = [];

                    const features = [...(indiaData.features || []), ...(globalData.features || [])];
                    features.forEach(f => {
                            const p = f.properties || {};
                            let city = p.city || p.town || p.village || p.county || p.name || '';
                            let state = p.state || '';
                            let country = p.country || '';
                            
                            if (type === 'city') {
                                if (p.osm_key === 'boundary' && p.osm_value === 'administrative' && !p.city && !p.town) {
                                }
                                if (p.osm_key === 'place' && (p.osm_value === 'country' || p.osm_value === 'state')) return;
                            }
                            if (type === 'state') {
                                if (p.osm_value !== 'state' && p.osm_value !== 'administrative') return;
                                city = '';
                                if (p.name) state = p.name;
                            }
                            if (type === 'country') {
                                if (p.osm_value !== 'country' && p.osm_value !== 'administrative') return;
                                city = '';
                                state = '';
                                if (p.name) country = p.name;
                            }

                            let display_name = [city, state, country].filter(Boolean).join(', ');
                            const parsed = { city, state, country, display: display_name, importance: p.importance || 0.5 };

                            let key = '';
                            if (type === 'city') key = `${parsed.city}_${parsed.state}_${parsed.country}`.toLowerCase();
                            else if (type === 'state') key = `${parsed.state}_${parsed.country}`.toLowerCase();
                            else key = `${parsed.country}`.toLowerCase();

                            if (!key || seen.has(key)) return;
                            seen.add(key);
                            results.push(parsed);
                        });

                    if (type === 'city') {
                        const qLower = query.toLowerCase();
                        const getMatchQuality = (str) => {
                            const c = (str || '').toLowerCase();
                            if (c === qLower) return 4;
                            if (c.startsWith(qLower)) return 3;
                            if (c.split(/[\s,-]+/).some(w => w.startsWith(qLower))) return 2;
                            if (c.includes(qLower)) return 1;
                            return 0;
                        };
                        const getLocationPriority = (res) => {
                            const country = (res.country || '').toLowerCase();
                            const state = (res.state || '').toLowerCase();
                            if (country === 'india') {
                                if (state === 'gujarat') return 2;
                                return 1;
                            }
                            return 0;
                        };

                        results = results.filter(r => getMatchQuality(r.city) > 0);

                        results.sort((a, b) => {
                            const mqA = getMatchQuality(a.city);
                            const mqB = getMatchQuality(b.city);
                            if (mqA !== mqB) return mqB - mqA;

                            const lpA = getLocationPriority(a);
                            const lpB = getLocationPriority(b);
                            if (lpA !== lpB) return lpB - lpA;
                            
                            return b.importance - a.importance;
                        });
                    } else if (type === 'state') {
                        const qLower = query.toLowerCase();
                        const getMatchQuality = (str) => {
                            const c = (str || '').toLowerCase();
                            if (c === qLower) return 4;
                            if (c.startsWith(qLower)) return 3;
                            if (c.split(/[\s,-]+/).some(w => w.startsWith(qLower))) return 2;
                            if (c.includes(qLower)) return 1;
                            return 0;
                        };
                        results = results.filter(r => getMatchQuality(r.state) > 0);

                        results.sort((a, b) => {
                            const mqA = getMatchQuality(a.state);
                            const mqB = getMatchQuality(b.state);
                            if (mqA !== mqB) return mqB - mqA;

                            const isIndA = (a.country || '').toLowerCase() === 'india' ? 1 : 0;
                            const isIndB = (b.country || '').toLowerCase() === 'india' ? 1 : 0;
                            if (isIndA !== isIndB) return isIndB - isIndA;
                            
                            return b.importance - a.importance;
                        });
                    } else if (type === 'country') {
                        const qLower = query.toLowerCase();
                        const getMatchQuality = (str) => {
                            const c = (str || '').toLowerCase();
                            if (c === qLower) return 4;
                            if (c.startsWith(qLower)) return 3;
                            if (c.split(/[\s,-]+/).some(w => w.startsWith(qLower))) return 2;
                            if (c.includes(qLower)) return 1;
                            return 0;
                        };
                        results = results.filter(r => getMatchQuality(r.country) > 0);

                        results.sort((a, b) => {
                            const mqA = getMatchQuality(a.country);
                            const mqB = getMatchQuality(b.country);
                            if (mqA !== mqB) return mqB - mqA;

                            const isIndA = (a.country || '').toLowerCase() === 'india' ? 1 : 0;
                            const isIndB = (b.country || '').toLowerCase() === 'india' ? 1 : 0;
                            if (isIndA !== isIndB) return isIndB - isIndA;
                            
                            return b.importance - a.importance;
                        });
                    }

                    results = results.slice(0, 10);

                    if (results.length === 0) {
                        suggestionsDiv.innerHTML = `<div class="px-4 py-3 text-xs text-gray-400 font-medium">No results found</div>`;
                        return;
                    }

                    results.forEach(res => {
                        const row = document.createElement('div');
                        row.className = 'px-4 py-2.5 hover:bg-orange-50 cursor-pointer text-xs font-semibold text-gray-700 transition-colors flex flex-col justify-center border-b border-gray-50 last:border-0';
                        
                        let mainText = '';
                        let subText = '';
                        
                        if (type === 'city') {
                            mainText = res.city;
                            subText = [res.state, res.country].filter(Boolean).join(', ');
                            row.innerHTML = `<span>${mainText}</span><span class="text-[10px] text-gray-400 font-medium">${subText}</span>`;
                        } else if (type === 'state') {
                            mainText = res.state;
                            subText = res.country;
                            row.innerHTML = `<span>${mainText}</span><span class="text-[10px] text-gray-400 font-medium">${subText}</span>`;
                        } else {
                            mainText = res.country;
                            row.innerHTML = `<span>${mainText}</span>`;
                        }

                        row.onclick = () => {
                            if (type === 'city') {
                                input.value = res.city;
                                const stateEl = document.getElementById(targetStateId || 'departureState');
                                const countryEl = document.getElementById(targetCountryId || 'departureCountry');
                                if (!onSelect) {
                                    if (stateEl && res.state) {
                                        stateEl.value = res.state;
                                        stateEl.dispatchEvent(new Event('input', { bubbles: true }));
                                        stateEl.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                    if (countryEl && res.country) {
                                        countryEl.value = res.country;
                                        countryEl.dispatchEvent(new Event('input', { bubbles: true }));
                                        countryEl.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                }
                            } else if (type === 'state') {
                                input.value = res.state;
                                const countryEl = document.getElementById(targetCountryId || 'departureCountry');
                                if (!onSelect) {
                                    if (countryEl && res.country) {
                                        countryEl.value = res.country;
                                        countryEl.dispatchEvent(new Event('input', { bubbles: true }));
                                        countryEl.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                }
                            } else {
                                input.value = res.country;
                            }
                            
                            if (onSelect) {
                                onSelect(res);
                            }
                            
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                            suggestionsDiv.classList.add('hidden');
                        };
                        suggestionsDiv.appendChild(row);
                    });
                }).catch(() => {
                    suggestionsDiv.classList.add('hidden');
                });
            }, 350);
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    };
</script>
<form id="packageMainForm" action="{{ $action }}" method="POST" enctype="multipart/form-data"
            @submit.prevent="if(!brochureName && !itineraryContent) { showBrochureError = true; document.getElementById('brochure-itinerary-section').scrollIntoView({behavior: 'smooth', block: 'center'}); } else if(galleryPreviews.length === 0) { showGalleryError = true; document.getElementById('gallery-portfolio-section').scrollIntoView({behavior: 'smooth', block: 'center'}); } else { $el.submit(); }"
            class="space-y-10">
            @csrf
            @if(strtoupper($method) !== 'POST')
                @method($method)
            @endif
            @if($pkg)
                <input type="hidden" name="id" value="{{ $pkg->id }}" />
            @endif

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

                    @if($isAdmin)
                    <div class="mb-4 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Publish On Behalf Of (Agent)</label>
                        @php
                            $paidAgents = collect($agents)->filter(function ($a) { return !empty($a->plan_id) && $a->plan_id > 1; });
                            $freeAgents = collect($agents)->filter(function ($a) { return empty($a->plan_id) || $a->plan_id <= 1; });
                            
                            $selectedAgentName = old('agent');
                            if (!$selectedAgentName && isset($pkg) && !empty($pkg->agent)) {
                                $agentData = json_decode($pkg->agent, true);
                                if (is_array($agentData)) {
                                    $selectedAgentName = $agentData['name'] ?? '';
                                } else {
                                    $selectedAgentName = $pkg->agent;
                                }
                            }
                        @endphp
                          <div class="relative" x-data="{
                              open: false,
                              search: '',
                              selected: '{{ addslashes($selectedAgentName ?? '') }}',
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
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package
                                Name <span class="text-red-500 text-sm">*</span></label>
                            <input required type="text" name="title" x-model="title" placeholder="The Ultimate Bali Escape"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
                        </div>

                        <!-- Destination Type (Segmented control) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Destination
                                Type <span class="text-red-500 text-sm">*</span></label>
                            <div class="segmented-control flex gap-2">
                                @if($canDomestic)
                                <div class="segmented-btn flex-1 text-center py-3 rounded-2xl cursor-pointer font-bold text-xs transition-all" :class="category === 'domestic' ? 'active' : ''"
                                    @click="category = 'domestic'">Domestic</div>
                                @endif
                                @if($canInternational)
                                <div class="segmented-btn flex-1 text-center py-3 rounded-2xl cursor-pointer font-bold text-xs transition-all" :class="category === 'international' ? 'active' : ''"
                                    @click="category = 'international'">International</div>
                                @endif
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

                <!-- Overview & Highlights Card -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="book-open" size="20"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-800">Overview & Highlights</h3>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Brief description and key features of the tour</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Overview Section -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Overview <span class="text-red-500 text-sm">*</span></label>
                            <textarea required name="overview" x-model="overview" rows="6"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 text-sm font-medium text-gray-700 outline-none focus:ring-2 focus:ring-blue-300/50 resize-none transition-all"
                                placeholder="Write a short summary about this package..."></textarea>
                        </div>

                        <!-- Highlights Section -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Highlights <span class="text-red-500 text-sm">*</span></label>
                            <ul class="space-y-2 max-h-48 overflow-y-auto pr-2" x-show="highlights.length > 0">
                                <template x-for="(hl, i) in highlights" :key="i">
                                    <li class="flex justify-between items-center bg-[#F5F5F5] rounded-xl py-2 px-4 shadow-sm group border border-transparent hover:border-blue-100 transition-colors">
                                        <div class="flex items-center gap-2 flex-1 pr-4">
                                            <i data-lucide="circle-check" size="14" class="text-blue-400 shrink-0"></i>
                                            <span class="text-xs font-semibold text-gray-700" x-text="hl"></span>
                                        </div>
                                        <button type="button" @click="removeHighlight(i)"
                                            class="text-gray-400 hover:text-red-500 transition-colors shrink-0" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                            <div class="flex items-start gap-2">
                                <div class="flex-1">
                                    <input type="text" x-model="newHighlight" @keydown.enter.prevent="addHighlight()"
                                        placeholder="Add highlight..."
                                        class="w-full bg-[#F5F5F5] border-none rounded-xl py-2 px-4 text-xs font-medium outline-none focus:ring-2 focus:ring-blue-300/50" />
                                </div>
                                <button type="button" @click="addHighlight()"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-xl text-xs font-bold shrink-0 mt-0.5 hover:bg-blue-600 transition-colors"
                                    style="min-height: 32px;">Add</button>
                            </div>
                            <template x-for="(hl, i) in highlights" :key="i">
                                <input type="hidden" name="highlights[]" :value="hl">
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Departure Dates Card -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-[#ea580c] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-800">Departure Dates</h3>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Set the month, year, and specific departure days for this package</p>
                        </div>
                    </div>

                    <!-- Hidden JSON field for backend submit -->
                    <input type="hidden" name="departure_dates" :value="JSON.stringify(departureDates)">

                    <div class="space-y-4">
                        <template x-for="(row, idx) in departureDates" :key="idx">
                            <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4 p-4 bg-gray-50 rounded-2xl relative" x-data="{ showCalendar: false, tempDates: [...row.dates] }">
                                <!-- Month & Year Input -->
                                <div class="w-full md:w-1/3 space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Month & Year *</label>
                                    <input required type="month" x-model="row.month" 
                                        class="w-full bg-white border border-gray-200 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm shadow-sm" />
                                </div>

                                <!-- Dates Input & Picker Overlay -->
                                <div class="w-full md:w-2/3 space-y-2 relative">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure Days *</label>
                                    <div class="relative">
                                        <input readonly required type="text" 
                                            :value="row.dates.sort((a,b)=>a-b).join(', ')" 
                                            placeholder="Example: 1, 5, 10, 15, 21" 
                                            @click="showCalendar = !showCalendar; tempDates = [...row.dates]"
                                            class="w-full bg-white border border-gray-200 rounded-xl py-3 px-4 pr-10 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm shadow-sm cursor-pointer" />
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                        </div>
                                    </div>

                                    <!-- Day Picker Overlay -->
                                    <div x-show="showCalendar" @click.outside="showCalendar = false" 
                                        class="absolute z-[60] left-0 mt-1 bg-white border border-gray-200 rounded-2xl shadow-xl p-4 w-72 max-w-full" style="display: none;">
                                        <div class="grid grid-cols-7 gap-1.5 text-center mb-4">
                                            <!-- Calendar days grid 1-31 -->
                                            <template x-for="day in 31" :key="day">
                                                <button type="button" 
                                                    @click="if(tempDates.includes(day)) { tempDates = tempDates.filter(d => d !== day) } else { tempDates.push(day) }"
                                                    class="w-8 h-8 flex items-center justify-center text-xs font-bold rounded-lg transition-all"
                                                    :class="tempDates.includes(day) ? 'bg-[#ea580c] text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-700'">
                                                    <span x-text="day"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                                            <button type="button" @click="showCalendar = false" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all">Cancel</button>
                                            <button type="button" @click="row.dates = [...tempDates]; showCalendar = false" class="px-3 py-1.5 bg-[#ea580c] hover:bg-orange-600 text-white rounded-lg text-xs font-bold transition-all">Save</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Button -->
                                <div class="absolute right-4 top-4 md:static md:mt-7 flex items-center justify-center">
                                    <button type="button" @click="removeDepartureRow(idx)" class="w-10 h-10 bg-red-50 border border-red-100 hover:bg-red-100 hover:border-red-200 text-red-500 rounded-xl flex items-center justify-center transition-colors shadow-sm" title="Delete Row">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="addDepartureRow" class="bg-orange-50 hover:bg-orange-100 text-[#ea580c] px-5 py-3 rounded-2xl font-bold text-xs transition-colors flex items-center gap-2 border border-orange-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add more
                        </button>
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
                                (Nights) <span class="text-red-500 text-sm">*</span></label>
                            <input type="hidden" name="duration" x-model="duration">
                            <div class="flex items-center gap-4">
                                <input required type="number" min="1" x-model="nights" @input="updateDurationFromNights"
                                    class="w-1/3 bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm"
                                    placeholder="Enter nights" />
                                <span class="text-sm font-bold text-gray-500"
                                    x-text="nights ? (parseInt(nights) + 1) + ' Days' : 'Days will calculate automatically'"></span>
                            </div>
                        </div>

                        <!-- Package Validity -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Package
                                Expiry Date <span class="text-red-500 text-sm">*</span></label>
                            <div class="relative">
                                <i data-lucide="calendar" size="16"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input required type="text" name="validity" x-model="validity" x-ref="validityPicker"
                                    placeholder="Select Expiry Date"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-10 pr-4 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-foreground text-sm" />
                            </div>
                        </div>

                        <!-- Transit Type (group_size) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Transit
                                Type <span class="text-red-500 text-sm">*</span></label>

                            <select required name="group_size" x-model="group_size"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                @foreach($transits as $t)
                                    <option value="{{ $t->name }}">{{ $t->name }}</option>
                                @endforeach
                                @if($transits->isEmpty())
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
                                City <span class="text-red-500 text-sm">*</span></label>
                            <input required type="text" name="departure_city" id="departureCity" placeholder="New Delhi" value="{{ $pkg->departure_city ?? '' }}"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm"
                                autocomplete="off" />
                            <div id="departureCitySuggestions"
                                class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>

                        <!-- Departure State -->
                        <div class="space-y-2 relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                State <span class="text-red-500 text-sm">*</span></label>
                            <input required type="text" name="departure_state" id="departureState" placeholder="Delhi" value="{{ $pkg->departure_state ?? '' }}"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm"
                                autocomplete="off" />
                            <div id="departureStateSuggestions"
                                class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>

                        <!-- Departure Country -->
                        <div class="space-y-2 relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Departure
                                Country <span class="text-red-500 text-sm">*</span></label>
                            <input required type="text" name="departure_country" id="departureCountry" placeholder="India" value="{{ $pkg->departure_country ?? '' }}"
                                class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm"
                                autocomplete="off" />
                            <div id="departureCountrySuggestions"
                                class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lower Grid: Pricing and Classification -->
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
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Currency <span class="text-red-500 text-sm">*</span></label>
                            <div class="relative">
                                <i data-lucide="coins" size="16" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select required name="currency" x-model="currency" @change="updatePrice(false)"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm appearance-none">
                                    <option value="₹">INR (₹)</option>
                                    <option value="$">USD ($)</option>
                                    <option value="AED">AED</option>
                                    <option value="€">EUR (€)</option>
                                    <option value="£">GBP (£)</option>
                                </select>
                                <i data-lucide="chevron-down" size="16" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Price Per Person (<span x-text="currency"></span>)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400" x-text="currency"></span>
                                    <input required type="number" step="0.01" name="price" x-model="price" @input="updatePrice(true)" placeholder="45999"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm" />
                                </div>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400" x-text="currency"></span>
                                    <input type="number" step="0.01" name="old_price" x-model="old_price" placeholder="Old Price"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 pl-12 pr-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm line-through text-gray-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Hide Price Toggle -->
                        @if($canHidePrice || $isAdmin)
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="hide_price" x-model="hidePrice"
                                class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            <span class="text-xs font-bold text-gray-600">Hide price from package listing</span>
                        </label>
                        @endif
                    </div>

                    <!-- Right Column: Theme, Holiday Type & Tags -->
                    <div class="flex flex-col gap-4">
                        <!-- Theme & Holiday Type Card -->
                        @if($canThemeOptions || $isAdmin)
                        <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                    <i data-lucide="compass" size="20"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">Theme & Holiday Type</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Theme Selection</label>
                                    <select name="theme"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                        <option value="" disabled {{ empty($pkg->theme) ? 'selected' : '' }}>Select Theme</option>
                                        @foreach($themes as $t)
                                            <option value="{{ $t->name }}" {{ ($pkg->theme ?? '') == $t->name ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Holiday Type</label>
                                    <select name="holiday_type"
                                        class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-foreground text-sm">
                                        <option value="" disabled {{ empty($pkg->holiday_type) ? 'selected' : '' }}>Select Holiday Type</option>
                                        @foreach($holidayTypes as $h)
                                            <option value="{{ $h->name }}" {{ ($pkg->holiday_type ?? '') == $h->name ? 'selected' : '' }}>{{ $h->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tags & Badges Card -->
                        <div class="bg-white rounded-[32px] border border-gray-100 p-8 shadow-sm flex-1">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                                    <i data-lucide="tag" size="20"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">Tags & Badges</h3>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Tag Name</label>
                                <input type="text" name="badge" maxlength="9" placeholder="e.g. 25% Off" value="{{ $pkg->badge ?? '' }}"
                                    class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/25 transition-all font-bold text-gray-800 text-sm" />
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Trip Location/Search Keywords Card (separate, full-width) -->
                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="map-pin" size="20"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-800">Trip Location / Search Keywords <span class="text-red-500 text-sm">*</span></h3>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Add cities, states & countries so travellers can find this package</p>
                        </div>
                    </div>

                    <!-- Dynamic Rows -->
                    <div class="space-y-3">
                        <template x-for="(row, index) in keywordRows" :key="index">
                            <div class="flex flex-col md:flex-row items-end gap-3">
                                <div class="flex flex-col sm:flex-row items-stretch gap-3 flex-1">
                                    <div class="flex-1 space-y-1 relative" x-init="window.setupAutocompleteElement($el.querySelector('input'), $el.querySelector('.suggestions-box'), 'city', (res) => { row.city = res.city; row.state = res.state; row.country = res.country; })">
                                        <label class="text-[10px] font-black text-indigo-400 uppercase tracking-wider pl-1">City <span class="text-red-500 text-sm" x-show="index === 0">*</span></label>
                                        <input :required="index === 0" type="text" x-model="row.city" autocomplete="off" placeholder="e.g. New Delhi" class="w-full bg-[#F0F0FF] border-none rounded-2xl py-3.5 px-4 text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-indigo-300/50 transition-all" />
                                        <div class="suggestions-box absolute z-50 w-full bg-white rounded-2xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto hidden mt-1"></div>
                                    </div>
                                    <div class="flex-1 space-y-1 relative" x-init="window.setupAutocompleteElement($el.querySelector('input'), $el.querySelector('.suggestions-box'), 'state', (res) => { row.state = res.state; row.country = res.country; })">
                                        <label class="text-[10px] font-black text-indigo-400 uppercase tracking-wider pl-1">State <span class="text-red-500 text-sm" x-show="index === 0">*</span></label>
                                        <input :required="index === 0" type="text" x-model="row.state" autocomplete="off" placeholder="e.g. Delhi" class="w-full bg-[#F0F0FF] border-none rounded-2xl py-3.5 px-4 text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-indigo-300/50 transition-all" />
                                        <div class="suggestions-box absolute z-50 w-full bg-white rounded-2xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto hidden mt-1"></div>
                                    </div>
                                    <div class="flex-1 space-y-1 relative" x-init="window.setupAutocompleteElement($el.querySelector('input'), $el.querySelector('.suggestions-box'), 'country', (res) => { row.country = res.country; })">
                                        <label class="text-[10px] font-black text-indigo-400 uppercase tracking-wider pl-1">Country <span class="text-red-500 text-sm" x-show="index === 0">*</span></label>
                                        <input :required="index === 0" type="text" x-model="row.country" autocomplete="off" placeholder="e.g. India" class="w-full bg-[#F0F0FF] border-none rounded-2xl py-3.5 px-4 text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-indigo-300/50 transition-all" />
                                        <div class="suggestions-box absolute z-50 w-full bg-white rounded-2xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto hidden mt-1"></div>
                                    </div>
                                </div>
                                <button type="button" @click.prevent="removeKeywordRow(index)"
                                    class="px-4 py-3.5 bg-red-50 text-red-400 rounded-2xl text-xs font-black hover:bg-red-100 hover:text-red-600 transition-colors mb-0.5"
                                    x-show="keywordRows.length > 1 || row.city || row.state || row.country">
                                    <i data-lucide="trash-2" size="14"></i>
                                </button>
                                <input type="hidden" name="keywords[]" :value="[row.city, row.state, row.country].map(s => String(s || '').trim()).filter(Boolean).join(', ')">
                            </div>
                        </template>
                    </div>

                    <!-- Add More Button -->
                    <div>
                        <button type="button" @click.prevent="addKeywordRow()"
                            class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-2xl text-sm font-black hover:bg-indigo-100 transition-colors inline-flex items-center gap-2 shadow-sm">
                            <i data-lucide="plus" size="15"></i> Add another location
                        </button>
                    </div>
                </div>

            </div>

            <!-- ==================== STEP 2: ITINERARY, MEALS & PHOTOS ==================== -->
            <div class="space-y-8 mt-8">

                <!-- Note Message -->
                <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 flex gap-3 items-start w-full">
                    <div class="mt-0.5 text-orange-500">
                        <i data-lucide="info" size="18"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-orange-800">Note: You only need to provide ONE of these.</p>
                        <p class="text-xs text-orange-700 mt-1 font-medium">Either upload a PDF Brochure <b class="font-black text-orange-900">OR</b> write a Day-by-Day Itinerary. If you upload a Brochure, the Itinerary section is not required.</p>
                    </div>
                </div>

                <!-- ⚡ Full-width row: Upload Brochure  OR  Itinerary (Day-by-Day Plan) ⚡ -->
                <p x-show="showBrochureError && !brochureName && !itineraryContent" x-cloak class="text-[12px] text-red-500 font-bold mb-2">This is a required section. Please upload a Brochure OR provide an Itinerary.</p>
                <div id="brochure-itinerary-section" class="flex flex-row gap-4 items-stretch w-full overflow-hidden">

                    <!-- Brochure card -->
                    <div
                        class="w-1/2 lg:w-[40%] bg-white rounded-[28px] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col transition-all duration-300" x-show="!hasItineraryData">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" size="16" class="text-primary"></i>
                            </div>
                            <h4 class="text-sm font-bold text-gray-800">Upload Brochure</h4>
                        </div>
                        <div class="flex-1 w-full rounded-2xl p-5 border-2 border-dashed border-red-200 text-center cursor-pointer hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center min-h-[200px]"
                            @click="if(!brochureName) $refs.brochureInput.click()"
                            @dragover.prevent.stop="$el.classList.add('bg-orange-50', 'border-red-400')"
                            @dragleave.prevent.stop="$el.classList.remove('bg-orange-50', 'border-red-400')"
                            @drop.prevent.stop="$el.classList.remove('bg-orange-50', 'border-red-400'); 
                                if(!brochureName && $event.dataTransfer.files.length) { 
                                    let file = $event.dataTransfer.files[0];
                                    if(file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                                        $refs.brochureInput.files = $event.dataTransfer.files; 
                                        brochureName = file.name; 
                                        $refs.brochureInput.dispatchEvent(new Event('change', { bubbles: true }));
                                    } else {
                                        alert('Please upload a PDF file only.');
                                    }
                                }">
                            <div x-show="!brochureName" class="flex flex-col items-center justify-center">
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
                            <div x-show="brochureName" style="display: none;" class="flex flex-col items-center justify-center w-full space-y-4">
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
                            <input type="file" name="brochure_file" x-ref="brochureInput" accept=".pdf" class="hidden"
                                @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                        </div>
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
                            class="w-full flex-1 bg-transparent border-none py-4 px-5 outline-none text-gray-700 text-sm resize-none">{!! $pkg->editorial_itinerary ?? '' !!}</textarea>
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

                <!-- ── 2-col layout: left content + right sidebar ── -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                    <!-- Left Column -->
                    <div class="space-y-8">

                        <!-- Editorial Details Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-6 shadow-sm transition-all duration-300" x-show="!brochureName">
                            <h3 class="text-lg font-bold text-gray-900">Editorial Details</h3>

                            <div class="space-y-6">
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
                                                                    x-text="(ht.city ? ht.city + ' - ' : '') + (ht.room || 'Standard Room')"></p>
                                                            </div>
                                                        </template>
                                                        <template x-if="editingHotelIndex === idx">
                                                            <div class="space-y-1 pr-4">
                                                                <div>
                                                                    <input type="text" x-model="ht.name"
                                                                        @input="let w = $el.value.trim().split(/\s+/); if(w.length > 10 && w[0] !== '') { ht.name = w.slice(0,10).join(' ') + ' '; }"
                                                                        class="w-full bg-gray-50 border border-gray-100 rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                                        @keydown.enter.prevent="editingHotelIndex = null" />
                                                                    <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">
                                                                        <span x-text="(ht.name || '').trim() ? (ht.name || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((ht.name || '').trim() ? (ht.name || '').trim().split(/\s+/).length : 0) >= 10}"></span> / 10 words
                                                                    </div>
                                                                </div>
                                                                <div class="relative" x-init="window.setupAutocompleteElement($el.querySelector('input'), $el.querySelector('.suggestions-box'), 'city', (res) => { ht.city = res.city; })">
                                                                    <input type="text" x-model="ht.city" autocomplete="off"
                                                                        @input="let w = $el.value.trim().split(/\s+/); if(w.length > 5 && w[0] !== '') { ht.city = w.slice(0,5).join(' ') + ' '; }"
                                                                        class="w-full bg-gray-50 border border-gray-100 rounded-lg py-1 px-2 text-[10px] outline-none focus:ring-1 focus:ring-primary/20"
                                                                        placeholder="City..."
                                                                        @keydown.enter.prevent="editingHotelIndex = null" />
                                                                    <div class="suggestions-box absolute z-50 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-48 overflow-y-auto hidden mt-1 left-0"></div>
                                                                    <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">
                                                                        <span x-text="(ht.city || '').trim() ? (ht.city || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((ht.city || '').trim() ? (ht.city || '').trim().split(/\s+/).length : 0) >= 5}"></span> / 5 words
                                                                    </div>
                                                                </div>
                                                                <div class="relative" x-data="{ openRoom: false }">
                                                                     <button type="button" @click="openRoom = !openRoom" @click.outside="openRoom = false" 
                                                                         class="w-full bg-gray-50 border border-gray-100 rounded-lg py-1.5 px-2 text-[10px] font-medium text-left flex items-center justify-between outline-none focus:ring-1 focus:ring-primary/20">
                                                                         <span x-text="ht.room || 'Select Room / Category...'" :class="{'text-gray-400': !ht.room, 'text-gray-800 font-semibold': ht.room}"></span>
                                                                         <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 transition-transform text-gray-400" :class="{'rotate-180': openRoom}"><path d="m6 9 6 6 6-6"/></svg>
                                                                     </button>
                                                                     <div x-show="openRoom" x-transition.opacity.duration.150ms 
                                                                         class="absolute z-50 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-44 overflow-y-auto mt-1 left-0 py-1 divide-y divide-gray-50">
                                                                         <div @click="ht.room = ''; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-400 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Select Room / Category...</div>
                                                                         @foreach($hotelCategoriesList as $cat)
                                                                             <div @click="ht.room = '{{ $cat->name }}'; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium" :class="{'bg-orange-50 text-primary font-bold': ht.room === '{{ $cat->name }}'}">{{ $cat->name }}</div>
                                                                         @endforeach
                                                                         <div @click="ht.room = 'Standard Room'; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Standard Room</div>
                                                                         <div @click="ht.room = 'Deluxe Room'; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Deluxe Room</div>
                                                                         <div @click="ht.room = 'Executive Suite'; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Executive Suite</div>
                                                                         <div @click="ht.room = 'Luxury Suite'; openRoom = false" class="px-2.5 py-1.5 text-[10px] text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Luxury Suite</div>
                                                                     </div>
                                                                 </div>
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
                                        <div>
                                            <input type="text" x-model="newHotelName" placeholder="Hotel Name..."
                                                @input="let w = $el.value.trim().split(/\s+/); if(w.length > 10 && w[0] !== '') { newHotelName = w.slice(0,10).join(' ') + ' '; }"
                                                class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                            <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                                <span x-text="(newHotelName || '').trim() ? (newHotelName || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((newHotelName || '').trim() ? (newHotelName || '').trim().split(/\s+/).length : 0) >= 10}"></span> / 10 words
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <div class="flex-1 relative" x-init="window.setupAutocompleteElement($el.querySelector('input'), $el.querySelector('.suggestions-box'), 'city', (res) => { newHotelCity = res.city; })">
                                                <input type="text" x-model="newHotelCity" autocomplete="off" placeholder="City..."
                                                    @input="let w = $el.value.trim().split(/\s+/); if(w.length > 5 && w[0] !== '') { newHotelCity = w.slice(0,5).join(' ') + ' '; }"
                                                    class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-orange-200" />
                                                <div class="suggestions-box absolute z-50 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-48 overflow-y-auto hidden mt-1 left-0"></div>
                                                <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                                    <span x-text="(newHotelCity || '').trim() ? (newHotelCity || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((newHotelCity || '').trim() ? (newHotelCity || '').trim().split(/\s+/).length : 0) >= 5}"></span> / 5 words
                                                </div>
                                            </div>
                                            <div class="flex-1 relative" x-data="{ openRoom: false }">
                                                 <button type="button" @click="openRoom = !openRoom" @click.outside="openRoom = false" 
                                                     class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium text-left flex items-center justify-between outline-none focus:ring-1 focus:ring-orange-200">
                                                     <span x-text="newHotelRoom || 'Select Room / Category...'" :class="{'text-gray-400': !newHotelRoom, 'text-gray-800 font-semibold': newHotelRoom}"></span>
                                                     <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 transition-transform text-gray-400" :class="{'rotate-180': openRoom}"><path d="m6 9 6 6 6-6"/></svg>
                                                 </button>
                                                 <div x-show="openRoom" x-transition.opacity.duration.150ms 
                                                     class="absolute z-50 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-44 overflow-y-auto mt-1 left-0 py-1 divide-y divide-gray-50">
                                                     <div @click="newHotelRoom = ''; openRoom = false" class="px-3 py-1.5 text-xs text-gray-400 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Select Room / Category...</div>
                                                     @foreach($hotelCategoriesList as $cat)
                                                         <div @click="newHotelRoom = '{{ $cat->name }}'; openRoom = false" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium" :class="{'bg-orange-50 text-primary font-bold': newHotelRoom === '{{ $cat->name }}'}">{{ $cat->name }}</div>
                                                     @endforeach
                                                     <div @click="newHotelRoom = 'Standard Room'; openRoom = false" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Standard Room</div>
                                                     <div @click="newHotelRoom = 'Deluxe Room'; openRoom = false" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Deluxe Room</div>
                                                     <div @click="newHotelRoom = 'Executive Suite'; openRoom = false" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Executive Suite</div>
                                                     <div @click="newHotelRoom = 'Luxury Suite'; openRoom = false" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-orange-50 hover:text-primary cursor-pointer font-medium">Luxury Suite</div>
                                                 </div>
                                             </div>
                                            <button type="button"
                                                @click="addHotel()"
                                                class="w-10 h-10 shrink-0 text-white rounded-xl text-sm font-bold flex items-center justify-center shadow-sm hover:opacity-90 transition-opacity bg-primary"
                                                style="background-color: #e85d26 !important; color: white !important;">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Premium Services Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-6 shadow-sm transition-all duration-300" x-show="!brochureName">
                            <h3 class="text-lg font-bold text-gray-900">Premium Services</h3>
                            @php $pkgAmenities = isset($pkg->amenities) ? json_decode($pkg->amenities, true) : []; if(!is_array($pkgAmenities)) $pkgAmenities = []; @endphp
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-4 bg-purple-50 rounded-2xl cursor-pointer hover:bg-purple-100/60 transition-all border border-purple-100/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-200/50 flex items-center justify-center">
                                            <i data-lucide="chef-hat" class="text-purple-600" size="16"></i>
                                        </div>
                                        <span class="text-xs font-bold text-purple-900">Private Chef Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Private Chef Included" {{ in_array('Private Chef Included', $pkgAmenities) ? 'checked' : '' }} class="w-5 h-5 rounded border-purple-300 text-purple-600 focus:ring-purple-500/25 cursor-pointer" />
                                </label>
                                <label class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl cursor-pointer hover:bg-blue-100/60 transition-all border border-blue-100/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-200/50 flex items-center justify-center">
                                            <i data-lucide="user-check" class="text-blue-600" size="16"></i>
                                        </div>
                                        <span class="text-xs font-bold text-blue-900">Tour Manager Included</span>
                                    </div>
                                    <input type="checkbox" name="amenities[]" value="Tour Manager Included" {{ in_array('Tour Manager Included', $pkgAmenities) ? 'checked' : '' }} class="w-5 h-5 rounded border-blue-300 text-blue-600 focus:ring-blue-500/25 cursor-pointer" />
                                </label>
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
                                                <td class="py-4 px-6 align-top">
                                                    <div>
                                                        <input type="text" name="itinerary_titles[]"
                                                            x-model="day.title"
                                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 10 && w[0] !== '') { day.title = w.slice(0,10).join(' ') + ' '; }"
                                                            class="w-full bg-transparent border-none outline-none font-bold text-gray-800 focus:ring-0 p-0 text-sm"
                                                            placeholder="e.g. Red Fort" />
                                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                                            <span x-text="(day.title || '').trim() ? (day.title || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((day.title || '').trim() ? (day.title || '').trim().split(/\s+/).length : 0) >= 10}"></span> / 10 words
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 align-top">
                                                    <div>
                                                        <input type="text"
                                                            name="itinerary_descriptions[]" x-model="day.desc"
                                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 20 && w[0] !== '') { day.desc = w.slice(0,20).join(' ') + ' '; }"
                                                            class="w-full bg-transparent border-none outline-none text-gray-500 focus:ring-0 p-0 text-sm"
                                                            placeholder="e.g. Historical Guided Tour" />
                                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                                            <span x-text="(day.desc || '').trim() ? (day.desc || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((day.desc || '').trim() ? (day.desc || '').trim().split(/\s+/).length : 0) >= 20}"></span> / 20 words
                                                        </div>
                                                    </div>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300" x-show="!brochureName">
                            <!-- Inclusions Card -->
                            <div class="bg-[#F0FAF5] rounded-[28px] border border-green-100 p-6 space-y-4 shadow-sm"
                                style="background-color: #F0FAF5 !important; border-color: #d3f9d8 !important;">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-[#2f9e44]">
                                        <i data-lucide="circle-check" size="20"></i>
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
                                                    <div class="w-full">
                                                        <input type="text" x-model="inclusions[i]"
                                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 20 && w[0] !== '') { inclusions[i] = w.slice(0,20).join(' ') + ' '; }"
                                                            @keydown.enter.prevent="editingInclusionIndex = null"
                                                            @blur="editingInclusionIndex = null"
                                                            class="w-full bg-[#F5F5F5] border-none rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                            x-init="$nextTick(() => $el.focus())" />
                                                        <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">
                                                            <span x-text="(inclusions[i] || '').trim() ? (inclusions[i] || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((inclusions[i] || '').trim() ? (inclusions[i] || '').trim().split(/\s+/).length : 0) >= 20}"></span> / 20 words
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
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
                                <div class="flex items-start gap-2">
                                    <div class="flex-1">
                                        <input type="text" x-model="newInclusion" @keydown.enter.prevent="addInclusion()"
                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 20 && w[0] !== '') { newInclusion = w.slice(0,20).join(' ') + ' '; }"
                                            placeholder="Add inclusion..."
                                            class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-green-300" />
                                        <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                            <span x-text="(newInclusion || '').trim() ? (newInclusion || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((newInclusion || '').trim() ? (newInclusion || '').trim().split(/\s+/).length : 0) >= 20}"></span> / 20 words
                                        </div>
                                    </div>
                                    <button type="button" @click="addInclusion()"
                                        class="px-4 py-2 bg-[#2f9e44] text-white rounded-xl text-xs font-bold shrink-0 mt-0.5"
                                        style="background-color: #2f9e44 !important; min-height: 34px;">Add</button>
                                </div>
                                <template x-for="(inc, i) in inclusions" :key="i">
                                    <input type="hidden" name="included[]" :value="inc">
                                </template>
                            </div>

                            <!-- Exclusions Card -->
                            <div class="bg-[#FFF5F5] rounded-[28px] border border-red-100 p-6 space-y-4 shadow-sm"
                                style="background-color: #FFF5F5 !important; border-color: #ffe3e3 !important;">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-[#e03131]">
                                        <i data-lucide="circle-x" size="20"></i>
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
                                                    <div class="w-full">
                                                        <input type="text" x-model="exclusions[i]"
                                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 20 && w[0] !== '') { exclusions[i] = w.slice(0,20).join(' ') + ' '; }"
                                                            @keydown.enter.prevent="editingExclusionIndex = null"
                                                            @blur="editingExclusionIndex = null"
                                                            class="w-full bg-[#F5F5F5] border-none rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                            x-init="$nextTick(() => $el.focus())" />
                                                        <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">
                                                            <span x-text="(exclusions[i] || '').trim() ? (exclusions[i] || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((exclusions[i] || '').trim() ? (exclusions[i] || '').trim().split(/\s+/).length : 0) >= 20}"></span> / 20 words
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
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
                                <div class="flex items-start gap-2">
                                    <div class="flex-1">
                                        <input type="text" x-model="newExclusion" @keydown.enter.prevent="addExclusion()"
                                            @input="let w = $el.value.trim().split(/\s+/); if(w.length > 20 && w[0] !== '') { newExclusion = w.slice(0,20).join(' ') + ' '; }"
                                            placeholder="Add exclusion..."
                                            class="w-full bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-red-300" />
                                        <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                            <span x-text="(newExclusion || '').trim() ? (newExclusion || '').trim().split(/\s+/).length : 0" :class="{'text-red-500': ((newExclusion || '').trim() ? (newExclusion || '').trim().split(/\s+/).length : 0) >= 20}"></span> / 20 words
                                        </div>
                                    </div>
                                    <button type="button" @click="addExclusion()"
                                        class="px-4 py-2 bg-[#FFF0F0] text-red-500 rounded-xl text-xs font-bold hover:bg-red-50 hover:text-red-600 transition-colors shrink-0 mt-0.5"
                                        style="min-height: 34px;">Add</button>
                                </div>
                                <template x-for="(exc, i) in exclusions" :key="i">
                                    <input type="hidden" name="excluded[]" :value="exc">
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">

                        <!-- About Tours Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-4 shadow-sm transition-all duration-300" x-show="!brochureName" x-data="{ 
                            count: 0, 
                            limit: 150, 
                            updateCount() { 
                                let text = $refs.textarea.value.trim();
                                let words = text ? text.split(/\s+/) : [];
                                if (words.length > this.limit) {
                                    $refs.textarea.value = words.slice(0, this.limit).join(' ');
                                    text = $refs.textarea.value;
                                    words = text.split(/\s+/);
                                }
                                this.count = words.length;
                            } 
                        }" x-init="updateCount()">
                            <h3 class="text-lg font-bold text-gray-900">About Tours <span class="text-red-500 text-sm">*</span></h3>
                            <textarea required x-ref="textarea" @input="updateCount()" name="about_tours" rows="5"
                                class="w-full h-32 bg-[#E8E8E8] border-none rounded-xl py-3 px-4 text-xs font-medium text-gray-700 outline-none focus:ring-2 focus:ring-primary/20 resize-none"
                                placeholder="Brief overview about the tour...">{{ $pkg->about_tours ?? '' }}</textarea>
                            <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                <span x-text="count" :class="{'text-red-500': count >= limit}"></span> / <span x-text="limit"></span> words
                            </div>
                        </div>

                        <!-- Terms & Conditions Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-4 shadow-sm transition-all duration-300" x-show="!brochureName" x-data="{ 
                            count: 0, 
                            limit: 250, 
                            updateCount() { 
                                let text = $refs.textarea.value.trim();
                                let words = text ? text.split(/\s+/) : [];
                                if (words.length > this.limit) {
                                    $refs.textarea.value = words.slice(0, this.limit).join(' ');
                                    text = $refs.textarea.value;
                                    words = text.split(/\s+/);
                                }
                                this.count = words.length;
                            } 
                        }" x-init="updateCount()">
                            <h3 class="text-lg font-bold text-gray-900">Terms & Conditions</h3>
                            <textarea x-ref="textarea" @input="updateCount()" name="terms" rows="4" placeholder="Specific booking policies for this package..."
                                class="w-full bg-[#E8E8E8] border-none rounded-xl py-3 px-4 text-xs font-medium text-gray-700 outline-none focus:ring-2 focus:ring-primary/20 resize-none">{{ $pkg->terms ?? '' }}</textarea>
                            <div class="text-right text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                <span x-text="count" :class="{'text-red-500': count >= limit}"></span> / <span x-text="limit"></span> words
                            </div>
                        </div>

                        <!-- Essential Amenities Card -->
                        <div class="bg-white rounded-[28px] border border-gray-100 p-8 space-y-4 shadow-sm transition-all duration-300" x-show="!brochureName">
                            <h3 class="text-lg font-bold text-gray-900">Essential Amenities</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                                <template x-for="(am, idx) in amenitiesList" :key="idx">
                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-700 bg-gray-50 p-2.5 rounded-xl border border-gray-200/60 shadow-sm w-full">
                                        <div class="flex items-center shrink-0">
                                            <input type="checkbox" x-model="amenitiesList[idx].selected" class="w-4 h-4 text-primary bg-white border-gray-300 rounded focus:ring-primary/20 cursor-pointer">
                                        </div>
                                        <div class="flex-1">
                                            <template x-if="editingAmenityIndex !== idx">
                                                <span x-text="am.name" class="cursor-pointer hover:underline" @click="editingAmenityIndex = idx"></span>
                                            </template>
                                            <template x-if="editingAmenityIndex === idx">
                                                <input type="text" x-model="amenitiesList[idx].name"
                                                    @keydown.enter.prevent="editingAmenityIndex = null"
                                                    @blur="editingAmenityIndex = null"
                                                    class="w-full bg-white border border-gray-200 rounded-lg py-1 px-2 text-xs outline-none focus:ring-1 focus:ring-primary/20"
                                                    x-init="$nextTick(() => $el.focus())" />
                                            </template>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0 ml-1">
                                            <button type="button" @click="editingAmenityIndex = (editingAmenityIndex === idx ? null : idx)" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>
                                            <button type="button" @click="removeAmenity(idx)" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </div>
                                        <template x-if="am.selected">
                                            <input type="hidden" name="amenities[]" :value="am.name">
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <div class="flex gap-2 pt-2">
                                <input type="text" x-model="newAmenity" @keydown.enter.prevent="addAmenity()" placeholder="Add new amenity..." class="flex-1 bg-white border border-gray-200 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-primary/50 max-w-sm" />
                                <button type="button" @click="addAmenity()" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl text-xs font-bold transition-colors">Add Amenity</button>
                            </div>
                        </div>

                        <!-- Gallery Portfolio Card -->
                        @if($canAddGallery || $isAdmin)
                        <div id="gallery-portfolio-section" class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-8 shadow-sm">
                            <div class="space-y-4">
                                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest pl-1">Gallery
                                    Portfolio <span class="text-red-500 text-sm">*</span></h4>
                                <p class="text-[10px] text-gray-400 font-medium pl-1 -mt-3">Search image regarding your package/choose image or uplod your package imgaes</p>
                                <p x-show="showGalleryError && galleryPreviews.length === 0" x-cloak class="text-[11px] text-red-500 font-bold pl-1 -mt-2">This is a required section. Please select or upload at least one image.</p>

                                <div class="grid grid-cols-3 gap-3 relative">
                                    <template x-for="(img, idx) in galleryPreviews" :key="idx">
                                        <div
                                            class="relative aspect-[4/3] rounded-2xl overflow-hidden group border border-gray-100 shadow-sm">
                                            <img :src="img.url" class="w-full h-full object-cover" />
                                            <template x-if="img.is_gallery">
                                                <input type="hidden" name="existing_gallery_urls[]" :value="img.path" />
                                            </template>
                                            <input type="hidden" name="gallery_order[]" :value="img.is_gallery ? ('existing:' + img.path) : ('file:' + img.file_index)" />
                                            
                                            <!-- Cover Photo Badge for index 0 -->
                                            <template x-if="idx === 0">
                                                <div class="absolute top-2 left-2 bg-[#e85d26] text-white text-[9px] font-black px-2 py-0.5 rounded-md shadow-md uppercase tracking-wider z-10" style="background-color: #e85d26 !important; color: white !important;">
                                                    Cover Photo
                                                </div>
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

                                    <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-orange-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-orange-50/30 hover:bg-orange-50/60"
                                        @click="if (photoLimit > 0 && galleryPreviews.length >= photoLimit) { window.showAlertLimit('Your plan allows a maximum of ' + photoLimit + ' package photos in Gallery Portfolio.'); } else { openGalleryModal('gallery'); }">
                                        <i data-lucide="image" class="text-primary mb-1" size="20"></i>
                                        <span class="text-xs font-bold text-primary text-center">From<br>Gallery</span>
                                    </div>
                                    
                                    <div class="aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition-all flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-orange-50/20"
                                        @click="if (photoLimit > 0 && galleryPreviews.length >= photoLimit) { window.showAlertLimit('Your plan allows a maximum of ' + photoLimit + ' package photos in Gallery Portfolio.'); } else { $refs.galleryFilesInput.click(); }">
                                        <i data-lucide="plus" class="text-gray-400 mb-1" size="20"></i>
                                        <span class="text-xs font-bold text-gray-800">Add More</span>
                                        <input type="file" name="gallery_files[]" x-ref="galleryFilesInput" multiple
                                            class="hidden" @change="handleGalleryChange($event)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div id="gallery-portfolio-section" class="bg-gray-50 rounded-[32px] border border-gray-200 p-8 space-y-3 shadow-sm text-center">
                            <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mx-auto mb-1">
                                <i data-lucide="lock" size="20"></i>
                            </div>
                            <h4 class="text-xs font-black text-gray-700 uppercase tracking-widest">Gallery Portfolio Restricted</h4>
                            <p class="text-xs text-gray-500 font-medium max-w-sm mx-auto">Gallery photo upload is not included in your current subscription plan. Upgrade your plan to add gallery photos.</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Footer Actions Panel -->
            <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-8">
                <div></div> <!-- Spacer -->
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ request()->is('admin/*') ? url('/admin/packages') : url('/agent/my-packages') }}"
                        class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all cursor-pointer">
                        Discard
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-primary hover:bg-orange-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20 cursor-pointer"
                        style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save Package
                    </button>
                </div>
            </div>
        </form>

        <!-- Gallery Selection Modal -->
        <template x-teleport="body">
            <div x-show="isGalleryModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
                <div class="bg-white rounded-3xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden" @click.away="closeGalleryModal()">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                            <i data-lucide="image" size="20" class="text-primary"></i>
                            Select from Gallery
                        </h3>
                        <button type="button" @click="closeGalleryModal()" class="p-2 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">
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
                                    <img :src="image.file_path.startsWith('http') ? image.file_path : ('{{ rtrim(asset(''), '/') }}/' + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path))" class="w-full h-full object-cover" />
                                    
                                    <!-- Selection Overlay -->
                                    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    </div>

                                    <!-- Checkbox -->
                                    <div class="absolute top-2 right-2 flex items-center justify-center">
                                        <input type="checkbox" 
                                               class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer pointer-events-none" 
                                               :checked="galleryPreviews.some(p => p.url === '/' + image.file_path || (p.url === '{{ asset('') }}' + (image.file_path.startsWith('/') ? image.file_path.substring(1) : image.file_path)))">
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
                        <button type="button" @click="closeGalleryModal()" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-700 transition-colors">
                            Done
                        </button>
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
                        window.dispatchEvent(new CustomEvent('itinerary-updated', { detail: editor.getContent({format: 'text'}).trim() }));
                    });
                }
            });
        }); // End DOMContentLoaded for tinymce

        document.addEventListener('DOMContentLoaded', () => {
            const setupAutocomplete = (inputId, suggestionsId, type) => {
                const input = document.getElementById(inputId);
                const suggestionsDiv = document.getElementById(suggestionsId);
                if (input && suggestionsDiv) {
                    window.setupAutocompleteElement(input, suggestionsDiv, type);
                }
            };

            setupAutocomplete('departureCity', 'departureCitySuggestions', 'city');
            setupAutocomplete('departureState', 'departureStateSuggestions', 'state');
            setupAutocomplete('departureCountry', 'departureCountrySuggestions', 'country');
        });
    </script>





