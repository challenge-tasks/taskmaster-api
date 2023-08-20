<?php

namespace App\Enums;

enum ErrorTypeEnum: string
{
    case BAD_REQUEST = 'bad_request'; // 400
    case UNAUTHORIZED = 'unauthorized'; // 401
    case FORBIDDEN = 'forbidden'; // 403
    case NOT_FOUND = 'not_found'; // 404
    case METHOD_NOT_ALLOWED = 'method_not_allowed'; // 405
    case VALIDATION_ERROR = 'validation_error'; // 422
    case SERVER_ERROR = 'server_error'; // 500
}
