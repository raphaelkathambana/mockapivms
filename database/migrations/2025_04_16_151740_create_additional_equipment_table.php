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
        Schema::create('additional_equipment', function (Blueprint $table) {
            $table->id('equipment_id');
            $table->string('vin');
            $table->text('equipment_description');
            $table->string('condition');
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_equipment');
    }
};
