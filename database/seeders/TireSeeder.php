<?php

namespace Database\Seeders;

use App\Models\Tire;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $rimTypes = [
            'Steel',
            'Aluminum',
            'Alloy',
            'Magnesium',
            'Carbon Fiber',
        ];

        foreach ($vehicles as $vehicle) {
            // Create 4 or 5 tires for each vehicle (4 regular + possibly 1 spare)
            $tireCount = rand(0, 10) > 3 ? 5 : 4;

            for ($i = 0; $i < $tireCount; $i++) {
                Tire::create([
                    'vin' => $vehicle->vin,
                    'rim_type' => $rimTypes[array_rand($rimTypes)],
                    'tread_depth' => rand(2, 10),
                ]);
            }
        }
    }
}
