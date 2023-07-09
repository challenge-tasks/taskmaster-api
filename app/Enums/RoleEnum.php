<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function getOptions(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::USER->value => 'User'
        ];
    }
}
