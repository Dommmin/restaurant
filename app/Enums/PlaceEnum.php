<?php

namespace App\Enums;

enum PlaceEnum: int
{
    case TWO = 2;
    case FOUR = 4;
    case SIX = 6;
    case EIGHT = 8;
    case TEN = 10;

    public static function values(): array
    {
        return [
            self::TWO->value,
            self::FOUR->value,
            self::SIX->value,
            self::EIGHT->value,
            self::TEN->value,
        ];
    }
}
