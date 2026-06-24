import re

with open('resources/views/admin/package-detail.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

# Remove the late definition
late_def = r'''\s*\$hotels = json_decode\(\$pkg->hotels, true\) \?\: \[\];\s*\$transfers = json_decode\(\$pkg->transfers, true\) \?\: \[\];'''
blade = re.sub(late_def, '', blade)

# Add to the early @php block
early_php_target = r"(\$dbAgent = \\DB::table\('agents'\)->where\('name', \$agentName\)->first\(\);)"
early_php_inject = r"\1\n    $hotels = json_decode($pkg->hotels, true) ?: [];\n    $transfers = json_decode($pkg->transfers, true) ?: [];"
blade = re.sub(early_php_target, early_php_inject, blade)

with open('resources/views/admin/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print("Moved variables to top.")
