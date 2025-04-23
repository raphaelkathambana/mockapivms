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
        $vins = Vehicle::pluck('vin')->toArray();
        $sellerIds = Seller::pluck('seller_id')->toArray();
        $employeeIds = Employee::pluck('employee_id')->toArray();

        // Possible status changes
        $statusChanges = [
            'Vehicle Inspection',
            'Initial Vehicle Evaluation',
            'Procurement Process Started',
            'Procurement Offer Made',
            'Procurement Offer Accepted',
            'Vehicle Purchased',
            'Vehicle Added to Inventory',
        ];

        // Create 20 sample purchase logs
        for ($i = 0; $i < 20; $i++) {
            PurchaseLog::create([
                'timestamp' => Carbon::now()->subDays(rand(1, 90))->subHours(rand(1, 24)),
                'vin' => $vins[array_rand($vins)],
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'seller_id' => $sellerIds[array_rand($sellerIds)],
                'status_change' => $statusChanges[array_rand($statusChanges)],
            ]);
        }
    }
}
