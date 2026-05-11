<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/listing', [ListingController::class, 'index']);

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/profile', [HomeController::class, 'profile']);

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    });
    Route::get('/signup', function () {
        return view('admin.signup');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Inventory & Stays
    Route::get('/packages', [AdminController::class, 'packages']);
    Route::get('/hotels', [AdminController::class, 'hotels']);
    Route::get('/amenities', [AdminController::class, 'amenities']);
    Route::get('/holiday-types', [AdminController::class, 'holidayTypes']);
    Route::get('/activities', [AdminController::class, 'activities']);

    // Admin Central
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/agents', [AdminController::class, 'agents']);
    Route::get('/leads', [AdminController::class, 'leads']);

    // Subscription Oversight
    Route::get('/paid-users', [AdminController::class, 'paidUsers']);
    Route::get('/user-plans', [AdminController::class, 'userPlans']);
    Route::get('/payments', [AdminController::class, 'payments']);
    Route::get('/ads', [AdminController::class, 'ads']);
    Route::get('/plans', [AdminController::class, 'plans']);

    // Platform Settings
    Route::get('/home-editor', [AdminController::class, 'homeEditor']);
    Route::get('/notifications', [AdminController::class, 'notifications']);
    Route::get('/cms', [AdminController::class, 'cms']);
    Route::get('/contact', [AdminController::class, 'contact']);
    Route::get('/subscribers', [AdminController::class, 'subscribers']);
    Route::get('/settings', [AdminController::class, 'settings']);
});

Route::get('/discover', [ListingController::class, 'index'])->name('discover');
Route::get('/tour/{slug}', function($slug) {
    return view('tour.show', compact('slug'));
})->name('tour.show');
Route::get('/login', function() {
    return view('admin.login');
})->name('login');

Route::get('/package/{id}', [PackageController::class, 'show']);

