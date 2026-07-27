@props([
    'searchTerm' => '',
    'filterCounts' => [],
])

<style>
    .range-slider-input::-webkit-slider-thumb {
        pointer-events: auto !important;
        width: 16px;
        height: 16px;
        -webkit-appearance: none;
        appearance: none;
    }
    .range-slider-input::-moz-range-thumb {
        pointer-events: auto !important;
        width: 16px;
        height: 16px;
        appearance: none;
    }
    .activity-checkbox-input:checked + .activity-checkbox-btn {
        background-color: rgba(232, 93, 38, 0.08) !important;
        color: #e85d26 !important;
        border-color: #e85d26 !important;
    }
    .custom-sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-sidebar-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
</style>

<aside class="w-full bg-white rounded-lg font-sans border-0 shadow-sm flex flex-col h-full">
    <!-- Header -->
    <div class="bg-primary text-white py-4 px-5 flex items-center justify-between rounded-t-lg shrink-0">
        <h2 class="font-bold uppercase tracking-wide" style="font-size: 26px;">Filters</h2>
        <button type="button" onclick="clearAllFilters()" class="text-[10px] font-bold bg-white/20 hover:bg-white text-white hover:text-primary px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider">
            Clear All
        </button>
    </div>

    <div class="p-5 space-y-6 flex-1 overflow-y-auto custom-sidebar-scroll"> 

        <!-- Search Fields in Sidebar -->
        <div class="space-y-4 mb-6">
            <!-- Travel Destination -->
            <div x-data="{
                open: false,
                query: '{{ request('search') }}',
                results: [],
                loading: false,
                init() {
                    this.$watch('query', value => {
                        this.loading = true;
                        fetch(`/api/search-suggestions?q=${encodeURIComponent(value)}&type=destination`)
                            .then(res => res.json())
                            .then(data => {
                                this.results = data;
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    });
                    
                    // Initial load if open is true
                    this.$watch('open', value => {
                        if (value && this.results.length === 0) {
                            this.loading = true;
                            fetch(`/api/search-suggestions?q=${encodeURIComponent(this.query)}&type=destination`)
                                .then(res => res.json())
                                .then(data => {
                                    this.results = data;
                                    this.loading = false;
                                })
                                .catch(() => { this.loading = false; });
                        }
                    });
                },
                selectOption(text) {
                    this.query = text;
                    this.open = false;
                }
            }" class="relative" @click.away="open = false">
                <label class="block text-sm font-bold text-gray-900 mb-1.5">Travel Destination</label>
                <div class="relative">
                    <input type="text" name="search" x-model.debounce.300ms="query" @focus="open = true" autocomplete="new-password" placeholder="Search travel destination..." class="w-full bg-white border border-gray-300 rounded-lg py-2.5 px-3.5 pr-10 text-sm font-medium text-gray-800 focus:outline-none focus:border-primary transition-all placeholder:text-gray-400 text-ellipsis overflow-hidden">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="16"></i>
                </div>
                
                <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg" x-cloak>
                    <div class="text-sm text-gray-700 py-1 max-h-48 overflow-y-auto custom-sidebar-scroll">
                        <template x-if="loading">
                            <div class="text-gray-600 px-3 py-2">Loading...</div>
                        </template>
                        <template x-if="!loading">
                            <div>
                                <template x-for="item in results" :key="item.text">
                                    <div @click.prevent.stop="selectOption(item.text)" class="cursor-pointer hover:bg-[#e85d26] hover:text-white px-3 py-2 transition-colors" x-text="item.text"></div>
                                </template>
                                <template x-if="results.length === 0">
                                    <div class="text-gray-500 px-3 py-2">No results found for '<span x-text="query"></span>'</div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Travel Agent Location -->
            <div x-data="{
                open: false,
                query: '{{ is_array(request('city')) ? implode(', ', request('city')) : request('city') }}',
                results: [],
                loading: false,
                init() {
                    this.$watch('query', value => {
                        this.loading = true;
                        fetch(`/api/search-suggestions?q=${encodeURIComponent(value)}&type=agent_location`)
                            .then(res => res.json())
                            .then(data => {
                                this.results = data;
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    });
                    
                    // Initial load if open is true
                    this.$watch('open', value => {
                        if (value && this.results.length === 0) {
                            this.loading = true;
                            fetch(`/api/search-suggestions?q=${encodeURIComponent(this.query)}&type=agent_location`)
                                .then(res => res.json())
                                .then(data => {
                                    this.results = data;
                                    this.loading = false;
                                })
                                .catch(() => { this.loading = false; });
                        }
                    });
                },
                selectOption(text) {
                    this.query = text;
                    this.open = false;
                }
            }" class="relative" @click.away="open = false">
                <label class="block text-sm font-bold text-gray-900 mb-1.5">Travel Agent Location</label>
                <div class="relative">
                    <input type="text" name="city" x-model.debounce.300ms="query" @focus="open = true" autocomplete="new-password" placeholder="Search agent location..." class="w-full bg-white border border-gray-300 rounded-lg py-2.5 px-3.5 pr-10 text-sm font-medium text-gray-800 focus:outline-none focus:border-primary transition-all placeholder:text-gray-400 text-ellipsis overflow-hidden">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="16"></i>
                </div>
                
                <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg" x-cloak>
                    <div class="text-sm text-gray-700 py-1 max-h-48 overflow-y-auto custom-sidebar-scroll">
                        <template x-if="loading">
                            <div class="text-gray-600 px-3 py-2">Loading...</div>
                        </template>
                        <template x-if="!loading">
                            <div>
                                <template x-for="item in results" :key="item.text">
                                    <div @click.prevent.stop="selectOption(item.text)" class="cursor-pointer hover:bg-[#e85d26] hover:text-white px-3 py-2 transition-colors" x-text="item.text"></div>
                                </template>
                                <template x-if="results.length === 0">
                                    <div class="text-gray-500 px-3 py-2">No results found for '<span x-text="query"></span>'</div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <style>
                .no-cal-icon::-webkit-calendar-picker-indicator {
                    display: none;
                    -webkit-appearance: none;
                }
            </style>
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-1.5">Date of Travel</label>
                <div class="relative">
                    <input type="text" onfocus="(this.type='date')" onblur="(this.value == '' ? this.type='text' : this.type='date')" name="check_in" value="{{ request('check_in') }}" class="no-cal-icon w-full bg-white border border-gray-300 rounded-lg py-2.5 px-3.5 pr-10 text-sm font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all placeholder:text-gray-400" placeholder="YYYY-MM-DD">
                    <i data-lucide="calendar" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="16"></i>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#e85d26] hover:bg-[#d04c1a] text-white font-bold py-2.5 rounded-lg text-sm transition-colors shadow-sm mt-2">
                Search
            </button>
        </div>
        <hr class="border-gray-100 mb-6">

        <!-- Services (Private Chef / Tour Manager) -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Services</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="private_chef" value="1" {{ request('private_chef') == 1 ? 'checked' : '' }} onchange="this.form.dispatchEvent(new Event('submit'))" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors flex items-center gap-1">
                        <i data-lucide="utensils" class="w-3.5 h-3.5 text-gray-500 group-hover:text-primary transition-colors"></i> Private Chef Included (<span data-filter-count="services.private_chef">{{ $filterCounts['services']['private_chef'] ?? 0 }}</span>)
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="tour_manager" value="1" {{ request('tour_manager') == 1 ? 'checked' : '' }} onchange="this.form.dispatchEvent(new Event('submit'))" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors flex items-center gap-1">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-gray-500 group-hover:text-primary transition-colors"></i> Tour Manager Included (<span data-filter-count="services.tour_manager">{{ $filterCounts['services']['tour_manager'] ?? 0 }}</span>)
                    </span>
                </label>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 1. Tour Type -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Tour Type</h3>
            @php
                $allTourTypes = DB::table('transits')->where('status', 'Active')->pluck('name')->toArray();
                if (empty($allTourTypes)) {
                    $allTourTypes = ['Land/Customised Packages', 'Bullet Packages', 'Flight Packages', 'Train Packages', 'Bus Packages', 'Cruise Packages', 'Tracking Packages', 'Helicopter Packages'];
                }
                usort($allTourTypes, function($a, $b) {
                    $nameA = strtolower(trim($a));
                    $nameB = strtolower(trim($b));
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
                    
                    $normA = $nameA;
                    if (str_contains($nameA, 'land') || str_contains($nameA, 'custom')) $normA = 'land';
                    elseif (str_contains($nameA, 'bullet') || str_contains($nameA, 'bike')) $normA = 'bullet';
                    elseif (str_contains($nameA, 'flight') || str_contains($nameA, 'air')) $normA = 'flight';
                    elseif (str_contains($nameA, 'train') || str_contains($nameA, 'rail')) $normA = 'train';
                    elseif (str_contains($nameA, 'bus') || str_contains($nameA, 'coach')) $normA = 'bus';
                    elseif (str_contains($nameA, 'cruise') || str_contains($nameA, 'ship') || str_contains($nameA, 'boat')) $normA = 'cruise';
                    elseif (str_contains($nameA, 'track') || str_contains($nameA, 'hike') || str_contains($nameA, 'trek')) $normA = 'tracking';
                    elseif (str_contains($nameA, 'helicopter') || str_contains($nameA, 'sky')) $normA = 'helicopter';

                    $normB = $nameB;
                    if (str_contains($nameB, 'land') || str_contains($nameB, 'custom')) $normB = 'land';
                    elseif (str_contains($nameB, 'bullet') || str_contains($nameB, 'bike')) $normB = 'bullet';
                    elseif (str_contains($nameB, 'flight') || str_contains($nameB, 'air')) $normB = 'flight';
                    elseif (str_contains($nameB, 'train') || str_contains($nameB, 'rail')) $normB = 'train';
                    elseif (str_contains($nameB, 'bus') || str_contains($nameB, 'coach')) $normB = 'bus';
                    elseif (str_contains($nameB, 'cruise') || str_contains($nameB, 'ship') || str_contains($nameB, 'boat')) $normB = 'cruise';
                    elseif (str_contains($nameB, 'track') || str_contains($nameB, 'hike') || str_contains($nameB, 'trek')) $normB = 'tracking';
                    elseif (str_contains($nameB, 'helicopter') || str_contains($nameB, 'sky')) $normB = 'helicopter';

                    $orderValA = $orderMap[$normA] ?? 999;
                    $orderValB = $orderMap[$normB] ?? 999;
                    return $orderValA <=> $orderValB;
                });
                $visibleTypes = array_slice($allTourTypes, 0, 5);
                $hiddenTypes = array_slice($allTourTypes, 5);
                $selectedTypes = (array) request('tour_type', []);
            @endphp
            <div class="space-y-2">
                @foreach($visibleTypes as $type)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $type }} (<span data-filter-count="tour_type.{{ rtrim(strtolower($type), 's') }}">{{ $filterCounts['tour_type'][rtrim(strtolower($type), 's')] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenTypes as $type)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $type }} (<span data-filter-count="tour_type.{{ rtrim(strtolower($type), 's') }}">{{ $filterCounts['tour_type'][rtrim(strtolower($type), 's')] ?? 0 }}</span>)</span>
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

                <!-- 1.4. Destination Type -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Destination Type</h3>
            @php
                $selectedDestTypes = (array) request('category', []);
            @endphp
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="category[]" value="domestic" {{ in_array('domestic', $selectedDestTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">Domestic (<span data-filter-count="destination_type.domestic">{{ $filterCounts['destination_type']['domestic'] ?? 0 }}</span>)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="category[]" value="international" {{ in_array('international', $selectedDestTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">International (<span data-filter-count="destination_type.international">{{ $filterCounts['destination_type']['international'] ?? 0 }}</span>)</span>
                </label>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 1.5. Categories -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Category</h3>
            @php
                $rawCategories = DB::table('packages')->whereNotNull('categories_list')->where('categories_list', '!=', '')->pluck('categories_list')->toArray();
                $parsedCategories = [];
                foreach ($rawCategories as $cat) {
                    if (str_starts_with(trim($cat), '[') || str_starts_with(trim($cat), '{')) {
                        $decoded = json_decode($cat, true);
                        if (is_array($decoded)) {
                            foreach ($decoded as $c) {
                                if (is_string($c)) $parsedCategories[] = trim($c);
                            }
                        }
                    } elseif (is_string($cat)) {
                        $parsedCategories[] = trim($cat);
                    }
                }
                $parsedCategories = array_unique(array_filter($parsedCategories));
                $dbCategories = array_map('ucwords', array_map('strtolower', $parsedCategories));
                
                $hardcodedCategories = ['Mountain', 'Safari', 'Desert', 'Flower', 'Beach', 'Temples', 'Yacht'];
                
                $allCategories = array_unique(array_merge($hardcodedCategories, $dbCategories));
                
                $visibleCategories = array_slice($allCategories, 0, 7);
                $hiddenCategories = array_slice($allCategories, 7);
                $selectedCategories = (array) request('categories', []);
            @endphp
            <div class="space-y-2">
                @foreach($visibleCategories as $category)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="categories[]" value="{{ $category }}" {{ in_array($category, $selectedCategories) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $category }} (<span data-filter-count="category.{{ strtolower($category) }}">{{ $filterCounts['category'][strtolower($category)] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                @if(!empty($hiddenCategories))
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenCategories as $category)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="categories[]" value="{{ $category }}" {{ in_array($category, $selectedCategories) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $category }} (<span data-filter-count="category.{{ strtolower($category) }}">{{ $filterCounts['category'][strtolower($category)] ?? 0 }}</span>)</span>
                        </label>
                    @endforeach
                </div>
                @endif
            </div>
            @if(!empty($hiddenCategories))
            <button type="button" @click="expanded = !expanded" class="text-primary text-[10px] font-bold mt-3 hover:opacity-80 uppercase tracking-wider flex items-center gap-1">
                <span x-text="expanded ? 'See Less' : 'See More'"></span>
                <i :data-lucide="expanded ? 'chevron-up' : 'chevron-down'" size="12"></i>
            </button>
            @endif
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- Holiday Types -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Holiday Types</h3>
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
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $label }} (<span data-filter-count="holiday_type.{{ $val }}">{{ $filterCounts['holiday_type'][$val] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <div x-data="rangeSlider({{ request('min_nights', 0) }}, {{ request('max_nights', 365) }}, 0, 365)" class="pt-6">
            <h3 class="font-bold text-gray-900 mb-5  tracking-wide" style="font-size: 20px;">Duration (Nights)</h3>
            
            <div class="px-2 mt-4 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <!-- Track Highlight -->
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                
                <!-- Min Thumb -->
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1" @input="updateMin()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 5 : 3)">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + minPercent + '%'" style="z-index: 2;"></div>
                
                <!-- Max Thumb -->
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1" @input="updateMax()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 3 : 4)">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + maxPercent + '%'" style="z-index: 2;"></div>
            </div>
            
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-2 shrink-0">Night</span>
                    <input type="number" name="min_nights" x-model="min" @change="document.getElementById('duration_custom').checked = true; updateMin(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
                <span class="text-gray-400 text-[10px] font-bold uppercase shrink-0">To</span>
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-2 shrink-0">Night</span>
                    <input type="number" name="max_nights" x-model="max" @change="document.getElementById('duration_custom').checked = true; updateMax(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
            </div>

            <!-- Duration Radios removed -->
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 3. Price -->
        <div x-data="rangeSlider({{ request('min_price', 1000) }}, {{ request('max_price', 500000) }}, 0, 500000)" class="pt-6">
            <h3 class="font-bold text-gray-900 mb-5 tracking-wide" style="font-size: 20px;">Price</h3>
            
            <!-- Price Slider -->
            <div class="px-2 mt-4 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1000" @input="updateMin()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 5 : 3)">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + minPercent + '%'" style="z-index: 2;"></div>
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1000" @input="updateMax()" @change="triggerSubmit()" class="absolute w-full h-1.5 opacity-0 cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 3 : 4)">
                <div class="absolute w-4 h-4 bg-white border-[3px] border-primary rounded-full top-1/2 -translate-y-1/2 -ml-2 pointer-events-none" :style="'left: ' + maxPercent + '%'" style="z-index: 2;"></div>
            </div>
            
            <div class="flex items-center justify-between gap-3 mb-5">
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-xs font-bold text-gray-400 mr-1 shrink-0">₹</span>
                    <input type="number" name="min_price" x-model="min" @change="document.getElementById('price_custom').checked = true; updateMin(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
                <span class="text-gray-400 text-[10px] font-bold uppercase shrink-0">To</span>
                <div class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition-all overflow-hidden">
                    <span class="text-xs font-bold text-gray-400 mr-1 shrink-0">₹</span>
                    <input type="number" name="max_price" x-model="max" @change="document.getElementById('price_custom').checked = true; updateMax(); triggerSubmit()" class="w-full bg-transparent border-none p-0 text-sm font-bold text-gray-800 focus:ring-0 min-w-0 text-center">
                </div>
            </div>

            <!-- Price Radios -->
            <div class="space-y-2.5 mt-6">
                @php $pr = request('price_radio', 'all'); @endphp
                @foreach([
                    'all' => 'All Price',
                    'custom' => 'Custom Range',
                    'under_20k' => 'Under ₹ 20,000',
                    '20k_40k' => '₹ 20,000 - ₹ 40,000',
                    '40k_60k' => '₹ 40,000 - ₹ 60,000',
                    'above_60k' => 'Above ₹ 60,000'
                ] as $val => $label)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="price_radio" id="{{ $val === 'custom' ? 'price_custom' : '' }}" value="{{ $val }}" {{ $pr === $val ? 'checked' : '' }} onchange="this.form.dispatchEvent(new Event('submit'))" class="w-4 h-4 border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 4. City Removed -->

        <!-- 5. Rating -->
        <div class="pt-6">
            <h3 class="font-bold text-gray-900 mb-4 tracking-wide" style="font-size: 20px;">Rating</h3>
            @php
                $selectedRatings = (array) request('ratings', []);
            @endphp
            <div class="space-y-2.5">
                @foreach(['5' => '5 Stars', '4' => '4 Stars', '3' => '3 Stars', '2' => '2 Stars', '1' => '1 Star', '0' => 'No rating'] as $val => $label)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="ratings[]" value="{{ $val }}" {{ in_array($val, $selectedRatings) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors flex items-center gap-1">
                            @if($val > 0)
                                @for($i = 0; $i < $val; $i++)
                                    <i data-lucide="star" class="text-orange-400 fill-orange-400" size="12"></i>
                                @endfor
                                <span class="ml-1">{{ $label }}</span>
                            @else
                                {{ $label }}
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 6. Theme -->
        <div x-data="{ expanded: false }" class="pt-6">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Theme</h3>
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
                        <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $theme }} (<span data-filter-count="theme.{{ strtolower($theme) }}">{{ $filterCounts['theme'][strtolower($theme)] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenThemes as $theme)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="theme[]" value="{{ $theme }}" {{ in_array($theme, $selectedThemes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">{{ $theme }} (<span data-filter-count="theme.{{ strtolower($theme) }}">{{ $filterCounts['theme'][strtolower($theme)] ?? 0 }}</span>)</span>
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
        <div class="pt-6">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Activity Type</h3>
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
        <div class="pt-6">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Travel Company/Agent</h3>
            <div class="relative group mb-3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="building" class="text-gray-400" size="14"></i>
                </div>
                <input type="text" name="company" placeholder="Search Travel Company..." value="{{ request('company') }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 pr-4 text-xs font-semibold text-gray-800 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all cursor-text" style="padding-left: 2.5rem;" autocomplete="off">
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
