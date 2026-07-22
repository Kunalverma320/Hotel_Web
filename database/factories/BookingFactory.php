<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('-1 month', '+1 month');
        $nights = fake()->numberBetween(1, 14);
        $rate = fake()->randomFloat(2, 100, 500);
        $tax = $rate * 0.18;
        $totalAmount = $rate + $tax;

        return [
            'hotel_id' => Hotel::factory(),
            'room_type_id' => RoomType::factory(),
            'guest_id' => Guest::factory(),
            'booking_number' => 'BK-' . strtoupper(fake()->unique()->bothify('##??##??')),
            'source' => fake()->randomElement(['website', 'app', 'phone', 'walk-in', 'travel_agent']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']),
            'check_in_date' => $checkIn,
            'check_out_date' => (clone $checkIn)->modify("+{$nights} days"),
            'actual_check_in' => null,
            'actual_check_out' => null,
            'adults' => fake()->numberBetween(1, 4),
            'children' => fake()->numberBetween(0, 3),
            'infants' => fake()->numberBetween(0, 2),
            'nights' => $nights,
            'room_rate' => $rate,
            'total_amount' => $totalAmount,
            'paid_amount' => fake()->randomFloat(2, 0, $totalAmount),
            'discount_amount' => fake()->optional(0.3)->randomFloat(2, 10, 100),
            'tax_amount' => $tax,
            'currency_code' => 'USD',
            'exchange_rate' => 1.0000,
            'special_requests' => fake()->optional(0.4)->sentence(),
            'internal_notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function pending(): Factory
    {
        return $this->state(fn() => ['status' => 'pending']);
    }

    public function confirmed(): Factory
    {
        return $this->state(fn() => ['status' => 'confirmed', 'confirmed_at' => now()]);
    }

    public function cancelled(): Factory
    {
        return $this->state(fn() => [
            'status' => 'cancelled',
            'cancellation_reason' => fake()->sentence(),
            'cancelled_at' => now(),
        ]);
    }
}
