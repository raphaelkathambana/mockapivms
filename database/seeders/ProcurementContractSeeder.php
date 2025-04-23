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
        // Get existing VINs, seller IDs, and employee IDs for reference
        $vins = Vehicle::pluck('vin')->toArray();
        $sellerIds = Seller::pluck('seller_id')->toArray();
        $employeeIds = Employee::pluck('employee_id')->toArray();

        // Create 10 sample procurement contracts
        for ($i = 0; $i < 10; $i++) {
            ProcurementContract::create([
                'vin' => $vins[array_rand($vins)],
                'seller_id' => $sellerIds[array_rand($sellerIds)],
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'contract_date' => Carbon::now()->subDays(rand(1, 90)),
                'digital_signature' => null, // You might want to add actual binary data for testing
                'purchase_amount' => rand(10000, 50000) / 100, // Random amount between $100 and $500
            ]);
        }
    }
}
