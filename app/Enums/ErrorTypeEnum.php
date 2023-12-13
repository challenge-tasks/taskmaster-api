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
    case TOO_MANY_REQUESTS = 'too_many_requests'; // 429
    case SERVER_ERROR = 'server_error'; // 500

    // Validation types
    case EMAIL_REQUIRED = 'email_required';
    case EMAIL_EXISTS = 'email_exists';
    case EMAIL_NOT_EXISTS = 'email_not_exists';
    case PASSWORD_REQUIRED = 'password_required';
    case PASSWORD_MIN = 'password_min_eight';
    case PASSWORD_MAX = 'password_max_one_hundred';
    case PASSWORD_NOT_CONFIRMED = 'password_not_confirmed';
    case INCORRECT_PASSWORD = 'incorrect_password';
    case USERNAME_REQUIRED = 'username_required';
    case USERNAME_EXISTS = 'username_exists';
    case FILE_REQUIRED = 'file_required';
    case FILE_MUST_BE_RAR_OR_ZIP = 'file_must_be_rar_or_zip';
    case FILE_OR_URL_REQUIRED = 'file_or_url_required';
    case GITHUB_ID_REQUIRED = 'github_id_required';
    case GITHUB_URL_REQUIRED = 'github_url_required';
    case SIGN_IN_WITH_PROVIDER_FAILED = 'sign_in_with_provider_failed';
    case TOKEN_REQUIRED = 'token_required';
    case URL_INVALID = 'url_invalid';

    // Other types
    case EMAIL_ALREADY_VERIFIED = 'email_already_verified';
}
