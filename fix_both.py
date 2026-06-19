import sys

def fix_show():
    with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Change editorial_itinerary heading
    text = text.replace('Itinerary (Day-by-Day Plan)</h3>\n<p class="standard-body-text detail-overview-text whitespace-pre-wrap">{{ $package[\'editorial_itinerary\'] }}', 'Editorial Details</h3>\n<p class="standard-body-text detail-overview-text whitespace-pre-wrap">{{ $package[\'editorial_itinerary\'] }}')

    start_idx = text.find('{{-- Sightseeing Details --}}')
    if start_idx == -1:
        print("Could not find {{-- Sightseeing Details --}} in show.blade.php")
        return
        
    end_idx = text.find('</div>\n@endif\n', start_idx)
    if end_idx == -1:
        print("Could not find end of Sightseeing Details in show.blade.php")
        return
    end_idx += len('</div>\n@endif\n')

    new_section = '''{{-- Sightseeing Details --}}
@php
    $hasSightseeing = !empty($package['sightseeing']);
    $sightseeingItems = $hasSightseeing ? array_filter(array_map('trim', explode(',', $package['sightseeing']))) : [];
@endphp
@if($hasSightseeing && count($sightseeingItems) > 0)
<div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
    <div class="flex flex-wrap gap-2">
        @foreach($sightseeingItems as $place)
            <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F6F8FA] border border-gray-200 text-gray-700 text-sm font-semibold rounded-full shadow-sm hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e85d26]" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                {{ $place }}
            </span>
        @endforeach
    </div>
</div>
@endif

{{-- Itinerary --}}
@if(!empty($package['itinerary']) && count($package['itinerary']) > 0)
<div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-gray-900 mb-8 section-heading">Itinerary (Day-by-Day Plan)</h2>
    <div class="relative pl-2">
        @foreach($package['itinerary'] as $idx => $day)
        <div class="relative flex gap-6 pb-8 last:pb-2">
            {{-- Timeline Line --}}
            @if(!$loop->last)
                <div class="absolute left-[11px] top-6 bottom-0" style="border-left: 2px dashed #e85d26 !important;"></div>
            @endif
            
            {{-- Timeline Circle --}}
            <div class="relative z-10 shrink-0">
                @if($loop->first || $loop->last)
                    {{-- Solid Circle --}}
                    <div class="w-6 h-6 rounded-full shadow-sm flex items-center justify-center text-[10px] font-bold text-white" style="background-color: #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.2);">{{ $idx + 1 }}</div>
                @else
                    {{-- Hollow Circle --}}
                    <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-[10px] font-bold" style="border: 4px solid #e85d26 !important; color: #e85d26; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.12);">{{ $idx + 1 }}</div>
                @endif
            </div>
            
            {{-- Content --}}
            <div class="-mt-1 flex-1">
                <h4 class="font-black text-gray-800" style="font-family: 'Outfit', sans-serif; font-size: 16px;">Day {{ $idx + 1 }}: {{ $day['title'] }}</h4>
                @if(!empty($day['desc']))
                    <p class="standard-body-text mt-2 max-w-3xl">{{ $day['desc'] }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
'''
    text = text[:start_idx] + new_section + text[end_idx:]

    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed show.blade.php")


def fix_admin():
    with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Change editorial_itinerary heading
    text = text.replace('Itinerary (Day-by-Day Plan)</h3>\n<div class="prose prose-sm max-w-none text-gray-600">\n{!! nl2br(e($pkg->editorial_itinerary ?? \'\')) !!}', 'Editorial Details</h3>\n<div class="prose prose-sm max-w-none text-gray-600">\n{!! nl2br(e($pkg->editorial_itinerary ?? \'\')) !!}')

    start_idx = text.find('{{-- Sightseeing Details --}}')
    if start_idx == -1:
        print("Could not find {{-- Sightseeing Details --}} in admin/package-detail.blade.php")
        return
        
    end_idx = text.find('</div>\n            @endif\n', start_idx)
    if end_idx == -1:
        print("Could not find end of Sightseeing Details in admin/package-detail.blade.php")
        return
    end_idx += len('</div>\n            @endif\n')

    new_section = '''{{-- Sightseeing Details --}}
            @php
                $hasSightseeing = !empty($pkg->sightseeing);
                $sightseeingItems = $hasSightseeing ? array_filter(array_map('trim', explode(',', $pkg->sightseeing))) : [];
            @endphp
            @if($hasSightseeing && count($sightseeingItems) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($sightseeingItems as $place)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm font-semibold rounded-full shadow-sm hover:bg-orange-100 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> {{ $place }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Itinerary --}}
            @if(count($itinerary) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft">
                <h3 class="text-lg font-black text-foreground mb-6">Itinerary (Day-by-Day Plan)</h3>
                <div class="relative pl-2">
                    @foreach($itinerary as $idx => $day)
                        <div class="relative flex gap-6 pb-8 last:pb-2">
                            {{-- Timeline Line --}}
                            @if(!$loop->last)
                                <div class="absolute left-[11px] top-6 bottom-0" style="border-left: 2px dashed #e85d26 !important;"></div>
                            @endif
                            
                            {{-- Timeline Circle --}}
                            <div class="relative z-10 shrink-0">
                                @if($loop->first || $loop->last)
                                    <div class="w-6 h-6 rounded-full shadow-sm flex items-center justify-center text-[10px] font-bold text-white" style="background-color: #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.2);">{{ $idx + 1 }}</div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-[10px] font-bold" style="border: 4px solid #e85d26 !important; color: #e85d26; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.12);">{{ $idx + 1 }}</div>
                                @endif
                            </div>
                            
                            {{-- Content --}}
                            <div class="-mt-1 flex-1">
                                <h4 class="font-bold text-gray-800 text-base">Day {{ $idx + 1 }}: {{ $day['title'] }}</h4>
                                @if(!empty($day['desc']))
                                    <p class="text-sm text-gray-600 mt-2">{{ $day['desc'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
'''
    text = text[:start_idx] + new_section + text[end_idx:]

    with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed admin/package-detail.blade.php")

fix_show()
fix_admin()
