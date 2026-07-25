<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('job_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('open_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('department_id');
            $table->text('locations'); // Stored as a comma-separated string or JSON array
            $table->string('experience');
            $table->string('job_type'); // Full Time, Part Time, Contract
            $table->string('salary')->nullable();
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('job_departments')->onDelete('cascade');
        });

        // Insert initial departments
        DB::table('job_departments')->insert([
            ['name' => 'Air Operation Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Customer Support', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert initial locations
        DB::table('job_locations')->insert([
            ['name' => 'Ahmedabad', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kolkata', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Delhi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mumbai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bengaluru', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert initial open positions matching user's image example
        $airOpId = DB::table('job_departments')->where('name', 'Air Operation Department')->value('id');
        if ($airOpId) {
            DB::table('open_positions')->insert([
                [
                    'title' => 'Travel Consultant / Sr. Travel Consultant - Ahmedabad',
                    'department_id' => $airOpId,
                    'locations' => json_encode(['Ahmedabad', 'Kolkata']),
                    'experience' => '2-4 Years',
                    'job_type' => 'Full Time',
                    'salary' => 'Competitive',
                    'status' => 'Active',
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3)
                ],
                [
                    'title' => 'Travel Consultant ( Offline Ticketing) - Delhi',
                    'department_id' => $airOpId,
                    'locations' => json_encode(['Delhi']),
                    'experience' => '2-4 Years',
                    'job_type' => 'Full Time',
                    'salary' => null,
                    'status' => 'Active',
                    'created_at' => now()->subDays(26),
                    'updated_at' => now()->subDays(26)
                ],
                [
                    'title' => 'Travel Consultant ( Refund Recovery Team)',
                    'department_id' => $airOpId,
                    'locations' => json_encode(['Mumbai']),
                    'experience' => '3-7 Years',
                    'job_type' => 'Full Time',
                    'salary' => null,
                    'status' => 'Active',
                    'created_at' => now()->subDays(32),
                    'updated_at' => now()->subDays(32)
                ],
                [
                    'title' => 'Travel Consultant - SOTO',
                    'department_id' => $airOpId,
                    'locations' => json_encode(['Mumbai']),
                    'experience' => '2-4 Years',
                    'job_type' => 'Full Time',
                    'salary' => 'Competitive',
                    'status' => 'Active',
                    'created_at' => now()->subDays(17),
                    'updated_at' => now()->subDays(17)
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_positions');
        Schema::dropIfExists('job_locations');
        Schema::dropIfExists('job_departments');
    }
};
