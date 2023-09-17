<?php

namespace App\Helpers;

use App\Enums\ErrorTypeEnum;

class ValidationHelper
{
    public static function getErrorType(array $failedRules): ErrorTypeEnum
    {
        $type = ErrorTypeEnum::VALIDATION_ERROR;

        if (isset($failedRules['email']['Required'])) {
            $type = ErrorTypeEnum::EMAIL_REQUIRED;
        }

        else if (isset($failedRules['email']['Exists'])) {
            $type = ErrorTypeEnum::EMAIL_NOT_EXISTS;
        }

        else if (isset($failedRules['email']['Unique'])) {
            $type = ErrorTypeEnum::EMAIL_EXISTS;
        }

        else if (isset($failedRules['username']['Required'])) {
            $type = ErrorTypeEnum::USERNAME_REQUIRED;
        }

        else if (isset($failedRules['username']['Unique'])) {
            $type = ErrorTypeEnum::USERNAME_EXISTS;
        }

        else if (isset($failedRules['password']['Required'])) {
            $type = ErrorTypeEnum::PASSWORD_REQUIRED;
        }

        else if (isset($failedRules['password']['Min'])) {
            $type = ErrorTypeEnum::PASSWORD_MIN;
        }

        else if (isset($failedRules['password']['Max'])) {
            $type = ErrorTypeEnum::PASSWORD_MAX;
        }

        return $type;
    }
}
