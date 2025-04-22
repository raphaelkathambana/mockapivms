<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FuelType;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            ['fuel_name' => 'Gasoline'],
            ['fuel_name' => 'Diesel'],
            ['fuel_name' => 'Electric'],
            ['fuel_name' => 'Hybrid'],
            ['fuel_name' => 'Plug-in Hybrid'],
            ['fuel_name' => 'Natural Gas'],
            ['fuel_name' => 'Hydrogen'],
        ];

        foreach ($fuelTypes as $fuelType) {
            FuelType::create($fuelType);
        }
    }
}
