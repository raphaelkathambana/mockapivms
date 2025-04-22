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
        Schema::create('purchase_contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->string('vin');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('contract_date');
            $table->binary('digital_signature')->nullable();
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
            $table->foreign('customer_id')->references('customer_id')->on('buyers');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_contracts');
    }
};
