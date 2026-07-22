<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'logo' => null,
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'currency_id' => Currency::factory(),
            'timezone_id' => Timezone::factory(),
            'registration_number' => fake()->bothify('REG-###-????'),
            'tax_id' => fake()->bothify('TAX-#####-###'),
            'is_active' => true,
            'settings' => [
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i',
                'week_starts_on' => 'monday',
                'default_language' => 'en',
                'invoice_prefix' => 'INV-',
                'tax_rate' => 18.00,
                'currency_position' => 'before',
            ],
        ];
    }

    public function inactive(): Factory
    {
        return $this->state(fn() => ['is_active' => false]);
    }
}
