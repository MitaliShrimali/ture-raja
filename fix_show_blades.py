import re

def fix_show_blades(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Change "Editorial Details" to "Itinerary"
    content = content.replace('Editorial Details', 'Itinerary (Day-by-Day Plan)')
    content = content.replace('Travel Guide &amp; Itinerary Brochure', 'Itinerary (Day-by-Day Plan)')

    # 2. Change the "Itinerary" heading for the repeater to "Sightseeing Details"
    # Wait, the heading is <h3 class="text-lg font-black text-foreground mb-6">Itinerary</h3>
    # Let's replace Itinerary with Sightseeing Details for that specific heading structure
    content = re.sub(
        r'<h3 class="text-lg font-black text-foreground mb-6">Itinerary</h3>',
        r'<h3 class="text-lg font-black text-foreground mb-6">Sightseeing Details</h3>',
        content
    )
    content = re.sub(
        r'<h2 class="font-black text-gray-900 mb-8 section-heading">Itinerary</h2>',
        r'<h2 class="font-black text-gray-900 mb-8 section-heading">Sightseeing Details</h2>',
        content
    )

    # 3. Remove the Sightseeing Details list I added in patch_show.py earlier
    # It started with {{-- Sightseeing Details --}} and ended with @endif
    # Because they don't want it.
    content = re.sub(
        r'\{\{-- Sightseeing Details --\}\}.*?@endif',
        '',
        content,
        flags=re.DOTALL
    )

    # 4. In the repeater block, the numbers are currently 1, 2, 3. The user's screenshot showed 1, 2, 3.
    # The default title fallback is "Day " . ($idx + 1). We should change it to "Spot " . ($idx + 1) or just remove the fallback so it uses the title.
    content = content.replace('"Day " . ($idx + 1)', '"Spot " . ($idx + 1)')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for view in ['resources/views/packages/show.blade.php', 'resources/views/admin/package-detail.blade.php']:
    fix_show_blades(view)

print("Show blades patched.")
