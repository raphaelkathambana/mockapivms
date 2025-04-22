<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleModel;
use App\Models\Manufacturer;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // Audi
            ['manufacturer_id' => 1, 'model_name' => 'A3'],
            ['manufacturer_id' => 1, 'model_name' => 'A4'],
            ['manufacturer_id' => 1, 'model_name' => 'Q5'],

            // BMW
            ['manufacturer_id' => 2, 'model_name' => '3 Series'],
            ['manufacturer_id' => 2, 'model_name' => '5 Series'],
            ['manufacturer_id' => 2, 'model_name' => 'X3'],

            // Ford
            ['manufacturer_id' => 3, 'model_name' => 'Focus'],
            ['manufacturer_id' => 3, 'model_name' => 'Mustang'],
            ['manufacturer_id' => 3, 'model_name' => 'F-150'],

            // Honda
            ['manufacturer_id' => 4, 'model_name' => 'Civic'],
            ['manufacturer_id' => 4, 'model_name' => 'Accord'],
            ['manufacturer_id' => 4, 'model_name' => 'CR-V'],

            // Hyundai
            ['manufacturer_id' => 5, 'model_name' => 'Elantra'],
            ['manufacturer_id' => 5, 'model_name' => 'Tucson'],
            ['manufacturer_id' => 5, 'model_name' => 'Santa Fe'],

            // Kia
            ['manufacturer_id' => 6, 'model_name' => 'Forte'],
            ['manufacturer_id' => 6, 'model_name' => 'Sportage'],
            ['manufacturer_id' => 6, 'model_name' => 'Sorento'],

            // Mazda
            ['manufacturer_id' => 7, 'model_name' => 'Mazda3'],
            ['manufacturer_id' => 7, 'model_name' => 'Mazda6'],
            ['manufacturer_id' => 7, 'model_name' => 'CX-5'],

            // Mercedes-Benz
            ['manufacturer_id' => 8, 'model_name' => 'C-Class'],
            ['manufacturer_id' => 8, 'model_name' => 'E-Class'],
            ['manufacturer_id' => 8, 'model_name' => 'GLC'],

            // Nissan
            ['manufacturer_id' => 9, 'model_name' => 'Altima'],
            ['manufacturer_id' => 9, 'model_name' => 'Rogue'],
            ['manufacturer_id' => 9, 'model_name' => 'Sentra'],

            // Toyota
            ['manufacturer_id' => 10, 'model_name' => 'Camry'],
            ['manufacturer_id' => 10, 'model_name' => 'Corolla'],
            ['manufacturer_id' => 10, 'model_name' => 'RAV4'],

            // Volkswagen
            ['manufacturer_id' => 11, 'model_name' => 'Golf'],
            ['manufacturer_id' => 11, 'model_name' => 'Jetta'],
            ['manufacturer_id' => 11, 'model_name' => 'Tiguan'],

            // Volvo
            ['manufacturer_id' => 12, 'model_name' => 'S60'],
            ['manufacturer_id' => 12, 'model_name' => 'XC60'],
            ['manufacturer_id' => 12, 'model_name' => 'XC90'],

            // Tesla
            ['manufacturer_id' => 13, 'model_name' => 'Model 3'],
            ['manufacturer_id' => 13, 'model_name' => 'Model S'],
            ['manufacturer_id' => 13, 'model_name' => 'Model X'],
            ['manufacturer_id' => 13, 'model_name' => 'Model Y'],
        ];

        foreach ($models as $model) {
            VehicleModel::create($model);
        }
    }
}
