import re

def fix_labels(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Rename 'Editorial Details (Overview)' back to 'Itinerary (Day-by-Day Plan)'
    content = content.replace('Editorial Details (Overview)', 'Itinerary (Day-by-Day Plan)')

    # 2. Rename 'Day-by-Day Itinerary' back to 'Sightseeing Details'
    content = content.replace('<h3 class="text-lg font-bold text-gray-900">Day-by-Day Itinerary</h3>', '<h3 class="text-lg font-bold text-gray-900">Sightseeing Details</h3>')

    # 3. Remove the duplicate sightseeingList repeater I added
    content = re.sub(r'<!-- Sightseeing Details List -->.*?<label class="text-\[10px\] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>', '<label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terms & Conditions</label>', content, flags=re.DOTALL)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for view in ['resources/views/agent/pages/create-package.blade.php', 'resources/views/agent/pages/edit-package.blade.php', 'resources/views/admin/packages-create.blade.php', 'resources/views/admin/packages-edit.blade.php']:
    fix_labels(view)
