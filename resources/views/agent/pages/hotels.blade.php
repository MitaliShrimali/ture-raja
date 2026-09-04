@extends('agent.layouts.app')

@section('title', 'Hotels - Tour Raja Agent')

@section('content')

    <!-- Search Bar -->
    <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
        <div class="flex-grow flex items-center px-4">
            <i class="fas fa-search text-gray-300 mr-3"></i>
            <input type="text" id="hotelsSearchInput" oninput="filterHotels()" placeholder="Search/Edit Hotel"
                class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
        </div>
        <button
            class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Hotel Table Container -->
    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Hotels</h3>
            @if(isset($hotelLimitReached) && $hotelLimitReached)
                <a href="{{ route('agent.payment') }}"
                    class="bg-red-500 text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-red-100 hover:scale-105 transition-all w-fit flex items-center gap-2">
                    <i class="fas fa-lock"></i> Upgrade to Add Hotel
                </a>
            @else
                <a href="javascript:void(0)" onclick="toggleHotelModal()"
                    class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                    + Add Hotel
                </a>
            @endif
        </div>

        @if(isset($hotelLimitReached) && $hotelLimitReached)
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm font-medium">This is the limitation of your current plan. Upgrade now to add more hotels!</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 whitespace-nowrap">
                        <th class="pb-4 pl-4">Srl No.</th>
                        <th class="pb-4">Hotel Names</th>
                        <th class="pb-4">Category</th>
                        <th class="pb-4">Package</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4">City</th>
                        <th class="pb-4">State</th>
                        <th class="pb-4">Country</th>
                        <th class="pb-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="hotelTableBody">
                    @foreach($hotels as $index => $h)
                        <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="hotel-row-{{ $h->id }}">
                            <td class="py-4 pl-4 text-xs font-bold text-gray-800">{{ 101 + $index }}</td>
                            <td class="py-4">
                                <div class="flex items-center">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-800 hotel-name">{{ $h->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 hotel-cat">{{ $h->category ?? 'Hotel' }}</td>
                            <td class="py-4 text-[10px] font-bold text-primary hotel-package">{{ $h->package_title ?? 'N/A' }}</td>
                            <td class="py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter hotel-status {{ $h->status == 'Online' || $h->status == 'Published' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $h->status }}
                                </span>
                            </td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 hotel-address">{{ $h->location }}</td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 hotel-state">{{ $h->state ?? 'N/A' }}</td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 hotel-country">{{ $h->country ?? 'N/A' }}</td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button
                                        onclick='editHotel({{ json_encode(["id" => $h->id, "name" => $h->name, "loc" => $h->location, "state" => $h->state, "country" => $h->country, "status" => $h->status, "cat" => $h->category ?? "Hotel", "package_id" => $h->package_id]) }})'
                                        class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                                    <button onclick="deleteHotel({{ $h->id }})"
                                        class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




    <!-- Add/Edit Hotel Modal -->
    <div id="addHotelModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/20 backdrop-blur-sm" onclick="toggleHotelModal()"></div>

        <!-- Modal Content -->
        <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-300"
            id="modalContainer">
            <button onclick="toggleHotelModal()"
                class="absolute top-6 right-8 text-gray-400 hover:text-gray-800 transition-colors">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-2xl font-bold text-gray-800 mb-0.5" id="modalTitle">Add Hotel</h3>
            <p class="text-[10px] text-gray-400 font-medium mb-6">Include a new stay in the traveler's itinerary.</p>

            <form class="space-y-4" method="POST" action="{{ route('agent.hotels.store') }}" id="hotelForm">
                @csrf
                <input type="hidden" id="hotelId" name="id">
                
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Hotel Name</label>
                    <input type="text" id="hotelName" name="name" required placeholder="e.g. Alila Villas Uluwatu"
                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                </div>

                <!-- City Search Bar & Suggestions -->
                <div class="relative">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">City</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="hotelCity" name="location" required placeholder="Search City" autocomplete="off"
                            class="w-full pl-12 pr-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                    </div>
                    <!-- Place Suggestion Dropdown -->
                    <div id="placeSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">State</label>
                        <input type="text" id="hotelState" name="state" required placeholder="State (e.g. Goa)" autocomplete="off"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                        <div id="stateSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll"></div>
                    </div>
                    <div class="relative">
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Country</label>
                        <input type="text" id="hotelCountry" name="country" required placeholder="Country (e.g. India)" autocomplete="off"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                        <div id="countrySuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Category</label>
                        <div class="relative">
                            <select id="hotelCategory" name="category" required
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                                @foreach($hotelCategories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Status</label>
                        <div class="relative">
                            <select id="hotelStatus" name="status" required
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Link to Package (Optional)</label>
                    <div class="relative">
                        <select name="package_id" id="hotelPackage"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="">-- See Packages --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-6 pt-2">
                    <button type="button" onclick="toggleHotelModal()"
                        class="text-xs font-bold text-gray-800 hover:text-gray-400 transition-colors">Cancel</button>
                    <button type="submit"
                        class="bg-primary text-white px-6 py-3 rounded-2xl text-xs font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-[1.02] active:scale-95 transition-all">
                        <i class="fas fa-save mr-2"></i> <span id="submitBtnText">Save Hotel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suggested Cities List -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.setupAutocompleteElement) {
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

                        suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-primary"></i> Searching...</div>';
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
                                            const stateEl = document.getElementById(targetStateId || 'hotelState');
                                            const countryEl = document.getElementById(targetCountryId || 'hotelCountry');
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
                                            const countryEl = document.getElementById(targetCountryId || 'hotelCountry');
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
                                        
                                        if (onSelect) onSelect(res);
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
            }

            const cityInput = document.getElementById('hotelCity');
            const citySuggestionsDiv = document.getElementById('placeSuggestions');
            if (cityInput && citySuggestionsDiv) {
                window.setupAutocompleteElement(cityInput, citySuggestionsDiv, 'city', null, 'hotelState', 'hotelCountry');
            }

            const stateInput = document.getElementById('hotelState');
            const stateSuggestionsDiv = document.getElementById('stateSuggestions');
            if (stateInput && stateSuggestionsDiv) {
                window.setupAutocompleteElement(stateInput, stateSuggestionsDiv, 'state', null, null, 'hotelCountry');
            }

            const countryInput = document.getElementById('hotelCountry');
            const countrySuggestionsDiv = document.getElementById('countrySuggestions');
            if (countryInput && countrySuggestionsDiv) {
                window.setupAutocompleteElement(countryInput, countrySuggestionsDiv, 'country');
            }
        });

        function toggleHotelModal(mode = 'add') {
            const modal = document.getElementById('addHotelModal');
            const container = document.getElementById('modalContainer');
            const title = document.getElementById('modalTitle');
            const submitBtnText = document.getElementById('submitBtnText');
            const form = document.getElementById('hotelForm');

            if (modal.classList.contains('hidden')) {
                if (mode === 'add') {
                    title.innerText = 'Add Hotel';
                    submitBtnText.innerText = 'Save Hotel';
                    form.action = "{{ route('agent.hotels.store') }}";
                    document.getElementById('hotelId').value = '';
                    document.getElementById('hotelName').value = '';
                    document.getElementById('hotelCity').value = '';
                    document.getElementById('hotelState').value = '';
                    document.getElementById('hotelCountry').value = '';
                    if (document.getElementById('hotelCategory')) {
                        document.getElementById('hotelCategory').selectedIndex = 0;
                    }
                    if (document.getElementById('hotelPackage')) document.getElementById('hotelPackage').value = '';
                }
                modal.classList.remove('hidden');
                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                container.classList.remove('scale-100', 'opacity-100');
                container.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        function editHotel(hotel) {
            const title = document.getElementById('modalTitle');
            const submitBtnText = document.getElementById('submitBtnText');
            const form = document.getElementById('hotelForm');

            title.innerText = 'Edit Hotel';
            submitBtnText.innerText = 'Update Hotel';
            form.action = "{{ route('agent.hotels.update') }}";

            document.getElementById('hotelId').value = hotel.id;
            document.getElementById('hotelName').value = hotel.name;
            document.getElementById('hotelCity').value = hotel.loc;
            document.getElementById('hotelState').value = hotel.state || '';
            document.getElementById('hotelCountry').value = hotel.country || '';
            if (document.getElementById('hotelCategory')) {
                document.getElementById('hotelCategory').value = hotel.cat || 'Hotel';
            }
            if (document.getElementById('hotelPackage')) {
                document.getElementById('hotelPackage').value = hotel.package_id || '';
            }

            toggleHotelModal('edit');
        }

        function deleteHotel(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F0642F',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                borderRadius: '2rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "/agent/hotels/delete/" + id;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function filterHotels() {
            const input = document.getElementById('hotelsSearchInput');
            const filter = input.value.toLowerCase();
            const tbody = document.getElementById('hotelTableBody');
            const rows = tbody.querySelectorAll('tr[id^="hotel-row-"]');

            rows.forEach(row => {
                const text = row.textContent || row.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
@endsection
