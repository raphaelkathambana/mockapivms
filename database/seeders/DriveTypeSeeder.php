<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DriveType;

class DriveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driveTypes = [
            ['drive_type_name' => 'FWD (Front-Wheel Drive)'],
            ['drive_type_name' => 'RWD (Rear-Wheel Drive)'],
            ['drive_type_name' => 'AWD (All-Wheel Drive)'],
            ['drive_type_name' => '4WD (Four-Wheel Drive)'],
        ];

        foreach ($driveTypes as $driveType) {
            DriveType::create($driveType);
        }
    }
}
