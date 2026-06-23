const fs = require('fs');
let content = fs.readFileSync('resources/views/layouts/app.blade.php', 'utf8');
content = content.replace(/â‚¹\$\{Number\(item\.price\)\.toLocaleString\(\)\}/g, '${item.currency || `₹`}${Number(item.price).toLocaleString()}');
fs.writeFileSync('resources/views/layouts/app.blade.php', content, 'utf8');

content = fs.readFileSync('resources/views/components/package-card.blade.php', 'utf8');
content = content.replace(/price:\s*'\{\{\s*\$price\s*\}\}'/g, "price: '{{ $price }}', currency: '{{ $pkgArr[\"currency\"] ?? \"₹\" }}'");
fs.writeFileSync('resources/views/components/package-card.blade.php', content, 'utf8');

content = fs.readFileSync('resources/views/package/show.blade.php', 'utf8');
content = content.replace(/price:\s*'\{\{\s*\$pkg\['price'\]\s*\}\}'/g, "price: '{{ $pkg[\"price\"] }}', currency: '{{ $pkg[\"currency\"] ?? \"₹\" }}'");
fs.writeFileSync('resources/views/package/show.blade.php', content, 'utf8');
