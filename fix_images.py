import os
import re
import glob

# Path to the views directory
views_dir = 'resources/views'

# Patterns to match and wrap with asset()
# We want to match things like {{ $pkg->image }} or {{ $agentLogo }} or {{ $pkg->image ?: '...' }}
# and replace with {{ asset(...) }}
# Exclude things that are already asset(...)
patterns = [
    # Match: src="{{ $var }}" or src="{{ $var ?? '...' }}"
    (r'src="{{\s*(\$[^}]+?)\s*}}"', r'src="{{ asset(\1) }}"'),
]

# Specifically we saw these variables used for images:
# $pkg->image
# $agentLogo
# $agent->logo
# $sticker->image
# $img
# $imgUrl
# $user->avatar
# $adminAvatar
# $homeAd->image
# $pkg['agent']['logo']
# $testi['img']

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    # For every img src="{{ ... }}" we check if it already has asset(
    # If not, we wrap the inside with asset()
    def replacer(match):
        inner = match.group(1).strip()
        if inner.startswith('asset(') or inner.startswith('json_encode(') or inner.startswith('url(') or inner.startswith('route('):
            return match.group(0) # Do not modify
        return f'src="{{{{ asset({inner}) }}}}"'

    content = re.sub(r'src="{{\s*([^}]+?)\s*}}"', replacer, content)
    
    # Also fix background-image: url('{{ $var }}')
    def bg_replacer(match):
        inner = match.group(1).strip()
        if inner.startswith('asset(') or inner.startswith('url('):
            return match.group(0)
        return f"url('{{{{ asset({inner}) }}}}')"
        
    content = re.sub(r"url\('{{\s*([^}]+?)\s*}}'\)", bg_replacer, content)
    content = re.sub(r'url\("{{\s*([^}]+?)\s*}}"\)', bg_replacer, content)

    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed: {filepath}')

blade_files = glob.glob(os.path.join(views_dir, '**', '*.blade.php'), recursive=True)
for bf in blade_files:
    fix_file(bf)

print("Done.")