Route::get('/packages/{slug}', function ($slug) {
    $allPackages = [
        'monaco-luxury-tour' => [
            'slug'       => 'monaco-luxury-tour',
            'title'      => 'Monaco Luxury Tour Package',
            'image'      => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '2 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.96',
            'reviews'    => '672',
            'price'      => 44825,
            'oldPrice'   => 59825,
            'badge'      => 'Top Rated',
            'category'   => 'international',
            'overview'   => 'Experience the pinnacle of luxury in the glamorous Monaco. Walk along the famous Monte Carlo Casino, enjoy the breathtaking Mediterranean coastline, and immerse yourself in Formula 1 culture at every corner of this stunning principality.',
            'highlights' => [
                'Visit the iconic Monte Carlo Casino and Royal Palace',
                'Sunset cruise along the Mediterranean coast',
                'Exclusive dining at award-winning restaurants',
                'Private guide for the entire trip',
                'Carry on from any major Indian airport',
            ],
            'included'   => ['Beverages, drinking water, hot/key tea and buffet lunch', 'Local tours', 'Hotel pickup and drop off', 'Insurance/Transfer in a private car', 'Sick drinks', 'Tour Guide'],
            'excluded'   => ['Airfare', 'Tips', 'Alcoholic Beverages'],
            'itinerary'  => [
                ['title' => 'Airport Pick Up', 'desc' => 'Arrive at Nice Côte d\'Azur Airport and transfer to your luxury hotel in Monaco.'],
                ['title' => 'Temple & River Cruise', 'desc' => 'Morning guided tour of the Royal Palace, followed by an evening Mediterranean cruise.'],
                ['title' => 'Casino & Overnight Stay', 'desc' => 'Explore Monte Carlo Casino, enjoy fine dining, and overnight at your 5-star hotel.'],
            ],
        ],
        'vietnam-tour-package' => [
            'slug'       => 'vietnam-tour-package',
            'title'      => 'Vietnam Tour Package',
            'image'      => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '3 days 3 nights',
            'groupSize'  => '2-3 guest',
            'rating'     => '4.91',
            'reviews'    => '670',
            'price'      => 17320,
            'oldPrice'   => 25320,
            'badge'      => 'Best Sale',
            'category'   => 'international',
            'overview'   => 'Discover the stunning Ha Long Bay, cruise through emerald waters dotted with towering limestone karsts, visit Monkey Island, and experience the vibrant local culture of Vietnam.',
            'highlights' => [
                'Ha Long Bay cruise with stunning karst landscapes',
                'Visit Monkey Island and pristine beaches',
                'Kayaking through hidden lagoons',
                'Authentic Vietnamese cooking class',
                'All-inclusive cruise package',
            ],
            'included'   => ['All meals on cruise', 'Kayaking & activities', 'Hotel transfers', 'Tour guide', 'Entry fees'],
            'excluded'   => ['International flights', 'Vietnam Visa fees', 'Personal expenses'],
            'itinerary'  => [
                ['title' => 'Arrival & Embarkation', 'desc' => 'Arrive at Hanoi, transfer to Ha Long Bay, and board your luxury cruise.'],
                ['title' => 'Ha Long Bay Exploration', 'desc' => 'Kayaking through limestone caves, visit Monkey Island and floating villages.'],
                ['title' => 'Sunrise Tai Chi & Departure', 'desc' => 'Morning Tai Chi on deck, breakfast, and transfer back to Hanoi.'],
            ],
        ],
        'char-dham-yatra' => [
            'slug'       => 'char-dham-yatra',
            'title'      => 'Char Dham Yatra Package',
            'image'      => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '7 days 6 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.86',
            'reviews'    => '656',
            'price'      => 15463,
            'oldPrice'   => 19000,
            'badge'      => '25% Off',
            'category'   => 'religious',
            'overview'   => 'Embark on the sacred Char Dham Yatra — a spiritual journey to Yamunotri, Gangotri, Kedarnath, and Badrinath nestled in the majestic Himalayas. Experience divine serenity and breathtaking mountain scenery.',
            'highlights' => [
                'Visit all four sacred Dhams: Yamunotri, Gangotri, Kedarnath, Badrinath',
                'Helicopter option available for Kedarnath',
                'Comfortable stays at each Dham',
                'Experienced religious guide throughout',
                'Starting from Haridwar/Rishikesh',
            ],
            'included'   => ['Accommodation (6 nights)', 'Daily breakfast & dinner', 'AC vehicle', 'Guide', 'All darshan arrangements'],
            'excluded'   => ['Helicopter charges', 'Personal pooja items', 'Tips', 'Lunch'],
            'itinerary'  => [
                ['title' => 'Haridwar to Yamunotri', 'desc' => 'Depart from Haridwar. Drive to Janki Chatti and trek to Yamunotri temple.'],
                ['title' => 'Yamunotri to Gangotri', 'desc' => 'Morning aarti at Yamunotri, drive to Uttarkashi, overnight stay.'],
                ['title' => 'Gangotri Darshan', 'desc' => 'Visit the Gangotri temple at the source of the river Ganga.'],
                ['title' => 'Kedarnath Trek', 'desc' => 'Drive to Gaurikund, trek 16 km (or helicopter) to Kedarnath temple.'],
                ['title' => 'Kedarnath to Badrinath', 'desc' => 'Morning darshan at Kedarnath, drive to Badrinath via Chopta.'],
                ['title' => 'Badrinath Darshan', 'desc' => 'Early morning abhishek and darshan at Badrinath, visit Mana Village.'],
                ['title' => 'Return to Haridwar', 'desc' => 'Drive back to Haridwar with divine memories and blessings.'],
            ],
        ],
        'goa-beach-package' => [
            'slug'       => 'goa-beach-package',
            'title'      => 'Goa Beach Holiday Package',
            'image'      => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '2 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.74',
            'reviews'    => '631',
            'price'      => 14755,
            'oldPrice'   => 19825,
            'badge'      => 'Top Rated',
            'category'   => 'domestic',
            'overview'   => 'Sun, sand, and surf await you in Goa! Enjoy pristine beaches, vibrant nightlife, delicious seafood, and a blend of Indian and Portuguese culture in this ultimate beach holiday.',
            'highlights' => [
                'North Goa beaches: Baga, Calangute, Anjuna',
                'South Goa tranquil beaches: Palolem, Colva',
                'Water sports: parasailing, jet ski, banana ride',
                'Old Goa heritage churches tour',
                'Sunset cruise on the Mandovi River',
            ],
            'included'   => ['Hotel stay (3 nights)', 'Breakfast daily', 'Airport transfers', 'Sightseeing by AC cab', 'Water sports (1 session)'],
            'excluded'   => ['Flights', 'Personal expenses', 'Lunch & dinner', 'Tips'],
            'itinerary'  => [
                ['title' => 'Arrival & North Goa Beach', 'desc' => 'Arrive at Goa airport, check in, relax at Calangute/Baga beach, enjoy the nightlife.'],
                ['title' => 'Sightseeing & Water Sports', 'desc' => 'Old Goa churches, spice plantation, water sports session, sunset cruise.'],
                ['title' => 'South Goa & Departure', 'desc' => 'Morning at Palolem beach, checkout, and drop to airport.'],
            ],
        ],
        'spiti-valley-adventure' => [
            'slug'       => 'spiti-valley-adventure',
            'title'      => 'Spiti Valley Package',
            'image'      => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '3 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.51',
            'reviews'    => '617',
            'price'      => 24840,
            'oldPrice'   => 31825,
            'badge'      => 'Best Sale',
            'category'   => 'adventure',
            'overview'   => 'Journey into the cold desert of Spiti Valley — one of the world\'s highest inhabited regions. Experience ancient monasteries, dramatic landscapes, starry nights, and raw Himalayan adventure.',
            'highlights' => [
                'Key Monastery — oldest in Spiti',
                'Chandratal Lake at 14,100 ft',
                'Kaza market and local village walks',
                'Tabo Monastery UNESCO heritage site',
                'Off-road adventure on mountain trails',
            ],
            'included'   => ['Accommodation (3 nights)', 'All meals', '4x4 vehicle', 'Experienced local guide', 'Permits'],
            'excluded'   => ['Flights to Manali/Shimla', 'Travel insurance', 'Personal gear'],
            'itinerary'  => [
                ['title' => 'Manali to Kaza', 'desc' => 'Cross Rohtang Pass, drive through Lahaul valley to Kaza. Check in and rest.'],
                ['title' => 'Kaza Sightseeing', 'desc' => 'Visit Key Monastery, Kibber village, Chicham bridge, and Pin Valley.'],
                ['title' => 'Chandratal & Departure', 'desc' => 'Drive to Chandratal Lake, enjoy the surreal beauty, return to Manali.'],
            ],
        ],
        'swiss-paris-delight' => [
            'slug'       => 'swiss-paris-delight',
            'title'      => 'Swiss Paris Delight',
            'image'      => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '7 days 6 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.29',
            'reviews'    => '608',
            'price'      => 51247,
            'oldPrice'   => null,
            'badge'      => '25% Off',
            'category'   => 'international',
            'overview'   => 'The ultimate European double-header! Explore the romantic streets of Paris and then escape to the breathtaking Swiss Alps. Eiffel Tower, Louvre, Jungfraujoch, and Interlaken await.',
            'highlights' => [
                'Eiffel Tower visit with skip-the-line tickets',
                'Louvre Museum guided tour',
                'Swiss Alps — Jungfraujoch (Top of Europe)',
                'Interlaken adventure activities',
                'Seine River dinner cruise',
            ],
            'included'   => ['6 nights hotel (3 Paris + 3 Switzerland)', 'Breakfast daily', 'Europe Schengen visa assistance', 'All transfers', 'City tours'],
            'excluded'   => ['International flights', 'Travel insurance', 'Personal shopping'],
            'itinerary'  => [
                ['title' => 'Arrive Paris', 'desc' => 'Land at Charles de Gaulle, hotel check-in, evening Seine cruise.'],
                ['title' => 'Paris City Tour', 'desc' => 'Eiffel Tower, Louvre Museum, Notre Dame, Champs-Élysées.'],
                ['title' => 'Versailles Day Trip', 'desc' => 'Palace of Versailles and gardens tour.'],
                ['title' => 'Paris to Zurich', 'desc' => 'High-speed train to Switzerland. Check in at Interlaken.'],
                ['title' => 'Jungfraujoch Excursion', 'desc' => 'Top of Europe — snow, glaciers, and panoramic Alps views.'],
                ['title' => 'Lucerne Free Day', 'desc' => 'Chapel Bridge, Lion Monument, shopping, lake cruise.'],
                ['title' => 'Departure', 'desc' => 'Transfer to Zurich airport for your return flight.'],
            ],
        ],
    ];

    if (!isset($allPackages[$slug])) {
        abort(404);
    }

    return view('packages.show', ['package' => $allPackages[$slug]]);
})->name('packages.show');
