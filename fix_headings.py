import sys

def fix_show_headings():
    with open('resources/views/packages/show.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Change editorial_itinerary heading to "Overview"
    text = text.replace(
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Itinerary (Day-by-Day Plan)</h3>',
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Overview</h3>'
    )
    # Just in case it was Editorial Details
    text = text.replace(
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Editorial Details</h3>',
        '<h3 class="font-black text-gray-900 mt-6 mb-3 section-heading text-xl">Overview</h3>'
    )

    with open('resources/views/packages/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Updated show.blade.php headings")

def fix_admin_headings():
    with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
        text = f.read()

    # Change editorial_itinerary heading to "Overview"
    text = text.replace(
        '<h3 class="text-lg font-black text-foreground mb-4">Itinerary (Day-by-Day Plan)</h3>',
        '<h3 class="text-lg font-black text-foreground mb-4">Overview</h3>'
    )
    # Just in case it was Editorial Details
    text = text.replace(
        '<h3 class="text-lg font-black text-foreground mb-4">Editorial Details</h3>',
        '<h3 class="text-lg font-black text-foreground mb-4">Overview</h3>'
    )

    with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Updated admin/package-detail.blade.php headings")

fix_show_headings()
fix_admin_headings()
