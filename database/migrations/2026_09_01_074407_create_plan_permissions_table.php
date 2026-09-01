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
        Schema::create('plan_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('permission_key');
            $table->enum('permission_type', ['boolean', 'numeric']);
            $table->boolean('boolean_value')->default(false);
            $table->integer('limit_value')->nullable(); // null means unlimited
            $table->timestamps();
            
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->unique(['plan_id', 'permission_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_permissions');
    }
};
