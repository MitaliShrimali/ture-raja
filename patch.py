import os
import re

files = [
    'resources/views/agent/pages/create-package.blade.php',
    'resources/views/agent/pages/edit-package.blade.php',
    'resources/views/admin/packages-create.blade.php',
    'resources/views/admin/packages-edit.blade.php'
]

for filepath in files:
    if not os.path.exists(filepath):
        print(f'File not found: {filepath}')
        continue
        
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # 1. Change Departure City input name='badge' -> name='departure_city' and x-model='badge' -> x-model='departure_city'
    content = re.sub(
        r'(<label[^>]*>Departure City</label>\s*<input type="text" name=")badge("\s*x-model=")badge(")',
        r'\g<1>departure_city\g<2>departure_city\g<3>',
        content
    )
    # Also update value="{{ $pkg->badge ?? '' }}" if it's there
    content = re.sub(
        r'(<label[^>]*>Departure City</label>\s*<input type="text" name="departure_city" value="{{ )\$pkg->badge( \?\? \'\' }}")',
        r'\g<1>$pkg->departure_city\g<2>',
        content
    )
    
    # 2. Remove the extra 'Save & Next' button block in Step 1
    content = re.sub(
        r'<!-- Step 1 Footer Buttons -->\s*<div class="flex items-center justify-between pt-2">\s*<a href="[^"]+" class="[^"]+">Discard</a>\s*<button type="button" @click="step = 2; window\.scrollTo\(\{top: 0, behavior: \'smooth\'\}\);" class="[^"]+">Save &amp; Next &rarr;</button>\s*</div>',
        '',
        content
    )
    
    # 3. Remove the FIRST duplicate 'Sightseeing Details Card (Repeater)'
    start_tag = '<!-- Sightseeing Details Card (Repeater) -->'
    if start_tag in content:
        start_idx = content.find(start_tag)
        end_str = '<!-- ── 3-col layout: left content + right sidebar ── -->'
        end_idx = content.find(end_str, start_idx)
        if end_idx != -1:
            content = content[:start_idx] + content[end_idx:]
            print(f'Removed extra sightseeing in {filepath}')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f'Processed {filepath}')
