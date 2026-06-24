import os

files = [
    'resources/views/admin/packages-create.blade.php',
    'resources/views/admin/packages-edit.blade.php',
    'resources/views/agent/pages/create-package.blade.php',
    'resources/views/agent/pages/edit-package.blade.php'
]

for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if '<input type="hidden" name="category" :value="category">' not in content:
        # insert right after the segmented control for category
        target = '''<div class="segmented-btn" :class="category === 'international' ? 'active' : ''" @click="category = 'international'">International</div>
                        </div>'''
        replacement = target + '''\n                        <input type="hidden" name="category" :value="category">'''
        content = content.replace(target, replacement)
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f'Patched {f}')
    else:
        print(f'Already patched {f}')
