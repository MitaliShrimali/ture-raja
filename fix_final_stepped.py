import re

def patch_blade_views():
    # Helper PHP parsing function text to inject at the top of the Blade files or define inline
    # We can define the PHP helper block directly inside the Blade file before rendering
    php_helper = """@php
    if (!function_exists('parseTextItinerary')) {
        function parseTextItinerary($text) {
            if (empty($text)) return [];
            $text = str_replace("\\r", "", $text);
            $parts = preg_split('/•\\s*/u', $text);
            $days = [];
            foreach ($parts as $part) {
                $part = trim($part);
                if (empty($part)) continue;
                $lines = explode("\\n", $part);
                $titleLine = trim($lines[0]);
                $descLines = array_slice($lines, 1);
                $desc = implode("\\n", $descLines);
                $desc = trim($desc);
                
                if (preg_match('/Day\\s+\\d+/i', $titleLine) || preg_match('/Day\\s+/i', $titleLine)) {
                    $days[] = [
                        'title' => $titleLine,
                        'desc' => $desc
                    ];
                } else {
                    if (count($days) > 0) {
                        $days[count($days) - 1]['desc'] .= "\\n\\n• " . $part;
                    }
                }
            }
            
            foreach ($days as &$day) {
                $desc = e($day['desc']);
                $desc = preg_replace('/\\*\\*(.*?)\\*\\*/', '<strong>$1</strong>', $desc);
                $desc = preg_replace('/_(.*?)_/', '<em>$1</em>', $desc);
                $desc = preg_replace('/\\[(.*?)\\]\\((.*?)\\)/', '<a href="$2" target="_blank" class="text-orange-600 hover:underline font-bold">$1</a>', $desc);
                $desc = nl2br($desc);
                $day['desc_html'] = $desc;
            }
            return $days;
        }
    }
    
    $parsedItinerary = [];
    $rawItineraryText = "";
    if (isset($package) && !empty($package['editorial_itinerary'])) {
        $rawItineraryText = $package['editorial_itinerary'];
    } elseif (isset($pkg) && !empty($pkg->editorial_itinerary)) {
        $rawItineraryText = $pkg->editorial_itinerary;
    }
    $parsedItinerary = parseTextItinerary($rawItineraryText);
@endphp"""

    # Fix customer show.blade.php
    with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Find the start of the details section (we will replace everything from Tour Overview & Editorial onwards)
    overview_start = text.find('{{-- Tour Overview & Editorial --}}')
    if overview_start == -1:
        overview_start = text.find('<h2>Tour Overview</h2>')
        
    faq_start = text.find('{{-- FAQ Section --}}')
    if faq_start == -1:
        faq_start = text.find('{{-- FAQs --}}')

    if overview_start != -1 and faq_start != -1:
        new_overview_section = """{{-- Tour Overview & Editorial --}}
                @php
                    $overviewText = isset($package['overview']) ? $package['overview'] : '';
                @endphp
                @if(!empty($overviewText))
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 mb-8">
                    <h2 class="font-black text-gray-900 mb-4 section-heading">Tour Overview</h2>
                    <p class="standard-body-text detail-overview-text">{{ $overviewText }}</p>
                </div>
                @endif

                """ + php_helper + """
                
                {{-- Itinerary Timeline --}}
                @if(count($parsedItinerary) > 0)
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-8 section-heading text-xl">Itinerary</h2>
                    <div class="relative pl-2">
                        @foreach($parsedItinerary as $idx => $day)
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
                                <h4 class="font-black text-gray-800" style="font-family: 'Outfit', sans-serif; font-size: 16px;">{{ $day['title'] }}</h4>
                                @if(!empty($day['desc']))
                                    <p class="standard-body-text mt-2 max-w-3xl">{!! $day['desc_html'] !!}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                """
        text = text[:overview_start] + new_overview_section + text[faq_start:]

    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed customer view timeline parser")

    # Fix admin package-detail.blade.php
    with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Find the start of the details section
    # Let's locate the overview/itinerary blocks
    # We will look for Package Details card (around line 100) or similar
    # Actually, we can replace the blocks from where `editorial_itinerary` was checked (around line 105)
    # up to the right sidebar.
    
    # Let's search for `@if(!empty($pkg->editorial_itinerary))`
    start_tag = text.find('@if(!empty($pkg->editorial_itinerary))')
    if start_tag == -1:
        start_tag = text.find('{{-- Sightseeing Details --}}')
        
    end_tag = text.find('{{-- Right: Pricing + Agent --}}')
    if end_tag == -1:
        end_tag = text.find('</div>\n\n        {{-- Right: Pricing + Agent --}}')

    if start_tag != -1 and end_tag != -1:
        new_admin_section = php_helper + """
            
            {{-- Itinerary Timeline --}}
            @if(count($parsedItinerary) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Itinerary</h3>
                <div class="relative pl-2">
                    @foreach($parsedItinerary as $idx => $day)
                        <div class="relative flex gap-6 pb-8 last:pb-2">
                            @if(!$loop->last)
                                <div class="absolute left-[11px] top-6 bottom-0" style="border-left: 2px dashed #e85d26 !important;"></div>
                            @endif
                            <div class="relative z-10 shrink-0">
                                @if($loop->first || $loop->last)
                                    <div class="w-6 h-6 rounded-full shadow-sm flex items-center justify-center text-[10px] font-bold text-white" style="background-color: #e85d26 !important; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.2);">{{ $idx + 1 }}</div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-[10px] font-bold" style="border: 4px solid #e85d26 !important; color: #e85d26; box-shadow: 0 0 0 4px rgba(232, 93, 38, 0.12);">{{ $idx + 1 }}</div>
                                @endif
                            </div>
                            <div class="-mt-1 flex-1">
                                <h4 class="font-bold text-gray-800 text-base">{{ $day['title'] }}</h4>
                                @if(!empty($day['desc']))
                                    <p class="text-sm text-gray-600 mt-2">{!! $day['desc_html'] !!}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        """
        text = text[:start_tag] + new_admin_section + text[end_tag:]

    with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed admin view timeline parser")


def patch_form_labels():
    forms = [
        'resources/views/agent/pages/create-package.blade.php',
        'resources/views/agent/pages/edit-package.blade.php',
        'resources/views/admin/packages-create.blade.php',
        'resources/views/admin/packages-edit.blade.php'
    ]
    for form in forms:
        with open(form, 'r', encoding='utf-8') as f:
            content = f.read()

        # Rename back to Itinerary (Day-by-Day Plan)
        content = content.replace(
            '<h4 class="text-sm font-bold text-gray-800">Tour Overview</h4>',
            '<h4 class="text-sm font-bold text-gray-800">Itinerary (Day-by-Day Plan)</h4>'
        )
        content = content.replace(
            '<h4 class="text-sm font-bold text-gray-800">Editorial Details (Overview)</h4>',
            '<h4 class="text-sm font-bold text-gray-800">Itinerary (Day-by-Day Plan)</h4>'
        )

        with open(form, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Patched card label to 'Itinerary (Day-by-Day Plan)' in {form}")

patch_blade_views()
patch_form_labels()
