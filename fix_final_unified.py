import re
import codecs

def patch_show():
    with codecs.open('resources/views/packages/show.blade.php', 'r', 'utf-8') as f:
        text = f.read()

    # 1. Parse function and logic injection at the top of the file
    parser_code = """@php
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
    if (!empty($package['editorial_itinerary'])) {
        $parsedItinerary = parseTextItinerary($package['editorial_itinerary']);
    }
    
    // Combine both sources of sightseeing details
    $sightseeingPills = [];
    if (!empty($package['sightseeing'])) {
        $sightseeingPills = array_filter(array_map('trim', explode(',', $package['sightseeing'])));
    }
    if (!empty($package['itinerary']) && is_array($package['itinerary'])) {
        foreach ($package['itinerary'] as $day) {
            if (!empty($day['title'])) {
                $sightseeingPills[] = $day['title'];
            }
        }
    }
    $sightseeingPills = array_unique($sightseeingPills);
@endphp
"""
    # Inject parser_code at the very beginning
    text = parser_code + text

    # 2. Remove the old editorial_itinerary block under Tour Overview
    editorial_pattern = r'@if\(!empty\(\$package\[\'editorial_itinerary\'\]\)\).*?@endif'
    text = re.sub(editorial_pattern, '', text, flags=re.DOTALL)

    # 3. Locate the original Sightseeing Details and Itinerary sections at the bottom, and replace them
    start_tag = text.find('{{-- Sightseeing Details --}}')
    if start_tag == -1:
        start_tag = text.find('{{-- Itinerary --}}')
        
    end_tag = text.find('{{-- FAQ Section --}}')
    if end_tag == -1:
        end_tag = text.find('{{-- FAQs --}}')

    if start_tag != -1 and end_tag != -1:
        new_sections = """{{-- Itinerary Timeline --}}
                @if(count($parsedItinerary) > 0)
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-8 section-heading text-xl">Itinerary</h2>
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

                {{-- Sightseeing Details --}}
                @if(count($sightseeingPills) > 0)
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
                    <h2 class="font-black text-gray-900 mb-6 section-heading text-xl">Sightseeing Details</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sightseeingPills as $place)
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

                """
        text = text[:start_tag] + new_sections + text[end_tag:]

    with codecs.open('resources/views/packages/show.blade.php', 'w', 'utf-8') as f:
        f.write(text)
    print("Patched show.blade.php successfully")


def patch_admin():
    with codecs.open('resources/views/admin/package-detail.blade.php', 'r', 'utf-8') as f:
        text = f.read()

    parser_code = """@php
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
    if (!empty($pkg->editorial_itinerary)) {
        $parsedItinerary = parseTextItinerary($pkg->editorial_itinerary);
    }
    
    // Combine sightseeing sources
    $sightseeingPills = [];
    if (!empty($pkg->sightseeing)) {
        $sightseeingPills = array_filter(array_map('trim', explode(',', $pkg->sightseeing)));
    }
    if (!empty($itinerary) && is_array($itinerary)) {
        foreach ($itinerary as $day) {
            if (!empty($day['title'])) {
                $sightseeingPills[] = $day['title'];
            }
        }
    }
    $sightseeingPills = array_unique($sightseeingPills);
@endphp
"""
    text = parser_code + text

    # Remove the old editorial_itinerary block
    editorial_pattern = r'@if\(!empty\(\$pkg->editorial_itinerary\)\).*?@endif'
    text = re.sub(editorial_pattern, '', text, flags=re.DOTALL)

    # Locate sections at the bottom main column (before the right sidebar)
    start_tag = text.find('{{-- Sightseeing Details --}}')
    if start_tag == -1:
        start_tag = text.find('{{-- Itinerary --}}')
        
    end_tag = text.find('{{-- Right: Pricing + Agent --}}')
    if end_tag == -1:
        end_tag = text.find('</div>\n\n        {{-- Right: Pricing + Agent --}}')

    if start_tag != -1 and end_tag != -1:
        new_sections = """{{-- Itinerary Timeline --}}
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

            {{-- Sightseeing Details --}}
            @if(count($sightseeingPills) > 0)
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft mb-6">
                <h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($sightseeingPills as $place)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm font-semibold rounded-full shadow-sm hover:bg-orange-100 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> {{ $place }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        """
        text = text[:start_tag] + new_sections + text[end_tag:]

    with codecs.open('resources/views/admin/package-detail.blade.php', 'w', 'utf-8') as f:
        f.write(text)
    print("Patched admin package-detail.blade.php successfully")


def fix_forms():
    forms = [
        'resources/views/agent/pages/create-package.blade.php',
        'resources/views/agent/pages/edit-package.blade.php',
        'resources/views/admin/packages-create.blade.php',
        'resources/views/admin/packages-edit.blade.php'
    ]
    for form in forms:
        with open(form, 'r', encoding='utf-8') as f:
            content = f.read()

        # 1. Completely remove the duplicate custom "Sightseeing Details List" block.
        pattern = r'<!-- Sightseeing Details List -->.*?</div>\s+</div>\s+<!-- Terms & Conditions -->'
        if re.search(pattern, content, re.DOTALL):
            content = re.sub(pattern, '<!-- Terms & Conditions -->', content, flags=re.DOTALL)
        else:
            pattern_fallback = r'<div class="space-y-2 mb-6">\s*<label class="text-\[10px\] font-black text-gray-400 uppercase tracking-widest">Sightseeing Details List</label>.*?</div>\s*</div>\s*<label class="text-\[10px\] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>'
            content = re.sub(
                pattern_fallback, 
                '<label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>', 
                content, 
                flags=re.DOTALL
            )

        # 2. Rename the repeater table card back to "Sightseeing Details"
        content = content.replace(
            '<h3 class="text-lg font-bold text-gray-900">Itinerary (Day-by-Day Plan)</h3>',
            '<h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>'
        )
        content = content.replace(
            '<h3 class="text-lg font-bold text-gray-900">Day-by-Day Itinerary</h3>',
            '<h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>'
        )

        with open(form, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Renamed table to Sightseeing Details and cleaned list in {form}")

patch_show()
patch_admin()
fix_forms()
