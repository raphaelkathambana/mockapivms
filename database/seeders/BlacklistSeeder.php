<?php

namespace Database\Seeders;

use App\Models\Blacklist;
use App\Models\Buyer;
use App\Models\Manufacturer;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //black list is a combination of rules for a car that should not be in the system
        // the rule_data has JSON for the combination of vehicle properties that should bnot be in the system

        // sample blacklist rules
        // Mercedes Benz, C-Class, 2018, 4-door sedan, 4-cylinder, 2.0L engine, 9-speed automatic transmission

        $blacklistRules = [
            [
                'rule_name' => 'Mercedes Benz C-Class 2018',
                'rule_data' => json_encode([
                    'manufacturer' => 'Mercedes Benz',
                    'model' => 'C-Class',
                    'year' => 2018,
                    'body_style' => '4-door sedan',
                    'engine_type' => '4-cylinder',
                    'engine_size' => '2.0L engine',
                    'transmission' => '9-speed automatic transmission'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'rule_name' => 'BMW 3 Series 2019',
                'rule_data' => json_encode([
                    'manufacturer' => 'BMW',
                    'model' => '3 Series',
                    'year' => 2019,
                    'body_style' => '4-door sedan',
                    'engine_type' => '6-cylinder',
                    'engine_size' => '3.0L engine',
                    'transmission' => '8-speed automatic transmission'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'rule_name' => 'Audi A4 2020',
                'rule_data' => json_encode([
                    'manufacturer' => 'Audi',
                    'model' => 'A4',
                    'year' => 2020,
                    'body_style' => '4-door sedan',
                    'engine_type' => '4-cylinder',
                    'engine_size' => '2.0L engine',
                    'transmission' => '7-speed automatic transmission'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($blacklistRules as $rule) {
            Blacklist::create([
                'rule_data' => $rule['rule_data'],
                'rejection_reason' => 'This vehicle is blacklisted due to the following rule: ' . $rule['rule_name'],
            ]);
        }
    }
}
