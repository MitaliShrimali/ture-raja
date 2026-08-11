@extends('agent.layouts.app')

@section('title', 'Add Branch - Tour Raja Agent')

@section('content')


        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Form -->
            <div class="lg:col-span-8 bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $branch ? 'Edit Branch' : 'Add Branch' }}</h3>
                <p class="text-[11px] text-gray-400 font-medium mb-10">Register a new physical office location to your agency network. Ensure all asterisk (*) fields are filled.</p>

                <form action="{{ $branch ? route('agent.branch.update', $branch->id) : route('agent.branch.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">TRAVEL COMPANY / AGENCY NAME *</label>
                            <div class="relative">
                                <i class="far fa-building absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                @php
                                    $agentName = session('agent_name');
                                    if(session('agent_id')) {
                                        $dbAgent = \DB::table('agents')->where('id', session('agent_id'))->first();
                                        if($dbAgent) $agentName = $dbAgent->name;
                                    }
                                @endphp
                                <input type="text" name="agency_name" value="{{ old('agency_name', $branch ? $branch->agency_name : $agentName) }}" readonly required placeholder="e.g. Horizon Ascent Bali" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-100 text-gray-500 cursor-not-allowed border-none text-xs font-bold">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">PHONE *</label>
                            <div class="flex gap-2 items-center">
                                <div class="relative w-28 shrink-0">
                                    <select class="phone-country-code w-full px-3 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
                                        <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                        <option value="+1" data-len="10">🇺🇸 +1</option>
                                        <option value="+44" data-len="10">🇬🇧 +44</option>
                                        <option value="+62" data-len="11">🇮🇩 +62</option>
                                        <option value="+65" data-len="8">🇸🇬 +65</option>
                                        <option value="+971" data-len="9">🇦🇪 +971</option>
                                        <option value="+61" data-len="9">🇦🇺 +61</option>
                                        <option value="+66" data-len="9">🇹🇭 +66</option>
                                        <option value="+60" data-len="10">🇲🇾 +60</option>
                                    </select>
                                </div>
                                <div class="relative flex-grow">
                                    <input type="tel" required placeholder="Phone Number *"
                                        class="phone-number-val w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                    <i class="fas fa-phone-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                </div>
                                <input type="hidden" class="phone-full-val" name="phone" value="{{ old('phone', $branch ? $branch->phone : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="relative">
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">CITY (SEARCH) *</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input type="text" id="branchCity" name="location" value="{{ old('location', $branch ? $branch->location : '') }}" required placeholder="Search City (e.g. Ahmedabad)" autocomplete="off" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div id="citySuggestions" class="absolute left-0 right-0 z-50 mt-1 max-h-60 overflow-y-auto bg-white rounded-2xl border border-gray-100 shadow-xl hidden custom-scroll"></div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">STATE *</label>
                            <div class="relative">
                                <i class="fas fa-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input type="text" id="branchState" name="state" value="{{ old('state', $branch ? $branch->state : '') }}" required placeholder="State" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">COUNTRY *</label>
                            <div class="relative">
                                <i class="fas fa-globe absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input type="text" id="branchCountry" name="country" value="{{ old('country', $branch ? $branch->country : '') }}" required placeholder="Country" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">STATUS *</label>
                            <div class="relative">
                                <i class="fas fa-toggle-on absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <select name="status" required class="w-full pl-12 pr-10 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
                                    <option value="Online" {{ old('status', $branch ? $branch->status : '') == 'Online' ? 'selected' : '' }}>Active</option>
                                    <option value="Offline" {{ old('status', $branch ? $branch->status : '') == 'Offline' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">ADDRESS *</label>
                        <div class="relative">
                            <i class="far fa-map absolute left-5 top-6 text-gray-300"></i>
                            <textarea name="address" rows="5" required placeholder="Full street address, building name, and floor number" class="w-full pl-12 pr-6 py-5 rounded-[32px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">{{ old('address', $branch ? $branch->address : '') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-8 pt-6">
                        <a href="{{ route('agent.branch') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">Cancel</a>
                        <button type="submit" class="bg-orange-800 text-white px-10 py-4 rounded-[24px] text-sm font-bold flex items-center shadow-xl shadow-orange-100 hover:scale-[1.02] transition-all">
                            {{ $branch ? 'Update Branch' : 'Save Branch' }} <i class="far fa-check-circle ml-3"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Side: Guidelines -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Branch Standards -->
                <div class="bg-white p-8 rounded-[48px] shadow-sm border border-gray-100 relative overflow-hidden group">
                    <!-- Decorative background pattern (simulated) -->
                    <div class="absolute inset-0 opacity-5 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    
                    <h4 class="text-[10px] font-bold text-orange-800 uppercase tracking-[3px] mb-8">Branch Standards</h4>
                    <ul class="space-y-6 relative z-10">
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Each branch must have a dedicated local phone number.</p>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Verification of address will be required within 48 hours.</p>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Branches are visible to customers immediately after saving.</p>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('branchCity');
        const suggestionsDiv = document.getElementById('citySuggestions');
        let debounceTimer;

        if (input && suggestionsDiv) {
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
                                        document.getElementById('branchState').value = state;
                                        document.getElementById('branchCountry').value = country;
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
@endpush
@endsection
