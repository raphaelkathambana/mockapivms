<?php

namespace Database\Seeders;

use App\Models\AdditionalEquipment;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdditionalEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $equipmentTypes = [
            'Navigation System', 'Sunroof', 'Roof Rack', 'Tow Package',
            'Premium Sound System', 'Backup Camera', 'Parking Sensors',
            'Heated Seats', 'Leather Interior', 'Alloy Wheels',
            'Remote Start', 'Bluetooth', 'USB Ports', 'Wireless Charging'
        ];
        $equipmentConditions = [
            'New', 'Used', 'Refurbished'
        ];

        foreach ($vehicles as $vehicle) {
            // Add 0-5 pieces of additional equipment to each vehicle
            $equipmentCount = rand(0, 5);

            // Only proceed if we want to add at least one piece of equipment
            if ($equipmentCount > 0) {
                $selectedEquipment = array_rand($equipmentTypes, min($equipmentCount, count($equipmentTypes)));

                // Make sure $selectedEquipment is always an array
                if (!is_array($selectedEquipment)) {
                    $selectedEquipment = [$selectedEquipment];
                }

                foreach ($selectedEquipment as $equipmentIndex) {
                    AdditionalEquipment::create([
                        'vin' => $vehicle->vin,
                        'equipment_description' => $equipmentTypes[$equipmentIndex],
                        'condition' => $equipmentConditions[array_rand($equipmentConditions)]
                    ]);
                }
            }
        }
    }
}
