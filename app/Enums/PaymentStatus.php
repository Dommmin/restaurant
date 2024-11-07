<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case SUCCESS = 'success';
    case FAIL = 'fail';

    public static function values(): array
    {
        return [
            self::IN_PROGRESS->value,
            self::SUCCESS->value,
            self::FAIL->value,
        ];
    }
}
