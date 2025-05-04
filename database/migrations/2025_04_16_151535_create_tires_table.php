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
        Schema::create('tires', function (Blueprint $table) {
            $table->id('tire_id');
            $table->string('vin');
            $table->decimal('tread_depth', 5, 2);
            $table->string('rim_type');
            $table->enum('position', ['left-front', 'right-front', 'left-back', 'right-back']);
            $table->enum('tire_type', ['summer', 'winter', 'all-season']);
            $table->enum('rim_status', ['aftermarket', 'original']);
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tires');
    }
};
