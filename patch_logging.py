import re

filepath = r'c:\Users\tusha\Downloads\Tour_raja\app\Http\Controllers\AdminController.php'
with open(filepath, 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_lines = []
i = 0
while i < len(lines):
    line = lines[i]
    
    # Check if this line is a redirect with success
    if '->with(\'success\'' in line and 'return redirect' in line:
        # Check if we already logged something recently
        recently_logged = False
        for j in range(max(0, i-5), i):
            if '$this->logActivity(' in lines[j]:
                recently_logged = True
                break
                
        if not recently_logged:
            # Extract success message
            match = re.search(r'with\(\'success\',\s*\'(.*?)\'\)', line)
            if match:
                msg = match.group(1).replace("'", "\\'")
                # Find indentation
                indent_match = re.match(r'^(\s*)', line)
                indent = indent_match.group(1) if indent_match else ""
                
                # We'll just call it 'Platform Action' but include the exact success message
                # That way it's specific but we don't have to parse function names
                log_line = f"{indent}$this->logActivity('Platform Action', '{msg}');\n"
                new_lines.append(log_line)
    
    new_lines.append(line)
    i += 1

with open(filepath, 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

print("Applied catch-all logging patch.")
