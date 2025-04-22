<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EngineSpecification;
use App\Models\Vehicle;

class EngineSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        $engineSpecs = [
            // Volkswagen Golf
            [
                'vin' => $vehicles[0]->vin,
                'kw' => 110,
                'hp' => 150,
                'ccm' => 1498,
                'fuel_type_id' => 1, // Gasoline
            ],
            // BMW 3 Series
            [
                'vin' => $vehicles[1]->vin,
                'kw' => 140,
                'hp' => 190,
                'ccm' => 1998,
                'fuel_type_id' => 1, // Gasoline
            ],
            // Audi A4
            [
                'vin' => $vehicles[2]->vin,
                'kw' => 150,
                'hp' => 204,
                'ccm' => 1984,
                'fuel_type_id' => 1, // Gasoline
            ],
            // Honda Civic
            [
                'vin' => $vehicles[3]->vin,
                'kw' => 95,
                'hp' => 129,
                'ccm' => 1498,
                'fuel_type_id' => 1, // Gasoline
            ],
            // Tesla Model 3
            [
                'vin' => $vehicles[4]->vin,
                'kw' => 258,
                'hp' => 351,
                'ccm' => 0,
                'fuel_type_id' => 3, // Electric
            ],
            // Toyota RAV4
            [
                'vin' => $vehicles[5]->vin,
                'kw' => 160,
                'hp' => 218,
                'ccm' => 2487,
                'fuel_type_id' => 4, // Hybrid
            ],
            // Volkswagen Jetta
            [
                'vin' => $vehicles[6]->vin,
                'kw' => 110,
                'hp' => 150,
                'ccm' => 1498,
                'fuel_type_id' => 1, // Gasoline
            ],
            // BMW 5 Series
            [
                'vin' => $vehicles[7]->vin,
                'kw' => 185,
                'hp' => 252,
                'ccm' => 1998,
                'fuel_type_id' => 1, // Gasoline
            ],
            // Hyundai Elantra
            [
                'vin' => $vehicles[8]->vin,
                'kw' => 110,
                'hp' => 150,
                'ccm' => 1598,
                'fuel_type_id' => 1, // Gasoline
            ],
            // Mercedes-Benz C-Class
            [
                'vin' => $vehicles[9]->vin,
                'kw' => 145,
                'hp' => 197,
                'ccm' => 1991,
                'fuel_type_id' => 1, // Gasoline
            ],
        ];

        foreach ($engineSpecs as $spec) {
            EngineSpecification::create($spec);
        }
    }
}
