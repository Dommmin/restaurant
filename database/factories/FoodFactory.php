<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FoodFactory extends Factory
{
    protected $model = Food::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->numberBetween(10 * 100, 100 * 100),
            'ingredients' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
