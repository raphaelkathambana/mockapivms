<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manufacturer;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manufacturers = [
            ['name' => 'Audi', 'country_of_origin' => 'Germany'],
            ['name' => 'BMW', 'country_of_origin' => 'Germany'],
            ['name' => 'Ford', 'country_of_origin' => 'USA'],
            ['name' => 'Honda', 'country_of_origin' => 'Japan'],
            ['name' => 'Hyundai', 'country_of_origin' => 'South Korea'],
            ['name' => 'Kia', 'country_of_origin' => 'South Korea'],
            ['name' => 'Mazda', 'country_of_origin' => 'Japan'],
            ['name' => 'Mercedes-Benz', 'country_of_origin' => 'Germany'],
            ['name' => 'Nissan', 'country_of_origin' => 'Japan'],
            ['name' => 'Toyota', 'country_of_origin' => 'Japan'],
            ['name' => 'Volkswagen', 'country_of_origin' => 'Germany'],
            ['name' => 'Volvo', 'country_of_origin' => 'Sweden'],
            ['name' => 'Tesla', 'country_of_origin' => 'USA'],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }
    }
}
