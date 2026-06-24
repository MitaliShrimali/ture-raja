import re

# 1. Update hotels.blade.php
with open('resources/views/agent/pages/hotels.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

# Replace hardcoded array and update property accesses
hotels_loop_regex = r'<\?php\s*\$hotels = \[.*?\];\s*foreach \(\$hotels as \$h\): \?>.*?<\?php endforeach; \?>'

new_hotels_loop = r'''@foreach($hotels as $index => $h)
    <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="hotel-row-{{ $h->id }}">
        <td class="py-4 pl-4 text-xs font-bold text-gray-800">{{ 101 + $index }}</td>
        <td class="py-4">
            <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=50&auto=format&fit=crop"
                    class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100 shadow-sm">
                <div>
                    <p class="text-[10px] font-bold text-gray-800 hotel-name">{{ $h->name }}</p>
                    <p class="text-[8px] text-gray-400 font-medium hotel-loc">{{ $h->location }}</p>
                </div>
            </div>
        </td>
        <td class="py-4">
            <span class="px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter hotel-status {{ $h->status == 'Online' || $h->status == 'Published' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                {{ $h->status }}
            </span>
        </td>
        <td class="py-4 text-[10px] font-bold text-gray-800 hotel-address">{{ $h->location }}</td>
        <td class="py-4 text-[10px] font-bold text-gray-800 hotel-cat">{{ $h->category }}</td>
        <td class="py-4 text-[10px] font-bold text-gray-800 hotel-state">N/A</td>
        <td class="py-4 text-[10px] font-bold text-gray-800 hotel-country">N/A</td>
        <td class="py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
                <button onclick='editHotel(@json(["id" => $h->id, "name" => $h->name, "loc" => $h->location, "cat" => $h->category, "status" => $h->status]))'
                    class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                <button onclick="deleteHotel({{ $h->id }})"
                    class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
            </div>
        </td>
    </tr>
@endforeach'''

blade = re.sub(hotels_loop_regex, new_hotels_loop, blade, flags=re.DOTALL)
with open('resources/views/agent/pages/hotels.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)

# 2. Update package-detail.blade.php
with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    package_blade = f.read()

# I will inject the Hotels and Transfers sections just after the Itinerary block, or before Policies
# Let's look for "<!-- Policies & Terms -->" or "{{-- Itinerary Timeline --}}" ending.
# I will insert it before "<!-- Policies & Terms -->"
injection_point = r'<!-- Policies & Terms -->'

hotels_transfers_html = r'''<!-- Hotels & Transfers Sections -->
            <div class="space-y-8 mb-12">
                @if(!empty($hotels))
                <div>
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                        <i class="fas fa-hotel text-primary mr-2"></i> Hotels Included
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($hotels as $hotel)
                        <div class="bg-white p-4 rounded-2xl border border-border-color shadow-sm flex items-center space-x-4">
                            @if(isset($hotel['image']))
                            <img src="{{ $hotel['image'] }}" class="w-16 h-16 rounded-xl object-cover">
                            @else
                            <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fas fa-bed text-xl"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-sm text-foreground">{{ $hotel['name'] ?? 'Hotel Name' }}</h4>
                                <p class="text-xs text-muted-text mt-1">{{ isset($hotel['room']) ? $hotel['room'] : (isset($hotel['category']) ? $hotel['category'] : 'Standard Room') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($transfers))
                <div>
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                        <i class="fas fa-car text-primary mr-2"></i> Transfers
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($transfers as $transfer)
                        <div class="bg-white p-4 rounded-2xl border border-border-color shadow-sm flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-primary">
                                <i class="fas {{ isset($transfer['type']) && strtolower($transfer['type']) == 'flight' ? 'fa-plane' : (isset($transfer['type']) && strtolower($transfer['type']) == 'train' ? 'fa-train' : 'fa-car') }} text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-foreground">{{ $transfer['name'] ?? $transfer['title'] ?? 'Transfer' }}</h4>
                                <p class="text-xs text-muted-text mt-1">{{ $transfer['desc'] ?? $transfer['description'] ?? 'Transport included' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Policies & Terms -->'''

package_blade = re.sub(injection_point, hotels_transfers_html, package_blade)
with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(package_blade)

print("Patch applied for hotels blade and package-detail blade.")
