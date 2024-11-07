<?php

namespace Database\Seeders;

use App\Enums\WeekDayEnum;
use App\Models\WorkingHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dayOfWeeks = WeekDayEnum::values();

        foreach ($dayOfWeeks as $dayOfWeek) {
            $closeTime = '22:00:00';

            if (in_array($dayOfWeek, [WeekDayEnum::FRIDAY->value, WeekDayEnum::SATURDAY->value])) {
                $closeTime = '23:59:59';
            }

            WorkingHour::factory()->create([
                'day_of_week' => $dayOfWeek,
                'is_open' => true,
                'open_time' => '10:00:00',
                'close_time' => $closeTime,
            ]);
        }
    }
}
