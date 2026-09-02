@props([
    'searchTerm' => '',
    'filterCounts' => [],
])

<style>
    .range-slider-input {
        -webkit-appearance: none !important;
        appearance: none !important;
        background: transparent !important;
        outline: none !important;
        border: none !important;
        pointer-events: none !important;
        height: 16px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
    }
    .range-slider-input::-webkit-slider-runnable-track {
        background: transparent !important;
        border: none !important;
        height: 16px;
    }
    .range-slider-input::-moz-range-track {
        background: transparent !important;
        border: none !important;
        height: 16px;
    }
    .range-slider-input::-webkit-slider-thumb {
        pointer-events: auto !important;
        width: 16px;
        height: 16px;
        -webkit-appearance: none !important;
        appearance: none !important;
        background-color: white !important;
        border: 3px solid #e85d26 !important;
        border-radius: 9999px !important;
        cursor: pointer !important;
        position: relative;
        z-index: 50;
    }
    .range-slider-input::-moz-range-thumb {
        pointer-events: auto !important;
        width: 16px;
        height: 16px;
        appearance: none !important;
        background-color: white !important;
        border: 3px solid #e85d26 !important;
        border-radius: 9999px !important;
        cursor: pointer !important;
        position: relative;
        z-index: 50;
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
            <div class="relative mb-4">
                <label class="block text-base font-bold text-gray-900 mb-1.5">Travel Destination</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" autocomplete="off" placeholder="Search travel destination..." class="w-full bg-white border border-gray-300 rounded-lg py-2.5 px-3.5 pr-10 text-sm font-medium text-gray-800 focus:outline-none focus:border-primary transition-all placeholder:text-gray-400 text-ellipsis overflow-hidden">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="16"></i>
                </div>
            </div>
            
            <!-- Travel Agent Location -->
            <div class="relative mb-4">
                <label class="block text-base font-bold text-gray-900 mb-1.5">Travel Agent Location</label>
                <div class="relative">
                    <input type="text" name="city" value="{{ is_array(request('city')) ? implode(', ', request('city')) : request('city') }}" autocomplete="off" placeholder="Search agent location..." class="w-full bg-white border border-gray-300 rounded-lg py-2.5 px-3.5 pr-10 text-sm font-medium text-gray-800 focus:outline-none focus:border-primary transition-all placeholder:text-gray-400 text-ellipsis overflow-hidden">
                    <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="16"></i>
                </div>
            </div>

            <style>
                .no-cal-icon::-webkit-calendar-picker-indicator {
                    display: none;
                    -webkit-appearance: none;
                }
            </style>
            <div>
                <label class="block text-base font-bold text-gray-900 mb-1.5">Date of Travel</label>
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
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer group bg-rose-50/70 hover:bg-rose-100 px-3 py-2 rounded-lg border border-rose-100 transition-colors overflow-hidden">
                    <input type="checkbox" name="private_chef" value="1" {{ request('private_chef') == 1 ? 'checked' : '' }} onchange="this.form.dispatchEvent(new Event('submit'))" class="w-4 h-4 shrink-0 rounded border-rose-300 text-rose-500 focus:ring-rose-500/50 cursor-pointer">
                    <span class="text-rose-700 text-[13px] md:text-sm font-bold group-hover:text-rose-800 transition-colors flex items-center gap-1.5 whitespace-nowrap overflow-hidden text-ellipsis">
                        <i data-lucide="utensils" class="w-4 h-4 shrink-0 text-rose-500 group-hover:text-rose-600 transition-colors"></i>
                        <span>Private Chef Included (<span data-filter-count="services.private_chef">{{ $filterCounts['services']['private_chef'] ?? 0 }}</span>)</span>
                    </span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group bg-blue-50/70 hover:bg-blue-100 px-3 py-2 rounded-lg border border-blue-100 transition-colors overflow-hidden">
                    <input type="checkbox" name="tour_manager" value="1" {{ request('tour_manager') == 1 ? 'checked' : '' }} onchange="this.form.dispatchEvent(new Event('submit'))" class="w-4 h-4 shrink-0 rounded border-blue-300 text-blue-500 focus:ring-blue-500/50 cursor-pointer">
                    <span class="text-blue-700 text-[13px] md:text-sm font-bold group-hover:text-blue-800 transition-colors flex items-center gap-1.5 whitespace-nowrap overflow-hidden text-ellipsis">
                        <i data-lucide="user" class="w-4 h-4 shrink-0 text-blue-500 group-hover:text-blue-600 transition-colors"></i>
                        <span>Tour Manager Included (<span data-filter-count="services.tour_manager">{{ $filterCounts['services']['tour_manager'] ?? 0 }}</span>)</span>
                    </span>
                </label>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 1. Tour Type -->
        <div x-data="{ expanded: false }">
            <h3 class="font-bold text-gray-900 mb-3 tracking-wide" style="font-size: 20px;">Tour Type</h3>
            @php
                $allTourTypes = DB::table('transits')->where('status', 'Active')->orderBy('sr_no', 'asc')->pluck('name')->toArray();
                if (empty($allTourTypes)) {
                    $allTourTypes = ['Land/Customised Packages', 'Bullet Packages', 'Flight Packages', 'Train Packages', 'Bus Packages', 'Cruise Packages', 'Tracking Packages', 'Helicopter Packages'];
                }
                
                $visibleTypes = array_slice($allTourTypes, 0, 5);
                $hiddenTypes = array_slice($allTourTypes, 5);
                $selectedTypes = (array) request('tour_type', []);
            @endphp
            <div class="space-y-2">
                @foreach($visibleTypes as $type)
                    @php
                        $nt = strtolower(trim($type));
                        if (str_contains($nt, 'land') || str_contains($nt, 'custom')) $nt = 'land';
                        elseif (str_contains($nt, 'bullet') || str_contains($nt, 'bike')) $nt = 'bullet';
                        elseif (str_contains($nt, 'flight') || str_contains($nt, 'air')) $nt = 'flight';
                        elseif (str_contains($nt, 'train') || str_contains($nt, 'rail')) $nt = 'train';
                        elseif (str_contains($nt, 'bus') || str_contains($nt, 'coach')) $nt = 'bus';
                        elseif (str_contains($nt, 'cruise') || str_contains($nt, 'ship') || str_contains($nt, 'boat')) $nt = 'cruise';
                        elseif (str_contains($nt, 'track') || str_contains($nt, 'hike') || str_contains($nt, 'trek')) $nt = 'tracking';
                        elseif (str_contains($nt, 'helicopter') || str_contains($nt, 'sky')) $nt = 'helicopter';
                        else $nt = rtrim($nt, 's');
                    @endphp
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $type }} (<span data-filter-count="tour_type.{{ $nt }}">{{ $filterCounts['tour_type'][$nt] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenTypes as $type)
                        @php
                            $nt = strtolower(trim($type));
                            if (str_contains($nt, 'land') || str_contains($nt, 'custom')) $nt = 'land';
                            elseif (str_contains($nt, 'bullet') || str_contains($nt, 'bike')) $nt = 'bullet';
                            elseif (str_contains($nt, 'flight') || str_contains($nt, 'air')) $nt = 'flight';
                            elseif (str_contains($nt, 'train') || str_contains($nt, 'rail')) $nt = 'train';
                            elseif (str_contains($nt, 'bus') || str_contains($nt, 'coach')) $nt = 'bus';
                            elseif (str_contains($nt, 'cruise') || str_contains($nt, 'ship') || str_contains($nt, 'boat')) $nt = 'cruise';
                            elseif (str_contains($nt, 'track') || str_contains($nt, 'hike') || str_contains($nt, 'trek')) $nt = 'tracking';
                            elseif (str_contains($nt, 'helicopter') || str_contains($nt, 'sky')) $nt = 'helicopter';
                            else $nt = rtrim($nt, 's');
                        @endphp
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="tour_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $type }} (<span data-filter-count="tour_type.{{ $nt }}">{{ $filterCounts['tour_type'][$nt] ?? 0 }}</span>)</span>
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
                    <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">Domestic (<span data-filter-count="destination_type.domestic">{{ $filterCounts['destination_type']['domestic'] ?? 0 }}</span>)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="category[]" value="international" {{ in_array('international', $selectedDestTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">International (<span data-filter-count="destination_type.international">{{ $filterCounts['destination_type']['international'] ?? 0 }}</span>)</span>
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
                        <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $category }} (<span data-filter-count="category.{{ strtolower($category) }}">{{ $filterCounts['category'][strtolower($category)] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                @if(!empty($hiddenCategories))
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenCategories as $category)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="categories[]" value="{{ $category }}" {{ in_array($category, $selectedCategories) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $category }} (<span data-filter-count="category.{{ strtolower($category) }}">{{ $filterCounts['category'][strtolower($category)] ?? 0 }}</span>)</span>
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
                $holidayTypesOptions = DB::table('holiday_types')->where('status', 'Active')->pluck('name')->toArray();
            @endphp
            <div class="space-y-2">
                @foreach($holidayTypesOptions as $val)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="holiday_type[]" value="{{ $val }}" {{ in_array($val, $selectedHolidayTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $val }} (<span data-filter-count="holiday_type.{{ strtolower($val) }}">{{ $filterCounts['holiday_type'][strtolower($val)] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <div x-data="rangeSlider({{ request('min_nights', 0) }}, {{ request('max_nights', 365) }}, 0, 365)" class="pt-6">
            <h3 class="font-bold text-gray-900 mb-5  tracking-wide" style="font-size: 20px;">Duration (Nights)</h3>
            
            <div class="mt-4 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <!-- Track Highlight -->
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                
                <!-- Min Thumb -->
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1" @input="updateMin()" @change="triggerSubmit()" class="absolute left-0 w-full cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 5 : 3)">
                
                <!-- Max Thumb -->
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1" @input="updateMax()" @change="triggerSubmit()" class="absolute left-0 w-full cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 3 : 4)">
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

            <!-- Duration Radios removed -->
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 3. Price -->
        <div x-data="rangeSlider({{ request('min_price', 1000) }}, {{ request('max_price', 500000) }}, 0, 500000)" class="pt-6">
            <h3 class="font-bold text-gray-900 mb-5 tracking-wide" style="font-size: 20px;">Price</h3>
            
            <!-- Price Slider -->
            <div class="mt-4 mb-6 relative h-1.5 bg-gray-200 rounded-full">
                <div class="absolute h-full bg-primary rounded-full" :style="'left: ' + minPercent + '%; right: ' + (100 - maxPercent) + '%'"></div>
                <input type="range" x-model="min" :min="minLimit" :max="maxLimit" step="1000" @input="updateMin()" @change="triggerSubmit()" class="absolute left-0 w-full cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 5 : 3)">
                <input type="range" x-model="max" :min="minLimit" :max="maxLimit" step="1000" @input="updateMax()" @change="triggerSubmit()" class="absolute left-0 w-full cursor-pointer pointer-events-none range-slider-input" :style="'z-index: ' + (minPercent >= 95 ? 3 : 4)">
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
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 4. City Removed -->

        <!-- 5. Rating Removed/Commented Out -->
        <!--
        <div class="pt-6">
            <h3 class="font-bold text-gray-900 mb-4 tracking-wide" style="font-size: 20px;">Rating</h3>
            @php
                $selectedRatings = (array) request('ratings', []);
            @endphp
            <div class="space-y-2.5">
                @foreach(['5' => '5 Stars', '4' => '4 Stars', '3' => '3 Stars', '2' => '2 Stars', '1' => '1 Star', '0' => 'No rating'] as $val => $label)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="ratings[]" value="{{ $val }}" {{ in_array($val, $selectedRatings) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                        <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors flex items-center gap-1">
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
        -->

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
                        <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $theme }} (<span data-filter-count="theme.{{ strtolower($theme) }}">{{ $filterCounts['theme'][strtolower($theme)] ?? 0 }}</span>)</span>
                    </label>
                @endforeach
                
                <div x-show="expanded" x-transition.opacity class="space-y-2 pt-2">
                    @foreach($hiddenThemes as $theme)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="theme[]" value="{{ $theme }}" {{ in_array($theme, $selectedThemes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                            <span class="text-gray-600 text-base font-semibold group-hover:text-primary transition-colors">{{ $theme }} (<span data-filter-count="theme.{{ strtolower($theme) }}">{{ $filterCounts['theme'][strtolower($theme)] ?? 0 }}</span>)</span>
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
