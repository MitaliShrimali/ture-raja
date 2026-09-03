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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('SUPER ADMIN');
            $table->text('permissions')->nullable();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Migrate data
        $emailsToKeep = ['admin@tourraja.com', 'superadmin@tourraja.com'];
        
        $adminsToMigrate = \Illuminate\Support\Facades\DB::table('users')
            ->whereIn('email', $emailsToKeep)
            ->get();

        foreach ($adminsToMigrate as $admin) {
            \Illuminate\Support\Facades\DB::table('admins')->insert((array) $admin);
        }

        // Delete from users table
        $emailsToDelete = [
            'admin@tourraja.com', 'superadmin@tourraja.com',
            'siti.w@tourraja.id', 'rian_j@tourraja.id', 'budi.a@tourraja.id', 'dewi.a@tourraja.id', 'hendra.r@tourraja.id'
        ];
        
        \Illuminate\Support\Facades\DB::table('users')->whereIn('email', $emailsToDelete)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        $admins = \Illuminate\Support\Facades\DB::table('admins')->get();
        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\DB::table('users')->insert((array) $admin);
        }
        
        Schema::dropIfExists('admins');
    }
};
