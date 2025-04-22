<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Employee;
use App\Models\SalesLog;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SalesLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::where('status', 'Sold')->get();
        $buyers = Buyer::all();
        $employees = Employee::all();

        foreach ($vehicles as $vehicle) {
            $saleDate = Carbon::now()->subMonths(rand(1, 12));
            $customerNumber = $buyers->random()->customer_number;
            SalesLog::create([
                'vin' => $vehicle->vin,
                'customer_number' => $customerNumber,
                'employee_id' => $employees->random()->employee_id,
                'status_change' => 'Sold to customer '.$customerNumber,
                'timestamp' => $saleDate,
            ]);
        }
    }
}
