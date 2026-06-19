import re

with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Remove sightseeing_list injection completely
content = re.sub(r'\s*\{\{-- Sightseeing Details List --\}\}.*?@endif\s*', '\n', content, flags=re.DOTALL)

# 2. Rename Editorial Details to Itinerary
content = content.replace('<h3 class="text-lg font-black text-foreground mt-6">Editorial Details</h3>', '<h3 class="text-lg font-black text-foreground mt-6">Itinerary (Day-by-Day Plan)</h3>')

# 3. Rename Itinerary (stepper) to Sightseeing Details
content = content.replace('<h3 class="text-lg font-black text-foreground mb-6">Itinerary (Day-by-Day Plan)</h3>', '<h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>')

# 4. Replace the vertical stepper with the horizontal list of pills
repeater_html = """
            {{-- Sightseeing Details --}}
            @if(count($itinerary) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($itinerary as $idx => $day)
                        <span class="inline-flex flex-col bg-orange-50 text-orange-700 border border-orange-200 px-4 py-2 rounded-xl shadow-sm">
                            <div class="flex items-center text-sm font-bold">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-1.5"></i> {{ $day['title'] }}
                            </div>
                            @if(!empty($day['desc']))
                            <span class="text-xs text-orange-600 font-medium mt-1 ml-5">{{ $day['desc'] }}</span>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
"""

content = re.sub(r'\s*\{\{-- Itinerary --\}\}.*?</div>\s*</div>\s*@endif\s*', '\n' + repeater_html + '\n', content, flags=re.DOTALL)

with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Fixed admin/package-detail.blade.php')
