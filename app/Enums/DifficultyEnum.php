<?php

namespace App\Enums;

enum DifficultyEnum: int
{
    case INTERN = 1;
    case JUNIOR =2;
    case MIDDLE = 3;
    case SENIOR = 4;
    case GURU = 5;

    public static function options(): array
    {
        return [
            self::INTERN->value=> 'Intern',
            self::JUNIOR->value=> 'Junior',
            self::MIDDLE->value=> 'Middle',
            self::SENIOR->value=> 'Senior',
            self::GURU->value=> 'Guru',
        ];
    }
}
