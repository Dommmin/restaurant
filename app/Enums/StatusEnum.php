<?php

namespace App\Enums;

enum StatusEnum: int
{
    case CANCELLED = 0;
    case CONFIRMED = 1;

    public static function values(): array
    {
        return [
            self::CANCELLED->value,
            self::CONFIRMED->value,
        ];
    }
}
