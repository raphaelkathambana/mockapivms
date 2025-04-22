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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->string('vin')->primary();
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('model_id');
            $table->date('first_registration')->nullable();
            $table->integer('mileage');
            $table->unsignedBigInteger('transmission_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('drive_type_id');
            $table->string('color');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('num_previous_owners');
            $table->unsignedBigInteger('owner_type_id');
            $table->date('general_inspection_next_due_date')->nullable();
            $table->date('evaluation_date')->nullable();
            $table->date('last_edited_date')->nullable();
            $table->integer('days_on_stock')->default(0);
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('status');
            $table->string('customer_number')->nullable();
            $table->text('additional_info')->nullable();
            $table->timestamps();

            $table->foreign('manufacturer_id')->references('manufacturer_id')->on('manufacturers');
            $table->foreign('model_id')->references('model_id')->on('vehicle_models');
            $table->foreign('transmission_id')->references('transmission_id')->on('transmissions');
            $table->foreign('type_id')->references('type_id')->on('vehicle_types');
            $table->foreign('drive_type_id')->references('drive_type_id')->on('drive_types');
            $table->foreign('owner_type_id')->references('owner_type_id')->on('owner_types');
            $table->foreign('buyer_id')->references('customer_id')->on('buyers')->nullOnDelete();
            $table->foreign('seller_id')->references('seller_id')->on('sellers')->nullOnDelete();
            $table->foreign('customer_number')->references('customer_number')->on('buyers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
