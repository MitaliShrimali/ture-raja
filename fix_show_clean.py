import re

with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Rename Editorial Details to Itinerary (Day-by-Day Plan)
content = content.replace('<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Editorial Details</h3>', '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Itinerary (Day-by-Day Plan)</h3>')

# 2. Rename the sidebar link if any
content = content.replace('<h4 class="font-bold text-gray-800 text-sm sm:text-base leading-snug">Editorial Details</h4>', '<h4 class="font-bold text-gray-800 text-sm sm:text-base leading-snug">Itinerary (Day-by-Day Plan)</h4>')

# 3. Replace the entire Itinerary block with the Sightseeing Details pills layout
repeater_html = """{{-- Sightseeing Details --}}
@if(!empty($package['itinerary']) && count($package['itinerary']) > 0)
<div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
    <div class="flex flex-wrap gap-3">
        @foreach($package['itinerary'] as $idx => $day)
            <span class="inline-flex flex-col bg-orange-50 text-orange-700 border border-orange-200 px-4 py-2 rounded-xl shadow-sm">
                <div class="flex items-center text-sm font-bold">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $day['title'] }}
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

# Use regex to replace the old block from `{{-- Itinerary --}}` up to just before `{{-- FAQ Section --}}`
content = re.sub(r'\{\{-- Itinerary --\}\}.*?(?=\{\{-- FAQ Section --\}\})', repeater_html, content, flags=re.DOTALL)

with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Fixed show.blade.php correctly.')
