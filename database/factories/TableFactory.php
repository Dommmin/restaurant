<?php

namespace Database\Factories;

use App\Enums\PlaceEnum;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition()
    {
        return [
            'places' => Arr::random(PlaceEnum::values()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
