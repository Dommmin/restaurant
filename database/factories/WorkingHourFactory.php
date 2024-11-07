<?php

namespace Database\Factories;

use App\Models\WorkingHour;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkingHourFactory extends Factory
{
    protected $model = WorkingHour::class;

    public function definition(): array
    {
        return [
            'day_of_week' => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            'is_open' => $this->faker->boolean,
            'open_time' => $this->faker->time,
            'close_time' => $this->faker->time,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
