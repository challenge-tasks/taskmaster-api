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
            self::TODO->value => 'Todo',
            self::IN_DEVELOPMENT->value => 'In development',
            self::REVIEWING->value => 'Reviewing',
            self::DONE->value => 'Done',
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
