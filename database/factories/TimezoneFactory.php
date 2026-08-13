<?php

namespace Database\Factories;

use App\Models\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimezoneFactory extends Factory
{
    protected $model = Timezone::class;

    public function definition(): array
    {
        return [
            'name' => 'Coordinated Universal Time',
            'code' => 'UTC',
            'utc_offset' => '+00:00',
            'is_active' => true,
        ];
    }
}
