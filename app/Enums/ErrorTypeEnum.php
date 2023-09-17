<?php

namespace App\Enums;

enum ErrorTypeEnum: string
{
    // Base types
    case BAD_REQUEST = 'bad_request'; // 400
    case UNAUTHORIZED = 'unauthorized'; // 401
    case FORBIDDEN = 'forbidden'; // 403
    case NOT_FOUND = 'not_found'; // 404
    case METHOD_NOT_ALLOWED = 'method_not_allowed'; // 405
    case VALIDATION_ERROR = 'validation_error'; // 422
    case SERVER_ERROR = 'server_error'; // 500

    // Validation types
    case EMAIL_REQUIRED = 'email_required';
    case EMAIL_EXISTS = 'email_exists';
    case EMAIL_NOT_EXISTS = 'email_not_exists';
    case PASSWORD_REQUIRED = 'password_required';
    case PASSWORD_MIN = 'password_min_eight';
    case PASSWORD_MAX = 'password_max_one_hundred';
    case INCORRECT_PASSWORD = 'incorrect_password';
    case USERNAME_REQUIRED = 'username_required';
    case USERNAME_EXISTS = 'username_exists';
}
