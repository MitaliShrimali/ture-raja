import re

with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

# 1. Remove Hotels and Transfers from sidebar stats
remove_hotels = r'@if\(\!empty\(\$hotels\)\)\s*<div class="flex items-center justify-between pb-4 border-b border-gray-50">\s*<span class="text-muted-text">Hotels</span>\s*<span class="font-bold text-foreground">\{\{ count\(\$hotels\) \}\} Listed</span>\s*</div>\s*@endif'
blade = re.sub(remove_hotels, '', blade)

remove_transfers = r'@if\(\!empty\(\$transfers\)\)\s*<div class="flex items-center justify-between pb-4 border-b border-gray-50">\s*<span class="text-muted-text">Transfers</span>\s*<span class="font-bold text-foreground">\{\{ count\(\$transfers\) \}\} Types</span>\s*</div>\s*@endif'
blade = re.sub(remove_transfers, '', blade)

# 2. Add detailed section before {{-- Terms & Conditions --}}
injection_html = r'''
            {{-- Hotels & Transfers Detailed Sections --}}
            @if(!empty($hotels))
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                    <i class="fas fa-hotel text-primary mr-2" style="color: #e85d26;"></i> Hotels Included
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($hotels as $hotel)
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-bed text-xl"></i>
                        </div>
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
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 border border-gray-200 shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-4 flex items-center">
                    <i class="fas fa-car text-primary mr-2" style="color: #e85d26;"></i> Transfers Included
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($transfers as $transfer)
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: rgba(232, 93, 38, 0.1); color: #e85d26;">
                            <i class="fas {{ isset($transfer['type']) && strtolower($transfer['type']) == 'flight' ? 'fa-plane' : (isset($transfer['type']) && strtolower($transfer['type']) == 'train' ? 'fa-train' : 'fa-car') }} text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-foreground">{{ $transfer['name'] ?? (isset($transfer['title']) ? $transfer['title'] : 'Transfer') }}</h4>
                            <p class="text-xs text-muted-text mt-1">{{ $transfer['desc'] ?? (isset($transfer['description']) ? $transfer['description'] : 'Transport included') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Terms & Conditions --}}'''

blade = blade.replace('{{-- Terms & Conditions --}}', injection_html)

with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print("Updated package-detail.blade.php")
