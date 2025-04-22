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
        Schema::create('inspection_records', function (Blueprint $table) {
            $table->id('inspection_id');
            $table->string('vin');
            $table->date('inspection_date');
            $table->integer('mileage_at_inspection');
            $table->text('service_details');
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_records');
    }
};
