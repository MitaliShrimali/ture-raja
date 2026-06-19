import re

def add_overview_to_admin(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    if '{{ $pkg->overview }}' not in content:
        # We will insert it before "Inclusions & Exclusions"
        block_to_insert = """
            {{-- Overview & Editorial Itinerary --}}
            <div class="bg-white rounded-[32px] p-8 border border-border-soft shadow-soft space-y-6">
                <h3 class="text-lg font-black text-foreground">Overview</h3>
                <p class="text-sm text-foreground whitespace-pre-wrap">{{ $pkg->overview }}</p>

                @if(!empty($pkg->editorial_itinerary))
                <h3 class="text-lg font-black text-foreground mt-6">Itinerary (Day-by-Day Plan)</h3>
                <p class="text-sm text-foreground whitespace-pre-wrap">{{ $pkg->editorial_itinerary }}</p>
                @endif
            </div>
"""
        content = content.replace("{{-- Inclusions & Exclusions --}}", block_to_insert + "\n            {{-- Inclusions & Exclusions --}}")

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

add_overview_to_admin('resources/views/admin/package-detail.blade.php')
print("Added overview and editorial_itinerary to admin package-detail")
