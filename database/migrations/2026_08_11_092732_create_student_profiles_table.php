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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone_number')->nullable();
            $table->string('roll_number')->unique();
            $table->string('gr_number')->unique();
            $table->string('course')->nullable();     // e.g. B.Tech CSE
            $table->string('school')->nullable();     // e.g. School of Engineering
            $table->timestamps();

            $table->index('roll_number');
            $table->index('gr_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};