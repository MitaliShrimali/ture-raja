<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Ensure a demo user exists ────────────────────────────────
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Priya Sharma',
            'email'      => 'priya@demo.com',
            'password'   => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── 1. user_profiles ────────────────────────────────────────
        DB::table('user_profiles')->insert([
            'user_id'       => $userId,
            'username'      => 'priya_explorer',
            'phone'         => '+91 98765 43210',
            'city'          => 'Mumbai',
            'country'       => 'India',
            'date_of_birth' => '1995-06-15',
            'avatar'        => null,
            'gender'        => 'Female',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ─── 2. user_wishlists ───────────────────────────────────────
        $wishlistItems = [
            ['package_id' => 1, 'package_title' => 'Monaco Luxury Tour Package',  'package_image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=600', 'package_price' => 44825],
            ['package_id' => 3, 'package_title' => 'Char Dham Yatra Package',      'package_image' => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=600', 'package_price' => 15463],
            ['package_id' => 4, 'package_title' => 'Goa Beach Holiday Package',   'package_image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600', 'package_price' => 14755],
        ];

        foreach ($wishlistItems as $item) {
            DB::table('user_wishlists')->insert(array_merge($item, [
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ─── 3. user_bookings ────────────────────────────────────────
        $bookings = [
            [
                'package_id'     => 4,
                'package_title'  => 'Goa Beach Holiday Package',
                'package_image'  => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=600',
                'package_price'  => 14755,
                'traveler_name'  => 'Priya Sharma',
                'traveler_email' => 'priya@demo.com',
                'traveler_phone' => '+91 98765 43210',
                'guests'         => 2,
                'travel_date'    => '2024-12-25',
                'status'         => 'Confirmed',
                'created_at'     => Carbon::now()->subDays(30),
                'updated_at'     => Carbon::now()->subDays(30),
            ],
            [
                'package_id'     => 3,
                'package_title'  => 'Char Dham Yatra Package',
                'package_image'  => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=600',
                'package_price'  => 15463,
                'traveler_name'  => 'Priya Sharma',
                'traveler_email' => 'priya@demo.com',
                'traveler_phone' => '+91 98765 43210',
                'guests'         => 4,
                'travel_date'    => '2025-03-15',
                'status'         => 'Pending',
                'created_at'     => Carbon::now()->subDays(5),
                'updated_at'     => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($bookings as $booking) {
            DB::table('user_bookings')->insert(array_merge($booking, ['user_id' => $userId]));
        }

        // ─── 4. user_inquiries ───────────────────────────────────────
        DB::table('user_inquiries')->insert([
            'user_id'    => $userId,
            'name'       => 'Priya Sharma',
            'email'      => 'priya@demo.com',
            'phone'      => '+91 98765 43210',
            'subject'    => 'Custom Package Query',
            'message'    => 'I am interested in a custom 10-day Europe tour for our family of 4. Can you please provide a customized itinerary?',
            'status'     => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── 5. user_newsletter_subscriptions ───────────────────────
        DB::table('user_newsletter_subscriptions')->insertOrIgnore([
            ['email' => 'priya@demo.com',          'status' => 'Subscribed', 'created_at' => now(), 'updated_at' => now()],
            ['email' => 'demo.travel@example.com', 'status' => 'Subscribed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─── 6. user_search_queries ──────────────────────────────────
        DB::table('user_search_queries')->insert([
            ['user_id' => $userId, 'destination' => 'Goa', 'from_city' => 'Mumbai', 'results_count' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null,    'destination' => 'Bali', 'from_city' => null,    'results_count' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─── 7. user_reviews ────────────────────────────────────────
        DB::table('user_reviews')->insert([
            'user_id'       => $userId,
            'package_id'    => 4,
            'package_title' => 'Goa Beach Holiday Package',
            'rating'        => 5,
            'review_title'  => 'Absolutely loved the Goa trip!',
            'review_body'   => 'The package was perfectly organized. Hotel was great, transfers were smooth and the water sports session was the highlight!',
            'status'        => 'Approved',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ─── 8. user_notifications ──────────────────────────────────
        $notifs = [
            ['title' => 'Booking Confirmed!',      'message' => 'Your Goa Beach Holiday booking (Dec 25) has been confirmed. Have a great trip!', 'type' => 'Info',  'is_read' => false],
            ['title' => 'Special Offer Inside 🎉', 'message' => '20% off on all international packages this week. Use code TOUR20 at checkout.',     'type' => 'Promo', 'is_read' => false],
            ['title' => 'Review Request',          'message' => 'How was your Goa trip? Leave a review and help other travelers.',                   'type' => 'Info',  'is_read' => true],
        ];

        foreach ($notifs as $n) {
            DB::table('user_notifications')->insert(array_merge($n, [
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
