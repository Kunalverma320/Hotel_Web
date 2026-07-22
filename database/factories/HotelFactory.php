<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        $name = fake()->unique()->company() . ' Hotel';

        return [
            'company_id' => Company::factory(),
            'branch_id' => null,
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => fake()->paragraph(),
            'star_rating' => fake()->numberBetween(3, 5),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'logo' => null,
            'cover_image' => null,
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'check_in_time' => '15:00',
            'check_out_time' => '11:00',
            'is_active' => true,
            'settings' => [
                'currency' => 'USD',
                'tax_rate' => 18,
                'default_room_status' => 'available',
            ],
        ];
    }

    public function inactive(): Factory
    {
        return $this->state(fn() => ['is_active' => false]);
    }
}
