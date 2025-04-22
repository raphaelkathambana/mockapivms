<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transmission;

class TransmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transmissions = [
            ['type' => 'Manual'],
            ['type' => 'Automatic'],
            ['type' => 'CVT'],
            ['type' => 'Dual-Clutch'],
            ['type' => 'Semi-Automatic'],
            ['type' => 'Single-Speed (Electric)'],
        ];

        foreach ($transmissions as $transmission) {
            Transmission::create($transmission);
        }
    }
}
