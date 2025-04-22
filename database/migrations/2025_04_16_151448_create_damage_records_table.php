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
        Schema::create('damage_records', function (Blueprint $table) {
            $table->id('damage_id');
            $table->string('vin');
            $table->string('damage_type');
            $table->string('location');
            $table->string('description');
            $table->decimal('cost', 10, 2);
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_records');
    }
};
