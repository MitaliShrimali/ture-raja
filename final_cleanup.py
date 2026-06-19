import re

def final_cleanup(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Change "Save & Next" to "Next Step ->"
    content = content.replace('>Save & Next<', '>Next Step -><')
    content = content.replace('>Save &amp; Next<', '>Next Step -><')
    
    # Change "Save And Exit" to "Save Package"
    content = content.replace('>Save And Exit<', '>Save Package<')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for view in ['resources/views/agent/pages/create-package.blade.php', 'resources/views/agent/pages/edit-package.blade.php', 'resources/views/admin/packages-create.blade.php', 'resources/views/admin/packages-edit.blade.php']:
    final_cleanup(view)

print("Final cleanups applied.")
