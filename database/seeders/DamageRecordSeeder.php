<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DamageRecord;
use App\Models\Vehicle;

class DamageRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            // Create 1-3 damage records per vehicle
            $numRecords = rand(1, 3);

            for ($i = 0; $i < $numRecords; $i++) {
                DamageRecord::create([
                    'vin' => $vehicle->vin,
                    'damage_type' => ['Dent', 'Scruff', 'Damage'][rand(0, 2)],
                    'location' => ['Front', 'Rear', 'Driver Side', 'Passenger Side'][rand(0, 3)],
                    'description' => 'Sample damage ' . ($i + 1) . ' on vehicle ' . $vehicle->vin,
                    'cost' => rand(100, 5000) + (rand(0, 99) / 100),
                ]);
            }
        }
    }
}
