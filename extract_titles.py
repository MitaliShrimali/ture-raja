import os
import glob
import re

directory = r"c:\Users\tusha\Downloads\Tour_raja\resources\views\admin"

# Pattern to find the section title declaration
title_pattern = re.compile(r"@section\('admin_title',\s*'([^']+)'\)")

# Pattern to find the first h2 and an optional following p tag
heading_pattern = re.compile(r'[ \t]*<h2[^>]*>(.*?)</h2>(?:\s*<p[^>]*>.*?</p>)?', re.IGNORECASE | re.DOTALL)

for filepath in glob.glob(os.path.join(directory, "*.blade.php")):
    basename = os.path.basename(filepath)
    
    # Skip dashboard and settings which we either already fixed or are special
    if basename in ['dashboard.blade.php', 'settings.blade.php']:
        continue

    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    # Skip if it doesn't have an admin_title section
    if "@section('admin_title'" not in content:
        continue

    # Find the first h2
    match = heading_pattern.search(content)
    if not match:
        continue

    raw_title = match.group(1).strip()
    
    # If the h2 contains blade syntax like {{ $metric }}, it's probably not the main title. Skip it.
    if "{{" in raw_title:
        continue
        
    # Strip any inner HTML from raw_title
    clean_title = re.sub(r'<[^>]+>', '', raw_title).strip()
    
    # Let's replace the admin_title
    content = title_pattern.sub(f"@section('admin_title', '{clean_title}')", content)
    
    # Remove the heading block (the h2 and the p)
    content = content[:match.start()] + content[match.end():]
    
    # Clean up empty wrappers if they exist
    content = re.sub(r'<div class="space-y-2">\s*</div>', '', content)
    content = re.sub(r'<div class="space-y-1">\s*</div>', '', content)
    
    # Write back
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(content)
    
    print(f"Updated {basename} with title: {clean_title}")
