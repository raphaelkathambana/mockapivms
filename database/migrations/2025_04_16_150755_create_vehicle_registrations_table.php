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
        Schema::create('vehicle_registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->string('vin');
            $table->string('sepa_data');
            $table->string('custom_license_plate')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_registrations');
    }
};
