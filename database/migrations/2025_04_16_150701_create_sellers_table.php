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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id('seller_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->unsignedBigInteger('address_id');
            $table->string('phone_number');
            $table->string('email');
            $table->unsignedBigInteger('customer_type_id');
            $table->timestamps();

            $table->foreign('address_id')->references('address_id')->on('addresses');
            $table->foreign('customer_type_id')->references('customer_type_id')->on('customer_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
