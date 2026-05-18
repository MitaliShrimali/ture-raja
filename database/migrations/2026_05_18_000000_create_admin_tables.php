<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('SUPER ADMIN'); // SUPER ADMIN, MANAGER, EDITOR
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
            });
        }

        // 2. Modify agents table
        if (Schema::hasTable('agents')) {
            Schema::table('agents', function (Blueprint $table) {
                if (!Schema::hasColumn('agents', 'name')) {
                    $table->string('name')->nullable();
                    $table->string('email')->nullable();
                    $table->string('phone')->nullable();
                    $table->string('region')->nullable();
                    $table->string('tier')->default('Premium'); // Standard, Premium, Enterprise
                    $table->string('status')->default('Active'); // Active, Inactive
                    $table->boolean('service_guaranteed')->default(true);
                    $table->boolean('api_access')->default(false);
                }
            });
        }

        // 3. Modify packages table
        if (Schema::hasTable('packages')) {
            Schema::table('packages', function (Blueprint $table) {
                if (!Schema::hasColumn('packages', 'status')) {
                    $table->string('status')->default('Active'); // Active, Draft
                    $table->string('stock')->default('10 Left');
                }
            });
        }

        // 4. Create leads table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('agent');
            $table->string('package');
            $table->string('status')->default('New'); // Booked, New, Contacted, Lost
            $table->timestamps();
        });

        // 5. Create hotels table
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Luxury Resort, Boutique Hotel, beachfront, etc
            $table->string('location');
            $table->integer('rating')->default(5);
            $table->string('status')->default('Published'); // Published, Draft
            $table->timestamps();
        });

        // 6. Create amenities table
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->string('category');
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        // 7. Create holiday_types table
        Schema::create('holiday_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        // 8. Create activities table
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->string('intensity')->default('Medium'); // High, Medium, Low
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        // 9. Create paid_users table
        Schema::create('paid_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('avatar')->nullable();
            $table->string('plan');
            $table->date('joined_date');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('Active'); // Active, Suspended
            $table->timestamps();
        });

        // 10. Create user_plans table
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email');
            $table->string('plan_name');
            $table->decimal('price', 10, 2);
            $table->string('duration');
            $table->string('status')->default('Active'); // Active, Expired
            $table->timestamps();
        });

        // 11. Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email');
            $table->string('plan_type');
            $table->decimal('amount', 10, 2);
            $table->string('payment_id');
            $table->date('date');
            $table->string('status')->default('Completed'); // Completed, Pending, Failed
            $table->timestamps();
        });

        // 12. Create ads table
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('position'); // Home Hero, Package Sidebar, etc
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->integer('clicks')->default(0);
            $table->integer('views')->default(0);
            $table->string('status')->default('Active'); // Active, Paused
            $table->timestamps();
        });

        // 13. Create plans table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('duration');
            $table->text('features')->nullable();
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        // 14. Create banners table
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        // 15. Create notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('Info'); // Alert, Info, Warning
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });

        // 16. Create cms_pages table
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('status')->default('Published'); // Published, Draft
            $table->timestamps();
        });

        // 17. Create contacts table
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('Pending'); // Pending, Resolved
            $table->timestamps();
        });

        // 18. Create subscribers table
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('status')->default('Subscribed'); // Subscribed, Unsubscribed
            $table->timestamps();
        });

        // 19. Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('ads');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('user_plans');
        Schema::dropIfExists('paid_users');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('holiday_types');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('hotels');
        Schema::dropIfExists('leads');

        if (Schema::hasTable('packages')) {
            Schema::table('packages', function (Blueprint $table) {
                if (Schema::hasColumn('packages', 'status')) {
                    $table->dropColumn('status');
                    $table->dropColumn('stock');
                }
            });
        }

        if (Schema::hasTable('agents')) {
            Schema::table('agents', function (Blueprint $table) {
                if (Schema::hasColumn('agents', 'name')) {
                    $table->dropColumn(['name', 'email', 'phone', 'region', 'tier', 'status', 'service_guaranteed', 'api_access']);
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn(['role', 'avatar']);
                }
            });
        }
    }
};
