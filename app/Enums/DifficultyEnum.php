<?php

namespace App\Enums;

enum DifficultyEnum: int
{
    case INTERN = 1;
    case JUNIOR = 2;
    case MIDDLE = 3;
    case SENIOR = 4;
    case GURU = 5;

    public static function options(): array
    {
        return [
            self::INTERN->value => 'Intern',
            self::JUNIOR->value => 'Junior',
            self::MIDDLE->value => 'Middle',
            self::SENIOR->value => 'Senior',
            self::GURU->value => 'Guru',
        ];
    }

    public static function filterOptions(): array
    {
        return [
            [
                'value' => self::INTERN->value,
                'label' => 'Intern'
            ],
            [
                'value' => self::JUNIOR->value,
                'label' => 'Junior'
            ],
            [
                'value' => self::MIDDLE->value,
                'label' => 'Middle'
            ],
            [
                'value' => self::SENIOR->value,
                'label' => 'Senior'
            ],
            [
                'value' => self::GURU->value,
                'label' => 'Guru'
            ]
        ];
    }
}
