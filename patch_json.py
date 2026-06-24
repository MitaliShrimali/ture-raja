import re

with open('resources/views/agent/pages/hotels.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

blade = re.sub(
    r'@json\(\[\s*"id"\s*=>\s*\$h->id,\s*"name"\s*=>\s*\$h->name,\s*"loc"\s*=>\s*\$h->location,\s*"cat"\s*=>\s*\$h->category,\s*"status"\s*=>\s*\$h->status\s*\]\)',
    r'{{ json_encode(["id" => $h->id, "name" => $h->name, "loc" => $h->location, "cat" => $h->category, "status" => $h->status]) }}',
    blade
)

with open('resources/views/agent/pages/hotels.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print('Fixed.')
