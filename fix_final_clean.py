import re

def fix_show():
    with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Ensure the Itinerary section is labeled "Itinerary (Day-by-Day Plan)" and shows editorial_itinerary
    # Let's locate the editorial_itinerary block
    # It is:
    # @if(!empty($package['editorial_itinerary']))
    # <h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Itinerary (Day-by-Day Plan)</h3>
    # <p class="standard-body-text detail-overview-text whitespace-pre-wrap">{{ $package['editorial_itinerary'] }}</p>
    # @endif
    
    # 2. Re-write the bottom block from {{-- Itinerary --}} / {{-- Sightseeing Details --}} completely.
    # We want to have only ONE "Sightseeing Details" section which displays the structured itinerary JSON list horizontally.
    # And we want NO duplicate vertical timeline.
    
    start_idx = text.find('{{-- Itinerary --}}')
    if start_idx == -1:
        start_idx = text.find('{{-- Sightseeing Details --}}')
        
    end_idx = text.find('{{-- FAQ Section --}}')
    if end_idx == -1:
        end_idx = text.find('{{-- FAQs --}}')
        
    if start_idx == -1 or end_idx == -1:
        print("Could not find start or end index in show.blade.php")
        return

    new_section = """{{-- Sightseeing Details --}}
                @if(!empty($package['itinerary']) && count($package['itinerary']) > 0)
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($package['itinerary'] as $day)
                            <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#F6F8FA] border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e85d26]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $day['title'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                """
    text = text[:start_idx] + new_section + text[end_idx:]

    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed show.blade.php")

def fix_admin():
    with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Ensure the Itinerary section is labeled "Itinerary (Day-by-Day Plan)" and shows editorial_itinerary
    # 2. Re-write the bottom block from {{-- Itinerary --}} to {{-- Right: Pricing + Agent --}}
    start_idx = text.find('{{-- Itinerary --}}')
    if start_idx == -1:
        start_idx = text.find('{{-- Sightseeing Details --}}')
        
    end_idx = text.find('{{-- Right: Pricing + Agent --}}')
    if end_idx == -1:
        end_idx = text.find('</div>\n\n        {{-- Right: Pricing + Agent --}}')

    if start_idx == -1 or end_idx == -1:
        print("Could not find start or end index in admin/package-detail.blade.php")
        return

    new_section = """{{-- Sightseeing Details --}}
            @if(count($itinerary) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($itinerary as $day)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm font-semibold rounded-xl shadow-sm">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> {{ $day['title'] }}
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
    print("Fixed admin/package-detail.blade.php")

fix_show()
fix_admin()
