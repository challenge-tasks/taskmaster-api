<?php

namespace App\Enums;

enum UserTaskStatusEnum: int
{
    case TODO = 1;
    case IN_DEVELOPMENT = 2;
    case REVIEWING = 3;
    case DONE = 4;

    public static function options(): array
    {
        return [
            self::TODO->value => 'Нужно сделать',
            self::IN_DEVELOPMENT->value => 'В процессе',
            self::REVIEWING->value => 'На проверке',
            self::DONE->value => 'Готово',
        ];
    }

    public static function filterOptions(): array
    {
        return [
            [
                'value' => self::TODO->value,
                'label' => 'Нужно сделать',
                'slug' => 'todo'
            ],
            [
                'value' => self::IN_DEVELOPMENT->value,
                'label' => 'В процессе',
                'slug' => 'in_development'
            ],
            [
                'value' => self::REVIEWING->value,
                'label' => 'На проверке',
                'slug' => 'reviewing'
            ],
            [
                'value' => self::DONE->value,
                'label' => 'Готово',
                'slug' => 'done'
            ]
        ];
    }

    public static function labelFromOption(int $status): string
    {
        return match ($status) {
            self::TODO->value => 'todo',
            self::IN_DEVELOPMENT->value => 'in_development',
            self::REVIEWING->value => 'reviewing',
            self::DONE->value => 'done',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'todo',
            self::IN_DEVELOPMENT => 'in_development',
            self::REVIEWING => 'reviewing',
            self::DONE => 'done',
        };
    }
}
