import re

files_to_patch = [
    'resources/views/admin/packages-create.blade.php',
    'resources/views/agent/pages/create-package.blade.php'
]

for file_path in files_to_patch:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find where x-data is initialized and add category
    if "category: 'domestic'," not in content:
        content = content.replace("categories: [],", "category: 'domestic',\n    categories: [],")
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

edit_files = [
    'resources/views/admin/packages-edit.blade.php',
    'resources/views/agent/pages/edit-package.blade.php'
]

for file_path in edit_files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if "category: {{" not in content:
        content = content.replace("step: 1,", "step: 1,\n      category: {{ json_encode($pkg->category ?? 'domestic') }},")
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("x-data patched for category.")
