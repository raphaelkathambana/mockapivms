<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleRegistration;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VehicleRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            // Only create registrations for some vehicles
            if (rand(0, 10) > 3) {
                VehicleRegistration::create([
                    'vin' => $vehicle->vin,
                    'sepa_data' => fake()->uuid(),
                    'custom_license_plate' => rand(0, 1) ? strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)) . '-' . rand(100, 999) : null,
                    'delivery_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                ]);
            }
        }
    }
}
