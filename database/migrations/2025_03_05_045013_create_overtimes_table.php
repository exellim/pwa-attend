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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->dateTime('clock_in_ovt');
            $table->string('clock_in_ovt_photo');
            $table->string('clock_in_ovt_lat');
            $table->string('clock_in_ovt_long');
            $table->dateTime('clock_out_ovt')->nullable();
            $table->string('clock_out_ovt_photo')->nullable();
            $table->string('clock_out_ovt_lat')->nullable();
            $table->string('clock_out_ovt_long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
