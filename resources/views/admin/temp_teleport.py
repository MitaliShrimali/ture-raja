import os
import re

files = [
    'careers.blade.php', 'hotel-categories.blade.php', 'amenities.blade.php', 
    'holiday-types.blade.php', 'activities.blade.php', 'durations.blade.php', 
    'themes.blade.php', 'countries.blade.php', 'states.blade.php', 'cities.blade.php'
]

dir_path = r'c:\Users\tusha\Downloads\Tour_raja\resources\views\admin'

for file_name in files:
    file_path = os.path.join(dir_path, file_name)
    if not os.path.exists(file_path):
        print(f'Missing: {file_path}')
        continue
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Pattern to find `<div` followed by whitespace/newlines and then `x-show="show...Modal"` or similar.
    # We will just look for `x-show="show` and walk backwards to find the `<div`.
    
    pattern = re.compile(r'x-show=\"show([A-Za-z]+)Modal\"')
    matches = list(pattern.finditer(content))
    
    if not matches:
        print(f'No modals found in {file_name}')
        continue
        
    offset = 0
    processed_count = 0
    for match in matches:
        # start of x-show
        xshow_start = match.start() + offset
        
        # find the <div before it
        div_start_idx = content.rfind('<div', 0, xshow_start)
        if div_start_idx == -1:
            continue
            
        # find the indentation of the <div
        nl_idx = content.rfind('\n', 0, div_start_idx)
        indent = content[nl_idx+1:div_start_idx] if nl_idx != -1 else ''
        if not indent.isspace():
            indent = '    '
            
        stack = 0
        i = div_start_idx
        end_idx = -1
        while i < len(content):
            if content[i:i+4] == '<div':
                stack += 1
                i += 4
            elif content[i:i+6] == '</div>':
                stack -= 1
                if stack == 0:
                    end_idx = i + 6
                    break
                i += 6
            else:
                i += 1
                
        if end_idx != -1:
            # Ensure it's not already teleported
            if content[div_start_idx-30:div_start_idx].find('<template x-teleport=\"body\">') != -1:
                print(f'Already teleported in {file_name}')
                continue
                
            replacement = f'<template x-teleport=\"body\">\n{indent}' + content[div_start_idx:end_idx] + f'\n{indent}</template>'
            
            content = content[:div_start_idx] + replacement + content[end_idx:]
            offset += len(f'<template x-teleport=\"body\">\n{indent}') + len(f'\n{indent}</template>')
            processed_count += 1
            
    if processed_count > 0:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Processed {file_name} ({processed_count} modals)')
    else:
        print(f'Nothing to change in {file_name}')
