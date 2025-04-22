<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseContract;
use App\Models\Vehicle;
use App\Models\Buyer;
use App\Models\Employee;

class PurchaseContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soldVehicles = Vehicle::where('status', 'Sold')->get();
        $employees = Employee::all();

        foreach ($soldVehicles as $vehicle) {
            PurchaseContract::create([
                'vin' => $vehicle->vin,
                'customer_id' => $vehicle->buyer_id,
                'employee_id' => $employees->random()->employee_id,
                'contract_date' => now()->subDays(rand(1, 30)),
                'digital_signature' => null,
            ]);
        }
    }
}
