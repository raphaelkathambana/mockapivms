<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OwnerType;

class OwnerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerTypes = [
            ['type_name' => 'Private Owner'],
            ['type_name' => 'Company Car'],
            ['type_name' => 'Leased Vehicle'],
            ['type_name' => 'Rental'],
            ['type_name' => 'Demo Vehicle'],
        ];

        foreach ($ownerTypes as $ownerType) {
            OwnerType::create($ownerType);
        }
    }
}
