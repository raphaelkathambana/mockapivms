<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buyer;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyers = [
            [
                'customer_number' => 'B00001',
                'first_name' => 'Hans',
                'last_name' => 'Wagner',
                'address_id' => 1,
                'phone_number' => '+49 176 12345678',
                'email' => 'hans.wagner@example.com',
            ],
            [
                'customer_number' => 'B00002',
                'first_name' => 'Maria',
                'last_name' => 'Schneider',
                'address_id' => 2,
                'phone_number' => '+49 176 23456789',
                'email' => 'maria.schneider@example.com',
            ],
            [
                'customer_number' => 'B00003',
                'first_name' => 'Peter',
                'last_name' => 'Hoffmann',
                'address_id' => 3,
                'phone_number' => '+49 176 34567890',
                'email' => 'peter.hoffmann@example.com',
            ],
            [
                'customer_number' => 'B00004',
                'first_name' => 'Julia',
                'last_name' => 'Meyer',
                'address_id' => 4,
                'phone_number' => '+49 176 45678901',
                'email' => 'julia.meyer@example.com',
            ],
            [
                'customer_number' => 'B00005',
                'first_name' => 'Stefan',
                'last_name' => 'Schulz',
                'address_id' => 5,
                'phone_number' => '+49 176 56789012',
                'email' => 'stefan.schulz@example.com',
            ],
        ];

        foreach ($buyers as $buyer) {
            Buyer::create($buyer);
        }
    }
}
