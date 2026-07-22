<?php

namespace Database\Factories;

use App\Models\Floor;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    private static int $roomNumberCounter = 100;

    public function definition(): array
    {
        self::$roomNumberCounter++;

        return [
            'hotel_id' => Hotel::factory(),
            'room_type_id' => RoomType::factory(),
            'floor_id' => Floor::factory(),
            'number' => (string) self::$roomNumberCounter,
            'status' => fake()->randomElement(['available', 'available', 'available', 'occupied', 'dirty', 'maintenance']),
            'condition' => fake()->randomElement(['excellent', 'good', 'good', 'good', 'fair']),
            'notes' => fake()->optional(0.3)->sentence(),
            'is_active' => true,
            'settings' => [
                'max_occupancy' => fake()->numberBetween(2, 6),
                'smoking_allowed' => fake()->boolean(20),
                'pet_friendly' => fake()->boolean(10),
            ],
        ];
    }

    public function available(): Factory
    {
        return $this->state(fn() => ['status' => 'available']);
    }

    public function occupied(): Factory
    {
        return $this->state(fn() => ['status' => 'occupied']);
    }

    public function maintenance(): Factory
    {
        return $this->state(fn() => ['status' => 'maintenance']);
    }
}
