<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$packages = \App\Models\Package::all();
$internationalCities = ['bali', 'paris', 'dubai', 'swiss', 'monaco', 'hanoi', 'vietnam', 'america', 'new york', 'london'];

foreach ($packages as $pkg) {
    $currentCategory = strtolower($pkg->category);
    
    // If it's already domestic or international, skip
    if ($currentCategory === 'domestic' || $currentCategory === 'international') {
        continue;
    }
    
    // Determine based on title or location
    $isInternational = false;
    $textToSearch = strtolower($pkg->title . ' ' . $pkg->location);
    foreach ($internationalCities as $city) {
        if (str_contains($textToSearch, $city)) {
            $isInternational = true;
            break;
        }
    }
    
    $newCat = $isInternational ? 'international' : 'domestic';
    
    // We should also push the old category into the categories_list if it's not already there!
    // Since the old category was a valid category tag (e.g. 'Tropical', 'Adventure')
    $oldCat = ucfirst($currentCategory);
    $categoriesList = $pkg->categories_list;
    if (is_string($categoriesList) && (str_starts_with(trim($categoriesList), '[') || str_starts_with(trim($categoriesList), '{'))) {
        $decoded = json_decode($categoriesList, true);
        if (is_array($decoded)) {
            if (!in_array($oldCat, $decoded)) {
                $decoded[] = $oldCat;
            }
            $pkg->categories_list = json_encode(array_values($decoded));
        } else {
            $pkg->categories_list = json_encode([$oldCat]);
        }
    } elseif (is_string($categoriesList) && !empty(trim($categoriesList))) {
        $pkg->categories_list = json_encode([trim($categoriesList), $oldCat]);
    } else {
        $pkg->categories_list = json_encode([$oldCat]);
    }
    
    $pkg->category = $newCat;
    $pkg->save();
    
    echo "Updated Package ID {$pkg->id} ({$pkg->title}): Category -> {$newCat}, Categories List -> {$pkg->categories_list}\n";
}
echo "Done fixing packages.\n";
