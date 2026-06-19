import re

with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

sightseeing_html = """
                @php
                    $sightseeingList = is_string($package['sightseeing_list'] ?? '') ? json_decode($package['sightseeing_list'], true) : ($package['sightseeing_list'] ?? []);
                @endphp
                @if(!empty($sightseeingList) && is_array($sightseeingList))
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($sightseeingList as $place)
                            @if(!empty($place['title']))
                            <span class="inline-flex items-center bg-orange-50 text-orange-700 border border-orange-200 px-4 py-2 rounded-xl text-sm font-bold shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $place['title'] }}
                            </span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
"""

if 'sightseeingList' not in content:
    content = content.replace('{{-- Itinerary --}}', sightseeing_html + '\n                {{-- Itinerary --}}')
    
    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
        print('Injected sightseeing list')
else:
    print('Already injected')
