import re

def fix_duration(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    old_code = r"""$dayDesc = $request->itinerary_descriptions\[\$i\] \?\? '';\s*if \(\!empty\(\$dayTitle\)\) \{\s*\$itinerary\[\] = \['title' => \$dayTitle, 'desc' => \$dayDesc\];\s*\}"""
    new_code = """$dayDesc = $request->itinerary_descriptions[$i] ?? '';
                $dayDur = $request->itinerary_durations[$i] ?? '';
                if (!empty($dayTitle)) {
                    $itinerary[] = ['title' => $dayTitle, 'desc' => $dayDesc, 'duration' => $dayDur];
                }"""
                
    content = re.sub(old_code, new_code, content)
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)

fix_duration('app/Http/Controllers/AgentController.php')
fix_duration('app/Http/Controllers/AdminController.php')

print("Durations patched.")
