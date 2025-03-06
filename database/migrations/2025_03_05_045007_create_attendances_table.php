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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('status');
            $table->dateTime('clock_in');
            $table->string('clock_in_photo');
            $table->string('clock_in_lat');
            $table->string('clock_in_long');
            $table->dateTime('clock_out')->nullable();
            $table->string('clock_out_photo')->nullable();
            $table->string('clock_out_lat')->nullable();
            $table->string('clock_out_long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
