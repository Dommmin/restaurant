<?php

namespace App\Enums;

enum WeekDayEnum: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public static function values(): array
    {
        return [
            self::MONDAY->value,
            self::TUESDAY->value,
            self::WEDNESDAY->value,
            self::THURSDAY->value,
            self::FRIDAY->value,
            self::SATURDAY->value,
            self::SUNDAY->value,
        ];
    }
}
