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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('address_id');
            $table->string('phone_number');
            $table->string('email');
            $table->timestamps();

            $table->foreign('address_id')->references('address_id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
