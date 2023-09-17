<?php

namespace App\Http\Requests\Api\V1\User;

use App\Enums\ErrorTypeEnum;
use App\Helpers\ValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100']
        ];
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
