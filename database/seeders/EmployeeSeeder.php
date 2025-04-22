<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'first_name' => 'Thomas',
                'last_name' => 'Müller',
                'role' => 'Sales Manager',
                'email' => 'thomas.mueller@example.com',
                'phone_number' => '+49 123 456789',
            ],
            [
                'first_name' => 'Anna',
                'last_name' => 'Schmidt',
                'role' => 'Sales Representative',
                'email' => 'anna.schmidt@example.com',
                'phone_number' => '+49 123 456790',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Weber',
                'role' => 'Sales Representative',
                'email' => 'michael.weber@example.com',
                'phone_number' => '+49 123 456791',
            ],
            [
                'first_name' => 'Laura',
                'last_name' => 'Fischer',
                'role' => 'Customer Service',
                'email' => 'laura.fischer@example.com',
                'phone_number' => '+49 123 456792',
            ],
            [
                'first_name' => 'Markus',
                'last_name' => 'Becker',
                'role' => 'Finance Manager',
                'email' => 'markus.becker@example.com',
                'phone_number' => '+49 123 456793',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
