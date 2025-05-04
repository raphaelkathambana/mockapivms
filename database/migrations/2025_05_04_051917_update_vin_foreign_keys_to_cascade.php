<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables with VIN foreign keys that need to be updated
     */
    protected $tables = [
        'vehicle_registrations',
        'damage_records',
        'tires',
        'inspection_records',
        'additional_equipment',
        'engine_specifications',
        'procurement_contracts',
        'purchase_contracts',
        'sales_logs',
        'purchase_logs',
        'vehicle_confirmations'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop all existing foreign key constraints and recreate with cascade
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['vin']);
            });
        }

        // Add new foreign key constraints with cascade on update and delete
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('vin')
                      ->references('vin')
                      ->on('vehicles')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original constraints without cascade
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Drop the cascading constraints
                $table->dropForeign(['vin']);
            });
        }

        // Add back the original constraints without cascade
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('vin')
                      ->references('vin')
                      ->on('vehicles');
            });
        }
    }
};
