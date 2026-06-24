import os

f = 'resources/views/components/filter-sidebar.blade.php'
with open(f, 'r', encoding='utf-8') as file:
    content = file.read()

replacement = """        <!-- 1.4. Destination Type -->
        <div>
            <h3 class="font-bold text-gray-900 mb-3 uppercase tracking-wide" style="font-size: 20px;">Destination Type</h3>
            @php
                $selectedDestTypes = (array) request('category', []);
            @endphp
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="category[]" value="domestic" {{ in_array('domestic', $selectedDestTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">Domestic</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="category[]" value="international" {{ in_array('international', $selectedDestTypes) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/50 cursor-pointer">
                    <span class="text-gray-600 text-xs font-semibold group-hover:text-primary transition-colors">International</span>
                </label>
            </div>
            <hr class="mt-5 border-gray-100">
        </div>

        <!-- 1.5. Categories -->"""

if "<!-- 1.4. Destination Type -->" not in content:
    content = content.replace('<!-- 1.5. Categories -->', replacement)
    with open(f, 'w', encoding='utf-8') as file:
        file.write(content)
