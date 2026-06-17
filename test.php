<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$packages = collect(\App\Models\Package::where('status', 'Active')->get()->toArray());
$static = [
    ['id'=>1, 'slug'=>'monaco-luxury-tour', 'title'=>'Monaco Luxury Tour Package', 'location'=>'Monaco', 'price'=>44825, 'rating'=>'4.96', 'reviews'=>'672', 'duration'=>'2 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'', 'category'=>'International', 'badge'=>'Top Rated', 'agent'=>'Azure Horizons', 'tour_type'=>'Cruise Package', 'city'=>'Monaco', 'theme'=>'Honeymoon', 'activities'=>['Cable Car / Rope way', 'Nature']],
    ['id'=>2, 'slug'=>'vietnam-tour-package', 'title'=>'Vietnam Tour Package', 'location'=>'Vietnam', 'price'=>17320, 'rating'=>'4.91', 'reviews'=>'670', 'duration'=>'3 days 3 nights', 'nights'=>3, 'groupSize'=>'2-3 guest', 'image'=>'', 'category'=>'International', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures', 'tour_type'=>'Flight Package', 'city'=>'Hanoi', 'theme'=>'Adventure', 'activities'=>['Water Activities', 'Nature']],
    ['id'=>3, 'slug'=>'char-dham-yatra', 'title'=>'Char Dham Yatra Package', 'location'=>'India', 'price'=>15463, 'rating'=>'4.86', 'reviews'=>'656', 'duration'=>'7 days 6 nights', 'nights'=>6, 'groupSize'=>'4-6 guest', 'image'=>'', 'category'=>'Religious', 'badge'=>'25% Off', 'agent'=>'Miths Holidays', 'tour_type'=>'Bus Package', 'city'=>'Haridwar', 'theme'=>'Religious', 'activities'=>['Hill Station', 'Religious']],
    ['id'=>4, 'slug'=>'goa-beach-package', 'title'=>'Goa Beach Holiday Package', 'location'=>'Goa, India', 'price'=>14755, 'rating'=>'4.74', 'reviews'=>'631', 'duration'=>'2 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'', 'category'=>'Domestic', 'badge'=>'Top Rated', 'agent'=>'Miths Holidays', 'tour_type'=>'Flight Package', 'city'=>'Goa', 'theme'=>'Honeymoon', 'activities'=>['Water Activities', 'Rides and Thrill']],
    ['id'=>5, 'slug'=>'spiti-valley-adventure', 'title'=>'Spiti Valley Package', 'location'=>'Himachal, India', 'price'=>24840, 'rating'=>'4.51', 'reviews'=>'617', 'duration'=>'3 days 3 nights', 'nights'=>3, 'groupSize'=>'4-6 guest', 'image'=>'', 'category'=>'Adventure', 'badge'=>'Best Sale', 'agent'=>'Nomad Ventures', 'tour_type'=>'Train Package', 'city'=>'Manali', 'theme'=>'Adventure', 'activities'=>['Jeep Safari', 'Hill Station']],
    ['id'=>6, 'slug'=>'swiss-paris-delight', 'title'=>'Swiss Paris Delight', 'location'=>'Europe', 'price'=>51247, 'rating'=>'4.29', 'reviews'=>'608', 'duration'=>'7 days 6 nights', 'nights'=>6, 'groupSize'=>'4-6 guest', 'image'=>'', 'category'=>'International', 'badge'=>'25% Off', 'agent'=>'Globe Trotters', 'tour_type'=>'Flight Package', 'city'=>'Paris', 'theme'=>'Family/Group', 'activities'=>['Cable Car / Rope way', 'Nature']],
    ['id'=>7, 'slug'=>'kerala-backwaters', 'title'=>'Kerala Backwaters Escape', 'location'=>'Kerala, India', 'price'=>12500, 'rating'=>'4.65', 'reviews'=>'420', 'duration'=>'4 days 3 nights', 'nights'=>3, 'groupSize'=>'2-4 guest', 'image'=>'', 'category'=>'Domestic', 'badge'=>'Popular', 'agent'=>'Miths Holidays', 'tour_type'=>'Train Package', 'city'=>'Kochi', 'theme'=>'Honeymoon', 'activities'=>['Nature', 'Water Activities']],
    ['id'=>8, 'slug'=>'dubai-desert-safari', 'title'=>'Dubai Desert Safari & Burj', 'location'=>'Dubai, UAE', 'price'=>29999, 'rating'=>'4.8', 'reviews'=>'890', 'duration'=>'4 days 3 nights', 'nights'=>3, 'groupSize'=>'2-6 guest', 'image'=>'', 'category'=>'International', 'badge'=>'Trending', 'agent'=>'Atlas Global Travels', 'tour_type'=>'Flight Package', 'city'=>'Dubai', 'theme'=>'Family/Group', 'activities'=>['Jeep Safari', 'Rides and Thrill']],
    ['id'=>9, 'slug'=>'bali-luxury-villa', 'title'=>'Bali Luxury Villa Escape', 'location'=>'Bali, Indonesia', 'price'=>35000, 'rating'=>'4.9', 'reviews'=>'543', 'duration'=>'5 days 4 nights', 'nights'=>4, 'groupSize'=>'2 guest', 'image'=>'', 'category'=>'Tropical', 'badge'=>'Honeymoon', 'agent'=>'Miths Holidays', 'tour_type'=>'Flight Package', 'city'=>'Bali', 'theme'=>'Honeymoon', 'activities'=>['Nature', 'Water Activities']],
    ['id'=>10, 'slug'=>'rishikesh-rafting', 'title'=>'Rishikesh Rafting & Yoga', 'location'=>'Rishikesh, India', 'price'=>8500, 'rating'=>'4.4', 'reviews'=>'312', 'duration'=>'2 days 1 nights', 'nights'=>1, 'groupSize'=>'4-10 guest', 'image'=>'', 'category'=>'Adventure', 'badge'=>'Weekend', 'agent'=>'Atlas Global Travels', 'tour_type'=>'Bus Package', 'city'=>'Rishikesh', 'theme'=>'Adventure', 'activities'=>['Water Activities', 'Nature']],
];

// Merge DB with Static
$staticAgents = [];
foreach ($static as $sPkg) {
    if (isset($sPkg['slug']) && isset($sPkg['agent'])) {
        $staticAgents[strtolower($sPkg['slug'])] = $sPkg['agent'];
    }
}
$dbPackages = array_map(function($pkg) use ($staticAgents) {
    $pkg = (array) $pkg;
    $slug = strtolower($pkg['slug'] ?? '');
    if ((empty($pkg['agent']) || $pkg['agent'] === 'Nomad Ventures') && isset($staticAgents[$slug])) {
        $pkg['agent'] = $staticAgents[$slug];
    }
    return $pkg;
}, $packages->toArray());

$merged = $dbPackages;
$dbTitles = array_map(fn($p) => strtolower($p['title'] ?? ''), $dbPackages);
$dbSlugs = array_map(fn($p) => strtolower($p['slug'] ?? ''), $dbPackages);

foreach ($static as $sPkg) {
    if (!in_array(strtolower($sPkg['title'] ?? ''), $dbTitles) && !in_array(strtolower($sPkg['slug'] ?? ''), $dbSlugs)) {
        $merged[] = $sPkg;
    }
}
$packages = collect($merged);

echo "Initial packages count: " . $packages->count() . "\n";

// ── Search Filter (Not filled)

// ── Category Filter (Not filled)

// ── Price filter (Complex: Radio + Min + Max)
// Simulated request values: min_price=1000, max_price=100000
$minPrice = 1000;
$maxPrice = 100000;
$packages = $packages->filter(function($pkg) use ($minPrice, $maxPrice) {
    $pkg = (array) $pkg;
    $price = $pkg['price'] ?? 0;
    return $price >= $minPrice && $price <= $maxPrice;
});
echo "After Price filter: " . $packages->count() . "\n";

// ── Duration (Nights) filter
// Simulated request values: min_nights=2, max_nights=11
$minN = 2;
$maxN = 11;
$packages = $packages->filter(function($pkg) use ($minN, $maxN) {
    $pkg = (array) $pkg;
    $nights = $pkg['nights'] ?? 0;
    if (!$nights && isset($pkg['duration'])) {
        if (preg_match('/(\d+)\s*nights?/', strtolower($pkg['duration']), $matches)) {
            $nights = (int)$matches[1];
        }
    }
    return $nights >= $minN && $nights <= $maxN;
});
echo "After Duration filter: " . $packages->count() . "\n";

// ── Agent Filter (company = Miths Holidays)
$companyName = "miths holidays";
$packages = $packages->filter(function($pkg) use ($companyName) {
    $pkg = (array) $pkg;
    $pAgentName = '';
    if (isset($pkg['agent'])) {
        if (is_array($pkg['agent'])) {
            $pAgentName = $pkg['agent']['name'] ?? '';
        } elseif (is_string($pkg['agent'])) {
            $decoded = json_decode($pkg['agent'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $pAgentName = $decoded['name'] ?? '';
            } else {
                $pAgentName = $pkg['agent'];
            }
        } elseif (is_object($pkg['agent'])) {
            $pAgentName = $pkg['agent']->name ?? '';
        }
    }
    return str_contains(strtolower(trim($pAgentName)), $companyName);
});
echo "After Agent filter (company=Miths Holidays) count: " . $packages->count() . "\n";

foreach($packages as $p) {
    echo " - " . $p['title'] . "\n";
}




