<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerType;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerTypes = [
            ['type_name' => 'Private'],
            ['type_name' => 'Business'],
            ['type_name' => 'Dealer'],
            ['type_name' => 'Government'],
            ['type_name' => 'Non-profit'],
        ];

        foreach ($customerTypes as $customerType) {
            CustomerType::create($customerType);
        }
    }
}
