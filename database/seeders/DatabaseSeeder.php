<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Packages using existing PackageSeeder
        $this->call(PackageSeeder::class);

        // 2. Seed Users (Admin Users)
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@tourraja.com',
                'password' => Hash::make('password123'),
                'role' => 'SUPER ADMIN',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Wahyuni',
                'email' => 'siti.w@tourraja.id',
                'password' => Hash::make('password123'),
                'role' => 'MANAGER',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Siti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rian Jatmiko',
                'email' => 'rian_j@tourraja.id',
                'password' => Hash::make('password123'),
                'role' => 'SUPER ADMIN',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Rian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Antoro',
                'email' => 'budi.a@tourraja.id',
                'password' => Hash::make('password123'),
                'role' => 'EDITOR',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Budi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Anggraeni',
                'email' => 'dewi.a@tourraja.id',
                'password' => Hash::make('password123'),
                'role' => 'EDITOR',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Dewi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hendra Rusli',
                'email' => 'hendra.r@tourraja.id',
                'password' => Hash::make('password123'),
                'role' => 'MANAGER',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Hendra',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Seed Agents
        DB::table('agents')->truncate();
        DB::table('agents')->insert([
            [
                'name' => 'Nomad Ventures',
                'logo' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=150',
                'email' => 'contact@nomadventures.com',
                'phone' => '+91 98765 43210',
                'region' => 'Asia Pacific',
                'tier' => 'Premium',
                'status' => 'Active',
                'service_guaranteed' => true,
                'api_access' => true,
                'pending' => 3,
                'approved' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Azure Horizons',
                'logo' => 'https://images.unsplash.com/photo-1542744094-2ab25be78b90?auto=format&fit=crop&q=80&w=150',
                'email' => 'hello@azurehorizons.travel',
                'phone' => '+91 91234 56789',
                'region' => 'Europe',
                'tier' => 'Standard',
                'status' => 'Active',
                'service_guaranteed' => false,
                'api_access' => false,
                'pending' => 8,
                'approved' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Globe Trotters Co',
                'logo' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=150',
                'email' => 'support@globetrotters.org',
                'phone' => '+91 99988 77766',
                'region' => 'North America',
                'tier' => 'Premium',
                'status' => 'Inactive',
                'service_guaranteed' => true,
                'api_access' => false,
                'pending' => 1,
                'approved' => 24,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alpine Escape',
                'logo' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&q=80&w=150',
                'email' => 'info@alpine-escape.com',
                'phone' => '+91 94433 22110',
                'region' => 'Europe',
                'tier' => 'Standard',
                'status' => 'Active',
                'service_guaranteed' => false,
                'api_access' => false,
                'pending' => 11,
                'approved' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Seed Leads
        DB::table('leads')->truncate();
        DB::table('leads')->insert([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.j@example.com',
                'phone' => '+1 555-0101',
                'agent' => 'Nomad Ventures',
                'package' => 'Bali Luxury Villa Escape',
                'status' => 'Booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mark Wilson',
                'email' => 'mark.w@example.com',
                'phone' => '+1 555-0202',
                'agent' => 'Azure Horizons',
                'package' => 'Swiss Alps Adventure',
                'status' => 'New',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Connor',
                'email' => 'sarah.c@example.com',
                'phone' => '+1 555-0303',
                'agent' => 'Globe Trotters',
                'package' => 'Goa Beach Holiday Package',
                'status' => 'Contacted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Wick',
                'email' => 'john.w@example.com',
                'phone' => '+1 555-0404',
                'agent' => 'Atlas Global Travels',
                'package' => 'Dubai Desert Safari & Burj',
                'status' => 'Lost',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 5. Seed Hotels
        DB::table('hotels')->truncate();
        DB::table('hotels')->insert([
            [
                'name' => 'The Grand Palace',
                'category' => 'Luxury Resort',
                'location' => 'Jaipur, India',
                'rating' => 5,
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alpine View Inn',
                'category' => 'Boutique Hotel',
                'location' => 'Zermatt, Switzerland',
                'rating' => 4,
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coastal Sands Resort',
                'category' => 'Beachfront',
                'location' => 'Goa, India',
                'rating' => 4,
                'status' => 'Draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desert Rose Oasis',
                'category' => 'Ultra Luxury',
                'location' => 'Dubai, UAE',
                'rating' => 5,
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Seed Amenities
        DB::table('amenities')->truncate();
        DB::table('amenities')->insert([
            [
                'name' => 'High-Speed WiFi',
                'icon' => 'wifi',
                'category' => 'Connectivity',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Breakfast Included',
                'icon' => 'coffee',
                'category' => 'Dining',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Free Parking',
                'icon' => 'car',
                'category' => 'Transport',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Air Conditioning',
                'icon' => 'wind',
                'category' => 'Comfort',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flat-Screen TV',
                'icon' => 'tv',
                'category' => 'Entertainment',
                'status' => 'Inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Seed Holiday Types
        DB::table('holiday_types')->truncate();
        DB::table('holiday_types')->insert([
            [
                'name' => 'Tropical Getaways',
                'icon' => 'sun',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mountain Adventures',
                'icon' => 'mountain',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cultural Exploration',
                'icon' => 'building-2',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desert Safaris',
                'icon' => 'compass',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 8. Seed Activities
        DB::table('activities')->truncate();
        DB::table('activities')->insert([
            [
                'name' => 'Scuba Diving',
                'icon' => 'zap',
                'intensity' => 'High',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Photography Tour',
                'icon' => 'camera',
                'intensity' => 'Low',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'City Sightseeing',
                'icon' => 'compass',
                'intensity' => 'Medium',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mountain Hiking',
                'icon' => 'map',
                'intensity' => 'High',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 9. Seed Paid Users
        DB::table('paid_users')->truncate();
        DB::table('paid_users')->insert([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.j@example.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Alice',
                'plan' => 'Premium Plus',
                'joined_date' => '2026-05-01',
                'amount' => 199.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mark Wilson',
                'email' => 'mark.w@example.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mark',
                'plan' => 'Standard',
                'joined_date' => '2026-05-03',
                'amount' => 99.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Connor',
                'email' => 'sarah.c@example.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah',
                'plan' => 'Premium Plus',
                'joined_date' => '2026-04-20',
                'amount' => 199.00,
                'status' => 'Suspended',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Wick',
                'email' => 'john.w@example.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=John',
                'plan' => 'Standard',
                'joined_date' => '2026-05-10',
                'amount' => 99.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 10. Seed User Plans
        DB::table('user_plans')->truncate();
        DB::table('user_plans')->insert([
            [
                'user_name' => 'Alice Johnson',
                'email' => 'alice.j@example.com',
                'plan_name' => 'Premium Plus',
                'price' => 199.00,
                'duration' => '1 Month',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Mark Wilson',
                'email' => 'mark.w@example.com',
                'plan_name' => 'Standard',
                'price' => 99.00,
                'duration' => '1 Month',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Sarah Connor',
                'email' => 'sarah.c@example.com',
                'plan_name' => 'Premium Plus',
                'price' => 199.00,
                'duration' => '1 Month',
                'status' => 'Expired',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 11. Seed Payments
        DB::table('payments')->truncate();
        DB::table('payments')->insert([
            [
                'user_name' => 'Alice Johnson',
                'email' => 'alice.j@example.com',
                'plan_type' => 'Premium Plus',
                'amount' => 199.00,
                'payment_id' => 'TXN_987213812',
                'date' => '2026-05-01',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Mark Wilson',
                'email' => 'mark.w@example.com',
                'plan_type' => 'Standard',
                'amount' => 99.00,
                'payment_id' => 'TXN_128731982',
                'date' => '2026-05-03',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Sarah Connor',
                'email' => 'sarah.c@example.com',
                'plan_type' => 'Premium Plus',
                'amount' => 199.00,
                'payment_id' => 'TXN_876238122',
                'date' => '2026-04-20',
                'status' => 'Failed',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 12. Seed Ads
        DB::table('ads')->truncate();
        DB::table('ads')->insert([
            [
                'campaign_name' => 'Summer Escape Bonanza',
                'position' => 'Home Hero Slider',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800',
                'link' => '/discover',
                'clicks' => 384,
                'views' => 4820,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_name' => 'Luxury Bali Getaway',
                'position' => 'Package Details Sidebar',
                'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=800',
                'link' => '/package/1',
                'clicks' => 129,
                'views' => 2100,
                'status' => 'Paused',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_name' => 'Swiss Alps Skiing banner',
                'position' => 'Hotels List Sidebar',
                'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',
                'link' => '/package/2',
                'clicks' => 512,
                'views' => 9840,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 13. Seed Plans
        DB::table('plans')->truncate();
        DB::table('plans')->insert([
            [
                'name' => 'WELCOME OFFER 1',
                'price' => 99.00,
                'package_limit' => 15,
                'description' => 'A specialized introductory subscription tier designed for elite agents entering the Horizon Ascent ecosystem. Includes premium placement and expanded travel package visibility.',
                'duration' => '1 Month',
                'features' => json_encode(['15 package listings']),
                'status' => 'Active',
                'created_at' => \Carbon\Carbon::parse('2024-09-24 10:00:00'),
                'updated_at' => \Carbon\Carbon::parse('2024-09-24 10:00:00'),
            ],
            [
                'name' => 'PREMIUM ANNUAL 2024',
                'price' => 199.00,
                'package_limit' => 50,
                'description' => 'Standard tier for seasonal travelers including priority support and curated itineraries.',
                'duration' => '1 Year',
                'features' => json_encode(['50 package listings']),
                'status' => 'Inactive', // Expired
                'created_at' => \Carbon\Carbon::parse('2024-08-24 10:00:00'),
                'updated_at' => \Carbon\Carbon::parse('2024-08-24 10:00:00'),
            ],
            [
                'name' => 'ENTERPRISE TRIAL',
                'price' => 499.00,
                'package_limit' => 100,
                'description' => 'High-volume agency tier with full API integrations, priority placements, and white-label support.',
                'duration' => '1 Month',
                'features' => json_encode(['100 package listings']),
                'status' => 'Inactive', // Expired
                'created_at' => \Carbon\Carbon::parse('2024-07-24 10:00:00'),
                'updated_at' => \Carbon\Carbon::parse('2024-07-24 10:00:00'),
            ],
            [
                'name' => 'CUSTOMISE PLAN',
                'price' => 0.00,
                'package_limit' => 9999,
                'description' => 'Bespoke integration model matching customized needs, volume quotas, and individual configurations.',
                'duration' => 'Custom',
                'features' => json_encode(['Unlimited package listings']),
                'status' => 'Inactive', // Expired
                'created_at' => \Carbon\Carbon::parse('2024-06-24 10:00:00'),
                'updated_at' => \Carbon\Carbon::parse('2024-06-24 10:00:00'),
            ]
        ]);

        // 14. Seed Banners
        DB::table('banners')->truncate();
        DB::table('banners')->insert([
            [
                'title' => 'Explore the World with TourRaja',
                'subtitle' => 'Watch this beautiful journey and find your next escape',
                'image' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'link' => '/discover',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vietnam Tropical Getaway',
                'subtitle' => 'Cruising the breathtaking Ha Long Bay karsts',
                'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=1200',
                'link' => '/packages/vietnam-tour-package',
                'status' => 'Inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Spiritual Char Dham Yatra',
                'subtitle' => 'Journey into the sacred Himalayan shrines',
                'image' => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=1200',
                'link' => '/packages/char-dham-yatra',
                'status' => 'Inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 15. Seed Notifications
        DB::table('notifications')->truncate();
        DB::table('notifications')->insert([
            [
                'title' => 'System Connection Established',
                'message' => 'Connected successfully to the local XAMPP MySQL database instance on port 3307.',
                'type' => 'Info',
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Agent Registration Request',
                'message' => 'New travel agency Atlas Global Travels registered and requested Premium Tier access.',
                'type' => 'Warning',
                'sent_at' => now()->subMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Database Backup Complete',
                'message' => 'Weekly platform database backup was completed safely to the storage volume.',
                'type' => 'Alert',
                'sent_at' => now()->subHours(2),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 16. Seed CMS Pages
        DB::table('cms_pages')->truncate();
        DB::table('cms_pages')->insert([
            [
                'title' => 'About TourRaja',
                'slug' => 'about-us',
                'content' => 'TourRaja is a premier travel discovery and package booking platform that links travelers to verified agents worldwide.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-conditions',
                'content' => 'Please read these administrative and customer service terms carefully before booking standard travel packages.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => 'We protect and secure customer traveler information in accordance with state digital governance laws.',
                'status' => 'Draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 17. Seed Contacts
        DB::table('contacts')->truncate();
        DB::table('contacts')->insert([
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul.s@example.com',
                'phone' => '+91 98765 43210',
                'subject' => 'Monaco Tour Pricing Query',
                'message' => 'Could you please confirm if airport transfers from Nice Côte d\'Azur Airport are private?',
                'status' => 'Pending',
                'created_at' => now()->subHours(4),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anita Desai',
                'email' => 'anita.d@example.com',
                'phone' => '+91 91234 56789',
                'subject' => 'Agent Premium Upgrading',
                'message' => 'I would like to activate my Premium Tier subscription, but payment was rejected. Please advise.',
                'status' => 'Resolved',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ]
        ]);

        // 18. Seed Subscribers
        DB::table('subscribers')->truncate();
        DB::table('subscribers')->insert([
            [
                'email' => 'traveler1@outlook.com',
                'status' => 'Subscribed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'nomad_tourist@gmail.com',
                'status' => 'Subscribed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'vacationer@yahoo.com',
                'status' => 'Unsubscribed',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 19. Seed Settings
        DB::table('settings')->truncate();
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'TourRaja Admin HQ', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_logo', 'value' => 'TR', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'support_email', 'value' => 'support@tourraja.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_motto', 'value' => 'Elevating Travel Experiences', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
