<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'secondary_phone' => fake()->optional(0.3)->phoneNumber(),
            'date_of_birth' => fake()->optional(0.7)->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other', null]),
            'nationality' => fake()->optional(0.8)->countryCode(),
            'id_type' => fake()->optional(0.5)->randomElement(['passport', 'drivers_license', 'national_id']),
            'id_number' => fake()->optional(0.5)->bothify('??######'),
            'company_name' => fake()->optional(0.3)->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'notes' => fake()->optional(0.4)->sentence(),
            'is_blacklisted' => fake()->boolean(2),
            'blacklist_reason' => null,
            'loyalty_points' => fake()->numberBetween(0, 5000),
            'loyalty_tier' => fake()->randomElement(['regular', 'bronze', 'silver', 'gold', 'platinum']),
            'total_stays' => fake()->numberBetween(0, 50),
            'total_spent' => fake()->randomFloat(2, 0, 50000),
            'metadata' => [
                'preferred_room_type' => fake()->randomElement(['standard', 'deluxe', 'suite']),
                'preferred_floor' => fake()->randomElement(['low', 'medium', 'high']),
                'newsletter_subscribed' => fake()->boolean(60),
            ],
        ];
    }

    public function blacklisted(): Factory
    {
        return $this->state(fn() => [
            'is_blacklisted' => true,
            'blacklist_reason' => fake()->sentence(),
        ]);
    }

    public function vip(): Factory
    {
        return $this->state(fn() => [
            'loyalty_tier' => 'platinum',
            'loyalty_points' => fake()->numberBetween(5000, 50000),
            'total_stays' => fake()->numberBetween(20, 100),
            'total_spent' => fake()->randomFloat(2, 10000, 100000),
        ]);
    }
}
