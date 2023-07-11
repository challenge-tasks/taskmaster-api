<?php

namespace App\Enums;

enum TaskStatusEnum: int
{
    case PUBLISHED = 1;
    case NOT_PUBLISHED = 2;
    case ON_MODERATION = 3;
    case FAILED_MODERATION = 4;

    public static function options(): array
    {
        return [
            self::PUBLISHED->value => 'Опубликовано',
            self::NOT_PUBLISHED->value => 'Не опубликовано',
            self::ON_MODERATION->value => 'На модерации',
            self::FAILED_MODERATION->value => 'Не прошел модерацию',
        ];
    }
}
