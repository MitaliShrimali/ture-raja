with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Change editorial_itinerary heading
text = text.replace('Itinerary (Day-by-Day Plan)</h3>\n<div class="prose prose-sm max-w-none text-gray-600">\n{!! nl2br(e($package[\'editorial_itinerary\'])) !!}', 'Editorial Details</h3>\n<div class="prose prose-sm max-w-none text-gray-600">\n{!! nl2br(e($package[\'editorial_itinerary\'])) !!}')

# 2. Change Sightseeing Details to use sightseeing column
old_sightseeing = '''@if(isset($package['itinerary']) && count($package['itinerary']) > 0)
<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Sightseeing Details
        </h3>
    </div>
    <div class="p-6">
        <div class="flex flex-wrap gap-2">
            @foreach($package['itinerary'] as $idx => $day)
                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F6F8FA] border border-gray-200 text-gray-700 text-sm font-semibold rounded-full shadow-sm hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e85d26]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    {{ $day['title'] }}
                </span>
            @endforeach
        </div>
    </div>
</section>
@endif'''

new_sightseeing = '''@php
    $hasSightseeing = !empty($package['sightseeing']);
    $sightseeingItems = $hasSightseeing ? array_filter(array_map('trim', explode(',', $package['sightseeing']))) : [];
@endphp
@if($hasSightseeing && count($sightseeingItems) > 0)
<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Sightseeing Details
        </h3>
    </div>
    <div class="p-6">
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
</section>
@endif

@if(isset($package['itinerary']) && count($package['itinerary']) > 0)
<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Itinerary (Day-by-Day Plan)
        </h3>
    </div>
    <div class="p-6">
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
</section>
@endif'''

text = text.replace(old_sightseeing, new_sightseeing)

with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(text)

print('Fixed admin/package-detail.blade.php cleanly')
