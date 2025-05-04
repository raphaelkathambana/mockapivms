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

        $positions = [
            'left-front',
            'right-front',
            'left-back',
            'right-back'
        ];

        $tireTypes = ['summer', 'winter', 'all-season'];
        $rimStatuses = ['aftermarket', 'original'];

        foreach ($vehicles as $vehicle) {
            // Create 4 tires for each vehicle
            foreach ($positions as $position) {
                Tire::create([
                    'vin' => $vehicle->vin,
                    'rim_type' => $rimTypes[array_rand($rimTypes)],
                    'tread_depth' => rand(2, 10),
                    'position' => $position,
                    'tire_type' => $tireTypes[array_rand($tireTypes)],
                    'rim_status' => $rimStatuses[array_rand($rimStatuses)]
                ]);
            }
        }
    }
}
