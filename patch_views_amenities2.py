import os

files = [
    'resources/views/admin/packages-edit.blade.php',
    'resources/views/agent/pages/edit-package.blade.php'
]

replacement_base = """
                            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="chef-hat" class="text-gray-400" size="18"></i>
                                    <span class="text-xs font-bold text-gray-700">Private Chef Included</span>
                                </div>
                                <input type="checkbox" name="amenities[]" value="Private Chef Included" {c1} class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            </label>

                            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="user-check" class="text-gray-400" size="18"></i>
                                    <span class="text-xs font-bold text-gray-700">Tour Manager Included</span>
                                </div>
                                <input type="checkbox" name="amenities[]" value="Tour Manager Included" {c2} class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-pointer" />
                            </label>
                        </div>
                    </div>

                    <!-- Primary featured photo upload hidden input -->
"""

search_str = """                        </div>
                    </div>

                    <!-- Primary featured photo upload hidden input -->"""

for f in files:
    if not os.path.exists(f):
        print(f"Skipping {f}, file not found")
        continue

    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if 'Private Chef Included' in content:
        print(f'{f} already has Private Chef Included')
        continue
    
    c1 = "{{ in_array('Private Chef Included', $amenities) ? 'checked' : '' }}"
    c2 = "{{ in_array('Tour Manager Included', $amenities) ? 'checked' : '' }}"
    
    repl = replacement_base.replace('{c1}', c1).replace('{c2}', c2)
    
    if search_str in content:
        content = content.replace(search_str, repl.strip())
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f'Updated {f}')
    else:
        print(f'Search string not found in {f}')
