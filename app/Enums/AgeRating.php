<?php

namespace App\Enums;

enum AgeRating: string
{
    case Rating0 = '0+';
    case Rating6 = '6+';
    case Rating12 = '12+';
    case Rating16 = '16+';
    case Rating18 = '18+';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
