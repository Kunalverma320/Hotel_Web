<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $hotel = Hotel::first();
        if (!$hotel) return;

        $departments = [
            ['name' => 'Front Office', 'description' => 'Front desk and reception', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Housekeeping', 'description' => 'Room cleaning and maintenance', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Food & Beverage', 'description' => 'Restaurant and room service', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Engineering', 'description' => 'Maintenance and repairs', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Sales & Marketing', 'description' => 'Sales and promotions', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Human Resources', 'description' => 'HR and payroll', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Finance', 'description' => 'Accounting and finance', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Security', 'description' => 'Hotel security', 'hotel_id' => $hotel->id, 'status' => true],
            ['name' => 'Spa', 'description' => 'Spa and wellness services', 'hotel_id' => $hotel->id, 'status' => true],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }

        $designations = [
            ['name' => 'General Manager', 'description' => 'Top management', 'status' => true],
            ['name' => 'Assistant Manager', 'description' => 'Middle management', 'status' => true],
            ['name' => 'Department Head', 'description' => 'Department leadership', 'status' => true],
            ['name' => 'Supervisor', 'description' => 'Team supervision', 'status' => true],
            ['name' => 'Staff', 'description' => 'Regular staff', 'status' => true],
        ];

        foreach ($designations as $desig) {
            Designation::firstOrCreate(['name' => $desig['name']], $desig);
        }

        $departmentIds = Department::where('hotel_id', $hotel->id)->pluck('id', 'name');
        $designationIds = Designation::pluck('id', 'name');

        $employees = [
            ['emp' => 'EMP001', 'first_name' => 'Alice', 'last_name' => 'Johnson', 'email' => 'alice.johnson@hotel.com', 'phone' => '+1-212-555-8001', 'dept' => 'Front Office', 'desig' => 'General Manager', 'salary' => 95000, 'user_email' => 'gm@hotelms.com', 'role' => 'manager'],
            ['emp' => 'EMP002', 'first_name' => 'Bob', 'last_name' => 'Williams', 'email' => 'bob.williams@hotel.com', 'phone' => '+1-212-555-8002', 'dept' => 'Front Office', 'desig' => 'Assistant Manager', 'salary' => 65000],
            ['emp' => 'EMP003', 'first_name' => 'Carol', 'last_name' => 'Davis', 'email' => 'carol.davis@hotel.com', 'phone' => '+1-212-555-8003', 'dept' => 'Front Office', 'desig' => 'Supervisor', 'salary' => 45000],
            ['emp' => 'EMP004', 'first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david.brown@hotel.com', 'phone' => '+1-212-555-8004', 'dept' => 'Housekeeping', 'desig' => 'Department Head', 'salary' => 52000],
            ['emp' => 'EMP005', 'first_name' => 'Eva', 'last_name' => 'Martinez', 'email' => 'eva.martinez@hotel.com', 'phone' => '+1-212-555-8005', 'dept' => 'Housekeeping', 'desig' => 'Supervisor', 'salary' => 38000],
            ['emp' => 'EMP006', 'first_name' => 'Frank', 'last_name' => 'Wilson', 'email' => 'frank.wilson@hotel.com', 'phone' => '+1-212-555-8006', 'dept' => 'Food & Beverage', 'desig' => 'Department Head', 'salary' => 55000],
            ['emp' => 'EMP007', 'first_name' => 'Grace', 'last_name' => 'Taylor', 'email' => 'grace.taylor@hotel.com', 'phone' => '+1-212-555-8007', 'dept' => 'Food & Beverage', 'desig' => 'Supervisor', 'salary' => 42000],
            ['emp' => 'EMP008', 'first_name' => 'Henry', 'last_name' => 'Anderson', 'email' => 'henry.anderson@hotel.com', 'phone' => '+1-212-555-8008', 'dept' => 'Engineering', 'desig' => 'Department Head', 'salary' => 58000],
            ['emp' => 'EMP009', 'first_name' => 'Ivy', 'last_name' => 'Thomas', 'email' => 'ivy.thomas@hotel.com', 'phone' => '+1-212-555-8009', 'dept' => 'Sales & Marketing', 'desig' => 'Department Head', 'salary' => 62000],
            ['emp' => 'EMP010', 'first_name' => 'Jack', 'last_name' => 'Jackson', 'email' => 'jack.jackson@hotel.com', 'phone' => '+1-212-555-8010', 'dept' => 'Human Resources', 'desig' => 'Department Head', 'salary' => 56000],
            ['emp' => 'EMP011', 'first_name' => 'Kate', 'last_name' => 'White', 'email' => 'kate.white@hotel.com', 'phone' => '+1-212-555-8011', 'dept' => 'Finance', 'desig' => 'Department Head', 'salary' => 65000],
            ['emp' => 'EMP012', 'first_name' => 'Leo', 'last_name' => 'Harris', 'email' => 'leo.harris@hotel.com', 'phone' => '+1-212-555-8012', 'dept' => 'Security', 'desig' => 'Department Head', 'salary' => 48000],
            ['emp' => 'EMP013', 'first_name' => 'Mia', 'last_name' => 'Clark', 'email' => 'mia.clark@hotel.com', 'phone' => '+1-212-555-8013', 'dept' => 'Front Office', 'desig' => 'Staff', 'salary' => 32000],
            ['emp' => 'EMP014', 'first_name' => 'Noah', 'last_name' => 'Lewis', 'email' => 'noah.lewis@hotel.com', 'phone' => '+1-212-555-8014', 'dept' => 'Food & Beverage', 'desig' => 'Staff', 'salary' => 30000],
            ['emp' => 'EMP015', 'first_name' => 'Olivia', 'last_name' => 'Walker', 'email' => 'olivia.walker@hotel.com', 'phone' => '+1-212-555-8015', 'dept' => 'Housekeeping', 'desig' => 'Staff', 'salary' => 28000],
            ['emp' => 'EMP016', 'first_name' => 'Sophia', 'last_name' => 'Davis', 'email' => 'sophia.davis@hotel.com', 'phone' => '+1-212-555-8016', 'dept' => 'Spa', 'desig' => 'Staff', 'salary' => 31000],
        ];

        foreach ($employees as $data) {
            $role = $data['role'] ?? null;
            $userEmail = $data['user_email'] ?? null;

            $empData = [
                'hotel_id' => $hotel->id,
                'employee_code' => $data['emp'],
                'department_id' => $departmentIds[$data['dept']] ?? null,
                'designation_id' => $designationIds[$data['desig']] ?? null,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'gender' => fake()->randomElement(['male', 'female']),
                'date_of_birth' => fake()->dateTimeBetween('-50 years', '-22 years'),
                'date_of_joining' => fake()->dateTimeBetween('-3 years', '-1 month'),
                'basic_salary' => $data['salary'],
                'status' => 'active',
            ];

            // Create a user for every employee so they can login and be assigned tasks
            $user = User::firstOrCreate(
                ['email' => $data['email'] ?? $userEmail],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'company_id' => $hotel->company_id,
                    'hotel_id' => $hotel->id,
                    'phone' => $data['phone'] ?? null,
                    'status' => true,
                ]
            );

            // Assign Spatie role based on department
            $roleName = $role;
            if (!$roleName) {
                if ($data['dept'] === 'Housekeeping') {
                    $roleName = 'housekeeping';
                } elseif ($data['dept'] === 'Engineering') {
                    $roleName = 'maintenance';
                } elseif ($data['dept'] === 'Front Office') {
                    $roleName = ($data['desig'] === 'General Manager') ? 'manager' : 'receptionist';
                } elseif ($data['dept'] === 'Food & Beverage') {
                    $roleName = 'restaurant';
                } elseif ($data['dept'] === 'Spa') {
                    $roleName = 'receptionist';
                }
            }

            if ($roleName) {
                $user->assignRole($roleName);
            }

            $empData['user_id'] = $user->id;

            Employee::firstOrCreate(['employee_code' => $data['emp']], $empData);
        }
    }
}
