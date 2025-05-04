<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleConfirmation;
use Illuminate\Database\Seeder;

class VehicleConfirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            VehicleConfirmation::create([
                'vin' => $vehicle->vin,
                'num_previous_owners' => $vehicle->num_previous_owners,
                'warranty_status' => rand(0, 1) ? 'Valid' : 'Expired', // Randomly assign warranty status
                'inspection_status' => rand(0, 1) ? 'Passed' : 'Pending', // Randomly assign inspection status
                'seller_id' => $vehicle->seller_id,
            ]);
        }
    }
}
