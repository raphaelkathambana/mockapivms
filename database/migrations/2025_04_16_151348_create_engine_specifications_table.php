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
        Schema::create('engine_specifications', function (Blueprint $table) {
            $table->id('engine_id');
            $table->string('vin');
            $table->decimal('kw', 8, 2);
            $table->decimal('hp', 8, 2);
            $table->integer('ccm');
            $table->unsignedBigInteger('fuel_type_id');
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
            $table->foreign('fuel_type_id')->references('fuel_type_id')->on('fuel_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engine_specifications');
    }
};
