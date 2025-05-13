<?php

namespace Database\Seeders;

use App\Models\PurchaseLog;
use App\Models\Vehicle;
use App\Models\Seller;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PurchaseLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing VINs, seller IDs, and employee IDs for reference
        $vehicles = Vehicle::all();
        $sellers = Seller::all();
        $employeeIds = Employee::pluck('employee_id')->toArray();

        // Create complete purchase cycle for each vehicle
        foreach ($vehicles as $vehicle) {
            $seller = $sellers->random();
            $employeeId = $employeeIds[array_rand($employeeIds)];

            // Base timestamp for this vehicle (30-90 days ago)
            $baseTimestamp = Carbon::now()->subDays(rand(30, 90));

            // Create a single entry with the new status format
            PurchaseLog::create([
                'timestamp' => $baseTimestamp->addHours(rand(1, 8)),
                'vin' => $vehicle->vin,
                'employee_id' => $employeeId,
                'seller_id' => $seller->seller_id,
                'status_change' => "vehicle {$vehicle->vin} purchased from {$seller->name}",
            ]);
        }
    }
}
