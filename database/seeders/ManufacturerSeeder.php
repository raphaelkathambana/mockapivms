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
            ['name' => 'Audi'],
            ['name' => 'BMW'],
            ['name' => 'Ford'],
            ['name' => 'Honda'],
            ['name' => 'Hyundai'],
            ['name' => 'Kia'],
            ['name' => 'Mazda'],
            ['name' => 'Mercedes-Benz'],
            ['name' => 'Nissan'],
            ['name' => 'Toyota'],
            ['name' => 'Volkswagen'],
            ['name' => 'Volvo'],
            ['name' => 'Tesla'],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }
    }
}
