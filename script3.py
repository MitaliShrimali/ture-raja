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

    # 1. Fix Brochure x-show to allow showing if brochureName is already true (to break the tie)
    content = re.sub(
        r'(<div\s+class=\"md:w-\[40%\] bg-white rounded-\[28px\] border border-gray-100 p-6 space-y-4 shadow-sm flex flex-col transition-all duration-300\")\s*x-show=\"!itineraryContent\"',
        r'\1 x-show="brochureName || !itineraryContent"',
        content
    )
    
    # 2. Hide the OR divider properly
    content = re.sub(
        r'<div class=\"hidden md:flex items-center justify-center px-4\">\s*<div class=\"w-px h-full bg-gray-100 relative\">\s*<span class=\"absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-\[#F8F8F8\] p-2 text-\[10px\] font-black text-gray-400 uppercase tracking-widest rounded-full\">OR</span>',
        r'<div class="hidden md:flex items-center justify-center px-4" x-show="!brochureName && !itineraryContent">\n                        <div class="w-px h-full bg-gray-100 relative">\n                            <span class="absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-[#F8F8F8] p-2 text-[10px] font-black text-gray-400 uppercase tracking-widest rounded-full">OR</span>',
        content
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

print('Done')
