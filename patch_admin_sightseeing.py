import re

with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

sightseeing_html = """
            {{-- Sightseeing Details List --}}
            @php
                $sightseeingList = is_string($pkg->sightseeing_list ?? '') ? json_decode($pkg->sightseeing_list, true) : ($pkg->sightseeing_list ?? []);
            @endphp
            @if(!empty($sightseeingList) && is_array($sightseeingList))
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mt-6">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($sightseeingList as $place)
                        @if(!empty($place['title']))
                        <span class="inline-flex items-center bg-orange-50 text-orange-700 border border-orange-200 px-4 py-2 rounded-xl text-sm font-bold shadow-sm">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1.5"></i> {{ $place['title'] }}
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
"""

if 'Sightseeing Details List' not in content:
    content = content.replace('{{-- Itinerary --}}', sightseeing_html + '\n            {{-- Itinerary --}}')
    with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
        print('Injected sightseeing list into admin/package-detail')
else:
    print('Already injected')
