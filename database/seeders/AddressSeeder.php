<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = [
            [
                'street' => 'Main Street',
                'house_number' => '123',
                'city' => 'Berlin',
                'postal_code' => '10115',
                'country' => 'Germany',
            ],
            [
                'street' => 'Oak Avenue',
                'house_number' => '456',
                'city' => 'Munich',
                'postal_code' => '80331',
                'country' => 'Germany',
            ],
            [
                'street' => 'Pine Road',
                'house_number' => '789',
                'city' => 'Hamburg',
                'postal_code' => '20095',
                'country' => 'Germany',
            ],
            [
                'street' => 'Cedar Lane',
                'house_number' => '101',
                'city' => 'Frankfurt',
                'postal_code' => '60306',
                'country' => 'Germany',
            ],
            [
                'street' => 'Elm Street',
                'house_number' => '202',
                'city' => 'Cologne',
                'postal_code' => '50667',
                'country' => 'Germany',
            ],
            [
                'street' => 'Maple Drive',
                'house_number' => '303',
                'city' => 'Stuttgart',
                'postal_code' => '70173',
                'country' => 'Germany',
            ],
            [
                'street' => 'Birch Boulevard',
                'house_number' => '404',
                'city' => 'Düsseldorf',
                'postal_code' => '40213',
                'country' => 'Germany',
            ],
            [
                'street' => 'Willow Way',
                'house_number' => '505',
                'city' => 'Leipzig',
                'postal_code' => '04109',
                'country' => 'Germany',
            ],
            [
                'street' => 'Spruce Street',
                'house_number' => '606',
                'city' => 'Dresden',
                'postal_code' => '01067',
                'country' => 'Germany',
            ],
            [
                'street' => 'Fir Avenue',
                'house_number' => '707',
                'city' => 'Hannover',
                'postal_code' => '30159',
                'country' => 'Germany',
            ],
        ];

        foreach ($addresses as $address) {
            Address::create($address);
        }
    }
}
