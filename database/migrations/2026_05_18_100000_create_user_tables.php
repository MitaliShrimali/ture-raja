<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * All user-facing tables use the prefix "user_" so they are
     * clearly separated from admin tables in the DB panel.
     */
    public function up(): void
    {
        // ─── 1. user_profiles ─────────────────────────────────────────
        // Extended profile data for registered site visitors
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('username')->nullable();
                $table->string('phone')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable()->default('India');
                $table->string('date_of_birth')->nullable();
                $table->string('avatar')->nullable();               // file path or URL
                $table->string('gender')->nullable();               // Male, Female, Other
                $table->timestamps();
            });
        }

        // ─── 2. user_wishlists ────────────────────────────────────────
        // Packages saved by users (heart button)
        if (!Schema::hasTable('user_wishlists')) {
            Schema::create('user_wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('package_id');           // FK to packages
                $table->string('package_title');                    // denormalised for speed
                $table->string('package_image')->nullable();
                $table->decimal('package_price', 10, 2)->default(0);
                $table->timestamps();
                $table->unique(['user_id', 'package_id']);
            });
        }

        // ─── 3. user_bookings ────────────────────────────────────────
        // Tour package booking requests made by users
        if (!Schema::hasTable('user_bookings')) {
            Schema::create('user_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('package_id');
                $table->string('package_title');
                $table->string('package_image')->nullable();
                $table->decimal('package_price', 10, 2)->default(0);
                $table->string('traveler_name');
                $table->string('traveler_email');
                $table->string('traveler_phone');
                $table->integer('guests')->default(1);
                $table->date('travel_date');
                $table->string('special_request')->nullable();
                $table->string('status')->default('Pending');       // Pending, Confirmed, Cancelled, Completed
                $table->timestamps();
            });
        }

        // ─── 4. user_inquiries ───────────────────────────────────────
        // Contact form messages from visitors (auto-synced to contacts table in admin)
        if (!Schema::hasTable('user_inquiries')) {
            Schema::create('user_inquiries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();  // null = guest submission
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject')->nullable()->default('General Inquiry');
                $table->text('message');
                $table->string('status')->default('Pending');       // Pending, Replied, Closed
                $table->timestamps();
            });
        }

        // ─── 5. user_newsletter_subscriptions ───────────────────────
        // Newsletter email sign-ups from the home page footer CTA
        if (!Schema::hasTable('user_newsletter_subscriptions')) {
            Schema::create('user_newsletter_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('status')->default('Subscribed');    // Subscribed, Unsubscribed
                $table->timestamps();
            });
        }

        // ─── 6. user_search_queries ──────────────────────────────────
        // Search analytics: what users search for on the hero/listing page
        if (!Schema::hasTable('user_search_queries')) {
            Schema::create('user_search_queries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('destination')->nullable();
                $table->string('from_city')->nullable();
                $table->integer('results_count')->default(0);
                $table->timestamps();
            });
        }

        // ─── 7. user_reviews ─────────────────────────────────────────
        // User-submitted reviews for packages
        if (!Schema::hasTable('user_reviews')) {
            Schema::create('user_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('package_id');
                $table->string('package_title');
                $table->integer('rating')->default(5);              // 1-5
                $table->string('review_title')->nullable();
                $table->text('review_body');
                $table->string('status')->default('Pending');       // Pending, Approved, Rejected
                $table->timestamps();
            });
        }

        // ─── 8. user_notifications ───────────────────────────────────
        // Per-user in-app notification feed (booking confirmations, offers, etc.)
        if (!Schema::hasTable('user_notifications')) {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('Info');            // Info, Alert, Promo
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('user_reviews');
        Schema::dropIfExists('user_search_queries');
        Schema::dropIfExists('user_newsletter_subscriptions');
        Schema::dropIfExists('user_inquiries');
        Schema::dropIfExists('user_bookings');
        Schema::dropIfExists('user_wishlists');
        Schema::dropIfExists('user_profiles');
    }
};
