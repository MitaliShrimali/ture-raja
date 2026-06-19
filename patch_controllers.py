import re

def patch_controller(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Add parsing for sightseeing_list
    parsing_code = """
        // Sightseeing List parsing
        $sightseeing_list = [];
        if ($request->has('sightseeing_list')) {
            if (is_array($request->sightseeing_list)) {
                $sightseeing_list = array_values(array_filter(array_map('trim', $request->sightseeing_list)));
            } else {
                $sightseeing_list = array_values(array_filter(array_map('trim', explode("\\n", $request->sightseeing_list))));
            }
        }
    """
    
    # Insert it before $itinerary parsing in updatePackage and storePackage
    content = re.sub(
        r"(\s*// Itinerary Days parsing\s*\$itinerary = \[\];)",
        lambda m: "\n" + parsing_code + m.group(1),
        content
    )

    # 2. Add fields to DB::table('packages')->update()
    update_fields = """
            'departure_city'      => $request->departure_city ?? null,
            'terms'               => $request->terms ?? null,
            'sightseeing_list'    => json_encode($sightseeing_list),
            'currency'            => $request->currency ?? '₹',
    """
    content = re.sub(
        r"('title'\s*=>\s*\$request->title,)",
        lambda m: m.group(1) + update_fields,
        content
    )
    
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)

patch_controller('app/Http/Controllers/AgentController.php')
patch_controller('app/Http/Controllers/AdminController.php')

print("Controllers patched.")
