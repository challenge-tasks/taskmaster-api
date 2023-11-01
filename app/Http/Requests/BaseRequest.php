<?php

namespace App\Http\Requests;

use App\Enums\ErrorTypeEnum;
use App\Helpers\ValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'This action is unauthorized.',
            'type' => ErrorTypeEnum::FORBIDDEN
        ], 403));
    }

    protected function failedValidation(Validator $validator)
    {
        $failedRules = $validator->failed();

        throw new HttpResponseException(response()->json([
            'message' => 'Unprocessable Content.',
            'type' => ValidationHelper::getErrorType($failedRules)
        ], 422));
    }
}
