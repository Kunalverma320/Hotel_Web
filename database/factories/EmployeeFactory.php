<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'designation_id' => Designation::factory(),
            'employee_id' => strtoupper(fake()->unique()->bothify('EMP###??')),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->date('Y-m-d', '-25 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relationship' => fake()->randomElement(['Spouse', 'Parent', 'Sibling', 'Friend']),
            'joining_date' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'confirmation_date' => fake()->optional(0.7)->dateTimeBetween('-4 years', '-1 day'),
            'salary' => fake()->randomFloat(2, 30000, 120000),
            'bank_name' => fake()->randomElement(['Chase', 'Bank of America', 'Wells Fargo', 'Citi']),
            'bank_account_number' => fake()->bankAccountNumber(),
            'tax_id' => fake()->bothify('TAX-#########'),
            'social_security_number' => fake()->bothify('###-##-####'),
            'profile_photo' => null,
            'status' => fake()->randomElement(['active', 'active', 'active', 'on_leave', 'resigned']),
            'resignation_date' => null,
            'last_working_date' => null,
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function active(): Factory
    {
        return $this->state(fn() => ['status' => 'active']);
    }

    public function resigned(): Factory
    {
        return $this->state(fn() => [
            'status' => 'resigned',
            'resignation_date' => fake()->dateTimeBetween('-3 months', '-1 day'),
            'last_working_date' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}
