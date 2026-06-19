import re

def revert_show():
    with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Restore the "Itinerary (Day-by-Day Plan)" heading for editorial_itinerary
    text = text.replace(
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Overview</h3>',
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Itinerary (Day-by-Day Plan)</h3>'
    )

    # 2. Find and replace everything from {{-- Sightseeing Details --}} to the end of Itinerary section
    start_idx = text.find('{{-- Sightseeing Details --}}')
    if start_idx == -1:
        print("Could not find {{-- Sightseeing Details --}} in show.blade.php")
        return
        
    end_idx = text.find('{{-- FAQs --}}', start_idx)
    if end_idx == -1:
        # Fallback if FAQs not found
        end_idx = text.find('</div>', start_idx) # just locate next block or close of container
        
    # We want to replace everything between {{-- Sightseeing Details --}} and {{-- FAQs --}}
    # with just the horizontal Sightseeing Details section.
    new_section = """{{-- Sightseeing Details --}}
@if(!empty($package['itinerary']) && count($package['itinerary']) > 0)
<div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
    <div class="flex flex-wrap gap-3">
        @foreach($package['itinerary'] as $idx => $day)
            <span class="inline-flex flex-col bg-orange-50 text-orange-700 border border-orange-200 px-4 py-2.5 rounded-xl shadow-sm">
                <div class="flex items-center text-sm font-bold">
                    <svg class="w-4 h-4 mr-1.5 text-[#e85d26]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
    text = text[:start_idx] + new_section + text[end_idx:]

    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Reverted show.blade.php to show Sightseeing Details horizontally and Itinerary as the text block.")

def revert_admin():
    with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Restore the "Itinerary (Day-by-Day Plan)" heading for editorial_itinerary
    text = text.replace(
        '<h3 class="text-lg font-black text-foreground mb-4">Overview</h3>',
        '<h3 class="text-lg font-black text-foreground mb-4">Itinerary (Day-by-Day Plan)</h3>'
    )

    # 2. Find and replace everything from {{-- Sightseeing Details --}} to the end of Itinerary section
    start_idx = text.find('{{-- Sightseeing Details --}}')
    if start_idx == -1:
        print("Could not find {{-- Sightseeing Details --}} in admin/package-detail.blade.php")
        return
        
    # We want to replace everything from {{-- Sightseeing Details --}} up to where the column ends (i.e. before the right sidebar begins)
    # The right sidebar begins with: {{-- Right: Pricing + Agent --}} or similar
    end_idx = text.find('{{-- Right: Pricing + Agent --}}', start_idx)
    if end_idx == -1:
        end_idx = text.find('</div>\n\n        {{-- Right: Pricing + Agent --}}', start_idx)

    new_section = """{{-- Sightseeing Details --}}
            @if(count($itinerary) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
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

        </div>

        """
    text = text[:start_idx] + new_section + text[end_idx:]

    with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Reverted admin/package-detail.blade.php to show Sightseeing Details horizontally and Itinerary as the text block.")

revert_show()
revert_admin()
