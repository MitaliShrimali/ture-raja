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
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('resume_path');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('location');
            $table->string('location_other')->nullable();
            $table->string('notice_period');
            $table->string('gender');
            $table->string('education');
            $table->string('total_exp');
            $table->string('relevant_exp')->nullable();
            $table->string('current_ctc')->nullable();
            $table->string('expected_ctc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
