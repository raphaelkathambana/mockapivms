<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerTypeSeeder::class,
            OwnerTypeSeeder::class,
            FuelTypeSeeder::class,
            ManufacturerSeeder::class,
            VehicleModelSeeder::class,
            TransmissionSeeder::class,
            VehicleTypeSeeder::class,
            DriveTypeSeeder::class,
            AddressSeeder::class,
            EmployeeSeeder::class,
            BuyerSeeder::class,
            SellerSeeder::class,
            VehicleSeeder::class,
            VehicleConfirmationSeeder::class,
            EngineSpecificationSeeder::class,
            PurchaseContractSeeder::class,
            ProcurementContractSeeder::class,
            VehicleRegistrationSeeder::class,
            DamageRecordSeeder::class,
            TireSeeder::class,
            InspectionRecordSeeder::class,
            AdditionalEquipmentSeeder::class,
            SalesLogSeeder::class,
            PurchaseLogSeeder::class,
            BlacklistSeeder::class,
        ]);
    }
}
