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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Hotels</h3>
            <a href="javascript:void(0)" onclick="toggleHotelModal()"
                class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                + Add Hotel
            </a>
        </div>

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
                            oninput="suggestPlaces()"
                            class="w-full pl-12 pr-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                    </div>
                    <!-- Place Suggestion Dropdown -->
                    <div id="placeSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-lg z-[110] max-h-48 overflow-y-auto hidden">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">State</label>
                        <input type="text" id="hotelState" name="state" required placeholder="State (e.g. Goa)"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Country</label>
                        <input type="text" id="hotelCountry" name="country" required placeholder="Country (e.g. India)"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
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
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
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
        let debounceTimer;

        function suggestPlaces() {
            const input = document.getElementById('hotelCity');
            const suggestionsDiv = document.getElementById('placeSuggestions');
            const query = input.value.trim();

            clearTimeout(debounceTimer);
            if (!query || query.length < 3) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.add('hidden');
                return;
            }

            // Show loading indicator
            suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-primary"></i> Searching cities...</div>';
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
                            
                            // Determine city name: match city, town, village, suburb, municipality, county, state_district or fall back to main name
                            let city = address.city || address.town || address.village || address.suburb || address.municipality || address.county || address.state_district || '';
                            
                            if (!city && item.display_name) {
                                // Fallback to first part of display name
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
                                    document.getElementById('hotelState').value = state;
                                    document.getElementById('hotelCountry').value = country;
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
                    console.error('Error fetching global suggestions:', err);
                    suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-red-500 font-medium">Failed to load suggestions</div>';
                });
            }, 400);
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            const input = document.getElementById('hotelCity');
            const suggestionsDiv = document.getElementById('placeSuggestions');
            if (input && suggestionsDiv && !input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
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