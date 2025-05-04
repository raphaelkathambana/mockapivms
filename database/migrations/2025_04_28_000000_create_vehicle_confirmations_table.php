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
        Schema::create('vehicle_confirmations', function (Blueprint $table) {
            $table->id('confirmation_id');
            $table->string('vin');
            $table->integer('num_previous_owners');
            $table->string('warranty_status');
            $table->string('inspection_status');
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
            $table->foreign('seller_id')->references('seller_id')->on('sellers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_confirmations');
    }
};
