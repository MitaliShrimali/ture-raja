import re
import glob

def patch_blade(filepath, is_edit):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Update Departure City field to persist
    if is_edit:
        # In edit package, add value attr if missing
        content = re.sub(
            r'(name="departure_city"[^>]*?)(x-model="departure_city")',
            r'\1\2 x-init="departure_city = \'{{ addslashes($pkg->departure_city ?? \'\') }}\'"',
            content
        )
        # Wait, if x-model="badge" was there previously... Let's just forcefully replace the departure city input
        content = re.sub(
            r'<input type="text" name="badge" x-model="badge" placeholder="New Delhi"',
            r'<input type="text" name="departure_city" value="{{ $pkg->departure_city ?? \'\' }}" placeholder="New Delhi"',
            content
        )
        content = re.sub(
            r'<input type="text" name="departure_city" x-model="departure_city" placeholder="New Delhi"',
            r'<input type="text" name="departure_city" value="{{ $pkg->departure_city ?? \'\' }}" placeholder="New Delhi"',
            content
        )
    else:
        # create package
        content = re.sub(
            r'<input type="text" name="badge" x-model="badge" placeholder="New Delhi"',
            r'<input type="text" name="departure_city" placeholder="New Delhi"',
            content
        )
        content = re.sub(
            r'<input type="text" name="departure_city" x-model="departure_city" placeholder="New Delhi"',
            r'<input type="text" name="departure_city" placeholder="New Delhi"',
            content
        )

    # 2. Rename Add Your Itinerary -> Editorial Details (Overview)
    content = re.sub(
        r'<h4 class="text-sm font-bold text-gray-800">Add Your Itinerary</h4>',
        r'<h4 class="text-sm font-bold text-gray-800">Editorial Details (Overview)</h4>',
        content
    )
    content = re.sub(
        r'Upload Brochure(.*?)Add Your Itinerary',
        r'Upload Brochure\1Editorial Details (Overview)',
        content
    )

    # 3. Rename Sightseeing Details (repeater) -> Day-by-Day Itinerary
    content = re.sub(
        r'<h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>',
        r'<h3 class="text-lg font-bold text-gray-900">Day-by-Day Itinerary</h3>',
        content
    )

    # 4. Add Terms & Conditions fix
    if is_edit:
        terms_field = r"""<textarea name="terms" rows="3" placeholder="Specific booking policies for this package..." class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm text-gray-600 resize-none">{{ $pkg->terms ?? '' }}</textarea>"""
    else:
        terms_field = r"""<textarea name="terms" rows="3" placeholder="Specific booking policies for this package..." class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm text-gray-600 resize-none"></textarea>"""

    content = re.sub(
        r'<textarea name="excluded\[\]" rows="3" placeholder="Specific booking policies for this package\.\.\.".*?</textarea>',
        terms_field,
        content,
        flags=re.DOTALL
    )

    # 5. Add Sightseeing Details (list repeater)
    # We will inject this before Terms & Conditions card
    sightseeing_repeater = r"""
                            <!-- Sightseeing Details List -->
                            <div class="space-y-2 mb-6">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sightseeing Details List</label>
                                <template x-for="(spot, idx) in sightseeingList" :key="idx">
                                    <div class="flex items-center gap-2 mb-2">
                                        <input type="text" name="sightseeing_list[]" x-model="sightseeingList[idx]" class="flex-1 bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-primary" placeholder="e.g. Visit to Taj Mahal" />
                                        <button type="button" @click="sightseeingList.splice(idx, 1)" class="text-gray-300 hover:text-red-500">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="newSightseeing" @keydown.enter.prevent="if(newSightseeing.trim()){sightseeingList.push(newSightseeing.trim()); newSightseeing='';}" placeholder="Add sightseeing spot..." class="flex-1 bg-white border border-gray-100 rounded-xl py-2 px-3 text-xs font-medium outline-none focus:ring-1 focus:ring-primary" />
                                    <button type="button" @click="if(newSightseeing.trim()){sightseeingList.push(newSightseeing.trim()); newSightseeing='';}" class="px-3 py-2 bg-primary text-white rounded-xl text-xs font-bold">+</button>
                                </div>
                            </div>
    """
    
    if "sightseeingList" not in content:
        # insert before Terms & Conditions label
        content = re.sub(
            r'(<label class="text-\[10px\] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>)',
            lambda m: sightseeing_repeater + "\n" + m.group(1),
            content
        )
        
    # 6. Initialize sightseeingList in Alpine x-data
    if is_edit:
        content = re.sub(
            r'(amenities: .*?,)',
            r'\1\n                sightseeingList: {{ json_encode(!empty($pkg->sightseeing_list) ? (is_string($pkg->sightseeing_list) ? json_decode($pkg->sightseeing_list, true) : $pkg->sightseeing_list) : []) }},\n                newSightseeing: "",',
            content
        )
    else:
        content = re.sub(
            r'(amenities: \[\],)',
            r'\1\n                sightseeingList: [],\n                newSightseeing: "",',
            content
        )

    # 7. Make itineraryFormat async
    content = re.sub(
        r'function itineraryFormat\(type\)',
        r'async function itineraryFormat(type)',
        content
    )
    content = re.sub(
        r'const url = prompt',
        r'const url = await window.customPrompt',
        content
    )

    # 8. Remove duplicate Save & Next buttons
    # Just look for multiple occurrences and keep only the ones that are inside the wizard navigation
    # But wait, is it actually a duplication of the "Sightseeing Details" card or just the button?
    # User said: "and in the form Save & Next -> remove this extra button"
    # Wait, the duplicate "Sightseeing Details" block might still be there if there were TWO Sightseeing Details in the file.
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for view in ['resources/views/agent/pages/create-package.blade.php', 'resources/views/agent/pages/edit-package.blade.php', 'resources/views/admin/packages-create.blade.php', 'resources/views/admin/packages-edit.blade.php']:
    patch_blade(view, 'edit' in view)

print("Blade templates patched.")
