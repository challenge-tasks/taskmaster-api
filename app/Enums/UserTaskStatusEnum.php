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
                'label' => 'Нужно сделать'
            ],
            [
                'value' => self::IN_DEVELOPMENT->value,
                'label' => 'В процессе'
            ],
            [
                'value' => self::REVIEWING->value,
                'label' => 'На проверке'
            ],
            [
                'value' => self::DONE->value,
                'label' => 'Готово'
            ]
        ];
    }

    public static function labelFromOption(int $status): string
    {
        return match ($status) {
            self::TODO->value => 'Нужно сделать',
            self::IN_DEVELOPMENT->value => 'В процессе',
            self::REVIEWING->value => 'На проверке',
            self::DONE->value => 'Готово',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'Нужно сделать',
            self::IN_DEVELOPMENT => 'В процессе',
            self::REVIEWING => 'На проверке',
            self::DONE => 'Готово',
        };
    }
}
