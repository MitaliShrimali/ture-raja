@extends('agent.layouts.app')

@section('title', 'Add Hotel - Tour Raja Agent')

@section('content')

        <!-- Form Container -->
        <div class="bg-white p-10 rounded-[32px] shadow-sm border border-gray-100 max-w-3xl mx-auto">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-1">Add Hotel</h3>
                <p class="text-xs text-gray-400 font-medium">Include a new stay in the traveler's itinerary.</p>
            </div>

            <form action="{{ route('agent.hotels.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Hotel Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Alila Villas Uluwatu"
                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                </div>

                <!-- City Search Bar & Suggestions -->
                <div class="relative">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">City <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="hotelCity" name="location" required placeholder="Search City (e.g. Ahmedabad, Mumbai)" autocomplete="off"
                            class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                    </div>
                    <!-- Place Suggestion Dropdown -->
                    <div id="placeSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">State <span class="text-red-500">*</span></label>
                        <input type="text" id="hotelState" name="state" required placeholder="State (e.g. Gujarat)" autocomplete="off"
                            class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                        <div id="stateSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll"></div>
                    </div>
                    <div class="relative">
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Country <span class="text-red-500">*</span></label>
                        <input type="text" id="hotelCountry" name="country" required placeholder="Country (e.g. India)" autocomplete="off"
                            class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                        <div id="countrySuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden custom-scroll"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Category <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="category" required
                                class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                                @foreach($hotelCategories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Status <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="status" required
                                class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
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
                        <select name="package_id"
                            class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="">-- Select Package --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-50">
                    <a href="{{ route('agent.hotels') }}" class="px-6 py-3 rounded-2xl text-xs font-bold text-gray-500 hover:text-gray-800 transition-colors">Cancel</a>
                    <button type="submit" class="bg-primary text-white px-8 py-3.5 rounded-2xl text-xs font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-[1.02] active:scale-95 transition-all">
                        <i class="fas fa-save mr-2"></i> Save Hotel
                    </button>
                </div>
            </form>
        </div>

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
                                }

                                results = results.slice(0, 10);

                                if (results.length === 0) {
                                    suggestionsDiv.innerHTML = `<div class="px-4 py-3 text-xs text-gray-400 font-medium">No results found</div>`;
                                    return;
                                }

                                results.forEach(res => {
                                    const row = document.createElement('div');
                                    row.className = 'px-4 py-2.5 hover:bg-orange-50 cursor-pointer text-xs font-semibold text-gray-700 transition-colors flex flex-col justify-center border-b border-gray-50 last:border-0';
                                    
                                    let mainText = res.city;
                                    let subText = [res.state, res.country].filter(Boolean).join(', ');
                                    row.innerHTML = `<span>${mainText}</span><span class="text-[10px] text-gray-400 font-medium">${subText}</span>`;

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
    </script>
@endsection
