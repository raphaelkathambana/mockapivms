<?php

namespace Database\Seeders;

use App\Models\ProcurementContract;
use App\Models\Vehicle;
use App\Models\Seller;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProcurementContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all VINs to ensure every vehicle has a contract
        $vehicles = Vehicle::all();
        $sellerIds = Seller::pluck('seller_id')->toArray();
        $employeeIds = Employee::pluck('employee_id')->toArray();

        // Create a procurement contract for each vehicle
        foreach ($vehicles as $vehicle) {
            ProcurementContract::create([
                'vin' => $vehicle->vin,
                'seller_id' => $sellerIds[array_rand($sellerIds)],
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'contract_date' => Carbon::now()->subDays(rand(1, 90)),
                'digital_signature' => null,
                'purchase_amount' => rand(10000, 50000) / 100, // Random amount between $100 and $500
            ]);
        }
    }
}
