<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seller;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            [
                'first_name' => 'Klaus',
                'last_name' => 'Bauer',
                'gender' => 'Male',
                'address_id' => 6,
                'phone_number' => '+49 176 67890123',
                'email' => 'klaus.bauer@example.com',
                'customer_type_id' => 1, // Private
            ],
            [
                'first_name' => 'Sabine',
                'last_name' => 'Koch',
                'gender' => 'Female',
                'address_id' => 7,
                'phone_number' => '+49 176 78901234',
                'email' => 'sabine.koch@example.com',
                'customer_type_id' => 1, // Private
            ],
            [
                'first_name' => 'Jürgen',
                'last_name' => 'Richter',
                'gender' => 'Male',
                'address_id' => 8,
                'phone_number' => '+49 176 89012345',
                'email' => 'juergen.richter@example.com',
                'customer_type_id' => 2, // Business
            ],
            [
                'first_name' => 'Monika',
                'last_name' => 'Wolf',
                'gender' => 'Female',
                'address_id' => 9,
                'phone_number' => '+49 176 90123456',
                'email' => 'monika.wolf@example.com',
                'customer_type_id' => 1, // Private
            ],
            [
                'first_name' => 'Dieter',
                'last_name' => 'Schäfer',
                'gender' => 'Male',
                'address_id' => 10,
                'phone_number' => '+49 176 01234567',
                'email' => 'dieter.schaefer@example.com',
                'customer_type_id' => 3, // Dealer
            ],
        ];

        foreach ($sellers as $seller) {
            Seller::create($seller);
        }
    }
}
