<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            ['type_name' => 'Sedan'],
            ['type_name' => 'SUV'],
            ['type_name' => 'Hatchback'],
            ['type_name' => 'Coupe'],
            ['type_name' => 'Convertible'],
            ['type_name' => 'Wagon'],
            ['type_name' => 'Truck'],
            ['type_name' => 'Van'],
            ['type_name' => 'Minivan'],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}
