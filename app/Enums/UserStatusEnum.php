<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';

    public static function asList(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
        ];
    }
}
