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

    # 1. Update x-show for Brochure vs Itinerary
    content = re.sub(
        r'(<div\s+class=\"md:w-\[40%\] bg-white rounded-\[28px\] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col transition-all duration-300\")\s*(x-show=\"!itineraryContent\")?',
        r'\1 x-show="!itineraryContent"',
        content
    )
    
    sections_to_hide = [
        'Editorial Details Card',
        'Sightseeing Details Card',
        'Inclusions & Exclusions Grid',
        'Essential Amenities',
        'Itinerary card  ~65%'
    ]
    for section in sections_to_hide:
        pattern = rf'(<!-- {section} -->\s*<div\s+class=\"[^\"]+\")\s*(x-show=\"!brochureName\")?'
        content = re.sub(pattern, r'\1 x-show="!brochureName"', content)

    # 2. Fix Gallery Modal
    content = re.sub(
        r'<div x-show=\"isGalleryModalOpen\"\s+class=\"fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm\" style=\"display: none;\">',
        r'<template x-teleport="body">\n            <div x-show="isGalleryModalOpen"\n                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">',
        content
    )
    content = re.sub(
        r'</div>\s*</div>\s*</div>\s*<script src=\"https://cdnjs.cloudflare.com/ajax/libs/tinymce',
        r'</div>\n                </div>\n            </div>\n        </template>\n    </div>\n\n    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce',
        content
    )
    
    # 3. Save buttons
    if 'Save &amp; Next' in content or 'Save & Next' in content:
        footer_pattern = re.compile(
            r'<div class=\"flex items-center justify-between pt-8 border-t border-gray-100 mt-8\">.*?</div>\s*</form>',
            re.DOTALL
        )
        
        if 'admin' in file_path:
            discard_route = "{{ route('admin.packages.index') }}"
        else:
            discard_route = "{{ route('agent.my-packages') }}"
            
        new_footer = f'''<div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-8">
                <div></div>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{discard_route}"
                        class="px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all">
                        Discard
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-primary hover:bg-orange-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-orange-700/20"
                        style="background-color: #e85d26 !important; color: #ffffff !important;">
                        Save And Exit
                    </button>
                </div>
            </div>
        </form>'''
        
        content = footer_pattern.sub(new_footer, content)

    # 4. Remove Duration from Sightseeing
    content = re.sub(
        r'<th\s+class=\"py-4 px-6 text-\[10px\] font-black text-gray-400 uppercase tracking-widest\">\s*Duration</th>',
        '',
        content
    )
    content = re.sub(
        r'<td class=\"py-4 px-6\">\s*<input type=\"text\" name=\"itinerary_durations\[\]\" x-model=\"day\.duration\"[^>]+>\s*</td>',
        '',
        content
    )
    content = re.sub(
        r'this\.days\.push\(\{ title: \'\', desc: \'\', duration: \'3 Hours\' \}\);',
        r'this.days.push({ title: \'\', desc: \'\' });',
        content
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
        
print('Replaced')
