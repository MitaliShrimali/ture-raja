import re

def patch_show():
    filename = 'resources/views/packages/show.blade.php'
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Add Terms and Conditions right after Inclusions/Exclusions
    terms_html = """
                    <!-- Terms & Conditions -->
                    @if(!empty($package['terms']))
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h3 class="font-black text-gray-900 mb-4 section-heading text-lg flex items-center gap-2">
                            <i data-lucide="shield-alert" size="20" class="text-primary"></i>
                            Terms & Conditions
                        </h3>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap leading-relaxed">{{ $package['terms'] }}</p>
                    </div>
                    @endif
    """
    
    # Insert after Exclusions end
    # We look for the end of Exclusions div (usually around where Inclusions ends)
    # Let's just insert it before "Itinerary"
    content = re.sub(
        r"(\{\{-- Itinerary --\}\})",
        lambda m: terms_html + "\n                " + m.group(1),
        content
    )
    
    # 2. Add Sightseeing Details block
    # Insert it before Itinerary or after Itinerary. The user said:
    # "in this preview and in package details for customer , show both, in differnt section"
    # Let's insert it right before Itinerary
    sightseeing_html = """
                {{-- Sightseeing Details --}}
                @php 
                    $sightseeingList = is_string($package['sightseeing_list']) ? json_decode($package['sightseeing_list'], true) : $package['sightseeing_list']; 
                @endphp
                @if(!empty($sightseeingList) && is_array($sightseeingList))
                    <h2 class="font-black text-gray-900 mt-10 mb-6 section-heading flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1c7ed6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Sightseeing Details
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                        @foreach($sightseeingList as $point)
                            <div class="bg-gray-50/50 rounded-2xl p-4 flex items-start gap-3 border border-gray-100/50">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 leading-snug">{{ $point }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
    """
    
    content = re.sub(
        r"(\{\{-- Itinerary --\}\})",
        lambda m: sightseeing_html + "\n                " + m.group(1),
        content
    )

    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)

patch_show()
print("Show patched.")
