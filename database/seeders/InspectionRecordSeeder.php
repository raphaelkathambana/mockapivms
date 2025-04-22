<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\InspectionRecord;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InspectionRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            // Only create inspection records for some vehicles
            $inspectionCount = rand(0, 3);

            for ($i = 0; $i < $inspectionCount; $i++) {
                $inspectionDate = Carbon::now()->subMonths(rand(1, 12));

                InspectionRecord::create([
                    'vin' => $vehicle->vin,
                    'inspection_date' => $inspectionDate,
                    'mileage_at_inspection' => rand(1000, 200000),
                    'service_details' => fake()->paragraph(),
                ]);
            }
        }
    }
}
