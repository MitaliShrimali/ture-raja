import re

def patch_file(orig_path, backup_path):
    with open(orig_path, 'r', encoding='utf-8') as f:
        orig = f.read()
    with open(backup_path, 'r', encoding='utf-8') as f:
        backup = f.read()
        
    # Extract roles dropdown replacement
    role_match = re.search(r'<!-- Platform Role -->(.*?)</div>\s*</div>', backup, re.DOTALL)
    if role_match:
        orig = re.sub(r'<!-- Platform Role -->(.*?)</div>\s*</div>', role_match.group(0), orig, flags=re.DOTALL)
        
    # Extract permissions matrix
    perm_match = re.search(r'<!-- Permission Matrix Card -->(.*?)<!-- Sticky Footer bar -->', backup, re.DOTALL)
    if perm_match:
        orig = re.sub(r'(<div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">.*?</div>\s*</div>\s*</div>)', r'\1\n\n    ' + perm_match.group(0).replace('<!-- Sticky Footer bar -->', ''), orig, flags=re.DOTALL)
        
    # Extract script
    script_match = re.search(r'<script>.*?</script>', backup, re.DOTALL)
    if script_match:
        orig = re.sub(r'</div>\s*@endsection', '\n' + script_match.group(0) + '\n@endsection', orig, flags=re.DOTALL)
        
    # Extract x-data and role modal
    orig = orig.replace('<div class="pb-16 text-[#1A1A24]">', '<div class="pb-16 text-[#1A1A24]" x-data="adminData()">')
    
    modal_match = re.search(r'<!-- Role Management Modal -->.*?</div>\s*</div>\s*</div>', backup, re.DOTALL)
    if modal_match:
        orig = orig.replace('</form>', '</form>\n\n    ' + modal_match.group(0)[:-6])
        
    with open(orig_path, 'w', encoding='utf-8') as f:
        f.write(orig)
    print("Patched " + orig_path)

patch_file('resources/views/admin/users-create.blade.php', 'temp_backup/users-create.blade.php')
patch_file('resources/views/admin/users-edit.blade.php', 'temp_backup/users-edit.blade.php')
