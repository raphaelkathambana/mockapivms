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
        Schema::create('sales_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->timestamp('timestamp');
            $table->string('vin');
            $table->unsignedBigInteger('employee_id');
            $table->string('customer_number');
            $table->string('status_change');
            $table->timestamps();

            $table->foreign('vin')->references('vin')->on('vehicles');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('customer_number')->references('customer_number')->on('buyers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_logs');
    }
};
