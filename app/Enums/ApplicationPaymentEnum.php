<?php

namespace App\Enums;

enum ApplicationPaymentEnum: string
{
    case UNPAID = 'Unpaid';
    case PAID = 'Paid';


    public static function asList(): array
    {
        return [
            self::UNPAID,
            self::PAID,
        ];
    }
}
