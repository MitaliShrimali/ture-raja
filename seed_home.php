<?php

$intl = [
    ['title' => 'Bangkok', 'subtitle' => 'Thailand', 'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Dubai', 'subtitle' => 'UAE', 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Las Vegas', 'subtitle' => 'USA', 'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Rome', 'subtitle' => 'Italy', 'image' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Bali', 'subtitle' => 'Indonesia', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Andaman', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&q=80&w=400'],
];

foreach(array_reverse($intl) as $pkg) {
    \DB::table('home_packages')->insert([
        'type' => 'international',
        'title' => $pkg['title'],
        'subtitle' => $pkg['subtitle'],
        'image' => $pkg['image'],
        'price' => rand(15000, 50000),
        'status' => 'Live',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$dom = [
    ['title' => 'Goa', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Kerala', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Jaipur', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Kutch', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Mumbai', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1566552881560-0be862a7c445?auto=format&fit=crop&q=80&w=400'],
    ['title' => 'Srinagar', 'subtitle' => 'India', 'image' => 'https://images.unsplash.com/photo-1562979314-bee7453e911c?auto=format&fit=crop&q=80&w=400'],
];

foreach(array_reverse($dom) as $pkg) {
    \DB::table('home_packages')->insert([
        'type' => 'domestic',
        'title' => $pkg['title'],
        'subtitle' => $pkg['subtitle'],
        'image' => $pkg['image'],
        'price' => rand(10000, 30000),
        'status' => 'Live',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
echo "Seeded successfully.\n";
