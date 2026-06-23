const fs = require('fs');
let c = fs.readFileSync('resources/views/components/package-card.blade.php', 'utf8');
c = c.replace(/\{\{\s*\$pkgArr\['currency'\]\s*\?\?\s*'₹'\s*\}\}/g, "{{ $currency ?? '₹' }}");
c = c.replace(/\{\{\s*\$pkgArr\["currency"\]\s*\?\?\s*"â‚¹"\s*\}\}/g, "{{ $currency ?? '₹' }}");
c = c.replace(/\{\{\s*\$pkgArr\["currency"\]\s*\?\?\s*"₹"\s*\}\}/g, "{{ $currency ?? '₹' }}");
fs.writeFileSync('resources/views/components/package-card.blade.php', c, 'utf8');
