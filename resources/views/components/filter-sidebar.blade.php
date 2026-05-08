@props([
    'searchTerm' => '',
    'selectedCategories' => [],
    'priceRange' => 5000,
    'selectedDurations' => []
])

<aside class="space-y-8 sticky top-28 h-fit">
    <!-- Search Input -->
    <div class="relative group">
        <input 
            type="text" 
            name="search"
            placeholder="Search destination or package..." 
            value="{{ request('search') }}"
            class="w-full bg-white border border-border-soft rounded-2xl py-4 pl-12 pr-4 shadow-soft focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all font-bold text-foreground placeholder:text-muted-text/40"
        >
        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="20"></i>
    </div>

    <!-- Categories Filter -->
    <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <h3 class="text-xl font-black border-b border-border-soft pb-4 tracking-tight">Categories</h3>
        <div class="space-y-4">
            @foreach(["Tropical", "Mountains", "City", "Adventure", "International", "Domestic"] as $cat)
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input 
                            type="checkbox" 
                            name="categories[]"
                            value="{{ $cat }}"
                            {{ in_array($cat, request('categories', [])) ? 'checked' : '' }}
                            class="peer appearance-none w-6 h-6 border-2 border-gray-100 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer" 
                        >
                        <div class="absolute opacity-0 peer-checked:opacity-100 text-white transition-opacity pointer-events-none">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-muted-text font-bold group-hover:text-primary transition-colors text-sm">{{ $cat }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Price Range Filter -->
    <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <div class="flex items-center justify-between border-b border-border-soft pb-4">
            <h3 class="text-xl font-black tracking-tight">Price Range</h3>
            <span class="text-primary font-black text-sm">₹100 - ₹<span id="priceRangeValue">{{ request('max_price', 5000) }}</span></span>
        </div>
        <div class="px-2">
            <input 
                type="range" 
                name="max_price"
                min="100" 
                max="5000" 
                step="100"
                value="{{ request('max_price', 5000) }}"
                oninput="document.getElementById('priceRangeValue').innerText = this.value"
                class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-primary" 
            >
        </div>
        <div class="flex justify-between text-[10px] font-black text-muted-text uppercase tracking-widest">
            <span>Min: ₹100</span>
            <span>Max: ₹5000</span>
        </div>
    </div>

    <!-- Duration Filter -->
    <div class="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <h3 class="text-xl font-black border-b border-border-soft pb-4 tracking-tight">Duration</h3>
        <div class="space-y-4">
            @foreach(["1-3 Days", "4-7 Days", "8-14 Days", "15+ Days"] as $dur)
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input 
                            type="checkbox" 
                            name="durations[]"
                            value="{{ $dur }}"
                            {{ in_array($dur, request('durations', [])) ? 'checked' : '' }}
                            class="peer appearance-none w-6 h-6 border-2 border-gray-100 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer" 
                        >
                        <div class="absolute opacity-0 peer-checked:opacity-100 text-white transition-opacity pointer-events-none">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-muted-text font-bold group-hover:text-primary transition-colors text-sm">{{ $dur }}</span>
                </label>
            @endforeach
        </div>
    </div>
</aside>
