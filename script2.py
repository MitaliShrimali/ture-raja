import re

files = [
    'resources/views/agent/pages/edit-package.blade.php',
    'resources/views/agent/pages/create-package.blade.php',
    'resources/views/admin/packages-edit.blade.php',
    'resources/views/admin/packages-create.blade.php'
]

for file_path in files:
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception:
        continue

    # Inclusions
    content = re.sub(
        r'<button type=\"button\" @click=\"removeInclusion\(i\)\"\s*class=\"[^\"]+\">[^<]+</button>',
        r'''<button type="button" @click="newInclusion = item; removeInclusion(i)"
            class="text-blue-400 hover:text-blue-600 transition-all text-xs px-1" title="Edit">
            <i data-lucide="edit-2" size="14"></i>
        </button>
        <button type="button" @click="removeInclusion(i)"
            class="text-red-400 hover:text-red-600 transition-all text-xs px-1" title="Delete">
            <i data-lucide="trash-2" size="14"></i>
        </button>''',
        content
    )

    # Exclusions
    content = re.sub(
        r'<button type=\"button\" @click=\"removeExclusion\(i\)\"\s*class=\"[^\"]+\">[^<]+</button>',
        r'''<button type="button" @click="newExclusion = item; removeExclusion(i)"
            class="text-blue-400 hover:text-blue-600 transition-all text-xs px-1" title="Edit">
            <i data-lucide="edit-2" size="14"></i>
        </button>
        <button type="button" @click="removeExclusion(i)"
            class="text-red-400 hover:text-red-600 transition-all text-xs px-1" title="Delete">
            <i data-lucide="trash-2" size="14"></i>
        </button>''',
        content
    )

    # Hotels
    content = re.sub(
        r'<button type=\"button\" @click=\"removeHotel\(index\)\"\s*class=\"[^\"]+\"\s*title=\"Remove\">\s*<svg[^>]+>.*?</svg>\s*</button>',
        r'''<button type="button" @click="addHotel(); hotels[hotels.length-1] = { ...hotel }; removeHotel(index);"
            class="p-1.5 text-blue-300 hover:text-blue-500 transition-all" title="Edit">
            <i data-lucide="edit-2" size="16"></i>
        </button>
        <button type="button" @click="removeHotel(index)"
            class="p-1.5 text-gray-300 hover:text-red-400 transition-all" title="Remove">
            <i data-lucide="trash-2" size="16"></i>
        </button>''',
        content,
        flags=re.DOTALL
    )

    # Sightseeing (Itinerary)
    content = re.sub(
        r'<button type=\"button\" @click=\"removeDay\(index\)\"\s*class=\"[^\"]+\"\s*x-show=\"days\.length > 1\"\s*title=\"Remove\">\s*<svg[^>]+>.*?</svg>\s*</button>',
        r'''<button type="button" @click="removeDay(index)"
            class="p-1.5 text-gray-300 hover:text-red-400 transition-all" x-show="days.length > 1" title="Remove">
            <i data-lucide="trash-2" size="16"></i>
        </button>''',
        content,
        flags=re.DOTALL
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Icons updated")
