import os
import re

files_to_patch = [
    'app/Http/Controllers/AdminController.php',
    'app/Http/Controllers/AgentController.php'
]

for file_path in files_to_patch:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # AdminController
    content = re.sub(
        r"'category' => is_array\(\$request->category\) \? json_encode\(\$request->category\) : \(\$request->category \?\? 'Tropical'\),",
        r"'category' => $request->category ?? 'domestic',\n            'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,",
        content
    )
    
    # AgentController
    content = re.sub(
        r"'category'   => is_array\(\$request->category\) \? json_encode\(\$request->category\) : \(\$request->category \?\? 'domestic'\),",
        r"'category'   => $request->category ?? 'domestic',\n            'categories_list' => is_array($request->categories_list) ? json_encode($request->categories_list) : null,",
        content
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

# ListingController patch
listing_controller = 'app/Http/Controllers/ListingController.php'
with open(listing_controller, 'r', encoding='utf-8') as f:
    content = f.read()

# Make it filter on categories_list instead of category
content = content.replace(r"$pkgCategory = $pkg['category'] ?? '';", r"$pkgCategory = $pkg['categories_list'] ?? '';")

with open(listing_controller, 'w', encoding='utf-8') as f:
    f.write(content)

print("Controllers patched.")

# Blade templates patch
blade_files = [
    'resources/views/admin/packages-create.blade.php',
    'resources/views/admin/packages-edit.blade.php',
    'resources/views/agent/pages/create-package.blade.php',
    'resources/views/agent/pages/edit-package.blade.php'
]

segmented_control_html = """                    <!-- Destination Type (Segmented control) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Destination Type</label>
                        <div class="segmented-control">
                            <div class="segmented-btn" :class="category === 'domestic' ? 'active' : ''" @click="category = 'domestic'">Domestic</div>
                            <div class="segmented-btn" :class="category === 'international' ? 'active' : ''" @click="category = 'international'">International</div>
                        </div>
                    </div>

                    <!-- Categories (Multi-select) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Categories</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="cat in ['Mountain', 'Safari', 'Desert', 'Flower', 'Beach', 'Temples', 'Yacht']">
                                <label class="flex items-center gap-2 px-3 py-2 border rounded-xl cursor-pointer transition-all"
                                    :class="categories.includes(cat) ? 'border-[#e85d26] bg-orange-50 text-[#e85d26]' : 'border-gray-200 bg-white text-gray-600'">
                                    <input type="checkbox" name="categories_list[]" :value="cat" x-model="categories" class="hidden">
                                    <span class="text-xs font-bold" x-text="cat"></span>
                                </label>
                            </template>
                        </div>
                    </div>"""

for file_path in blade_files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace the Categories section
    # Use regex to find the block
    pattern = r"<!-- Categories \(Multi-select\) -->.*?</div>\s*</div>"
    content = re.sub(pattern, segmented_control_html, content, flags=re.DOTALL)
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Blade templates patched.")

# filter-sidebar patch
sidebar_file = 'resources/views/components/filter-sidebar.blade.php'
with open(sidebar_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace $rawCategories query
content = content.replace("DB::table('packages')->whereNotNull('category')->where('category', '!=', '')->pluck('category')->toArray();", 
                          "DB::table('packages')->whereNotNull('categories_list')->where('categories_list', '!=', '')->pluck('categories_list')->toArray();")

with open(sidebar_file, 'w', encoding='utf-8') as f:
    f.write(content)

print("Sidebar patched.")
