@props([
    'searchTerm' => '',
])

<style>
    .activity-checkbox-input:checked + .activity-checkbox-btn {
        background-color: rgba(232, 93, 38, 0.08) !important;
        color: #e85d26 !important;
        border-color: #e85d26 !important;
    }
</style>

<aside class="w-full bg-white rounded-lg overflow-hidden font-sans border-0 shadow-sm">
    <!-- Header -->
    <div class="bg-primary text-white py-4 px-5 flex items-center justify-between">
        <h2 class="font-bold uppercase tracking-wide" style="font-size: 26px;">Filters</h2>
        <button type="button" onclick="clearAllFilters()" class="text-[10px] font-bold bg-white/20 hover:bg-white text-white hover:text-primary px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider">
            Clear All
        </button>
    </div>

    <div class="p-5 space-y-6"> 

        <!-- Search (Hidden on Mobile, Visible on Desktop within Sidebar) -->
        <div class="relative group hidden lg:block mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="text-gray-400" size="16"></i>
            </div>
            <input 
                type="text" 
                name="search"
                placeholder="Search destination or package..." 
                value="{{ request('search') }}"
                class="w-full bg-gray-50 border border-gray-200 py-3 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-semibold text-gray-800 placeholder:text-gray-400"
                style="padding-left: 44px; border-radius: 6px;"
            >
        </div>

        <!-- 1. Tour Type -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Tour Type</h3>
            @php
                $allTourTypes = DB::table('transits')->where('status', 'Active')->pluck('name')->toArray();
                if (empty($allTourTypes)) {
                    $allTourTypes = ['Flight Package', 'Train Package', 'Bus Package', 'Bullet Ride', 'Cruise Package', 'Tracking Package', 'Helicopter Package', 'Other'];
                }
                $visibleTypes = array_slice($allTourTypes, 0, 5);
                $hiddenTypes = array_slice($allTourTypes, 5);
                $selectedTypes = (array) request('tour_type', []);
            @endphp
            <div class="space-y-2">
                @foreach($visibleTypes as $type)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $type }}</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenTypes as $type)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $type }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="button" @click="expanded = !expanded" class="text-primary text-[10px] font-bold mt-3 hover:opacity-80 uppercase tracking-wider flex items-center gap-1">
                <span x-text="expanded ? 'See Less' : 'See More'"></span>
                <i :data-lucide="expanded ? 'chevron-up' : 'chevron-down'" size="12"></i>
            </button>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- Holiday Types -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Holiday Types</h3>
            @php
                $selectedHolidayTypes = (array) request('holiday_type', []);
                $holidayTypesOptions = [
                    'Most Popular' => 'most popular',
                    'Honeymoon' => 'honeymoon',
                    'Budget' => 'budget',
                    'Multi City' => 'multi city',
                    'Short Tour' => 'short tour'
                ];
            @endphp
            <div class="space-y-2">
                @foreach($holidayTypesOptions as $label => $val)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="holiday_type[]" value="{{ $val }}" {{ in_array($val, $selectedHolidayTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>
        <div x-data="rangeSlider({{ request('min_nights', 2) }}, {{ request('max_nights', 11) }}, 1, 20)">
            <h3 class="font-bold text-gray-900 mb-5 uppercase tracking-wide" style="font-size: 20px;">Duration (Nights)</h3>
            
            <div class="px-2 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <!-- Track Highlight -->
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                
                <!-- Min Thumb -->
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1" @input="updateMin()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-auto" style="z-index: 3;">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + minPercent + '%'" style="z-index: 2;"></div>
                
                <!-- Max Thumb -->
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1" @input="updateMax()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-auto" style="z-index: 4;">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + maxPercent + '%'" style="z-index: 2;"></div>
            </div>
            
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-2 shrink-0">Night</span>
                    <input type="number" name="min_nights" x-model="min" @change="updateMin(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
                <span class="text-gray-400 text-[10px] font-bold uppercase shrink-0">To</span>
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-2 shrink-0">Night</span>
                    <input type="number" name="max_nights" x-model="max" @change="updateMax(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 3. Price -->
        <div x-data="rangeSlider({{ request('min_price', 1000) }}, {{ request('max_price', 100000) }}, 0, 150000)">
            <h3 class="font-bold text-gray-900 mb-5 uppercase tracking-wide" style="font-size: 20px;">Price</h3>
            
            <!-- Price Slider -->
            <div class="px-2 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1000" @input="updateMin()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-auto" style="z-index: 3;">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + minPercent + '%'" style="z-index: 2;"></div>
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1000" @input="updateMax()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-auto" style="z-index: 4;">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + maxPercent + '%'" style="z-index: 2;"></div>
            </div>
            
            <div class="flex items-center justify-between gap-3 mb-5">
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-xs font-bold text-gray-400 mr-1 shrink-0">₹</span>
                    <input type="number" name="min_price" x-model="min" @change="updateMin(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
                <span class="text-gray-400 text-[10px] font-bold uppercase shrink-0">To</span>
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-xs font-bold text-gray-400 mr-1 shrink-0">₹</span>
                    <input type="number" name="max_price" x-model="max" @change="updateMax(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
            </div>

            <!-- Price Radios -->
            <div class="space-y-2">
                @php $pr = request('price_radio', 'all'); @endphp
                @foreach([
                    'all' => 'All Price',
                    'under_20k' => 'Under ₹ 20,000',
                    '20k_40k' => '₹ 20,000 - ₹ 40,000',
                    '40k_60k' => '₹ 40,000 - ₹ 60,000',
                    'above_60k' => 'Above ₹ 60,000'
                ] as $val => $label)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="price_radio" value="{{ $val }}" {{ $pr === $val ? 'checked' : '' }} class="w-4 h-4 border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 4. City -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">City</h3>
            <div class="relative mb-3">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="text-gray-400" size="14"></i>
                </div>
                <input type="text" placeholder="Search cities..." class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 pl-8 pr-3 text-xs font-semibold text-gray-800 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all placeholder:text-gray-400">
            </div>
            
            @php
                $allCities = ['Manali', 'Goa', 'Shimla', 'Rishikesh', 'Kasol', 'Munnar', 'Darjeeling', 'Paris', 'Monaco', 'Hanoi', 'Dubai', 'Bali'];
                $visibleCities = array_slice($allCities, 0, 5);
                $hiddenCities = array_slice($allCities, 5);
                $selectedCities = (array) request('city', []);
            @endphp
            
            <div class="space-y-2">
                @foreach($visibleCities as $city)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="city[]" value="{{ $city }}" {{ in_array($city, $selectedCities) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $city }}</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenCities as $city)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="city[]" value="{{ $city }}" {{ in_array($city, $selectedCities) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $city }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="button" @click="expanded = !expanded" class="text-primary text-[10px] font-bold mt-3 hover:opacity-80 uppercase tracking-wider flex items-center gap-1">
                <span x-text="expanded ? 'See Less' : 'See More'"></span>
                <i :data-lucide="expanded ? 'chevron-up' : 'chevron-down'" size="12"></i>
            </button>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 5. Rating -->
        <div>
            <h3 class="font-bold text-gray-900 mb-4 uppercase tracking-wide" style="font-size: 20px;">Rating</h3>
            <div class="flex items-center justify-between mb-3">
                <div class="w-6 h-6 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center shrink-0">
                    <span class="text-[10px] font-bold text-gray-500">0</span>
                </div>
                <div class="flex-1 px-3 relative">
                    <input type="range" name="min_rating" min="0" max="5" step="0.5" value="{{ request('min_rating', 0) }}" oninput="document.getElementById('ratingLabel').innerText = this.value + ' Stars'" onchange="this.form.dispatchEvent(new Event('submit'))" class="w-full h-1.5 bg-gray-200 rounded-full appearance-none cursor-pointer accent-primary">
                </div>
                <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <span class="text-[10px] font-bold text-primary">5</span>
                </div>
            </div>
            <div class="flex items-center justify-center gap-1.5 bg-gray-50 py-1.5 px-3 rounded-lg border border-gray-100 w-max mx-auto">
                <i data-lucide="star" class="text-orange-400 fill-orange-400" size="14"></i>
                <span class="text-xs font-bold text-gray-800" id="ratingLabel">{{ request('min_rating', 0) }} Stars</span>
                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide ml-1">Or Above</span>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 6. Theme -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Theme</h3>
            @php
                $allThemes = DB::table('themes')->where('status', 'Active')->pluck('name')->toArray();
                if (empty($allThemes)) {
                    $allThemes = ['Honeymoon', 'Solo', 'Adventure', 'Family/Group', 'Religious', 'Romantic', 'Nature'];
                }
                $visibleThemes = array_slice($allThemes, 0, 5);
                $hiddenThemes = array_slice($allThemes, 5);
                $selectedThemes = (array) request('theme', []);
            @endphp
            <div class="space-y-2">
                @foreach($visibleThemes as $theme)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="theme[]" value="{{ $theme }}" {{ in_array($theme, $selectedThemes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $theme }}</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenThemes as $theme)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="theme[]" value="{{ $theme }}" {{ in_array($theme, $selectedThemes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $theme }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="button" @click="expanded = !expanded" class="text-primary text-[10px] font-bold mt-3 hover:opacity-80 uppercase tracking-wider flex items-center gap-1">
                <span x-text="expanded ? 'See Less' : 'See More'"></span>
                <i :data-lucide="expanded ? 'chevron-up' : 'chevron-down'" size="12"></i>
            </button>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 7. Activity Type (Tags) -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Activity Type</h3>
            @php
                $allActivities = ['Cable Car / Rope way', 'Adventure', 'Nature', 'Rides and Thrill', 'Water Activities', 'Jeep Safari', 'Hill Station', 'Religious'];
                $selectedActs = request('activities', []);
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach($allActivities as $act)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="activities[]" value="{{ $act }}" {{ in_array($act, $selectedActs) ? 'checked' : '' }} class="hidden activity-checkbox-input">
                        <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 activity-checkbox-btn hover:bg-gray-50 transition-colors">
                            {{ $act }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        <!-- 8. Travel Company -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Travel Company</h3>
            @php
                $agentsList = \DB::table('agents')->where('status', 'Active')->get();
            @endphp
            <div class="relative group mb-3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="building" class="text-gray-400" size="14"></i>
                </div>
                <select name="agent_id" onchange="this.form.dispatchEvent(new Event('submit'))" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 pr-10 text-xs font-semibold text-gray-800 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none cursor-pointer" style="padding-left: 2.5rem;">
                    <option value="" class="py-2 text-gray-800 font-semibold">All Companies</option>
                    @foreach($agentsList as $a)
                        <option value="{{ $a->id }}" {{ request('agent_id') == $a->id ? 'selected' : '' }} class="py-2 text-gray-800">{{ $a->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i data-lucide="chevron-down" class="text-gray-400" size="14"></i>
                </div>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

    </div>
</aside>

<!-- Alpine JS Dual Slider Logic & Lucide Init -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rangeSlider', (initialMin, initialMax, minLimit, maxLimit) => ({
            min: initialMin,
            max: initialMax,
            minLimit: minLimit,
            maxLimit: maxLimit,
            minPercent: 0,
            maxPercent: 100,

            init() {
                this.updateMin();
                this.updateMax();
            },
            updateMin() {
                this.min = Math.min(this.min, this.max - 1);
                this.min = Math.max(this.min, this.minLimit);
                this.minPercent = ((this.min - this.minLimit) / (this.maxLimit - this.minLimit)) * 100;
            },
            updateMax() {
                this.max = Math.max(this.max, this.min + 1);
                this.max = Math.min(this.max, this.maxLimit);
                this.maxPercent = ((this.max - this.minLimit) / (this.maxLimit - this.minLimit)) * 100;
            },
            triggerSubmit() {
                const form = document.getElementById('filter-form');
                if(form) form.dispatchEvent(new Event('submit'));
            }
        }));
    });
</script>
