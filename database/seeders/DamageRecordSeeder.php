<?php

namespace Database\Seeders;

use App\Models\DamageRecord;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DamageRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $damageTypes = ['Scratch', 'Dent', 'Collision', 'Hail', 'Water', 'Fire', 'Mechanical', 'Electrical'];

        foreach ($vehicles as $vehicle) {
            // Only create damage records for some vehicles
            $damageCount = rand(0, 3);

            for ($i = 0; $i < $damageCount; $i++) {

                DamageRecord::create([
                    'vin' => $vehicle->vin,
                    'damage_type' => $damageTypes[array_rand($damageTypes)],
                    'description' => fake()->paragraph(),
                    'cost' => rand(100, 5000),
                    'location' => ['Front', 'Rear', 'Driver Side', 'Passenger Side', 'Roof', 'Undercarriage'][array_rand(['Front', 'Rear', 'Driver Side', 'Passenger Side', 'Roof', 'Undercarriage'])],
                ]);
            }
        }
    }
}
